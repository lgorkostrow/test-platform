<?php

declare(strict_types=1);

namespace App\Tests\Application\Utils;

use App\Application\Utils\ArrayUtils;
use PHPUnit\Framework\TestCase;

class ArrayUtilsTest extends TestCase
{
    /**
     * @dataProvider isAssocArrayDataProvider
     * @param array $array
     * @param bool $result
     */
    public function testIsAssocArray(array $array, bool $result)
    {
        $this->assertEquals(
            $result,
            ArrayUtils::isAssocArray($array)
        );
    }

    /**
     * @dataProvider getFirstStringKeyInAssocArrayDataProvider
     * @param array $array
     * @param string $result
     */
    public function testGetFirstStringKeyInAssocArray(array $array, string $result)
    {
        $this->assertEquals(
            $result,
            ArrayUtils::getFirstStringKeyInAssocArray($array)
        );
    }

    /**
     * @dataProvider flattenDataProvider
     *
     * @param bool $useKeys
     * @param array $data
     * @param array $result
     */
    public function testFlatten(bool $useKeys, array $data, array $result)
    {
        $this->assertEquals(
            $result,
            ArrayUtils::flatten($data, $useKeys),
        );
    }

    public function isAssocArrayDataProvider()
    {
        return [
            [
                'array' => [
                    'key' => 'value',
                    'key1' => 'value'
                ],
                'result' => true,
            ],
            [
                'array' => [123, 321],
                'result' => false,
            ],
        ];
    }

    public function getFirstStringKeyInAssocArrayDataProvider()
    {
        return [
            [
                'array' => [
                    'key' => 'value',
                    'key1' => 'value'
                ],
                'result' => 'key',
            ],
            [
                'array' => [
                    213,
                    'key' => 'value'
                ],
                'result' => 'key',
            ],
        ];
    }

    public function flattenDataProvider()
    {
        return [
            [
                'useKeys' => false,
                'data' => ['test' => ['wqe', 'qwe', 'test1' => ['asd']]],
                'result' => ['wqe', 'qwe', 'asd'],
            ],
            [
                'useKeys' => false,
                'data' => ['test' => [1, 2], 'test1' => [3]],
                'result' => [1, 2, 3],
            ],
            [
                'useKeys' => true,
                'data' => ['test' => ['a' => 1, 'b' => 2], 'test1' => ['c' => 3]],
                'result' => ['a' => 1, 'b' => 2, 'c' => 3],
            ],
        ];
    }
}
