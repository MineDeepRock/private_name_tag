<?php

namespace private_name_tag;


use pocketmine\entity\Entity;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\DoubleTag;
use pocketmine\nbt\tag\FloatTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\network\mcpe\protocol\SetActorLinkPacket;
use pocketmine\network\mcpe\protocol\types\EntityLink;
use pocketmine\Player;
use pocketmine\Server;

class PrivateNameTag
{
    /**
     * @var Player
     */
    private $owner;
    /**
     * @var string
     */
    private $text;
    /**
     * @var Player[]
     */
    private $viewers;

    public function __construct(Player $owner, string $text, array $viewers) {
        $this->owner = $owner;
        $this->text = $text;
        $this->viewers = $viewers;
    }

    public function set(): void {
        $nbt = new CompoundTag('', [
            'Pos' => new ListTag('Pos', [
                new DoubleTag('', $this->owner->getX()),
                new DoubleTag('', $this->owner->eyeHeight),
                new DoubleTag('', $this->owner->getZ())
            ]),
            'Motion' => new ListTag('Motion', [
                new DoubleTag('', 0),
                new DoubleTag('', 0),
                new DoubleTag('', 0)
            ]),
            'Rotation' => new ListTag('Rotation', [
                new FloatTag("", 0),
                new FloatTag("", 0)
            ]),
        ]);

        $nameTagEntity = new NameTagEntity($this->owner->getLevel(), $nbt);
        $nameTagEntity->setNameTag($this->text);


        foreach ($this->viewers as $viewer) {
            $nameTagEntity->spawnTo($viewer);
        }


        $setEntity = new SetActorLinkPacket();
        $setEntity->link = new EntityLink($this->owner->getId(), $nameTagEntity->getId(), EntityLink::TYPE_RIDER, true, true);

        $this->owner->setGenericFlag(Entity::DATA_FLAG_RIDING, true);
        Server::getInstance()->broadcastPacket($this->viewers, $setEntity);

        $nameTagEntity->setDataFlag(Entity::DATA_FLAG_RIDING, true);

        PrivateNameTagStore::add($this);
    }

    public function updateNameTag(string $nameTag) {
        $this->text = $nameTag;
        foreach ($this->owner->getLevel()->getEntities() as $entity) {
            if ($entity instanceof NameTagEntity) {
                if ($entity->getOwnerName() === $this->owner->getName()) {
                    $entity->setNameTag($this->text);
                }
            }
        }

        PrivateNameTagStore::update($this);
    }

    public function updateViewers(array $viewers) {
        $this->viewers = $viewers;
        $this->remove();
        $this->set();
        PrivateNameTagStore::update($this);
    }

    public function remove(): void {
        foreach ($this->owner->getLevel()->getEntities() as $entity) {
            if ($entity instanceof NameTagEntity) {
                if ($entity->getOwnerName() === $this->owner->getName()) {
                    $entity->setInvisible(true);
                    $entity->kill();
                }
            }
        }
        PrivateNameTagStore::remove($this->getOwner()->getName());
    }

    static function get(Player $owner): ?PrivateNameTag {
        return PrivateNameTagStore::get($owner);
    }

    /**
     * @return Player[]
     */
    public function getViewers(): array {
        return $this->viewers;
    }

    /**
     * @return string
     */
    public function getText(): string {
        return $this->text;
    }

    /**
     * @return Player
     */
    public function getOwner(): Player {
        return $this->owner;
    }
}