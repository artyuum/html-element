<?php

namespace Tests\HtmlElement;

use Artyum\HtmlElement\Attribute;
use Artyum\HtmlElement\Element;
use InvalidArgumentException;
use LogicException;
use PHPUnit\Framework\TestCase;
use stdClass;

class AttributeTest extends TestCase
{
    /**
     * Tests that the build() method can generate the property string representation of the attribute.
     */
    public function testBuildWithSimpleValue()
    {
        $attribute = new Attribute('type', 'text');

        $this->assertEquals(
            'type="text"',
            $attribute->build()
        );
    }

    /**
     * Tests the generated value when "true" is using as value.
     */
    public function testTrueAsValue()
    {
        $attribute = new Attribute('required', true);

        $this->assertEquals(
            'required',
            $attribute->build()
        );
    }

    /**
     * Tests the generated value when "true" is using as value.
     */
    public function testFalseAsValue()
    {
        $attribute = new Attribute('autocapitalize', false);

        $this->assertEquals(
            'autocapitalize="off"',
            $attribute->build()
        );
    }

    /**
     * Tests that the exception is thrown when a non scalar value is passed.
     */
    public function testObjectAsValue()
    {
        $this->expectException(InvalidArgumentException::class);

        new Attribute('type', new stdClass());
    }

    /**
     * Tests that the default separator works when the attribute value is an array.
     */
    public function testDefaultSeparator()
    {
        $attribute = new Attribute('test', [
            'first',
            'second'
        ]);

        $this->assertEquals(
            'test="first;second"',
            $attribute->build()
        );
    }

    /**
     * Tests that the specified separator works when the attribute value is an array.
     */
    public function testCustomSeparator()
    {
        $attribute = new Attribute('test', [
            'first',
            'second'
        ], ' ');

        $this->assertEquals(
            'test="first second"',
            $attribute->build()
        );
    }

    /**
     * Tests that an exception is thrown when trying to get build an attribute without setting the name.
     */
    public function testBuildAttributeWithoutName()
    {
        $this->expectException(LogicException::class);

        $attribute = new Attribute();
        $attribute->build();
    }

    /**
     * Tests that an exception is thrown when trying to get build an attribute without setting the value.
     */
    public function testBuildAttributeWithoutValue()
    {
        $this->expectException(LogicException::class);

        $attribute = new Attribute('test');
        $attribute->build();
    }
}
