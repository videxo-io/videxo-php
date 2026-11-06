<?php

declare(strict_types=1);

namespace Videxo\Tests;

use PHPUnit\Framework\Attributes\Group;

/**
 * The request bodies are typed for PHPStan (src/Types.php): it accepts the bodies of
 * types/valid.php and refuses each marked line of types/invalid.php.
 */
#[Group('phpstan')]
final class TypesTest extends \PHPUnit\Framework\TestCase
{
    public function testPhpstanChecksTheRequestBodies(): void
    {
        $root = dirname(__DIR__);
        $config = tempnam(sys_get_temp_dir(), 'videxo-phpstan');
        self::assertIsString($config);
        rename($config, $config .= '.neon');
        file_put_contents($config, "parameters:\n  level: max\n  tmpDir: {$root}/.phpstan.cache\n");
        try {
            $command = [PHP_BINARY, "{$root}/vendor/bin/phpstan", 'analyse', '--no-progress', '--error-format=json', '--memory-limit=1G', "--configuration={$config}", "--autoload-file={$root}/vendor/autoload.php", "{$root}/tests/types"];
            $process = proc_open($command, [1 => ['pipe', 'w'], 2 => ['pipe', 'w']], $pipes);
            self::assertIsResource($process);
            $output = (string) stream_get_contents($pipes[1]);
            fclose($pipes[1]);
            fclose($pipes[2]);
            proc_close($process);
        } finally {
            unlink($config);
        }
        $result = json_decode($output, true);
        self::assertIsArray($result, $output);
        self::assertIsArray($result['files']);

        $errorLines = [];
        foreach ($result['files'] as $file => $report) {
            self::assertIsArray($report);
            self::assertIsArray($report['messages']);
            foreach ($report['messages'] as $message) {
                self::assertIsArray($message);
                $errorLines[basename((string) $file)][] = $message['line'];
            }
        }
        self::assertArrayNotHasKey('valid.php', $errorLines, $output);

        $lines = file("{$root}/tests/types/invalid.php") ?: [];
        $refused = array_keys(array_filter($lines, static fn (string $line) => str_contains($line, '// refused:')));
        self::assertNotSame([], $refused);
        foreach ($refused as $index) {
            self::assertContains($index + 1, $errorLines['invalid.php'] ?? [], 'PHPStan accepted: ' . trim($lines[$index]));
        }
    }
}
