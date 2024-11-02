```php
<?php

use Arris\DelightAuth\Auth\Auth;
use Arris\DelightAuth\Auth\Exceptions\InvalidEmailException;
use Arris\DelightAuth\Auth\Exceptions\InvalidPasswordException;
use Arris\DelightAuth\Auth\Exceptions\TooManyRequestsException;
use Arris\DelightAuth\Auth\Exceptions\UserAlreadyExistsException;
use Dotenv\Dotenv;

define('ENGINE_START_TIME', microtime(true));
const PATH_ENV = __DIR__;

function getPassword($prompt = "Enter password:"):string {
    echo $prompt;
    system('stty -echo');
    $password = trim(fgets(STDIN));
    system('stty echo');
    return $password;
}

$PATH_INSTALL = dirname(__DIR__, 1);

require_once __DIR__ . '/../vendor/autoload.php';

/*
used "vlucas/phpdotenv": "^5.6" 
*/

Dotenv::createUnsafeImmutable(PATH_ENV, ['site.conf'])->load();
$connection = [
    'driver'    =>  'mysql',
    'hostname'  =>  'localhost',
    'username'  =>  getenv('DB.USERNAME'),
    'password'  =>  getenv('DB.PASSWORD'),
    'database'  =>  getenv('DB.NAME'),
    'charset'   =>  'utf8mb4',
];

$db = new \PDO(
    "mysql:dbname={$connection['database']};host={$connection['hostname']};charset=utf8mb4",
    $connection['username'],
    $connection['password']
);

$auth = new Auth($db);

$cli_options = getopt('', ['help', 'email:', 'password:', 'role:', 'username:']);

$credentials = [
    'email'     =>  array_key_exists('email', $cli_options) ? $cli_options['email'] : '',
    'password'  =>  array_key_exists('password', $cli_options) ? $cli_options['password'] : '',
    'is_admin'  => array_key_exists('role', $cli_options) && $cli_options['role'] == 'admin',
];
$credentials['username'] = array_key_exists('username', $cli_options) ? $cli_options['username'] : explode('@', $credentials['email'])[0];

if (empty($credentials['email'])) {
    echo <<<MSG1
EMAIL required.  
Use register.php --email email [--password password] [--role admin|editor] 
MSG1;
    echo PHP_EOL;
    die;
}

if (empty($credentials['password'])) {
    $credentials['password'] = getPassword("Enter password for user {$credentials['email']} :");
    echo PHP_EOL . PHP_EOL;
}

try {
    $userId = $auth->admin()->createUser($credentials['email'], $credentials['password'], $credentials['username']);

    if ($credentials['is_admin']) {
        $auth->admin()->addRoleForUserById($userId, \Arris\DelightAuth\Auth\Role::ADMIN);
    } else {
        $auth->admin()->addRoleForUserById($userId, \Arris\DelightAuth\Auth\Role::EDITOR);
    }

    printf('We have created and activated a new user with the ID %s', $userId);

} catch (InvalidEmailException $e) {
    printf('Invalid email address');
} catch (InvalidPasswordException $e) {
    printf('Invalid password');
} catch (UserAlreadyExistsException $e) {
    printf('User already exists');
} catch (TooManyRequestsException $e) {
    printf('Too many requests');
} catch (\Arris\DelightAuth\Auth\Exceptions\DuplicateUsernameException $e) {
    printf('Duplicate user name found');
} catch (\Arris\DelightAuth\Auth\Exceptions\AuthError|\Arris\DelightAuth\Auth\Exceptions\UnknownIdException $e) {
    printf('Other error');
}
printf(PHP_EOL . PHP_EOL);
exit;
```

```
