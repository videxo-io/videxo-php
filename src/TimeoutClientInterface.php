<?php

declare(strict_types=1);

namespace Videxo;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Optional extension for custom PSR-18 clients: bounds one attempt by the remaining wait() budget.
 * Implementations must keep redirects disabled and return error statuses without throwing.
 */
interface TimeoutClientInterface extends ClientInterface
{
    /** Send within $timeout seconds, or throw a PSR-18 client exception. */
    public function sendRequestWithTimeout(RequestInterface $request, float $timeout): ResponseInterface;
}
