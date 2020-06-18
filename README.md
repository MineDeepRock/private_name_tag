# PrivateNameTag

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