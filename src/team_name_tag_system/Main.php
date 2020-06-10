<?php

namespace team_name_tag_system;

use pocketmine\entity\Entity;
use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use team_name_tag_system\pmmp\entities\NameTagEntity;

class Main extends PluginBase implements Listener
{
    public function onEnable() {
        new TeamNameTagSystem($this->getServer());

        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        Entity::registerEntity(NameTagEntity::class, true, ['NameTag']);
    }
}