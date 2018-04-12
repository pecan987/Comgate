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
        $config = $this->getConfig($this->defaults);
        $builder = $this->getContainerBuilder();

        $builder->addDefinition($this->prefix("config"))
            ->setClass("LZaplata\Comgate\Service", [
                $config["merchant"],
                $config["secret"],
                $config["sandbox"],
                $config["currency"],
                $config["preauth"]
            ]);
    }
}