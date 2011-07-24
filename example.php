<?php

// Autoloading
use BattleNet\Api\Wow\Call\Data\ItemCall;
use Symfony\Component\ClassLoader\UniversalClassLoader;
use BattleNet\Api\Wow\WowApi;

require_once __DIR__ . '/vendor/symfony/lib/Symfony/Component/ClassLoader/UniversalClassLoader.php';

$loader = new UniversalClassLoader;
$loader->registerNamespaces(array(
    'BattleNet' => __DIR__.'/lib'
));

$loader->register();

$wowApi = new WowApi(array('region'=>'eu','locale'=>'en_GB'));


    // Getting character as standard php class
    $khepri = $wowApi->getCharacter('Kilrogg', 'khepri');
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
try {
    $characterCall = new ItemCall(1000);
    $response = $wowApi->request($characterCall);
    var_dump($response);
} catch ( Exception $e ) {
    var_dump($e->getMessage());
}