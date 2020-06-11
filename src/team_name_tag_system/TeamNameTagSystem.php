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
use pocketmine\utils\TextFormat;
use team_name_tag_system\pmmp\entities\NameTagEntity;

class TeamNameTagSystem
{
    static private $server;

    public function __construct(Server $server) {
        self::$server = $server;
    }

    static function set(Player $player, string $nameTag, array $targets): void {
        $nameTagEntity = new NameTagEntity($player);
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

    static public function updateNameTag(Player $player, string $nameTag): void {
        foreach ($player->getLevel()->getEntities() as $entity) {
            if ($entity instanceof NameTagEntity) {
                if ($entity->getOwnerName() === $player->getName()) {
                    $entity->setNameTag($nameTag);
                }
            }
        }
    }

    static public function deleteNameTag(Player $player): void {
        foreach ($player->getLevel()->getEntities() as $entity) {
            if ($entity instanceof NameTagEntity) {
                if ($entity->getOwnerName() === $player->getName()) {
                    $entity->kill();
                }
            }
        }
    }
}