<?php

declare(strict_types=1);

namespace Videxo\Tests;

use PHPUnit\Framework\Attributes\DataProvider;
use Videxo\ApiKey;
use Videxo\Credentials;
use Videxo\Videxo;

final class ClientTest extends TestCase
{
    public function testSendsTheKeyTheUserAgentAndJson(): void
    {
        $this->videxo->fonts->list();
        $request = $this->api->request();
        self::assertSame('GET', $request['method']);
        self::assertSame(FakeApi::BASE_URL . '/v1/fonts', $request['url']);
        self::assertSame(['Bearer ' . FakeApi::KEY], $request['headers']['authorization']);
        self::assertSame(['application/json'], $request['headers']['accept']);
        self::assertMatchesRegularExpression('~^videxo-php/\d+\.\d+\.\d+ PHP/~', $request['headers']['user-agent'][0] ?? '');
    }

    public function testTrimsTheKeyAndTheBaseUrl(): void
    {
        $videxo = new Videxo('  ' . FakeApi::KEY . "\n", FakeApi::BASE_URL . '/', $this->api);
        self::assertSame(FakeApi::BASE_URL, $videxo->baseUrl);
        $videxo->fonts->list();
        self::assertSame(['Bearer ' . FakeApi::KEY], $this->api->request()['headers']['authorization']);
    }

    /** @return iterable<string, array{string}> */
    public static function invalidKeys(): iterable
    {
        yield 'empty' => [''];
        yield 'spaces' => ['   '];
        yield 'a space inside' => ['vdx_abc def'];
        yield 'a line break' => ["vdx_abc\r\nX-Other: 1"];
        yield 'not ASCII' => ['vdx_abcé'];
    }

    #[DataProvider('invalidKeys')]
    public function testRefusesAKeyThatCannotBeAHeader(string $key): void
    {
        try {
            new Videxo($key);
            self::fail('no exception');
        } catch (\InvalidArgumentException $error) {
            self::assertStringNotContainsString('vdx_abc', $error->getMessage());
        }
    }

    public function testRefusesABaseUrlThatIsNotHttp(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new Videxo(FakeApi::KEY, 'ftp://api.videxo.io');
    }

    public function testKeepsTheKeyOutOfDumps(): void
    {
        $key = new ApiKey(FakeApi::KEY);
        self::assertStringNotContainsString(FakeApi::KEY, print_r($key, true));
        ob_start();
        var_dump($key);
        self::assertStringNotContainsString(FakeApi::KEY, (string) ob_get_clean());
    }

    public function testTakesOtherCredentials(): void
    {
        $credentials = new class () implements Credentials {
            public function authorization(): string
            {
                return 'Bearer ' . FakeApi::KEY;
            }

            public function redact(string $text): string
            {
                return $text;
            }
        };
        (new Videxo($credentials, FakeApi::BASE_URL, $this->api))->fonts->list();
        self::assertSame(['Bearer ' . FakeApi::KEY], $this->api->request()['headers']['authorization']);
    }

    public function testReadsTheEnvironment(): void
    {
        $_ENV['VIDEXO_API_KEY'] = FakeApi::KEY;
        $_ENV['VIDEXO_API_URL'] = 'http://127.0.0.1:8787/';
        try {
            self::assertSame('http://127.0.0.1:8787', Videxo::fromEnvironment()->baseUrl);
            unset($_ENV['VIDEXO_API_URL']);
            self::assertSame(Videxo::DEFAULT_BASE_URL, Videxo::fromEnvironment()->baseUrl);
            $_ENV['VIDEXO_API_KEY'] = ' ';
            $this->expectExceptionMessage('Set VIDEXO_API_KEY');
            Videxo::fromEnvironment();
        } finally {
            unset($_ENV['VIDEXO_API_KEY'], $_ENV['VIDEXO_API_URL']);
        }
    }

    public function testListsPresetsAndFonts(): void
    {
        $presets = $this->videxo->presets->list();
        self::assertContains('web-720p', array_map(static fn ($preset) => $preset->name, $presets->data));
        $fonts = $this->videxo->fonts->list();
        self::assertNotSame([], $fonts->data);
        self::assertContains($fonts->data[0]->weight, [400, 700]);
        self::assertEquals($this->api->fixture('presets'), self::decode((string) json_encode($presets)));
        self::assertEquals($this->api->fixture('fonts'), self::decode((string) json_encode($fonts)));
    }
}
