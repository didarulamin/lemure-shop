<?php

declare(strict_types=1);

namespace Tamara\Wp\Plugin\Dependencies\Buzz\Client;

use Tamara\Wp\Plugin\Dependencies\Buzz\Exception\ClientException;
use Tamara\Wp\Plugin\Dependencies\Psr\Http\Message\RequestInterface;

/**
 * A client capable of running batches of requests.
 *
 * The Countable implementation should return the number of queued requests.
 */
interface BatchClientInterface extends \Countable
{
    public function sendAsyncRequest(RequestInterface $request, array $options = []): void;

    /**
     * Processes all queued requests. This is blocking.
     *
     * @throws ClientException If something goes wrong with initializing cUrl
     */
    public function flush(): void;

    /**
     * Processes zero or more queued requests. This is non-blocking.
     *
     * @throws ClientException If something goes wrong with initializing cUrl
     */
    public function proceed(): void;
}
