<?php

// Request bodies PHPStan must accept, analysed by TypesTest.

declare(strict_types=1);

use Videxo\Videxo;

function validBodies(Videxo $videxo): void
{
    $videxo->transcode(['input' => 'https://example.com/talk.mp4', 'preset' => 'web-720p', 'metadata' => ['order' => '42']]);
    $videxo->transcode(['input' => 'videxo://uploads/up_000000000000000000000001', 'resize' => ['width' => 640], 'trim' => ['start' => 1.5, 'end' => '00:00:10']]);
    $videxo->thumbnails(['input' => 'https://example.com/talk.mp4', 'times' => [1, 2.5, '00:00:04']]);
    $videxo->concat(['inputs' => ['https://example.com/a.mp4', 'https://example.com/b.mp4']]);
    $videxo->jobs->create([
        'inputs' => ['talk' => 'https://example.com/talk.mp4'],
        'outputs' => [['name' => 'web', 'input' => 'talk', 'preset' => 'web-720p'], ['name' => 'poster', 'input' => 'talk', 'preset' => 'poster', 'at' => 3]],
    ]);
    $videxo->jobs->create(['command' => '-i {{in_a}} {{out_a}}', 'input_files' => ['in_a' => 'https://example.com/a.mp4'], 'output_files' => ['out_a' => 'a.mp4']]);
    $videxo->jobs->compile(['operation' => 'gif', 'input' => 'https://example.com/talk.mp4']);
}
