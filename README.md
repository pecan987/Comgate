# Comgate
This is small Nette Framework wrapper for Comgate.

## Installation
The easiest way to install library is via Composer.

````sh
$ composer require lzaplata/comgate: dev-master
````
or edit `composer.json` in your project

````json
"require": {
        "lzaplata/comgate": "dev-master"
}
````

You have to register the library as extension in `config.neon` file.

````neon
extensions:
        comgate: LZaplata\Comgate\DI\Extension
````

Now you can set parameters...

````neon
comgate:
        merchant        : *
        secret          : *
        sandbox         : true                    
````

...and autowire library to presenter

````php
use LZaplata\Comgate\Service;

/** @var Service @inject */
public $comgate;
````

## Usage
Create payment.

````php
$payment = $this->comgate->createPayment(
    $price                  // total price - float
);
````

Get payment ID and save it to database.

````php
$payId = $payment->getPayId();
````

Send payment.

````php
$response = $payment->send();
````

Redirect to payment gateway.

````php
$this->sendResponse($response->getRedirectResponse());
````

...or get redirect url.

````php
$response->getRedirectUrl();
````

### After return from gateway
Get response and check if payment was successful

````php
$response = $this->comgate->getReturnResponse();

if ($response->isOk()) {
    // do something
}
````