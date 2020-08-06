<?php

namespace private_name_tag\models;


use pocketmine\entity\Entity;
use pocketmine\network\mcpe\protocol\SetActorLinkPacket;
use pocketmine\network\mcpe\protocol\types\EntityLink;
use pocketmine\Player;
use pocketmine\Server;
use private_name_tag\pmmp\entities\NameTagEntity;
use private_name_tag\store\PrivateNameTagStore;

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
        $nameTagEntity = new NameTagEntity($this->owner);
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