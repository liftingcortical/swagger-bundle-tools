<?php
/*
 * This file is part of the KleijnWeb\SwaggerBundleTools package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace KleijnWeb\SwaggerBundleTools\Generator;

use KleijnWeb\SwaggerBundle\Document\SwaggerDocument;
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
     * @param SwaggerDocument $document
     * @param string          $relativeNamespace
     */
    public function generate(BundleInterface $bundle, SwaggerDocument $document, $relativeNamespace = 'Model\Resources')
    {
        $dir = $bundle->getPath();

        $parameters = [
            'namespace' => $bundle->getNamespace(),
            'bundle' => $bundle->getName(),
            'resource_namespace' => $relativeNamespace,
        ];

        $definition = json_decode(json_encode($document->getDefinition()), true);

        foreach ($definition['definitions'] as $typeName => $spec) {
            $resourceFile = "$dir/".str_replace('\\', '/', $relativeNamespace)."/$typeName.php";
            $this->renderFile(
                'resource.php.twig',
                $resourceFile,
                array_merge($parameters, $this->castObjectToArray($spec), ['resource' => $typeName, 'resource_class' => $typeName])
            );
        }
    }

    protected function castObjectToArray($object)
    {
        if (is_object($object)) {
            $object = (array) $object;
        }
        if (is_array($object)) {
            $new = array();
            foreach ($object as $key => $val) {
                $new[$key] = $this->castObjectToArray($val);
            }
        } else {
            $new = $object;
        }

        return $new;
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
