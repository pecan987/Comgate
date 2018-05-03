<?php

namespace LZaplata\Comgate\DI;


use Nette\DI\CompilerExtension;

class Extension extends CompilerExtension
{
    public $defaults = [
        "sandbox" => true,
        "currency" => "CZK",
        "preauth" => false
    ];

    public function loadConfiguration()
    {
        $this->config += $this->defaults;

        $builder = $this->getContainerBuilder();
        $builder->addDefinition($this->prefix("config"))
            ->setClass("LZaplata\Comgate\Service", [
                $this->config["merchant"],
                $this->config["secret"],
                $this->config["sandbox"],
                $this->config["currency"],
                $this->config["preauth"]
            ]);
    }
}