<?php

class HtmlElement {

    /**
     * @var string Should contain the name of the element to create.
     */
	private $name;

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
     * @param $name
     */
	public function __construct($name)
	{
		$this->name = $name;
	}


    /**
     * Sets the element attributes.
     *
     * @param array $attributes
     * @return $this
     */
	public function setAttributes(array $attributes)
    {
        $this->attributes = $attributes;
        return $this;
    }

    /**
     * Gets the element content.
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Sets the element content.
     *
     * @param mixed ...$content
     * @return $this
     */
    public function setContent(...$content)
    {
        if (is_array($content)) {
            foreach ($content as $element) {
                if (is_string($element)) {
                    $this->content .= $element;
                } elseif ($element instanceof $this) {
                    $this->content .= $element->build();
                }
            }
        }

        return $this;
    }

    /**
     * Gets the element start tag.
     *
     * @return string
     */
    private function startTag() {
        $start = '<' . $this->name;
        $attributes = null;
        $end = '>';

        if (!empty($this->attributes)) {
            foreach ($this->attributes as $name => $value) {
                $attributes .= ' ' . $name  . '="' . $value . '"';
            }
        }

	    return $start . $attributes . $end;
    }

    /**
     * Gets the element end tag.
     *
     * @return string|null
     */
    private function endTag() {
        if (in_array($this->name, $this->selfClosingTags)) {
            return null;
        }

        return '</' . $this->name . '>';
    }

    /**
     * Generates the HTML.
     */
	public function build(): string
	{
	    $html = $this->startTag() . $this->content . $this->endTag();

		return $html;
	}

}
