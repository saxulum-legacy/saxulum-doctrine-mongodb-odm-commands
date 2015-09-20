<?php

/*
 * This file is part of the Doctrine Bundle
 *
 * The code was originally distributed inside the Symfony framework.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 * (c) Doctrine Project, Benjamin Eberlei <kontakt@beberlei.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Saxulum\DoctrineMongodbOdmCommands\Command;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Tools\Console\Helper\DocumentManagerHelper;
use Symfony\Component\Console\Application;

/**
 * Provides some helper and convenience methods to configure doctrine commands in the context of bundles
 * and multiple connections/entity managers.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
abstract class DoctrineCommandHelper
{
    /**
     * Convenience method to push the helper sets of a given entity manager into the application.
     * @param  Application|null          $application
     * @param  string                    $dmName
     * @throws \InvalidArgumentException
     */
    public static function setApplicationDocumentManager(Application $application = null, $dmName)
    {
        if (is_null($application)) {
            throw new \InvalidArgumentException('Application instance needed!');
        }

        $helperSet = $application->getHelperSet();

        /** @var ManagerRegistry $doctrine */
        $doctrine = $helperSet->get('doctrine_mongodb');

        /** @var DocumentManager $dm */
        $dm = $doctrine->getManager($dmName);

        $helperSet->set(new DocumentManagerHelper($dm), 'dm');
    }

}
