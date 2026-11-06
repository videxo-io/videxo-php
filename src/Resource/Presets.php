<?php

declare(strict_types=1);

namespace Videxo\Resource;

use Videxo\Internal\Transport;
use Videxo\Model\PresetList;

final class Presets
{
    /** @internal */
    public function __construct(private readonly Transport $transport)
    {
    }

    /** `GET /v1/presets`: every preset with its type, its full options and whether it is deprecated. */
    public function list(): PresetList
    {
        return PresetList::fromArray($this->transport->json($this->transport->apiRequest('GET', '/v1/presets'), 'safe'));
    }
}
