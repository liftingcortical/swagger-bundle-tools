<?php
/*
 * This file is part of the KleijnWeb\SwaggerBundle package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace KleijnWeb\SwaggerBundleTools\Tests\Functional;

use KleijnWeb\SwaggerBundleTools\Command\AmendSwaggerDocumentCommand;
use KleijnWeb\SwaggerBundleTools\Command\GenerateResourceClassesCommand;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Bundle\FrameworkBundle\Console\Application;

/**
 * @author John Kleijn <john@kleijnweb.nl>
 */
class CommandIntegrationTest extends KernelTestCase
{
    /**
     * @test
     */
    public function canRunAmendSwaggerDocumentCommand()
    {
        $commandName = AmendSwaggerDocumentCommand::NAME;
        $diKey = 'swagger.utils.command.amend_swagger';

        $mockFixer = $this
            ->getMockBuilder('KleijnWeb\SwaggerBundleTools\DocumentFixer\Fixer')
            ->disableOriginalConstructor()
            ->getMock();

        $diMocks = [
            'swagger.document.repository'                        => $this->getRepositoryStub(),
            'swagger.utils.document_fixer.swagger_bundle_response' => $mockFixer
        ];

        $this->runWithMocks($diMocks, $diKey, $commandName, ['file' => '/fake']);
    }

    /**
     * @test
     */
    public function canRunGenerateResourceClassesCommand()
    {
        $commandName = GenerateResourceClassesCommand::NAME;
        $diKey = 'swagger.utils.command.generate_resources';

        $mockGenerator = $this
            ->getMockBuilder('KleijnWeb\SwaggerBundleTools\Generator\ResourceGenerator')
            ->disableOriginalConstructor()
            ->getMock();

        $diStubs = [
            'swagger.document.repository'    => $this->getRepositoryStub(),
            'swagger.utils.resource_generator' => $mockGenerator
        ];

        $this->runWithMocks($diStubs, $diKey, $commandName, ['file' => '/fake', 'bundle' => 'PetStoreBundle']);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function getRepositoryStub()
    {
        $mockStub = $this
            ->getMockBuilder('KleijnWeb\SwaggerBundle\Document\DocumentRepository')
            ->disableOriginalConstructor()
            ->getMock();

        $mockStub->expects($this->once())->method('get')->willReturn(
            $this
                ->getMockBuilder('KleijnWeb\SwaggerBundle\Document\SwaggerDocument')
                ->disableOriginalConstructor()
                ->getMock()
        );

        return $mockStub;
    }

    /**
     * @param array  $diMocks
     * @param string $diKey
     * @param string $commandName
     * @param array  $arguments
     *
     * @return int
     */
    private function runWithMocks(array $diMocks, $diKey, $commandName, array $arguments)
    {
        $kernel = $this->createKernel();
        $kernel->boot();
        $container = $kernel->getContainer();
        foreach ($diMocks as $key => $mock) {
            $container->set($key, $mock);
        }

        $application = new Application($kernel);
        $application->add($container->get($diKey));

        $command = $application->find($commandName);
        $commandTester = new CommandTester($command);

        return $commandTester->execute($arguments);
    }
}
