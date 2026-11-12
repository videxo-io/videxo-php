<?php

declare(strict_types=1);

namespace Videxo\Tests;

use GuzzleHttp\Psr7\Response;
use Videxo\Exception\ApiException;
use Videxo\Exception\AuthenticationException;
use Videxo\Exception\CommandInvalidException;
use Videxo\Exception\InvalidRequestException;
use Videxo\Exception\UnexpectedResponseException;
use Videxo\Exception\VidexoException;
use Videxo\Model\JobState;
use Videxo\Videxo;

final class ErrorsTest extends TestCase
{
    public function testEveryDocumentedCodeHasItsOwnException(): void
    {
        $classes = (new \ReflectionClassConstant(ApiException::class, 'CLASSES'))->getValue();
        self::assertIsArray($classes);
        $codes = array_keys($this->api->fixture('problems'));
        self::assertSame([], array_values(array_diff($codes, array_keys($classes))), 'a new error code of the API needs its exception class');
        foreach ($codes as $code) {
            $problem = $this->api->fixture("problems.{$code}");
            $error = ApiException::fromResponse($this->api->problem($code), (string) json_encode($problem));
            self::assertSame($classes[$code], $error::class);
            self::assertInstanceOf(VidexoException::class, $error);
            self::assertSame($code, $error->getErrorCode());
            self::assertSame($problem['status'], $error->status);
            self::assertSame($problem['status'], $error->getCode());
            self::assertSame($problem['title'], $error->title);
            self::assertSame($problem['detail'], $error->detail);
            self::assertSame($problem['type'], $error->type);
            self::assertSame($problem['retryable'], $error->retryable);
            self::assertSame($problem['request_id'], $error->requestId);
            self::assertSame($problem['reason'] ?? null, $error->reason);
            self::assertSame($problem, $error->body);
        }
    }

    public function testKeepsTheLegacyUsageCapException(): void
    {
        $problem = $this->api->fixture('problems.limit_exceeded');
        $problem['code'] = 'usage_cap_reached';
        $response = FakeApi::json(402, $problem);
        $error = ApiException::fromResponse($response, (string) $response->getBody());
        self::assertInstanceOf(\Videxo\Exception\UsageCapReachedException::class, $error);
    }

    public function testThrowsTheProblemOfACall(): void
    {
        try {
            (new Videxo('vdx_wrong', FakeApi::BASE_URL, $this->api))->fonts->list();
            self::fail('no exception');
        } catch (AuthenticationException $error) {
            self::assertSame(401, $error->status);
            self::assertSame('invalid_api_key', $error->reason);
            self::assertStringStartsWith('authentication_failed (401): ', $error->getMessage());
            self::assertStringNotContainsString('vdx_wrong', $error->getMessage());
        }
    }

    public function testCarriesFieldErrorsAndCommandProblems(): void
    {
        $invalid = ApiException::fromResponse($this->api->problem('invalid_request'), (string) json_encode($this->api->fixture('problems.invalid_request')));
        self::assertInstanceOf(InvalidRequestException::class, $invalid);
        self::assertSame('#/preset', $invalid->errors[0]->pointer);
        self::assertSame('Unknown preset.', $invalid->errors[0]->detail);

        $command = ApiException::fromResponse($this->api->problem('command_invalid'), (string) json_encode($this->api->fixture('problems.command_invalid')));
        self::assertInstanceOf(CommandInvalidException::class, $command);
        self::assertInstanceOf(InvalidRequestException::class, $command);
        self::assertSame('option_unknown', $command->problems[0]->code);
        self::assertSame('-vtag', $command->problems[0]->option);
    }

    public function testAnAnswerThatIsNotProblemDetailsIsAnHttpError(): void
    {
        $this->api->queue = [new Response(500, ['Content-Type' => 'text/html', 'Request-Id' => 'req_x'], '<html>Oops</html>')];
        try {
            $this->videxo->fonts->list();
            self::fail('no exception');
        } catch (ApiException $error) {
            self::assertSame(ApiException::class, $error::class);
            self::assertSame('http_error', $error->errorCode);
            self::assertSame(500, $error->status);
            self::assertSame('req_x', $error->requestId);
            self::assertSame('<html>Oops</html>', $error->body);
            self::assertSame('The API answered 500 Internal Server Error.', $error->detail);
        }
    }

    public function testReadsRetryAfterAsSecondsOrADate(): void
    {
        self::assertSame(30, ApiException::retryAfterSeconds('30'));
        self::assertSame(90, ApiException::retryAfterSeconds('Wed, 04 Nov 2026 12:01:30 GMT', 1793793600));
        self::assertSame(0, ApiException::retryAfterSeconds('Wed, 04 Nov 2026 11:00:00 GMT', 1793793600));
        self::assertNull(ApiException::retryAfterSeconds(''));
        self::assertNull(ApiException::retryAfterSeconds('soon'));
    }

    public function testASuccessThatIsNotAnObjectIsAnUnexpectedResponse(): void
    {
        foreach (['not json', '[1,2]', '{"data": "x"}'] as $body) {
            $this->api->queue = [new Response(200, ['Content-Type' => 'application/json'], $body)];
            try {
                $this->videxo->fonts->list();
                self::fail("no exception for {$body}");
            } catch (UnexpectedResponseException $error) {
                self::assertSame('invalid_response', $error->getErrorCode());
            }
        }
    }

    public function testAJobInANewStateStillReads(): void
    {
        $job = $this->api->fixture('jobs.queued');
        $job['state'] = 'paused';
        $this->api->queue = [FakeApi::json(200, $job)];
        $read = $this->videxo->jobs->get('job_x');
        self::assertSame(JobState::Unknown, $read->state);
        self::assertFalse($read->state->isFinal());
    }
}
