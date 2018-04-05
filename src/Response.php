<?php

namespace LZaplata\Comgate;


use Nette\Object;
use Tracy\Debugger;

class Response extends Object
{
    /** @var \AgmoPaymentsSimpleProtocol */
    private $paymentsProtocol;

    /** @var array */
    private $response;

    /** @var Service */
    private $service;

    /**
     * Response constructor.
     * @param $paymentsProtocol
     * @param Service $service
     */
    public function __construct($paymentsProtocol, Service $service)
    {
        $this->paymentsProtocol = $paymentsProtocol;
        $this->service = $service;
    }

    /**
     * @return RedirectResponse
     */
    public function getRedirectResponse()
    {
        return new RedirectResponse($this->paymentsProtocol->getRedirectUrl());
    }

    /**
     * @return string
     */
    public function getRedirectUrl()
    {
        return $this->paymentsProtocol->getRedirectUrl();
    }

    /**
     * @return bool
     */
    public function isOk()
    {
        try {
            $payment = new Payment($this->service);
            $status = $payment->paymentsDatabase->getTransactionStatus(
                $_GET["id"],
                $_GET["refId"]
            );

            Debugger::dump($status);
        } catch (\Exception $exception) {
            return false;
        }
    }
}