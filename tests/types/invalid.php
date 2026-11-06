<?php

// Request bodies PHPStan must refuse, one per line, analysed by TypesTest: each line marked
// `refused` must have an error.

declare(strict_types=1);

use Videxo\Videxo;

function invalidBodies(Videxo $videxo): void
{
    $videxo->transcode(['preset' => 'web-720p']); // refused: no input
    $videxo->transcode(['input' => 'https://example.com/talk.mp4', 'preset' => 42]); // refused: preset is a string
    $videxo->transcode(['input' => 'https://example.com/talk.mp4', 'resize' => ['width' => 'wide']]); // refused: width is a number
    $videxo->transcode(['input' => 'https://example.com/talk.mp4', 'format' => 'avi']); // refused: not a format
    $videxo->thumbnails(['input' => 'https://example.com/talk.mp4', 'times' => 'all']); // refused: times is a list
    $videxo->jobs->create(['inputs' => ['talk' => 'https://example.com/talk.mp4'], 'outputs' => 'web']); // refused: outputs is a list
    $videxo->gif(['input' => 'https://example.com/talk.mp4', 'fps' => 'fast']); // refused: fps is a number
}
