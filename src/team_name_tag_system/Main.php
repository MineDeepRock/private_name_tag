<?php

namespace team_name_tag_system;

use pocketmine\entity\Entity;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\Listener;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat;
use team_name_tag_system\pmmp\entities\NameTagEntity;

class Main extends PluginBase implements Listener
{
    public function onEnable() {
        new TeamNameTagSystem($this->getServer());

        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        Entity::registerEntity(NameTagEntity::class, true, ['NameTag']);
    }

    public function onReceiveDamaged(EntityDamageByEntityEvent $event) {
        $victim = $event->getEntity();
        if ($victim instanceof NameTagEntity) $event->setCancelled();
    }
}