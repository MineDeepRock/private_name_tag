<?php


namespace private_name_tag;


use pocketmine\Player;

class PrivateNameTagStore
{

    /**
     * @var PrivateNameTag[]
     */
    static private $privateNameTags = [];

    static function getAll(): array {
        return self::$privateNameTags;
    }

    static function get(Player $player): ?PrivateNameTag {
        foreach (self::$privateNameTags as $privateNameTag) {
            if ($privateNameTag->getOwner()->getName() === $player->getName()) return $privateNameTag;
        }

        return null;
    }

    static function add(PrivateNameTag $privateNameTag): void {
        self::$privateNameTags[] = $privateNameTag;
    }

    static function remove(string $ownerName): void {
        foreach (self::$privateNameTags as $index => $privateNameTag) {
            if ($privateNameTag->getOwner()->getName() === $ownerName) {
                unset(self::$privateNameTags[$index]);
            }
        }

        self::$privateNameTags = array_values(self::$privateNameTags);
    }

    static function update(PrivateNameTag $privateNameTag): void {
        self::remove($privateNameTag->getOwner()->getName());
        self::add($privateNameTag);
    }
}