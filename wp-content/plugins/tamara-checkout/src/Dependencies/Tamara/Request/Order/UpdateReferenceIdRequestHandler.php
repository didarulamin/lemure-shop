<?php

declare(strict_types=1);

namespace Tamara\Wp\Plugin\Dependencies\Tamara\Request\Order;

use Tamara\Wp\Plugin\Dependencies\Tamara\Request\AbstractRequestHandler;
use Tamara\Wp\Plugin\Dependencies\Tamara\Response\Order\UpdateReferenceIdResponse;

class UpdateReferenceIdRequestHandler extends AbstractRequestHandler
{
    private const CANCEL_ORDER_ENDPOINT = '/orders/%s/reference-id';

    public function __invoke(UpdateReferenceIdRequest $request)
    {
        $response = $this->httpClient->put(
            sprintf(self::CANCEL_ORDER_ENDPOINT, $request->getOrderId()),
            $request->toArray()
        );

        return new UpdateReferenceIdResponse($response);
    }
}
