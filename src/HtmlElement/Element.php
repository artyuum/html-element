<?php

namespace Artyum\HtmlElement;

use InvalidArgumentException;
use Artyum\HtmlElement\Exceptions\SelfClosingTagException;
use Artyum\HtmlElement\Exceptions\WrongAttributeValueException;

/**
 * This class gives you the ability to easily create HTML elements and add attributes/content to them.
 *
 * @package Artyum\HtmlElement
 */
class Element
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
     * @param string|null $name
     * @param array|null $options
     */
    public function __construct(?string $name = null, ?array $options = null)
    {
        $this->name = trim($name);
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

                // handles attributes like "required", etc.
                if (is_bool($value)) {
                    if ($value === true) { // adds the name of the attribute but without a value (e.g required attribute)
                        $attributes .= ' ' . $name;
                    } else {  // adds "off" as value (e.g autocomplete attribute)
                        $attributes .= ' ' . $name . '="off"';
                    }

                    // continues to the next iteration to avoid further unneeded checking
                    continue;
                }

                // handles "style" attribute
                if (is_array($value)) {
                    $multipleValues = null;
                    foreach ($value as $propertyName => $propertyValue) {
                        $multipleValues .= $propertyName . ': ' . $propertyValue . '; ';
                    }
                    $attributes .= ' ' . $name . '="' . trim($multipleValues) . '"';

                    // continues to the next iteration to avoid further unneeded checking
                    continue;
                }

                // handles scalar values
                if (is_scalar($value)) {
                    // adds the name of the attribute along with its value without modifying it
                    $attributes .= ' ' . $name . '="' . $value . '"';
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
            if (!is_scalar($value) && !is_array($value)) {
                throw new WrongAttributeValueException('The following attribute does not have a valid value: ' . $name);
            }

            // ensures that the attribute that has an array as value has "style" as its name
            if (is_array($value) && $name !== 'style') {
                throw new WrongAttributeValueException('The following attribute has an array as value but this is only supported for "style" attribute: ' . $name);
            }

            // validate the content of the array
            if (is_array($value)) {
                foreach ($value as $propertyName => $propertyValue) {
                    // ensures that the value of the property is valid
                    if (!is_string($propertyValue) && !is_int($propertyValue) && !is_float($propertyValue)
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
     * @return Element
     */
    public function setName(string $name): self
    {
        $this->name = trim($name);

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
     * @return Element
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
     * @return Element
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
            } elseif ($element instanceof $this) {
                $this->content .= $element->toHtml();
            } else {
                throw new InvalidArgumentException('The $content argument must be either a string or an instance of ' . self::class);
            }
        }

        return $this;
    }
}
