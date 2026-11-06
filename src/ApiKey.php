<?php

declare(strict_types=1);

namespace Videxo;

/** An API key from the dashboard (`vdx_...`). Keep it on your server, never in a browser or an app. */
final class ApiKey implements Credentials
{
    private readonly string $key;

    public function __construct(#[\SensitiveParameter] string $key)
    {
        $key = trim($key);
        if ($key === '') {
            throw new \InvalidArgumentException('Videxo needs an API key: an API key from the dashboard, kept on your server.');
        }
        // Checked here so that no HTTP client refuses the header later with the key in its message.
        if (preg_match('/^[\x21-\x7e]+$/D', $key) !== 1) {
            throw new \InvalidArgumentException(
                'The API key is not valid: an API key has only printable ASCII characters, without spaces or line breaks.',
            );
        }
        $this->key = $key;
    }

    public function authorization(): string
    {
        return 'Bearer ' . $this->key;
    }

    public function redact(string $text): string
    {
        return str_replace($this->key, '[api key]', $text);
    }

    /** @return array<string, string> */
    public function __debugInfo(): array
    {
        return ['key' => '[api key]'];
    }
}
