<?php

namespace team_name_tag_system;


use pocketmine\entity\Entity;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\DoubleTag;
use pocketmine\nbt\tag\FloatTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\network\mcpe\protocol\SetActorLinkPacket;
use pocketmine\network\mcpe\protocol\types\EntityLink;
use pocketmine\Player;
use pocketmine\Server;
use team_name_tag_system\pmmp\entities\NameTagEntity;

class TeamNameTagSystem
{
    static private $server;

    public function __construct(Server $server) {
        self::$server = $server;
    }

    static function set(Player $player, string $nameTag, array $targets): void {
        $nbt = new CompoundTag('', [
            'Pos' => new ListTag('Pos', [
                new DoubleTag('', $player->getX()),
                new DoubleTag('', 1.8),
                new DoubleTag('', $player->getZ())
            ]),
            'Motion' => new ListTag('Motion', [
                new DoubleTag('', 0),
                new DoubleTag('', 0),
                new DoubleTag('', 0)
            ]),
            'Rotation' => new ListTag('Rotation', [
                new FloatTag("", $player->getYaw()),
                new FloatTag("", 0)
            ]),
        ]);
        $nameTagEntity = new NameTagEntity($player->getLevel(), $nbt);
        foreach ($targets as $target) {
            $nameTagEntity->spawnTo($target);
        }

        $nameTagEntity->setNameTag($nameTag);

        $setEntity = new SetActorLinkPacket();
        $setEntity->link = new EntityLink($player->getId(), $nameTagEntity->getId(), EntityLink::TYPE_RIDER);

        $player->setGenericFlag(Entity::DATA_FLAG_RIDING, true);
        self::$server->broadcastPacket(self::$server->getOnlinePlayers(), $setEntity);

        $nameTagEntity->setDataFlag(Entity::DATA_FLAG_RIDING, true);
    }
}