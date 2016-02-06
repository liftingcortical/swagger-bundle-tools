<?php
/*
 * This file is part of the KleijnWeb\SwaggerBundleTools package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace KleijnWeb\SwaggerBundleTools\Tests\Generator;

use JMS\Serializer\SerializerBuilder;
use KleijnWeb\SwaggerBundleTools\Generator\ResourceGenerator;
use KleijnWeb\SwaggerBundleTools\Tests\Functional\PetStore\PetStoreBundle;

/**
 * @author John Kleijn <john@kleijnweb.nl>
 */
class ResourceGeneratorJmsSerializerCompatibilityTest extends GeneratorTest
{
    protected function setUp()
    {
        $bundle = new PetStoreBundle();
        $document = $this->getPetStoreDocument();
        $generator = new ResourceGenerator();
        $generator->setSkeletonDirs('src/Resources/skeleton');
        $generator->generate($bundle, $document, 'Model\Jms');

        require_once $bundle->getPath() . '/Model/Jms/Pet.php';
        require_once $bundle->getPath() . '/Model/Jms/Tag.php';
        require_once $bundle->getPath() . '/Model/Jms/Category.php';
    }

    /**
     * @test
     */
    public function canSerializeAPet()
    {
        $pet = new \KleijnWeb\SwaggerBundleTools\Tests\Functional\PetStore\Model\Jms\Pet();
        $pet
            ->setId(1234567)
            ->setName('doggie')
            ->setPhotourls(['/a/b/c', '/d/e/f'])
            ->setTags([
                (new \KleijnWeb\SwaggerBundleTools\Tests\Functional\PetStore\Model\Jms\Tag())->setName('purebreeds'),
                (new \KleijnWeb\SwaggerBundleTools\Tests\Functional\PetStore\Model\Jms\Tag())->setName('puppies')
            ])
            ->setCategory(
                (new \KleijnWeb\SwaggerBundleTools\Tests\Functional\PetStore\Model\Jms\Category())->setName('Dogs')
            );

        $serializer = SerializerBuilder::create()->build();
        $actual = json_decode($serializer->serialize($pet, 'json'), true);
        $expected = [
            'id'         => 1234567,
            'category'   => ['name' => 'Dogs'],
            'name'       => 'doggie',
            'photo_urls' => ['/a/b/c', '/d/e/f'],
            'tags'       => [
                ['name' => 'purebreeds'],
                ['name' => 'puppies'],
            ]

        ];
        $this->assertSame($expected, $actual);
    }

    /**
     * @test
     */
    public function canDeserializeAPet()
    {
        $data = [
            'id'         => 1234567,
            'category'   => ['name' => 'Dogs'],
            'name'       => 'doggie',
            'photo_urls' => ['/a/b/c', '/d/e/f'],
            'tags'       => [
                ['name' => 'purebreeds'],
                ['name' => 'puppies'],
            ]
        ];

        $serializer = SerializerBuilder::create()->build();
        $actual = $serializer->deserialize(
            json_encode($data),
            'KleijnWeb\SwaggerBundleTools\Tests\Functional\PetStore\Model\Jms\Pet',
            'json'
        );

        $expected = new \KleijnWeb\SwaggerBundleTools\Tests\Functional\PetStore\Model\Jms\Pet();
        $expected
            ->setId(1234567)
            ->setName('doggie')
            ->setPhotourls(['/a/b/c', '/d/e/f'])
            ->setTags([
                (new \KleijnWeb\SwaggerBundleTools\Tests\Functional\PetStore\Model\Jms\Tag())->setName('purebreeds'),
                (new \KleijnWeb\SwaggerBundleTools\Tests\Functional\PetStore\Model\Jms\Tag())->setName('puppies')
            ])
            ->setCategory(
                (new \KleijnWeb\SwaggerBundleTools\Tests\Functional\PetStore\Model\Jms\Category())->setName('Dogs')
            );

        $this->assertEquals($expected, $actual);
    }
}
