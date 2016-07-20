<?php
error_reporting(E_ALL ^ E_NOTICE);
function my_autoloader($class)
{
    $f = $_SERVER['DOCUMENT_ROOT'] . "/includes/classes/" . $class . '.php';
    if (is_file($f))
        include_once($f);
}
spl_autoload_register('my_autoloader');
// SET DATABASE CONFIGURATION
Registry::setConfig(new MySqlConfig(DatabaseManager::dbUser, DatabaseManager::dbPass, DatabaseManager::dbName, DatabaseManager::host, DatabaseManager::dbPort));
CoreConfig::applySettings(require_once ('settings.php'));

$User = new User($_SESSION['uid']);

if(WebUser::isLoggedIn())
{
    if($User->isStudent())
    {
        WebUser::setUser(new Student($User->getUid()));
    }
    else
    {
        WebUser::setUser($User);
    }
}

?>