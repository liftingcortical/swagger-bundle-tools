<?php
/*
 * This file is part of the KleijnWeb\SwaggerBundleTools package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace KleijnWeb\SwaggerBundleTools\Console;

use Symfony\Bundle\FrameworkBundle\Console\Application as FrameworkApplication;
use Symfony\Component\Console\Application as ConsoleApplication;
use Symfony\Component\Console\Input\InputInterface;

/**
 * @author John Kleijn <john@kleijnweb.nl>
 */
class Application extends FrameworkApplication
{
    const NAME = 'SwaggerBundleTools';
    const VERSION = '0.9.0';

    /**
     * @param string $appDir
     * @param string $env
     * @param string $debug
     */
    public function __construct($appDir, $env, $debug = true)
    {
        FrameworkApplication::__construct(new \ToolsKernel($appDir, $env, $debug));
        ConsoleApplication::__construct(self::NAME, self::VERSION);
    }

    protected function registerCommands()
    {
        $container = $this->getKernel()->getContainer();

        if ($container->hasParameter('console.command.ids')) {
            foreach ($container->getParameter('console.command.ids') as $id) {
                if(0 === strpos($id, 'swagger.utils.command')){
                    $this->add($container->get($id));
                }
            }
        }
    }
}
