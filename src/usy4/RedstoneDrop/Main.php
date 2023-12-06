<?php

namespace usy4\RedstoneDrop;

use pocketmine\plugin\PluginBase;

class Main extends PluginBase{
	public function onEnable() : void{
		$this->getServer()->getPluginManager()->registerEvents(new EventListener(new Configuration($this->getConfig())), $this);
	}
}
