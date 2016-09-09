<?php
/*
 * This file is part of the KleijnWeb\SwaggerBundleTools package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace KleijnWeb\SwaggerBundleTools\Tests\Document;

use KleijnWeb\PhpApi\Descriptions\Description\Repository;
use KleijnWeb\SwaggerBundleTools\Command\GenerateResourceClassesCommand;
use KleijnWeb\SwaggerBundleTools\Generator\ResourceGenerator;
use KleijnWeb\SwaggerBundle\Document\DocumentRepository;
use org\bovigo\vfs\vfsStreamDirectory;
use org\bovigo\vfs\vfsStreamWrapper;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * @author John Kleijn <john@kleijnweb.nl>
 */
class GenerateResourceClassesCommandTest extends KernelTestCase
{
    /**
     * @var CommandTester
     */
    private $commandTester;

    /**
     * Set up the command tester
     */
    protected function setUp()
    {
        self::bootKernel();
        $application = new Application(self::$kernel);

        $application->add(
            new GenerateResourceClassesCommand(new Repository(), new ResourceGenerator())
        );

        $command = $application->find(GenerateResourceClassesCommand::NAME);
        $this->commandTester = new CommandTester($command);
    }

    /**
     * @test
     */
    public function canExecute()
    {
        $petStoreDocumentPath = __DIR__ . '/../Functional/PetStore/app/swagger/petstore.yml';
        vfsStreamWrapper::register();
        vfsStreamWrapper::setRoot(new vfsStreamDirectory('willAddResponsesToDocument'));

        $namespace = 'GenerateResourceClassesCommandTest';
        $this->commandTester->execute(
            [
                'command'     => GenerateResourceClassesCommand::NAME,
                'file'        => $petStoreDocumentPath,
                'bundle'      => 'PetStoreBundle',
                '--namespace' => $namespace
            ]
        );
        $bundle = self::$kernel->getBundle('PetStoreBundle');
        $filePathName = $bundle->getPath() . '/GenerateResourceClassesCommandTest/Pet.php';

        $this->assertTrue(
            file_exists($filePathName),
            sprintf('%s has not been generated', $filePathName)
        );
        $content = file_get_contents($filePathName);
        $this->assertContains("namespace {$bundle->getNamespace()}\\GenerateResourceClassesCommandTest;", $content);
    }
}
