<?php

$loader = require __DIR__.'/../vendor/autoload.php';
$loader->setPsr4('Saxulum\Tests\DoctrineMongodbOdmCommands\\', __DIR__);

\Doctrine\Common\Annotations\AnnotationRegistry::registerLoader(array($loader, 'loadClass'));
