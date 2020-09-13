<?php

namespace Artyum\HtmlElement\Tests;

use Artyum\HtmlElement\Exceptions\SelfClosingTagException;
use Artyum\HtmlElement\Exceptions\WrongAttributeValueException;
use Artyum\HtmlElement\HtmlElement;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use stdClass;

class HtmlElementTest extends TestCase
{
    public function testSimpleElement()
    {
        $element = new HtmlElement('div');

        $this->assertEquals(
            '<div></div>',
            $element->toHtml()
        );
    }

    public function testSelfClosingElement()
    {
        $element = new HtmlElement('input');

        $this->assertEquals(
            '<input>',
            $element->toHtml()
        );
    }

    public function testAutocloseOption()
    {
        $element = new HtmlElement('div', [
            'autoclose' => false,
        ]);

        $this->assertEquals(
            '<div>',
            $element->toHtml()
        );
    }

    public function testElementWithAttributes()
    {
        $element = new HtmlElement('div');

        $this->assertEquals(
            '<div class="test" title="test"></div>',
            $element
                ->addAttributes([
                    'class' => 'test',
                    'title' => 'test',
                ])
                ->toHtml()
        );
    }

    public function testElementWithArrayAsAttributeValue()
    {
        $element = new HtmlElement('div');

        $this->assertEquals(
            '<div style="width: 100px; height: 100px;"></div>',
            $element
                ->addAttributes([
                    'style' => [
                        'width'  => '100px',
                        'height' => '100px',
                    ],
                ])
                ->toHtml()
        );
    }

    public function testAttributesMerging()
    {
        $element = new HtmlElement('input');

        $this->assertEquals(
            '<input class="test" required>',
            $element
                ->addAttributes([
                    'class' => 'test',
                ])
                ->addAttributes([
                    'required' => true,
                ])
                ->toHtml()
        );
    }

    public function testBooleanAttributes()
    {
        $element = new HtmlElement('input');

        $this->assertEquals(
            '<input autocapitalize="off" autocomplete="off" required>',
            $element
                ->addAttributes([
                    'autocapitalize' => false,
                    'autocomplete'   => false,
                    'required'       => true,
                ])
                ->toHtml()
        );
    }

    public function testSimpleContent()
    {
        $element = new HtmlElement('div');

        $this->assertEquals(
            '<div>test</div>',
            $element
                ->setContent('test')
                ->toHtml()
        );
    }

    public function testMultipleContent()
    {
        $element = new HtmlElement('div');
        $string = 'test';
        $integer = 1;
        $float = 1.1;

        $this->assertEquals(
            '<div><div></div>test11.1</div>',
            $element
                ->setContent($element, $string, $integer, $float)
                ->toHtml()
        );
    }

    public function testNestedContent()
    {
        $element = new HtmlElement('div');

        $element->setContent(
            (new HtmlElement('div'))->setContent(new HtmlElement('div'))
        );

        $this->assertEquals(
            '<div><div><div></div></div></div>',
            $element->toHtml()
        );
    }

    public function testBooleanAsContent()
    {
        $this->expectException(InvalidArgumentException::class);

        $element = new HtmlElement('div');
        $element->setContent(true);
    }

    public function testNullAsContent()
    {
        $this->expectException(InvalidArgumentException::class);

        $element = new HtmlElement('div');
        $element->setContent(null);
    }

    public function testArrayAsContent()
    {
        $this->expectException(InvalidArgumentException::class);

        $element = new HtmlElement('div');
        $element->setContent([]);
    }

    public function testObjectAsContent()
    {
        $this->expectException(InvalidArgumentException::class);

        $element = new HtmlElement('div');
        $element->setContent(new stdClass());
    }

    public function testSetContentOnSelfClosingTag()
    {
        $this->expectException(SelfClosingTagException::class);

        $element = new HtmlElement('input');
        $element->setContent('test');
    }

    public function testArrayAsAttributeValueButWithoutAProperName()
    {
        $this->expectException(WrongAttributeValueException::class);

        $element = new HtmlElement('input');
        $element->addAttributes([
            'not-style' => [
                'test'
            ]
        ]);
    }
}
