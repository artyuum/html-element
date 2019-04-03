# HtmlElement
A PHP class giving you the ability to generate HTML elements (WIP)


## Usage example
```php
<?php

require 'HtmlElement.php';

$formElement = new HtmlElement('form');

$formElement
    ->setAttributes([
        'action' => '.',
        'method' => 'post'
    ])
    ->setContent(
        (new HtmlElement('div'))
            ->setAttributes([
                'class' => 'form-group'
            ])
            ->setContent(
                (new HtmlElement('label'))
                    ->setAttributes([
                        'for' => 'test'
                    ])
                    ->setContent('test'),
                (new HtmlElement('input'))
                    ->setAttributes([
                        'type' 			=> 'text',
                        'class' 		=> 'test',
                        'id'			=> 'test',
                        'value' 		=> 'test',
                        'placeholder'	=> 'test',
                        'style'			=> 'color: red; font-size: 15px',
                        'required'		=> true
                    ]),
                (new HtmlElement('button'))
                    ->setAttributes([
                        'type' => 'submit'
                    ])
                    ->setContent(
                        (new HtmlElement('span'))
                            ->setAttributes([
                                'class'=> 'fa fa-search'
                            ])
                            ->setContent('send')
                    )
            )
    );

echo $formElement->build();

```

## Output
```html
<form action="." method="post">
  <div class="form-group">
    <label for="test">test</label>
    <input type="text" class="test" id="test" value="test" placeholder="test" style="color: red; font-size: 15px" required="1">
    <button type="submit">
      <span class="fa fa-search">send</span>
    </button>
  </div>
</form>
```
