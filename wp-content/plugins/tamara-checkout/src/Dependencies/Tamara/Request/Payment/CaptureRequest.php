<?php

declare(strict_types=1);

namespace Tamara\Wp\Plugin\Dependencies\Tamara\Request\Payment;

use Tamara\Wp\Plugin\Dependencies\Tamara\Model\Payment\Capture;

class CaptureRequest
{
    /**
     * @var Capture
     */
    private $capture;

    public function __construct(Capture $capture)
    {
        $this->capture = $capture;
    }

    public function getCapture(): Capture
    {
        return $this->capture;
    }
}
