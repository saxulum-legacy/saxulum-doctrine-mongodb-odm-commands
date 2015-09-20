<?php

namespace Saxulum\Tests\DoctrineMongodbOdmCommands\Command;

use Saxulum\Console\Silex\Provider\ConsoleProvider;
use Saxulum\DoctrineMongoDb\Silex\Provider\DoctrineMongoDbProvider;
use Saxulum\DoctrineMongoDbOdm\Silex\Provider\DoctrineMongoDbOdmProvider;
use Saxulum\DoctrineMongodbOdmCommands\Command\ClearMetadataCacheDoctrineODMCommand;
use Saxulum\DoctrineMongodbOdmCommands\Command\CreateSchemaDoctrineODMCommand;
use Saxulum\DoctrineMongodbOdmCommands\Command\DropSchemaDoctrineODMCommand;
use Saxulum\DoctrineMongodbOdmCommands\Command\GenerateHydratorsDoctrineODMCommand;
use Saxulum\DoctrineMongodbOdmCommands\Command\GenerateProxiesDoctrineODMCommand;
use Saxulum\DoctrineMongodbOdmCommands\Command\InfoDoctrineODMCommand;
use Saxulum\DoctrineMongodbOdmCommands\Command\QueryDoctrineODMCommand;
use Saxulum\DoctrineMongodbOdmCommands\Command\UpdateSchemaDoctrineODMCommand;
use Saxulum\DoctrineMongodbOdmCommands\Helper\ManagerRegistryHelper;
use Saxulum\DoctrineMongodbOdmManagerRegistry\Silex\Provider\DoctrineMongodbOdmManagerRegistryProvider;
use Silex\Application;
use Silex\WebTestCase;
use Symfony\Component\Console\Application as ConsoleApplication;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;

class CommandTest extends WebTestCase
{
    public function testSchemaCreateCommand()
    {
        $input = new ArrayInput(array(
            'command' => 'doctrine:mongodb:schema:create',
        ));
        $output = new BufferedOutput();
        $this->assertEquals(0, $this->app['console']->run($input, $output));
        echo($output->fetch());
    }

    public function testSchemaUpdateCommand()
    {
        $input = new ArrayInput(array(
            'command' => 'doctrine:mongodb:schema:update'
        ));
        $output = new BufferedOutput();
        $this->assertEquals(0, $this->app['console']->run($input, $output));
        echo($output->fetch());
    }

    public function testDropUpdateCommand()
    {
        $input = new ArrayInput(array(
            'command' => 'doctrine:mongodb:schema:drop'
        ));
        $output = new BufferedOutput();
        $this->assertEquals(0, $this->app['console']->run($input, $output));
        echo($output->fetch());
    }

    public function testQueryCommand()
    {
        $input = new ArrayInput(array(
            'command' => 'doctrine:mongodb:query',
            'class' => 'Saxulum\Tests\DoctrineMongodbOdmCommands\Document\Example',
            'query' => '{}',
            '--hydrate' => 'array'
        ));
        $output = new BufferedOutput();
        $this->assertEquals(0, $this->app['console']->run($input, $output));
        echo($output->fetch());
    }

    public function testCacheClearMetadataCommand()
    {
        $input = new ArrayInput(array(
            'command' => 'doctrine:mongodb:cache:clear-metadata',
        ));
        $output = new BufferedOutput();
        $this->assertEquals(0, $this->app['console']->run($input, $output));
        echo($output->fetch());
    }

    public function testGenerateHydratorsCommand()
    {
        $input = new ArrayInput(array(
            'command' => 'doctrine:mongodb:generate:hydrators',
        ));
        $output = new BufferedOutput();
        $this->assertEquals(0, $this->app['console']->run($input, $output));
        echo($output->fetch());
    }

    public function testGenerateProxiesCommand()
    {
        $input = new ArrayInput(array(
            'command' => 'doctrine:mongodb:generate:proxies',
        ));
        $output = new BufferedOutput();
        $this->assertEquals(0, $this->app['console']->run($input, $output));
        echo($output->fetch());
    }

    public function testMappingInfoCommand()
    {
        $input = new ArrayInput(array(
            'command' => 'doctrine:mongodb:mapping:info',
        ));
        $output = new BufferedOutput();
        $this->assertEquals(0, $this->app['console']->run($input, $output));
        echo($output->fetch());
    }

    public function createApplication()
    {
        $app = new Application();

        $app->register(new DoctrineMongoDbProvider(), array(
            'mongodb.options' => array(
                'server' => 'mongodb://localhost:27017',
                'options' => array(
                    'username' => 'root',
                    'password' => 'root',
                    'db' => 'admin'
                )
            )
        ));
        $app->register(new DoctrineMongoDbOdmProvider(), array(
            "mongodbodm.proxies_dir" => $this->getCacheDir() . '/doctrine/proxies',
            "mongodbodm.hydrator_dir" => $this->getCacheDir() . '/doctrine/hydrator',
            'mongodbodm.dm.options' => array(
                'mappings' => array(
                    array(
                        'type' => 'annotation',
                        'namespace' => 'Saxulum\Tests\DoctrineMongodbOdmCommands\Document',
                        'path' => __DIR__.'/../Document',
                        'use_simple_annotation_reader' => false,
                    )
                )
            )
        ));
        $app->register(new DoctrineMongodbOdmManagerRegistryProvider());
        $app->register(new ConsoleProvider());

        $app['console'] = $app->share(
            $app->extend('console', function (ConsoleApplication $consoleApplication) use ($app) {
                $consoleApplication->setAutoExit(false);
                $helperSet = $consoleApplication->getHelperSet();
                $helperSet->set(new ManagerRegistryHelper($app['doctrine_mongodb']), 'doctrine_mongodb');

                return $consoleApplication;
            })
        );

        $app['console.commands'] = $app->share(
            $app->extend('console.commands', function ($commands) use ($app) {
                $commands[] = new CreateSchemaDoctrineODMCommand;
                $commands[] = new UpdateSchemaDoctrineODMCommand;
                $commands[] = new DropSchemaDoctrineODMCommand;
                $commands[] = new QueryDoctrineODMCommand;
                $commands[] = new ClearMetadataCacheDoctrineODMCommand;
                $commands[] = new GenerateHydratorsDoctrineODMCommand;
                $commands[] = new GenerateProxiesDoctrineODMCommand;
                $commands[] = new InfoDoctrineODMCommand;

                return $commands;
            })
        );

        $app->boot();

        return $app;
    }

    /**
     * @return string
     */
    protected function getTestDirectoryPath()
    {
        return realpath(__DIR__.'/..');
    }

    /**
     * @return string
     */
    protected function getCacheDir()
    {
        $cacheDir = $this->getTestDirectoryPath() . '/../cache';
        if (!is_dir($cacheDir)) {
            mkdir($cacheDir, 0777, true);
        }

        return $cacheDir;
    }
}
