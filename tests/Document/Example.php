<?php

namespace Saxulum\Tests\DoctrineMongodbOdmCommands\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * @ODM\Document()
 */
class Example
{
    /**
     * @var string
     * @ODM\Id(strategy="auto")
     */
    protected $id;

    /**
     * @var string
     * @ODM\Field(type="string")
     */
    protected $name;
}
