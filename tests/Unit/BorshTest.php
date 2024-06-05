<?php

declare(strict_types=1);

namespace MultipleChain\SolanaSDK\Tests\Unit;

use PHPUnit\Framework\TestCase;
use MultipleChain\SolanaSDK\Borsh\Borsh;
use MultipleChain\SolanaSDK\Borsh\BorshObject;

class Test
{
    use BorshObject;

    public mixed $x;
    public mixed $y;
    public mixed $z;
    public mixed $a;
    public mixed $b;
    public mixed $c;
    public mixed $q;
}

// @phpcs:ignore
class TestWithPrivateVariable
{
    use BorshObject;

    private mixed $m;

    /**
     * @param mixed $m
     * @return void
     */
    public function setM(mixed $m): void
    {
        $this->m = $m;
    }

    /**
     * @return mixed
     */
    public function getM(): mixed
    {
        return $this->m;
    }
}

// @phpcs:ignore
class TestWithConstructorParameters
{
    use BorshObject;

    private mixed $m;

    /**
     * @param mixed $m
     */
    public function __construct(mixed $m)
    {
        $this->m = $m;
    }

    /**
     * @return mixed
     */
    public function getM(): mixed
    {
        return $this->m;
    }

    /**
     * @return static
     */
    public static function borshConstructor(): static
    {
        return new static(null);
    }
}

// @phpcs:ignore
class BorshTest extends TestCase
{
    /**
     * @test
     * @return void
     */
    // @phpcs:ignore
    public function it_serialize_object(): void
    {
        $value = new Test();
        $value->x = 255;
        $value->y = 20;
        $value->z = '123';
        $value->a = 12.987;
        $value->b = -121;
        $value->c = -20;
        $value->q = [1, 2, 3];

        $schema = [
            Test::class => [
                'kind' => 'struct',
                'fields' => [
                    ['x', 'u8'],
                    ['y', 'u64'],
                    ['z', 'string'],
                    ['a', 'f64'],
                    ['b', 'i32'],
                    ['c', 'i8'],
                    ['q', [3]],
                ],
            ],
        ];

        $buffer = Borsh::serialize($schema, $value);
        $newValue = Borsh::deserialize($schema, Test::class, $buffer);

        $this->assertInstanceOf(Test::class, $newValue);
        $this->assertEquals(255, $newValue->x);
        $this->assertEquals(20, $newValue->y);
        $this->assertEquals('123', $newValue->z);
        $this->assertEquals(12.987, $newValue->a);
        $this->assertEquals(-121, $newValue->b);
        $this->assertEquals(-20, $newValue->c);
        $this->assertEquals([1, 2, 3], $newValue->q);
    }

    /**
     * @test
     * @return void
     */
    // @phpcs:ignore
    public function it_serialize_optional_field(): void
    {
        $schema = [
            Test::class => [
                'kind' => 'struct',
                'fields' => [
                    ['x', [
                        'kind' => 'option',
                        'type' => 'string',
                    ]],
                ],
            ],
        ];

        $value = new Test();
        $value->x = 'bacon';
        $buffer = Borsh::serialize($schema, $value);
        $newValue = Borsh::deserialize($schema, Test::class, $buffer);
        $this->assertEquals('bacon', $newValue->x);

        $value = new Test();
        $value->x = null;
        $buffer = Borsh::serialize($schema, $value);
        $newValue = Borsh::deserialize($schema, Test::class, $buffer);
        $this->assertNull($newValue->x);
    }

    /**
     * @test
     * @return void
     */
    // @phpcs:ignore
    public function it_serialize_deserialize_fixed_array(): void
    {
        $schema = [
            Test::class => [
                'kind' => 'struct',
                'fields' => [
                    ['x', ['string', 2]],
                ],
            ],
        ];

        $value = new Test();
        $value->x = ['hello', 'world'];

        $buffer = Borsh::serialize($schema, $value);
        $newValue = Borsh::deserialize($schema, Test::class, $buffer);

        $this->assertEquals([5, 0, 0, 0, 104, 101, 108, 108, 111, 5, 0, 0, 0, 119, 111, 114, 108, 100], $buffer);
        $this->assertEquals(['hello', 'world'], $newValue->x);
    }

    /**
     * @test
     * @return void
     */
    // @phpcs:ignore
    public function it_serialize_deserialize_invisible_properties(): void
    {
        $value = new TestWithPrivateVariable();
        $value->setM(255);

        $schema = [
            TestWithPrivateVariable::class => [
                'kind' => 'struct',
                'fields' => [
                    ['m', 'u8'],
                ],
            ],
        ];

        $buffer = Borsh::serialize($schema, $value);
        $newValue = Borsh::deserialize($schema, TestWithPrivateVariable::class, $buffer);

        $this->assertInstanceOf(TestWithPrivateVariable::class, $newValue);
        $this->assertEquals(255, $newValue->getM());
    }

    /**
     * @test
     * @return void
     */
    // @phpcs:ignore
    public function it_serialize_deserialize_handles_constructor_with_parameters(): void
    {
        $value = new TestWithConstructorParameters(255);

        $schema = [
            TestWithConstructorParameters::class => [
                'kind' => 'struct',
                'fields' => [
                    ['m', 'u8'],
                ],
            ],
        ];

        $buffer = Borsh::serialize($schema, $value);
        $newValue = Borsh::deserialize($schema, TestWithConstructorParameters::class, $buffer);

        $this->assertInstanceOf(TestWithConstructorParameters::class, $newValue);
        $this->assertEquals(255, $newValue->getM());
    }
}
