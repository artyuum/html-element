<?php

namespace Artyum\HtmlElement;

use InvalidArgumentException;
use LogicException;

/**
 * Creates an element attribute.
 */
class Attribute
{
    /**
     * @var string|null should contain the attribute name
     */
    private ?string $name = null;

    /**
     * @var mixed should contain the attribute value
     */
    private $value;

    /**
     * @var string should contain the value separator (used when the value is an array)
     */
    private string $separator;

    /**
     * @param string|null $name
     * @param mixed $value
     * @param string $separator
     */
    public function __construct(?string $name = null, $value = null, string $separator = ';')
    {
        $this->validateValue($value);

        // prevents transforming $name into an empty string if not set
        if (!empty($name)) {
            $this->name = trim($name);
        }

        $this->value = $value;
        $this->separator = $separator;
    }

    public function __toString(): string
    {
        return $this->build();
    }

    /**
     * Validates the attribute value.
     *
     * @param mixed $value
     */
    private function validateValue($value): void
    {
        if ($value === null) {
            return;
        }

        // ensures that the value is scalar or an array
        if (!is_scalar($value) && !is_array($value)) {
            throw new InvalidArgumentException('The passed attribute value type is not valid. You passed: ' . gettype($value));
        }

        // ensures that its values are valid if it's an array
        if (is_array($value)) {
            foreach ($value as $singleValue) {
                if (!is_scalar($singleValue)) {
                    throw new InvalidArgumentException('The passed array of values must contain scalar values. You passed: ' . gettype($singleValue));
                }
            }
        }
    }

    /**
     * Gets the name.
     *
     * @return string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Sets the name.
     *
     * @param string $name
     *
     * @return self
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Gets the value.
     *
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Sets the value.
     *
     * @param mixed $value
     * @return self
     */
    public function setValue($value): self
    {
        $this->validateValue($value);

        $this->value = $value;

        return $this;
    }

    /**
     * Gets the separator.
     *
     * @return string
     */
    public function getSeparator(): string
    {
        return $this->separator;
    }

    /**
     * Sets the attribute values separator.
     *
     * @param string $separator
     * @return self
     */
    public function setSeparator(string $separator): self
    {
        $this->separator = $separator;

        return $this;
    }

    /**
     * Builds & returns the HTML representation of the attribute.
     *
     * @return string
     */
    public function build(): string
    {
        if ($this->name === null) {
            throw new LogicException('The "$name" property must be set.');
        }

        if ($this->value === null) {
            throw new LogicException('The "$value" property must be set.');
        }

        // handles boolean attributes (e.g "required", "readonly", etc.)
        if (is_bool($this->value)) {
            // returns the name but without the value if the value is "true"
            if ($this->value === true) {
                return $this->name;
            }

            // otherwise, adds "off" as value (e.g autocomplete attribute)
            return $this->name . '="off"';
        }

        // handles array as value
        if (is_array($this->value)) {
            $values = null;

            // loops through all values in the array and separates all of them using the predefined separator
            foreach ($this->value as $singleValue) {
                $values .= $singleValue . $this->separator;
            }

            return $this->name . '="' . rtrim($values, $this->separator) . '"';
        }

        // adds the name of the attribute along with its value
        return $this->name . '="' . $this->value . '"';
    }
}
