<?php

namespace Artyum\HtmlElement;

use InvalidArgumentException;
use Artyum\HtmlElement\Exceptions\SelfClosingTagException;
use Artyum\HtmlElement\Exceptions\WrongAttributeValueException;

/**
 * Class HtmlElement
 *
 * This class gives you the ability to easily create HTML elements and add attributes/content to them.
 *
 * @package Artyum\HtmlElement
 */
class HtmlElement
{
    /**
     * @var string Should contain the name of the HTML element to create.
     */
    private $name;

    /**
     * @var array Should contain an array of options.
     */
    private $options;

    /**
     * @var string Should contain the content of the element.
     */
    private $content;

    /**
     * @var array Should contain an array of HTML attributes with their values.
     */
    private $attributes;

    /**
     * @var array Should contain an array of self-closing HTML tags.
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
        'wbr',
    ];

    /**
     * HtmlElement constructor.
     *
     * @param string $name
     * @param array|null $options
     */
    public function __construct(?string $name = null, ?array $options = null)
    {
        $this->name = trim($name); // removes any space around
        $this->options = $options;
    }

    /**
     * Gets the generated HTML.
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->toHtml();
    }

    /**
     * Generates the HTML code.
     */
    public function toHtml(): string
    {
        return $this->startTag() . $this->content . $this->endTag();
    }

    /**
     * Gets the element start tag.
     *
     * @return string
     */
    private function startTag(): string
    {
        return '<' . $this->name . $this->buildAttributes() . '>';
    }

    /**
     * Builds the element's attributes.
     *
     * @return string|null
     */
    private function buildAttributes(): ?string
    {
        $attributes = null;

        // ensures that the attributes have been set
        if ($this->attributes) {
            foreach ($this->attributes as $name => $value) {
                $name = trim($name); // removes any space around

                // handles the attribute to generate based on the type of the value
                switch (gettype($value)) {
                    case 'boolean':
                        if ($value === true) { // adds the name of the attribute but without a value (e.g required attribute)
                            $attributes .= ' ' . $name;
                        } else {  // adds "off" as value (e.g autocomplete attribute)
                            $attributes .= ' ' . $name . '="off"';
                        }
                        break;
                    case 'array': // style attribute
                        $multipleValues = null;
                        foreach ($value as $propertyName => $propertyValue) {
                            $multipleValues .= $propertyName . ': ' . $propertyValue . '; ';
                        }
                        $attributes .= ' ' . $name . '="' . trim($multipleValues) . '"';
                        break;
                    case 'double':
                    case 'integer':
                    case 'string':
                        // adds the name of the attribute along with its value without modifying it
                        $attributes .= ' ' . $name . '="' . $value . '"';
                        break;
                }
            }
        }

        return $attributes;
    }

    /**
     * Gets the element end tag.
     *
     * @return string
     */
    private function endTag(): ?string
    {
        // ends here if the element should not have a closing tag
        if ($this->hasSelfClosingTag()) {
            return null;
        }

        return '</' . $this->name . '>';
    }

    /**
     * Checks if the element has a self-closing tag.
     *
     * @return bool
     */
    private function hasSelfClosingTag(): bool
    {
        return in_array($this->name, $this->selfClosingTags) || $this->options['autoclose'] === false;
    }

    /**
     * Validates the attributes.
     *
     * @param array $attributes
     * @throws WrongAttributeValueException
     */
    private function validateAttributes(array $attributes): void
    {
        // ensures that the attributes has a valid value
        foreach ($attributes as $name => $value) {
            if (
                !is_bool($value) &&
                !is_array($value) &&
                !is_float($value) &&
                !is_int($value) &&
                !is_string($value)
            ) {
                throw new WrongAttributeValueException('The following attribute does not have a valid value: ' . $name);
            }

            // validate the content of the array (if the value is an array)
            if (is_array($value)) {
                foreach ($value as $propertyName => $propertyValue) {
                    // ensures that the value of the property is valid
                    if (
                        !is_string($propertyValue) &&
                        !is_int($propertyValue) &&
                        !is_float($propertyValue)
                    ) {
                        throw new WrongAttributeValueException('The following attribute does not have a valid value: ' . $propertyName);
                    }
                }
            }
        }
    }

    /**
     * Gets the name of the element.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Sets the name of the element.
     *
     * @param string $name
     * @return HtmlElement
     */
    public function setName(string $name): self
    {
        $this->name = trim($name); // removes any space around

        return $this;
    }

    /**
     * Gets the options.
     *
     * @return array|null
     */
    public function getOptions(): ?array
    {
        return $this->options;
    }

    /**
     * Sets the options.
     *
     * @param array $options
     * @return HtmlElement
     */
    public function setOptions(array $options): self
    {
        $this->options = $options;

        return $this;
    }

    /**
     * Get an array of attributes assigned to the element.
     *
     * @return array|null
     */
    public function getAttributes(): ?array
    {
        return $this->attributes;
    }

    /**
     * Adds attributes to the element.
     *
     * @param array $attributes
     * @return $this
     * @throws WrongAttributeValueException
     */
    public function addAttributes(array $attributes): self
    {
        // ensures that the attributes value is valid
        $this->validateAttributes($attributes);

        if ($this->attributes) {
            $this->attributes = array_merge($this->attributes, $attributes);
        } else {
            $this->attributes = $attributes;
        }

        return $this;
    }

    /**
     * Gets the element content.
     *
     * @return string|null
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * Sets the element content.
     *
     * @param mixed ...$content
     * @return HtmlElement
     * @throws SelfClosingTagException
     * @throws InvalidArgumentException
     */
    public function setContent(...$content): self
    {
        // ensures that we are not adding a content to a self-closing element/tag.
        if ($this->hasSelfClosingTag()) {
            throw new SelfClosingTagException('A self-closing tag cannot have a content.');
        }

        foreach ($content as $element) {
            if (
                is_string($element) ||
                is_int($element) ||
                is_float($element)
            ) {
                $this->content .= $element;
            } else if ($element instanceof $this) {
                $this->content .= $element->toHtml();
            } else {
                throw new InvalidArgumentException('The $content argument must be either a string or an instance of ' . self::class);
            }
        }

        return $this;
    }
}
