<?php

declare(strict_types=1);

namespace App\Tests\Application\Utils;

use App\Application\Http\Request\Advertisement\CreateAdvertisementRequest;
use App\Application\Utils\ObjectUtils;
use App\Domain\Advertisement\Entity\Category;
use PHPUnit\Framework\TestCase;

class ObjectUtilsTest extends TestCase
{
    /**
     * @dataProvider isEntityDataProvider
     *
     * @param $data
     * @param bool $result
     */
    public function testIsEntity($data, bool $result)
    {
        $this->assertEquals($result, ObjectUtils::isEntity($data));
    }

    /**
     * @dataProvider getIdFromEntityDataProvider
     *
     * @param $entity
     * @param string $id
     * @param bool $exception
     */
    public function testGetIdFromEntity($entity, string $id, bool $exception)
    {
        if ($exception) {
            $this->expectException(\InvalidArgumentException::class);
        }

        $entityId = ObjectUtils::getIdFromEntity($entity);

        $this->assertEquals($id, $entityId);
    }

    public function isEntityDataProvider()
    {
        $entity = (new \ReflectionClass(Category::class))->newInstanceWithoutConstructor();

        return [
            [
                'object' => 2,
                'result' => false,
            ],
            [
                'object' => '',
                'result' => false,
            ],
            [
                'object' => [],
                'result' => false,
            ],
            [
                'object' => new \stdClass(),
                'result' => false,
            ],
            [
                'object' => new CreateAdvertisementRequest(),
                'result' => false,
            ],
            [
                'object' => $entity,
                'result' => true,
            ],
        ];
    }

    public function getIdFromEntityDataProvider()
    {
        return [
            [
                'entity' => new Category('12', 'test'),
                'id' => '12',
                'exception' => false,
            ],
            [
                'entity' => new Category('test', 'test'),
                'id' => 'test',
                'exception' => false,
            ],
            [
                'entity' => 'test',
                'id' => '',
                'exception' => true,
            ],
            [
                'entity' => new CreateAdvertisementRequest(),
                'id' => '',
                'exception' => true,
            ],
            [
                'entity' => new \stdClass(),
                'id' => '',
                'exception' => true,
            ],
        ];
    }
}
