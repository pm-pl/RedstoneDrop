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

use pocketmine\block\VanillaBlocks;
use pocketmine\event\Listener;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityItemPickupEvent;
use pocketmine\item\VanillaItems;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\player\Player;
use usy4\RedstoneDrop\Main;

class EventListener implements Listener
{
    
	public function __construct(public Main $plugin) {
	
	}
	
	/**
* @ignoreCancelled true
* @priority MONITOR
*/
	public function onDamage(EntityDamageEvent $event)
    
	{
		$player = $event->getEntity();
		if (!$player instanceof Player) return; 
		if($event->isCancelled()) return;
		$mrand = mt_rand(0,100);
		if($mrand <= trim($this->plugin->getConfig()->get("RsDropRatio"), "%")){
			$rs = VanillaItems::REDSTONE_DUST();
			if ($this->plugin->getConfig()->get("RsCanPickup")) { // Default to false.
				// Sets an empty CompoundTag namespaced under another CompoundTag.
				$rs->setNamedTag(CompoundTag::create()->setTag(self::NBT_NAMESPACE, CompoundTag::create()));
			}

			$player->getWorld()->dropItem($player->getPosition(), $rs, $player->getMotion());
		}
	}

	private const NBT_NAMESPACE = "RedstoneDrop";
	
	/**
	 * @priority NORMAL
	 */
	public function onEntityItemPickupEvent(EntityItemPickupEvent $event) : void {
		if ($event->getItem()->getNamedTag()->getTag(self::NBT_NAMESPACE) !== null) {
			$event->setItem(VanillaBlocks::AIR()->asItem());
			// Not cancelling the event so the redstone despawn correctly.
		}
	}
}
