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

use pocketmine\player\Player;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\Listener;
use usy4\RedstoneDrop\Main;

class EventListener implements Listener
{
    
	public function __construct(public Main $plugin) {
   }
	
     /**
     * @ignoreCancelled true
     * @priority LOW
     */
    public function onDamage(EntityDamageEvent $event)
    {
        $player = $event->getEntity();
        if (!$player instanceof Player) return; 
        if(mt_rand(0,100) <= trim($this->plugin->getConfig()->get("RsDropRatio"), "%"))
        $player->getWorld()->dropItem($player->getPosition(), VanillaItems::REDSTONE_DUST(), $player->getMotion());
    }
    
}
