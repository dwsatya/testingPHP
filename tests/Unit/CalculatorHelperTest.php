<?php

namespace Tests\Unit;

use App\Helpers\DiscountHelper;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class DiscountHelperTest extends TestCase
{
    public function testCalculateDiscount()
    {
        $this->assertEquals(90, DiscountHelper::calculate(100, 10));
    }

    public function testMultipleDiscountCalculations()
    {
        $cases = [
            [200, 20, 160],
            [500, 50, 250],
            [1000, 10, 900]
        ];

        foreach ($cases as [$price, $discount, $expected]) {
            $this->assertEquals($expected, DiscountHelper::calculate($price, $discount));
        }
    }

    public function testZeroDiscount()
    {
        $this->assertEquals(100, DiscountHelper::calculate(100, 0));
    }

    #[DataProvider('discountProvider')]
    public function testCalculateDiscountWithDataProvider($price, $discount, $expected)
    {
        $this->assertEquals($expected, DiscountHelper::calculate($price, $discount));
    }

    public static function discountProvider()
    {
        return [
            [100, 10, 90],
            [250, 25, 187.5],
            [500, 15, 425],
        ];
    }
}