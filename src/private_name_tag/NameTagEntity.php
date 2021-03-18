<?php

namespace private_name_tag;


use pocketmine\entity\Human;
use pocketmine\entity\Skin;
use pocketmine\level\Level;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\DoubleTag;
use pocketmine\nbt\tag\FloatTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\Player;
use pocketmine\utils\UUID;
use private_name_tag\Main;

class NameTagEntity extends Human
{
    protected $skinId = "Standard_CustomSlim";

    public $eyeHeight = 2;
    protected $gravity = 0;
    protected $drag = 0;
    public $scale = 1.0;

    public $defaultHP = 20;
    public $uuid;

    const NAME = "NameTag";
    public $width = 0;
    public $height = 0;

    public $geometryId = "geometry." . self::NAME;
    public $geometryName = self::NAME . ".geo.json";

    private $ownerName;

    public function __construct(Level $level,$nbt) {
        $this->uuid = UUID::fromRandom();
        $this->initSkin();

        parent::__construct($level, $nbt);
        $this->setNameTagAlwaysVisible(true);
        $this->sendSkin();
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
            file_get_contents(Main::$resourcesPath . self::NAME . ".skin"),
            "",
            $this->geometryId,
            file_get_contents(Main::$resourcesPath . $this->geometryName)
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