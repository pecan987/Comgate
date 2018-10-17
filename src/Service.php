<?php

namespace LZaplata\Comgate;

use Nette\SmartObject;

class Service
{
	use SmartObject;

    /** @var string */
    public $merchant;

    /** @var string */
    public $secret;

    /** @var bool */
    public $sandbox;

    /** @var string */
    public $url;

    /** @var string */
    public $currency;

    /** @var bool */
    public $preauth;

    /**
     * Service constructor.
     * @param int $merchant
     * @param string $secret
     * @param bool $sandbox
     * @param string $currency
     * @param bool $preauth
     */
    public function __construct($merchant, $secret, $sandbox, $currency, $preauth)
    {
        $this->setMerchant($merchant);
        $this->setSecret($secret);
        $this->setSandbox($sandbox);
        $this->setCurrency($currency);
        $this->setPreauth($preauth);
    }

    /**
     * @param string $merchant
     * @return self
     */
    public function setMerchant($merchant)
    {
        $this->merchant = (string)$merchant;

        return $this;
    }

    /**
     * @return string
     */
    public function getMerchant()
    {
        return $this->merchant;
    }

    /**
     * @param string $secret
     * @return self
     */
    public function setSecret($secret)
    {
        $this->secret = (string)$secret;

        return $this;
    }

    /**
     * @return string
     */
    public function getSecret()
    {
        return $this->secret;
    }

    /**
     * @param int $sandbox
     * @return self
     */
    public function setSandbox($sandbox)
    {
        $this->sandbox = $sandbox;

        if ($sandbox) {
            $this->url = "https://payments.comgate.cz/v1.0";
        } else {
            $this->url = "https://payments.comgate.cz/v1.0";
        }

        return $this;
    }

    /**
     * @return bool
     */
    public function getSandbox()
    {
        return $this->sandbox;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $currency
     * @return self
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;

        return $this;
    }

    /**
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @param $preauth
     * @return self
     */
    public function setPreauth($preauth)
    {
        $this->preauth = $preauth;

        return $this;
    }

    /**
     * @return bool
     */
    public function getPreauth()
    {
        return $this->preauth;
    }

    /**
     * @param float $price
     * @return Payment
     * @throws \Exception
     */
    public function createPayment($price, string $refId, $currency = 'CZK')
    {
        $payment = new Payment($this);
        $payment->createPayment($price, $refId, $currency);

        return $payment;
    }

    /**
     * @return Response
     */
    public function getReturnResponse()
    {
        return new Response(null, $this);
    }
}