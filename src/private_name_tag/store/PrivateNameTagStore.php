<?php


namespace private_name_tag\store;


use pocketmine\Player;
use private_name_tag\models\PrivateNameTag;

class PrivateNameTagStore
{

    /**
     * @var PrivateNameTag[]
     * ownerName => nameTag
     */
    static private $privateNameTags = [];

    static function getAll(): array {
        return self::$privateNameTags;
    }

    static function get(Player $player): ?PrivateNameTag {
        foreach (self::$privateNameTags as $name => $privateNameTag) {
            if ($name === $player->getName()) return $privateNameTag;
        }

        return null;
    }

    static function add(PrivateNameTag $privateNameTag): void {
        self::$privateNameTags[$privateNameTag->getOwner()->getName()] = $privateNameTag;
    }

    static function remove(string $ownerName): void {
        foreach (self::$privateNameTags as $name => $privateNameTag) {
            if ($name === $ownerName) {
                unset(self::$privateNameTags[$name]);
            }
        }
    }

    static function update(PrivateNameTag $privateNameTag): void {
        self::remove($privateNameTag->getOwner()->getName());
        self::add($privateNameTag);
    }
}