<?php

use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Kernel;

class TestKernel extends Kernel
{
    /**
     * {@inheritdoc}
     */
    public function registerBundles()
    {
        $bundles = [
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle(),
            new KleijnWeb\SwaggerBundleTools\Tests\Functional\PetStore\PetStoreBundle(),
            new KleijnWeb\SwaggerBundleTools\KleijnWebSwaggerBundleToolsBundle()
        ];

        return $bundles;
    }

    /**
     * {@inheritdoc}
     */
    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__ . '/config_' . $this->getEnvironment() . '.yml');
    }
}
