<?php

declare(strict_types=1);

namespace Videxo\Model;

use Videxo\Internal\Read;

/**
 * A verified webhook delivery: the event, when it happened and the job it is about. Webhook::verify()
 * answers it. A delivery can arrive more than once with the same `id`: keep the ids you handled (a
 * unique database column in the same transaction as your update) and skip a repeat.
 */
final readonly class WebhookEvent implements \JsonSerializable
{
    /** @param array<string, mixed> $data The event's `data` as received. */
    public function __construct(
        /** The delivery's `webhook-id` header: the same on every attempt of one event. */
        public string $id,
        /** `job.succeeded`, `job.failed` or `job.cancelled`; see eventType(). */
        public string $type,
        public \DateTimeImmutable $timestamp,
        /** The job, for a `job.*` event; null for a kind of event this version does not know. */
        public ?Job $job,
        public array $data,
    ) {
    }

    /** The type as an enum; null for a type this version of the SDK does not know. */
    public function eventType(): ?WebhookEventType
    {
        return WebhookEventType::tryFrom($this->type);
    }

    /**
     * The event of a delivery whose body decoded to `$data`, with the delivery's `webhook-id`.
     *
     * @param array<array-key, mixed> $data
     */
    public static function fromArray(string $id, array $data): self
    {
        $type = Read::string($data, 'type');
        $body = Read::object($data, 'data');
        return new self(
            id: $id,
            type: $type,
            timestamp: Read::dateTime($data, 'timestamp'),
            job: str_starts_with($type, 'job.') ? Job::fromArray($body) : null,
            data: $body,
        );
    }

    /** @return array<string, mixed> The event's JSON shape, without the delivery id. */
    public function jsonSerialize(): array
    {
        return ['type' => $this->type, 'timestamp' => Read::format($this->timestamp), 'data' => $this->job ?? (object) $this->data];
    }
}
