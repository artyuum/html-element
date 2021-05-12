<?php

namespace Artyum\HtmlElement;

use InvalidArgumentException;

/**
 * Creates an element attribute.
 */
class Attribute
{
    /**
     * @var string should contain the attribute name
     */
    private string $name;

    /**
     * @var mixed should contain the attribute value
     */
    private $value;

    /**
     * @var string should contain the value separator (used when the value is an array)
     */
    private string $separator;

    /**
     * @param string $name
     * @param $value
     * @param string $separator
     */
    public function __construct(string $name, $value, string $separator = ';')
    {
        $this->validateValue($value);

        $this->name = trim($name);
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
     * @param $value
     */
    private function validateValue($value): void
    {
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
    public function getName(): string
    {
        return $this->name;
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
     * @return Attribute
     */
    public function setSeparator(string $separator): Attribute
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
        // handles boolean attributes (e.g "required", "readonly", etc.)
        if (is_bool($this->value)) {
            // returns the name but without the value if the value is "true"
            if ($this->value === true) {
                return $this->name;
            }    // otherwise, adds "off" as value (e.g autocomplete attribute)
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
