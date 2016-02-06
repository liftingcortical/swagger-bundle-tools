<?php
/*
 * This file is part of the KleijnWeb\SwaggerBundleTools package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace KleijnWeb\SwaggerBundleTools\Tests\Generator;

use KleijnWeb\SwaggerBundleTools\Tests\Functional\PetStore\PetStoreBundle;
use KleijnWeb\SwaggerBundleTools\Generator\ResourceGenerator;

/**
 * @author John Kleijn <john@kleijnweb.nl>
 */
class ResourceGeneratorTest extends GeneratorTest
{
    /**
     * @test
     */
    public function canRenderResourcesFromPetStore()
    {
        $bundle = new PetStoreBundle();
        $document = $this->getPetStoreDocument();
        $generator = new ResourceGenerator();
        $generator->setSkeletonDirs('src/Resources/skeleton');
        $generator->generate($bundle, $document, 'Foo\Bar');
        $files = [
            'User.php',
            'Category.php',
            'Pet.php',
            'Order.php',
        ];

        foreach ($files as $file) {
            $filePathName = $bundle->getPath() . '/Foo/Bar/' . $file;
            $this->assertTrue(
                file_exists($filePathName),
                sprintf('%s has not been generated', $filePathName)
            );
            $content = file_get_contents($filePathName);
            $this->assertContains("namespace {$bundle->getNamespace()}\\Foo\\Bar;", $content);
        }
    }
}
