<?php

/*
 * This file is part of the Doctrine MongoDBBundle
 *
 * The code was originally distributed inside the Symfony framework.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 * (c) Doctrine Project
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Saxulum\DoctrineMongodbOdmCommands\Command;

use Doctrine\ODM\MongoDB\Tools\DocumentGenerator;
use Symfony\Component\Console\Command\Command;

/**
 * Base class for Doctrine ODM console commands to extend.
 *
 * @author Justin Hileman <justin@justinhileman.info>
 */
abstract class DoctrineODMCommand extends Command
{
    /**
     * @return DocumentGenerator
     */
    protected function getDocumentGenerator()
    {
        $documentGenerator = new DocumentGenerator();
        $documentGenerator->setGenerateAnnotations(false);
        $documentGenerator->setGenerateStubMethods(true);
        $documentGenerator->setRegenerateDocumentIfExists(false);
        $documentGenerator->setUpdateDocumentIfExists(true);
        $documentGenerator->setNumSpaces(4);

        return $documentGenerator;
    }

    /**
     * Get a doctrine document manager by symfony name.
     *
     * @param string $name
     *
     * @return \Doctrine\ODM\MongoDB\Tools\DocumentGenerator
     */
    protected function getDocumentManager($name)
    {
        $helperSet = $this->getHelperSet();

        return $helperSet->get('doctrine_mongodb')->getManager($name);
    }

    /**
     * Get a doctrine document manager by symfony name.
     *
     * @return string
     */
    protected function getDefaultManagerName()
    {
        $helperSet = $this->getHelperSet();

        return $helperSet->get('doctrine_mongodb')->getDefaultManagerName();
    }


    /**
     * Get a doctrine dbal connection by symfony name.
     *
     * @param string $name
     *
     * @return \Doctrine\MongoDB\Connection
     */
    protected function getDoctrineConnection($name)
    {
        $helperSet = $this->getHelperSet();

        return $helperSet->get('doctrine_mongodb')->getConnection($name);
    }
}
