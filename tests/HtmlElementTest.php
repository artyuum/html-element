<?php

namespace Tests;

use stdClass;
use PHPUnit\Framework\TestCase;
use Artyum\HtmlElement\HtmlElement;
use Artyum\HtmlElement\Exceptions\SelfClosingTagException;
use Artyum\HtmlElement\Exceptions\WrongArgumentTypeException;

class HtmlElementTest extends TestCase
{

    /**
     * @test
     */
    public function testElementCreation()
    {
        $element = new HtmlElement('div');

        $this->assertEquals(
            '<div></div>',
            $element
        );
    }

    /**
     * @test
     */
    public function testElementCreationWithContent()
    {
        $element = new HtmlElement('div');

        $this->assertEquals(
            '<div>test</div>',
            $element
                ->setContent('test')
        );
    }

    /**
     * @test
     */
    public function testElementCreationWithAttributes()
    {
        $element = new HtmlElement('div');

        $this->assertEquals(
            '<div class="test" style="width: 100px;"></div>',
            $element->setAttributes([
                'class' => 'test',
                'style' => 'width: 100px;'
            ])
        );
    }

    /**
     * @test
     */
    public function testElementCreationWithBooleanAttributes()
    {
        $element = new HtmlElement('input');

        $this->assertEquals(
            '<input autocapitalize="off" autocomplete="off" required="required">',
            $element->setAttributes([
                'autocapitalize'    => false,
                'autocomplete'      => false,
                'required'          => true
            ])
        );
    }

    /**
     * @test
     */
    public function testGetAttributes()
    {
        $element = new HtmlElement('input');

        $this->assertEquals(
            [
                'class' => 'test',
                'style' => 'width: 100px;',
                'autocapitalize'    => false,
                'autocomplete'      => false,
                'required'          => true
            ],
            $element
                ->setAttributes([
                    'class' => 'test',
                    'style' => 'width: 100px;',
                    'autocapitalize'    => false,
                    'autocomplete'      => false,
                    'required'          => true
                ])
                ->getAttributes()
        );
    }

    /**
     * @test
     */
    public function testGetElementContent()
    {
        $element = new HtmlElement('div');

        $this->assertEquals(
            'test',
            $element
                ->setContent('test')
                ->getContent()
        );
    }

    /**
     * @test
     */
    public function testSetContentOnSelfClosingTag()
    {
        $this->expectException(SelfClosingTagException::class);

        $element = new HtmlElement('input');
        $element->setContent('test');
    }

    /**
     * @test
     */
    public function testHTMLElementAsContent()
    {
        $element = new HtmlElement('div');
        $secondElement = new HtmlElement('div');

        $this->assertEquals(
            '<div><div></div></div>',
            $element->setContent($secondElement)
        );
    }

    /**
     * @test
     */
    public function testMultipleContent()
    {
        $element = new HtmlElement('div');
        $secondElement = new HtmlElement('div');

        $this->assertEquals(
            '<div><div></div>test</div>',
            $element->setContent($secondElement, 'test')
        );
    }

    /**
     * @test
     */
    public function testBooleanAsContent()
    {

        $this->expectException(WrongArgumentTypeException::class);

        $element = new HtmlElement('div');
        $element->setContent(true);
    }

    /**
     * @test
     */
    public function testNullAsContent()
    {

        $this->expectException(WrongArgumentTypeException::class);

        $element = new HtmlElement('div');
        $element->setContent(null);
    }

    /**
     * @test
     */
    public function testIntegerAsContent()
    {

        $this->expectException(WrongArgumentTypeException::class);

        $element = new HtmlElement('div');
        $element->setContent(1);
    }

    /**
     * @test
     */
    public function testFloatAsContent()
    {

        $this->expectException(WrongArgumentTypeException::class);

        $element = new HtmlElement('div');
        $element->setContent(10.365);
    }

    /**
     * @test
     */
    public function testArrayAsContent()
    {

        $this->expectException(WrongArgumentTypeException::class);

        $element = new HtmlElement('div');
        $element->setContent(array());
    }

    /**
     * @test
     */
    public function testObjectAsContent()
    {

        $this->expectException(WrongArgumentTypeException::class);

        $element = new HtmlElement('div');
        $element->setContent(new stdClass());
    }

}
