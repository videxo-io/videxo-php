<?php

declare(strict_types=1);

namespace Videxo\Resource;

use Videxo\Internal\Transport;
use Videxo\Model\FontList;

final class Fonts
{
    /** @internal */
    public function __construct(private readonly Transport $transport)
    {
    }

    /** `GET /v1/fonts`: the fonts a text layer can name, with their family, weight, scripts and licence. */
    public function list(): FontList
    {
        return FontList::fromArray($this->transport->json($this->transport->apiRequest('GET', '/v1/fonts'), 'safe'));
    }
}
