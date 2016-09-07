<?php

namespace KleijnWeb\SwaggerBundleTools\Twig;

use Doctrine\Common\Util\Inflector;

/**
 * Class InflectorExtension
 *
 * @package KleijnWeb\SwaggerBundleTools\Twig
 */
class InflectorExtension extends \Twig_Extension
{
    /**
     * {@inheritdoc}
     */
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('klcamelize', array(Inflector::class, 'camelize')),
            new \Twig_SimpleFilter('klclassify', array(Inflector::class, 'classify')),
            new \Twig_SimpleFilter('klsingularize', array(Inflector::class, 'singularize')),
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return "app_extension";
    }

    /**
     * @param string $name
     *
     * @return string
     */
    public function camelizeFilter($name)
    {
        return Inflector::camelize($name);
    }
}
