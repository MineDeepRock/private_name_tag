# PrivateNameTag

`plugin_data\PrivateNameTag\`
に[NameTag.geo.json](https://github.com/MineDeepRock/private_name_tag/edit/master/NameTag.geo.json)と[NameTag.skin](https://github.com/MineDeepRock/private_name_tag/blob/master/NameTag.skin)を入れてください

set
```php
use private_name_tag\models\PrivateNameTag;

$nameTag = new PrivateNameTag($owner, $owner->getName(), $viewers);
$nameTag->set();
```

updateNameTag
```php
use private_name_tag\models\PrivateNameTag;

$nameTag = PrivateNameTag::get($player);
$nameTag->updateNameTag($player->getName() . " [LEVEL]");
```

updateViewers
```php
use private_name_tag\models\PrivateNameTag;

$nameTag = PrivateNameTag::get($player);
$nameTag->updateViewers($viewers);
```

remove
```php
use private_name_tag\models\PrivateNameTag;

$nameTag = PrivateNameTag::get($player);
$nameTag->remove();
```
