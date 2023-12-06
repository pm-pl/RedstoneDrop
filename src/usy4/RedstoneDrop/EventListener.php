<?php 

namespace usy4\RedstoneDrop;

/*  
 *  A plugin for PocketMine-MP.
 *
 *	This program is free software: you can redistribute it and/or modify
 *	it under the terms of the GNU General Public License as published by
 *	the Free Software Foundation, either version 3 of the License, or
 *	(at your option) any later version.
 *
 *	This program is distributed in the hope that it will be useful,
 *	but WITHOUT ANY WARRANTY; without even the implied warranty of
 *	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *	GNU General Public License for more details.
 *
 *	You should have received a copy of the GNU General Public License
 *	along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\Listener;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityItemPickupEvent;
use pocketmine\item\VanillaItems;
use pocketmine\math\Vector3;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\player\Player;
use pocketmine\world\Position;

class EventListener implements Listener
{
	public const NBT_NAMESPACE = "RedstoneDrop";

	public function __construct(private Configuration $config) {

	}

	/**
     * @priority MONITOR
     */
	public function onDamage(EntityDamageEvent $event)
	{
        if($this->config->specificItems) return;

		$player = $event->getEntity();
		if (!$player instanceof Player) return;

        if($this->config->specificWorlds && !in_array($player->getPosition()->getWorld()->getDisplayName(), $this->config->worlds)) return;
        $this->dropRedstone($player->getPosition(), $player->getMotion());
	}

    /**
     * @priority MONITOR
     */
    public function onDamageByPlayer(EntityDamageByEntityEvent $event)
    {
        if(!$this->config->specificItems) return;

        $player = $event->getEntity();
        if (!$player instanceof Player) return;
        $attacker = $event->getDamager();
        if(!$attacker instanceof Player) return;

        if($this->config->specificWorlds && !in_array($player->getPosition()->getWorld()->getDisplayName(), $this->config->worlds)) return;
        if(in_array($attacker->getInventory()->getItemInHand()->getVanillaName(), $this->config->items)) {
            $this->dropRedstone($player->getPosition(), $player->getMotion());
        }
    }


	/**
	 * @priority NORMAL
	 */
	public function onEntityItemPickupEvent(EntityItemPickupEvent $event) : void {
        $tag = $event->getItem()->getNamedTag()->getTag(self::NBT_NAMESPACE);
        if ($tag instanceof CompoundTag) {
            if($tag->getByte("canPickup", 0) === 1) {
                $event->setItem(VanillaItems::AIR());
                // Not cancelling the event so the redstone despawn correctly.
            }
		}
	}

    private function dropRedstone(Position $pos, Vector3 $motion)
    {
        $mrand = mt_rand(0,100);
        if($mrand <= $this->config->dropRatio){
            $rs = VanillaItems::REDSTONE_DUST();
            if (!$this->config->canPickup) { // Default to false.
                // Sets an empty CompoundTag namespaced under another CompoundTag.
                $nbt = $rs->getNamedTag();
                $nbt->setTag(self::NBT_NAMESPACE, CompoundTag::create()->setByte("canPickup", 1));
                $rs->setNamedTag($nbt);
            }

            $pos->getWorld()->dropItem($pos, $rs, $motion);
        }
    }
}
