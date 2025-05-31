<?php

namespace Tests\Unit;

use App\Helpers\CalculatorHelper;
use PHPUnit\Framework\TestCase;

class CalculatorHelperTest extends TestCase
{
    public function testMultiplyNegativeNumbers()
    {
        $this->assertEquals(20, CalculatorHelper::perkalian(-4, -5));
    }

    public function testSubtractResultNegative()
    {
        $this->assertEquals(-2, CalculatorHelper::subtract(3, 5));
    }

    public function testAddLargeNumbers()
    {
        $this->assertEquals(1000000000, CalculatorHelper::add(500000000, 500000000));
    }
}
