<?php
require_once '../../vendor/Symfony/Component/ClassLoader/ClassLoader.php';

/**
 * I make use of the Symphony class loader for the project but any PSR-0 compliant 
 * loader will work.
 * 
 * @var Symfony
 */
$loader = new Symfony\Component\ClassLoader\ClassLoader();
$loader->addPrefixes(array(
    'Gis' => dirname(dirname(__DIR__)),
));

$loader->setUseIncludePath(true);
$loader->register();
