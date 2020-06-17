<?php

namespace private_name_tag;

use pocketmine\entity\Entity;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use private_name_tag\models\PrivateNameTag;
use private_name_tag\pmmp\entities\NameTagEntity;

class Main extends PluginBase implements Listener
{
    public function onEnable() {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        Entity::registerEntity(NameTagEntity::class, true, ['NameTag']);
    }

    public function onQuit(PlayerQuitEvent $event) {
        $privateNameTag = PrivateNameTag::get($event->getPlayer());
        if ($privateNameTag !== null) $privateNameTag->remove();
    }

    public function onReceiveDamaged(EntityDamageByEntityEvent $event) {
        $victim = $event->getEntity();
        if ($victim instanceof NameTagEntity) $event->setCancelled();
    }

    public function onDead(PlayerDeathEvent $event) {
        $player = $event->getEntity();
        if ($player instanceof Player) {
            $privateNameTag = PrivateNameTag::get($event->getPlayer());
            if ($privateNameTag !== null) $privateNameTag->remove();
        }
    }
}