<?php

namespace usy4\RedstoneDrop;

use usy4\RedstoneDrop\EventListener;
use pocketmine\plugin\PluginBase;

class Main extends PluginBase{

	public function onEnable() : void{
		$this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
        $this->config();
        $this->saveDefaultConfig();
    }
    
    public function config(){
        return $this->getConfig();
    } // all this

}
