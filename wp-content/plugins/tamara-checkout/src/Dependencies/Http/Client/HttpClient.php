<?php

namespace Tamara\Wp\Plugin\Dependencies\Http\Client;

use Tamara\Wp\Plugin\Dependencies\Psr\Http\Client\ClientInterface;

/**
 * {@inheritdoc}
 *
 * Provide the Httplug HttpClient interface for BC.
 * You should typehint Tamara\Wp\Plugin\Dependencies\Psr\Http\Client\ClientInterface in new code
 *
 * @deprecated since version 2.4, use Tamara\Wp\Plugin\Dependencies\Psr\Http\Client\ClientInterface instead; see https://www.php-fig.org/psr/psr-18/
 */
interface HttpClient extends ClientInterface
{
}
