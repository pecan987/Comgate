<?php

namespace LZaplata\Comgate;


use Nette\Application\Responses\RedirectResponse;
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
            $payment->paymentsProtocol->checkTransactionStatus($_POST);
            $payment->paymentsDatabase->checkTransaction(
                $payment->paymentsProtocol->getTransactionStatusTransId(),
                $payment->paymentsProtocol->getTransactionStatusRefId(),
                $payment->paymentsProtocol->getTransactionStatusPrice(),
                $payment->paymentsProtocol->getTransactionStatusCurrency()
            );
            $payment->paymentsDatabase->saveTransaction(
                $payment->paymentsProtocol->getTransactionStatusTransId(),
                $payment->paymentsProtocol->getTransactionStatusRefId(),
                $payment->paymentsProtocol->getTransactionStatusPrice(),
                $payment->paymentsProtocol->getTransactionStatusCurrency(),
                $payment->paymentsProtocol->getTransactionStatus(),
                $payment->paymentsProtocol->getTransactionFee()
            );

            return true;
        } catch (\Exception $exception) {
            return false;
        }
    }

    /**
     * @return string
     */
    public function getPayId()
    {
        $payment = new Payment($this->service);

        return $payment->paymentsProtocol->getTransactionStatusTransId();
    }
}