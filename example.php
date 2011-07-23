<?php

// Autoloading
use Symfony\Component\ClassLoader\UniversalClassLoader;
use Khepri\BattleNet\Api\Wow\WowApi;

require_once __DIR__ . '/vendor/symfony/lib/Symfony/Component/ClassLoader/UniversalClassLoader.php';

$loader = new UniversalClassLoader;
$loader->registerNamespaces(array(
    'Khepri' => __DIR__.'/lib'
));

$loader->register();

$wowApi = new WowApi(array('region'=>'eu'));

// Getting character as standard php class
$khepri = $wowApi->getCharacter('Kilrogg', 'Khepri');
echo "realm: ".$khepri->realm."\n";
echo "name: ".$khepri->name."\n";
echo "level: ".$khepri->level."\n";

// Getting character as array
$cagalli = $wowApi->getCharacter('Kilrogg', 'Cagalli', array('achievements'),true);
echo "realm: ".$cagalli['realm']."\n";
echo "name: ".$cagalli['name']."\n";
echo "level: ".$cagalli['level']."\n";
echo "achievements Completed: ".count($cagalli['achievements']['achievementsCompleted'])."\n";

// Getting realm status
$realms = $wowApi->getRealmStatus('Kilrogg');
var_dump($realms->realms[0]);