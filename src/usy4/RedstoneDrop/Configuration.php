<?php

declare(strict_types=1);

namespace usy4\RedstoneDrop;

use pocketmine\utils\Config;

final class Configuration
{
    public readonly float $dropRatio;
    public readonly bool $canPickup;
    public readonly bool $specificItems;
    /**
     * @var array<int|string, string> $items
     */
    public readonly array $items;
    public readonly bool $specificWorlds;
    /**
     * @var array<int|string, string> $worlds
     */
    public readonly array $worlds;

    public function __construct(Config $config)
    {
        $dropRatio = $config->get("RsDropRatio");
        if(is_string($dropRatio) && str_ends_with($dropRatio, "%")) {
            $dropRatio = trim($dropRatio, "%");
        }

        $this->dropRatio = (float)$dropRatio;

        $this->canPickup = $config->get("RsCanPickup");

        $this->specificItems = $config->getNested("SpecificItems.enabled");

        if($this->specificItems) {
            $this->items = $config->getNested("SpecificItems.items");
        }

        $this->specificWorlds = $config->getNested("SpecificWorlds.enabled");

        if($this->specificWorlds) {
            $this->worlds = $config->getNested("SpecificWorlds.worlds");
        }
    }
}