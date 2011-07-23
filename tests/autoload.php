<?php
use Symfony\Component\ClassLoader\UniversalClassLoader;
require_once __DIR__ . '/../vendor/symfony/lib/Symfony/Component/ClassLoader/UniversalClassLoader.php';

$loader = new UniversalClassLoader;
$loader->registerNamespaces(array(
    'Khepri' => __DIR__.'/../lib',
    'Khepri\Tests' => __DIR__.'/../tests',
));

$loader->register();