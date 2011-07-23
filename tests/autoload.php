<?php
use Symfony\Component\ClassLoader\UniversalClassLoader;
require_once __DIR__ . '/../vendor/symfony/lib/Symfony/Component/ClassLoader/UniversalClassLoader.php';

$loader = new UniversalClassLoader;
$loader->registerNamespaces(array(
    'BattleNet' => __DIR__.'/../lib',
    'BattleNet\Tests' => __DIR__.'/../tests',
    'Doctrine\Common' => __DIR__.'/../vendor/doctrine-common/lib'
));

$loader->register();