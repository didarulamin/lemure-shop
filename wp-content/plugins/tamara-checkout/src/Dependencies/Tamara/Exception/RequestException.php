<?php

namespace Tamara\Wp\Plugin\Dependencies\Tamara\Exception;

use Tamara\Wp\Plugin\Dependencies\Psr\Http\Client\RequestExceptionInterface;
use Tamara\Wp\Plugin\Dependencies\Psr\Http\Message\RequestInterface;
use Tamara\Wp\Plugin\Dependencies\Psr\Http\Message\ResponseInterface;
use Exception;

class RequestException extends Exception implements RequestExceptionInterface
{
    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * @var ResponseInterface|null
     */
    protected $response;

    /**
     * @param string                 $message
     * @param int                    $code
     * @param RequestInterface       $request
     * @param null|ResponseInterface $response
     * @param Exception|null        $previous
     */
    public function __construct(
        string $message,
        int $code,
        RequestInterface $request,
        ?ResponseInterface $response,
        Exception $previous = null
    ) {
        parent::__construct($message, $code, $previous);
        $this->request = $request;
        $this->response = $response;
    }

    public function getRequest(): RequestInterface
    {
        return $this->request;
    }

    /**
     * @return ResponseInterface|null
     */
    public function getResponse(): ?ResponseInterface
    {
        return $this->response;
    }
}
