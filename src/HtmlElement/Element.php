<?php

namespace Artyum\HtmlElement;

use InvalidArgumentException;
use Artyum\HtmlElement\Exceptions\SelfClosingTagException;

/**
 * Creates an HTML element and adds attributes/content to it.
 */
class Element
{
    /**
     * @var string Should contain the name of the HTML element to create.
     */
    private ?string $name = null;

    /**
     * @var array|null Should contain an array of options.
     */
    private ?array $options;

    /**
     * @var string|null Should contain the content of the element.
     */
    private ?string $content = null;

    /**
     * @var Attribute[] Should contain an array of Attribute class instances.
     */
    private array $attributes = [];

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
     * @param string|null $name
     * @param array|null $options
     */
    public function __construct(?string $name = null, ?array $options = null)
    {
        // prevents transforming $name into an empty string if not set
        if (!empty($name)) {
            $this->name = trim($name);
        }

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
     * Gets the element start tag.
     *
     * @return string
     */
    private function startTag(): string
    {
        $output = '<' . $this->name;
        $attributes = $this->buildAttributes();

        if ($attributes) {
            $output .= ' ' . $attributes;
        }

        return $output . '>';
    }

    /**
     * Builds the element's attributes.
     *
     * @return string|null
     */
    private function buildAttributes(): ?string
    {
        if (!$this->attributes) {
            return null;
        }

        $attributes = null;

        foreach ($this->attributes as $attribute) {
            $attributes .= $attribute->build() . ' ';
        }

        return rtrim($attributes);
    }

    /**
     * Gets the element end tag.
     *
     * @return string
     */
    private function endTag(): ?string
    {
        // ends here if the element should not have a closing tag
        if ($this->isSelfClosingTag()) {
            return null;
        }

        return '</' . $this->name . '>';
    }

    /**
     * Checks if the element is a self-closing tag.
     *
     * @return bool
     */
    private function isSelfClosingTag(): bool
    {
        return in_array($this->name, $this->selfClosingTags) || (isset($this->options['autoclose']) && $this->options['autoclose'] === false);
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
     * @return self
     */
    public function setName(string $name): self
    {
        $this->name = $name;

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
     * @return Attribute[]|null
     */
    public function getAttributes(): ?array
    {
        return $this->attributes;
    }

    /**
     * Adds attributes to the element.
     *
     * @param Attribute ...$attributes
     * @return $this
     */
    public function addAttributes(...$attributes): self
    {
        // loops through all passed $attributes and validates that instances of Attribute only have been passed
        foreach ($attributes as $attribute) {
            if (!$attribute instanceof Attribute) {
                throw new InvalidArgumentException('The "$attributes" argument must only contain instance(s) of ' . Attribute::class);
            }
        }

        $this->attributes = array_merge($this->attributes ?? [], $attributes);

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
     * Adds content to the element.
     *
     * @param int|float|string|bool|self ...$content
     * @return Element
     * @throws SelfClosingTagException
     * @throws InvalidArgumentException
     */
    public function addContent(...$content): self
    {
        // ensures that we are not adding a content to a self-closing element/tag.
        if ($this->isSelfClosingTag()) {
            throw new SelfClosingTagException('A self-closing tag cannot have a content.');
        }

        // loops through all passed $content and validates their type before appending them to the existing element's content
        foreach ($content as $element) {
            if (is_scalar($element)) {
                $this->content .= $element;
            } elseif ($element instanceof $this) {
                $this->content .= $element->toHtml();
            } else {
                throw new InvalidArgumentException('The "$content" argument must contain scalar value(s) or instance(s) of ' . self::class);
            }
        }

        return $this;
    }

    /**
     * Outputs the HTML code.
     */
    public function toHtml(): string
    {
        return $this->startTag() . $this->content . $this->endTag();
    }
}
