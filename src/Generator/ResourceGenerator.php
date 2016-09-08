<?php
/*
 * This file is part of the KleijnWeb\SwaggerBundleTools package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace KleijnWeb\SwaggerBundleTools\Generator;

use KleijnWeb\PhpApi\Descriptions\Description\Description;
use KleijnWeb\SwaggerBundleTools\Twig\InflectorExtension;
use Sensio\Bundle\GeneratorBundle\Generator\Generator;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;

/**
 * @author John Kleijn <john@kleijnweb.nl>
 */
class ResourceGenerator extends Generator
{
    /**
     * @param BundleInterface $bundle
     * @param Description     $description
     * @param string          $relativeNamespace
     */
    public function generate(BundleInterface $bundle, Description $description, $relativeNamespace = 'Model\Resources')
    {
        $dir = $bundle->getPath();

        $parameters = [
            'namespace' => $bundle->getNamespace(),
            'bundle' => $bundle->getName(),
            'resource_namespace' => $relativeNamespace,
        ];

        $definition = $this->convertObjectToArray($description->getDocument()->getDefinition());

        foreach ($definition['definitions'] as $typeName => $spec) {
            $resourceFile = "$dir/".str_replace('\\', '/', $relativeNamespace)."/$typeName.php";
            $this->renderFile(
                'resource.php.twig',
                $resourceFile,
                array_merge(
                    $parameters,
                    $spec,
                    [
                        'resource' => $typeName,
                        'resource_class' => $typeName,
                    ]
                )
            );
        }
    }

    /**
     * The ugly hack of converting object to array
     *
     * @param $object
     *
     * @return mixed
     */
    protected function convertObjectToArray($object)
    {
        return json_decode(json_encode($object), true);
    }

    /**
     * Add InflectorExtension to Twig Environment
     *
     * @return \Twig_Environment
     */
    protected function getTwigEnvironment()
    {
        $twigEnvironment = parent::getTwigEnvironment();
        $twigEnvironment->addExtension(new InflectorExtension());

        return $twigEnvironment;
    }
}
