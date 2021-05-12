<?php

namespace Tests\HtmlElement;

use Artyum\HtmlElement\Attribute;
use Artyum\HtmlElement\Element;
use Artyum\HtmlElement\Exceptions\SelfClosingTagException;
use InvalidArgumentException;
use LogicException;
use PHPUnit\Framework\TestCase;
use stdClass;

class ElementTest extends TestCase
{
    /**
     * Tests the generation of HTML code for a simple element.
     */
    public function testSimpleElement()
    {
        $element = new Element('div');

        $this->assertEquals(
            '<div></div>',
            $element->toHtml()
        );
    }

    /**
     * Tests that a closing tag is not added to the ouput when it's a self-closing element.
     */
    public function testSelfClosingElement()
    {
        $element = new Element('input');

        $this->assertEquals(
            '<input>',
            $element->toHtml()
        );
    }

    /**
     * Tests that the closing tag is not added to the output when the option "autoclose" is set to "false".
     */
    public function testAutocloseOption()
    {
        $element = new Element('div', [
            'autoclose' => false,
        ]);

        $this->assertEquals(
            '<div>',
            $element->toHtml()
        );
    }

    /**
     * Tests the generation of an element with bunch of attributes.
     */
    public function testElementWithAttributes()
    {
        $element = new Element('div');

        $this->assertEquals(
            '<div class="test" title="test" style="width: 100px;height: 100px"></div>',
            $element
                ->addAttributes(
                    new Attribute('class', 'test'),
                    new Attribute('title', 'test'),
                    new Attribute('style', [
                        'width: 100px',
                        'height: 100px',
                    ])
                )
                ->toHtml()
        );
    }

    /**
     * Tests the generation of the element with a simple text as content.
     */
    public function testSimpleContent()
    {
        $element = new Element('div');

        $this->assertEquals(
            '<div>test</div>',
            $element
                ->addContent('test')
                ->toHtml()
        );
    }

    /**
     * Tests the generation of an element with multiple contents of different (valid) types.
     */
    public function testMultipleContents()
    {
        $element = new Element('div');
        $string = 'test';
        $integer = 1;
        $float = 1.1;

        $this->assertEquals(
            '<div><div></div>test11.1</div>',
            $element
                ->addContent($element)
                ->addContent($string)
                ->addContent($integer)
                ->addContent($float)
                ->toHtml()
        );
    }

    /**
     * Tests the generation of an element with an element as content.
     */
    public function testNestedContents()
    {
        $element = new Element('div');

        $element->addContent(
            (new Element('div'))->addContent(new Element('div'))
        );

        $this->assertEquals(
            '<div><div><div></div></div></div>',
            $element->toHtml()
        );
    }

    /**
     * Tests that an exception is thrown when passing "null" as content.
     */
    public function testNullAsContent()
    {
        $this->expectException(InvalidArgumentException::class);

        $element = new Element('div');
        $element->addContent(null);
    }

    /**
     * Tests that an exception is thrown when passing an array as content.
     */
    public function testArrayAsContent()
    {
        $this->expectException(InvalidArgumentException::class);

        $element = new Element('div');
        $element->addContent([]);
    }

    /**
     * Tests that an exception is thrown when passing an object as content.
     */
    public function testObjectAsContent()
    {
        $this->expectException(InvalidArgumentException::class);

        $element = new Element('div');
        $element->addContent(new stdClass());
    }

    /**
     * Tests that an exception is thrown trying to set a content for a self-closing element.
     */
    public function testAddingContentOnSelfClosingTag()
    {
        $this->expectException(SelfClosingTagException::class);

        $element = new Element('input');
        $element->addContent('test');
    }

    /**
     * Tests that an exception is thrown when trying to get the HTML of an element without setting the name.
     */
    public function testElementWithoutName()
    {
        $this->expectException(LogicException::class);

        $element = new Element();
        $element->toHtml();
    }
}
