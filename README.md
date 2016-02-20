### Add to your composer.json
```json
"require": {
    "jilexandr/skypewebapi": "@dev"
},
"repositories": [
    {
        "type": "git",
        "url": "https://github.com/JILeXanDR/SkypeWebAPI"
    }
]
```

### And run
```sh
composer install
```

### Usage example

```php
ini_set('display_errors', true);

require_once __DIR__ . '/vendor/autoload.php';

use WebSkype\DefaultStorageInObject;
use WebSkype\Logger;
use WebSkype\Session\SessionToken;
use WebSkype\Skype;

$skypeLogin = 'your_login';
$skypePassword = 'your_password';

$dateTime = (new DateTime())->format('H:i:s');
$toUser = 'user_skype_login';
$message = "Привет! Текущее время: {$dateTime}";

try {

    $defaultStorage = new TestDefaultStorageInObject(false); // храним данные токенов в объекте

    $sessionToken = SessionToken::setStorage($defaultStorage);
    $sessionToken->findActiveOrCreateNew($skypeLogin, $skypePassword);

    $skype = new Skype($sessionToken->storage);

    $skype->sendMessage($message, $toUser);

} catch (Exception $e) {
    Logger::append($e->getMessage());
} finally {
    Logger::write();
}
```