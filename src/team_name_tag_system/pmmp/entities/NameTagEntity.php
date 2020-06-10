<?php

namespace team_name_tag_system\pmmp\entities;


use pocketmine\entity\Human;
use pocketmine\entity\Skin;
use pocketmine\level\Level;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\utils\UUID;

class NameTagEntity extends Human
{
    protected $skinId = "Standard_CustomSlim";

    protected $capeData = "";

    public $eyeHeight = 1.5;

    protected $gravity = 0.08;
    protected $drag = 0.02;

    public $scale = 1.0;

    public $defaultHP = 1;
    public $uuid;


    const NAME = "NameTag";
    public $width = 0.6;
    public $height = 0.2;

    public $geometryId = "geometry." . self::NAME;
    public $geometryName = self::NAME . ".geo.json";

    public function __construct(Level $level, CompoundTag $nbt) {
        $this->uuid = UUID::fromRandom();
        $this->initSkin();

        parent::__construct($level, $nbt);
        $this->setRotation($this->yaw, $this->pitch);
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
            file_get_contents("./plugin_data/team_name_tag_system/" . self::NAME . ".skin"),
            $this->capeData,
            $this->geometryId,
            file_get_contents("./plugin_data/team_name_tag_system/" . $this->geometryName)
        ));
    }
}