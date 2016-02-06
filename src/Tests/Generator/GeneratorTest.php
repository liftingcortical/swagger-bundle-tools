<?php
/*
 * This file is part of the KleijnWeb\SwaggerBundleTools package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace KleijnWeb\SwaggerBundleTools\Tests\Generator;

use KleijnWeb\SwaggerBundle\Document\SwaggerDocument;

/**
 * @author John Kleijn <john@kleijnweb.nl>
 */
abstract class GeneratorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return SwaggerDocument
     */
    protected function getPetStoreDocument()
    {
        return new SwaggerDocument('src/Tests/Functional/PetStore/app/swagger/petstore.yml');
    }
}
