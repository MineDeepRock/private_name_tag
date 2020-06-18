<?php

namespace private_name_tag\pmmp\entities;


use pocketmine\entity\Human;
use pocketmine\entity\Skin;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\DoubleTag;
use pocketmine\nbt\tag\FloatTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\Player;
use pocketmine\utils\UUID;

class NameTagEntity extends Human
{
    protected $skinId = "Standard_CustomSlim";

    protected $capeData = "";

    public $eyeHeight = 2;

    protected $gravity = 0;
    protected $drag = 0;

    public $scale = 1.0;

    public $defaultHP = 20;
    public $uuid;


    const NAME = "NameTag";
    public $width = 0;
    public $height = 1.8;

    public $geometryId = "geometry." . self::NAME;
    public $geometryName = self::NAME . ".geo.json";

    private $ownerName;

    public function __construct(Player $owner) {
        $this->uuid = UUID::fromRandom();
        $this->initSkin();
        $nbt = new CompoundTag('', [
            'Pos' => new ListTag('Pos', [
                new DoubleTag('', $owner->getX()),
                new DoubleTag('', $this->eyeHeight),
                new DoubleTag('', $owner->getZ())
            ]),
            'Motion' => new ListTag('Motion', [
                new DoubleTag('', 0),
                new DoubleTag('', 0),
                new DoubleTag('', 0)
            ]),
            'Rotation' => new ListTag('Rotation', [
                new FloatTag("", $owner->getYaw()),
                new FloatTag("", 0)
            ]),
        ]);
        parent::__construct($owner->getLevel(), $nbt);
        $this->setRotation($this->yaw, $this->pitch);
        $this->setNameTagAlwaysVisible(true);
        $this->sendSkin();
        $this->ownerName = $owner->getName();
    }

    public function initEntity(): void {
        parent::initEntity();
        $this->setScale($this->scale);
        $this->setMaxHealth($this->defaultHP);
        $this->setHealth($this->getMaxHealth());
    }

    private function initSkin(): void {
        $this->setSkin(new Skin(
            $this->skinId,
            file_get_contents("./plugin_data/TeamNameTagSystem/" . self::NAME . ".skin"),
            $this->capeData,
            $this->geometryId,
            file_get_contents("./plugin_data/TeamNameTagSystem/" . $this->geometryName)
        ));
    }

    /**
     * @return string
     */
    public function getOwnerName(): string {
        return $this->ownerName;
    }

    public function kill(): void {
        $this->setHealth(0);
        $this->scheduleUpdate();
    }
}