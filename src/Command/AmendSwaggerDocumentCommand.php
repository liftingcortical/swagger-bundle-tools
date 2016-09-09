<?php
/*
 * This file is part of the KleijnWeb\SwaggerBundleTools package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace KleijnWeb\SwaggerBundleTools\Command;

use KleijnWeb\PhpApi\Descriptions\Description\Repository;
use KleijnWeb\SwaggerBundleTools\DocumentFixer\Fixer;
use KleijnWeb\SwaggerBundleTools\DocumentFixer\Utils;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Yaml;

/**
 * @author John Kleijn <john@kleijnweb.nl>
 */
class AmendSwaggerDocumentCommand extends Command
{
    const NAME = 'document:amend';

    /**
     * @var Repository
     */
    private $documentRepository;

    /**
     * @var Fixer
     */
    private $fixer;

    /**
     * @param Repository $documentRepository
     * @param Fixer      $fixer
     */
    public function __construct(Repository $documentRepository, Fixer $fixer)
    {
        parent::__construct(self::NAME);

        $this
            ->setDescription('Make your Swagger definition reflect your apps in- and output')
            ->setHelp(
                "Will update your definition with predefined SwaggerBundleTools responses,"
                ." as well as update it to reflect any changes in your DTOs, should they exist.\n\n"
                ."This is a development tool and will only work with require-dev dependencies included"
            )
            ->addArgument('file', InputArgument::REQUIRED, 'File path to the Swagger document')
            ->addOption(
                'out',
                'o',
                InputOption::VALUE_REQUIRED,
                'Write the resulting document to this location (will overwrite existing by default)'
            );

        $this->documentRepository = $documentRepository;
        $this->fixer = $fixer;
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $description = $this->documentRepository->get($input->getArgument('file'));
        $this->fixer->fix($description->getDocument());
        $yamlContent = Yaml::dump(Utils::objectToArray($description->getDocument()->getDefinition()), 2, 4);
        $yamlDescriptorContent = file_get_contents($input->getOption('out'));
        if (md5($yamlContent) !== md5($yamlDescriptorContent) && isset($yamlContent[0])) {
            file_put_contents($yamlContent, $input->getOption('out'));
        }
    }
}
