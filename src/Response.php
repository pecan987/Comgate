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

    /** @var string */
    private $payId;

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

            $this->payId = $payment->paymentsProtocol->getTransactionStatusTransId();

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

            print "code=0&message=OK";

            return true;
        } catch (\Exception $exception) {
            print "code=1&message=" . urlencode($exception->getMessage());

            return false;
        }
    }

    /**
     * @return string
     */
    public function getPayId()
    {
        return $this->payId;
    }
}