<?php

namespace Artyum\HtmlElement;

use Artyum\HtmlElement\Exceptions\SelfClosingTagException;
use Artyum\HtmlElement\Exceptions\WrongArgumentTypeException;

/**
 * Class HtmlElement
 *
 * This class allows you to create HTML elements and assign attributes or content to them.
 */
class HtmlElement
{

    /**
     * @var string Should contain the name of the element to create.
     */
    private $name;

    /**
     * @var array Should contain an array of options.
     */
    private $options;

    /**
     * @var string Should contain the element content.
     */
    private $content;

    /**
     * @var array Should contain an array of attributes.
     */
    private $attributes;

    /**
     * @var array Should contain an array of self closing HTML tags.
     */
    private $selfClosingTags = [
        'area',
        'base',
        'br',
        'col',
        'embed',
        'hr',
        'img',
        'input',
        'link',
        'meta',
        'param',
        'source',
        'track',
        'wbr'
    ];

    /**
     * Gets the generated HTML.
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->build();
    }

    /**
     * HtmlElement constructor.
     *
     * @param string $name
     * @param array $options
     */
    public function __construct(string $name, array $options = null)
    {
        $this->name = $name;
        $this->options = $options;
    }

    /**
     * Get an array of attributes assigned to the element.
     *
     * @return array
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * Sets the element attributes.
     *
     * @param array $attributes
     * @return $this
     */
    public function setAttributes(array $attributes): HtmlElement
    {
        $this->attributes = $attributes;

        return $this;
    }

    /**
     * Gets the element content.
     *
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * Sets the element content.
     *
     * @param mixed ...$content
     * @return HtmlElement
     * @throws SelfClosingTagException
     * @throws WrongArgumentTypeException
     */
    public function setContent(...$content): HtmlElement
    {
        if ($this->isSelfClosing()) {
            throw new SelfClosingTagException('A self-closing tag cannot have a content.');
        }

        foreach ($content as $element) {
            if (is_string($element)) {
                $this->content .= $element;
            } elseif ($element instanceof $this) {
                $this->content .= $element->build();
            } else {
                throw new WrongArgumentTypeException('Argument should be either a string or an instance of HtmlElement.');
            }
        }

        return $this;
    }

    /**
     * Gets the element options.
     *
     * @return array
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * Checks if it's a self-closing tag.
     *
     * @return bool
     */
    private function isSelfClosing(): bool
    {
        return in_array($this->name, $this->selfClosingTags) || $this->options['autoclose'] === false;
    }

    /**
     * Gets the element start tag.
     *
     * @return string
     */
    private function startTag(): string
    {
        $start = '<' . $this->name;
        $attributes = null;
        $end = '>';

        if (!empty($this->attributes)) {
            foreach ($this->attributes as $name => $value) {
                // ref. https://developer.mozilla.org/en-US/docs/Web/HTML/Attributes#Boolean_Attributes
                if ($value === true) { // if the value of the attribute is true we set its name as value (e.g required attribute)
                    $attributes .= ' ' . $name . '="' . $name . '"';
                } else if ($value === false) { // if the value of the attribute if false, then we add "off" as value (e.g autocomplete attribute)
                    $attributes .= ' ' . $name . '="off"';
                } else { // if it's not a boolean attribute we just add the value without modifying it (e.g class attribute)
                    $attributes .= ' ' . $name . '="' . $value . '"';
                }
            }
        }

        return $start . $attributes . $end;
    }

    /**
     * Gets the element end tag.
     *
     * @return string|null
     */
    private function endTag()
    {
        // we don't output a closing tag if it's a self-closing tag
        if ($this->isSelfClosing()) {
            return null;
        }

        return '</' . $this->name . '>';
    }

    /**
     * Generates the HTML code.
     */
    public function build(): string
    {
        $html = $this->startTag() . $this->content . $this->endTag();

        return $html;
    }

}
