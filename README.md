# HtmlElement
A PHP library giving you the ability to generate HTML elements in an object oriented way.

## Why did I create this ?
I used to work on a non-MVC PHP project and sometimes I needed to output few lines of HTML directly from the functions.
Having to mix HTML code in PHP code was inconsistent to me and it was hard to keep the code easily readable and easy to maintain in the longterm because of the crazy and ugly concatenations. 
That's why I came up with the idea of generating HTML elements directly in PHP. (of course if you need to create many HTML elements, you should consider using a templating engine instead)    
There are few existing libraries on Packagist that have the same purpose but I wasn't really satisfied and I also wanted to create my own library for fun & learning purpose.

## Features
* Supports self-closing tags. (e.g input tag)
* Supports boolean attributes. (e.g required attribute)

## Requirements
* PHP 7.2 or above
* Composer

## Installation
```
composer require artyuum/html-element
```

## Examples
### Simple
A simple DIV element with some attributes & a content.

```php
$divElement = new Artyum\HtmlElement\HtmlElement('div');

$divElement
    ->addAttributes([
        'title' => 'This is an editable DIV with a red background color',
        'style' => [
            'background-color' => 'red'
        ],
        'contenteditable' => true
    ])
    ->setContent('This is an editable DIV with a red background color.')
;

echo $divElement;
// or 
echo $divElement->toHtml();
```

**Output** 
```html
<div title="This is an editable DIV with a red background color" style="background-color: red;" contenteditable>
    This is a DIV with a red background color.
</div>
```

### Advanced
An example of a login form that contains childrens.

```php
$formElement = new Artyum\HtmlElement\HtmlElement('form');
$divElement = new Artyum\HtmlElement\HtmlElement('div');
$labelElement = new Artyum\HtmlElement\HtmlElement('label');
$usernameInputElement = new Artyum\HtmlElement\HtmlElement('input');
$passwordInputElement = new Artyum\HtmlElement\HtmlElement('input');
$buttonElement = new Artyum\HtmlElement\HtmlElement('button');
$spanElement = new Artyum\HtmlElement\HtmlElement('span');

$formElement
    ->addAttributes([
        'action' => '/login',
        'method' => 'post',
    ])
    ->setContent(
        $divElement
            ->addAttributes([
                'class' => 'form-group',
            ])
            ->setContent(
                $labelElement
                    ->addAttributes([
                        'for' => 'username',
                    ])
                    ->setContent('Username'),
                $usernameInputElement
                    ->addAttributes([
                        'type'        => 'text',
                        'class'       => 'form-control',
                        'id'          => 'username',
                        'name'        => 'username',
                        'placeholder' => 'Username',
                        'style'       => [
                            'border'           => 'none',
                            'background-color' => 'rgba(100, 100, 255, .1)',
                        ],
                        'required'    => true,
                    ]),
                $passwordInputElement
                    ->addAttributes([
                        'type'        => 'password',
                        'class'       => 'form-control',
                        'id'          => 'password',
                        'name'        => 'password',
                        'placeholder' => 'Password',
                        'style'       => [
                            'border'           => 'none',
                            'background-color' => 'rgba(100, 100, 255, .1)',
                        ],
                        'required'    => true,
                    ]),
                $buttonElement
                    ->addAttributes([
                        'type' => 'submit',
                    ])
                    ->setContent(
                        $spanElement
                            ->addAttributes([
                                'class' => 'fa fa-sign-in-alt',
                            ])
                            ->setContent('Login')
                    )
            )
    );

echo $formElement;
// or
echo $formElement->toHtml();
```

**Output**
```html
<form action="/login" method="post">
    <div class="form-group">
        <label for="username">Username</label>
        <input type="text" class="form-control" id="username" name="username" placeholder="Username" style="border: none; background-color: rgba(100, 100, 255, .1);" required>
        <input type="password" class="form-control" id="password" name="password" placeholder="Password" style="border: none; background-color: rgba(100, 100, 255, .1);" required>
        <button type="submit"><span class="fa fa-sign-in-alt">Login</span></button>
    </div>
</form>
```

## API
When instantiating the HtmlElelement class, you can optionally provide the name of the element as first argument and an array of options as second argument.
```php
__construct(?string $name = null, ?array $options = null)
```  

---
Gets the generated HTML code.
```php
toHtml(): string
```  
Note that you can also simply do an `echo` on the instance and it will internally call the `toHtml()` method. This is possible thanks to the `__toString()` magic method.  

**Example**  
```php
$html = $formElement->toHtml();

// both will return the same result
echo $html;
echo $formElement;
```

---
Gets the name of the element.
```php
getName(): string
```

---
Sets the name of the element.
```php
setName(string $name): self
```

---
Gets the options of the element.
```php
getOptions(): ?array
```

---
Sets the options of the element.
```php
setOptions(array $options): self
```

**Available options :**  

| Name      | Type    | Description                                        |
|-----------|---------|----------------------------------------------------|
| autoclose | boolean | Whether the element should have closing tag or not |  

---
Returns an array of attributes assigned to the element.
```php
getAttributes(): ?array
```

---
Takes an associative array of attributes.
```php
addAttributes(array $attributes): self
```
  
**Example**  
```php
addAttributes([
    'class' => 'is-red',
    'style' => [
        'font-size' => '2em'
    ]
]);
```
You can call this method multiple times, it will internally merge the existing attributes with the new ones.
Note that if an attribute already exists, its value will be overwritten.

---
Returns the content of the element.
```php
getContent(): ?string
```

---
Sets the content of the element. You can pass a string, an interger, a float or an instance of the HtmlElement class.  
Thanks to the splat operator (...), you can pass as much argument as you want.
```php
setContent(...$content): self
```  

## Changelog
This library uses [semantic versioning](https://semver.org/).

* **v2.0.1** - (2020-01-22)
    * Simplified buildAttributes() & validateAttributes() methods.
    * Added proper validation for attribute with an array as value.
    * Updated tests to be more easy to debug.

* **v2.0** - (2019-12-29)
    * Re-arranged the code.
    * Now requiring PHP 7.2 or above.
    * Removed an unneeded exception and added a new one.
    * Renamed `setAttributes()` to `addAttributes()` and implemented the ability to merge attributes.
    * Renamed `build()` to `toHtml()` (more explicit).
    * Added the ability to set an array as the attribute's value (for the "style" attribute).
    * The name of the element is now automatically trimmed to remove any space around.
    * Fixed the return type for methods that can return a null value.
    * `setContent()` now accepts integer and float values.
    * It's no longer required to pass the name of the element in the constructor when instantiating.
    * Added `setName()` & `setOptions()` methods.

* **v1.1** - (2019-05-05)
    * You can now pass an array of $options[] to the constructor when instantiating the HtmlElement class.

* **v1.0** - (2019-05-04)
    * The library is fully functional and ready to use.

## TODO
* Improve support for [global attributes](https://developer.mozilla.org/en-US/docs/Web/HTML/Global_attributes) & [boolean attributes](https://developer.mozilla.org/en-US/docs/Web/HTML/Attributes#Boolean_Attributes).

## Contributing
If you'd like to contribute, please fork the repository and make changes as you'd like. Be sure to follow the same coding style & naming used in this library to produce a consistent code.
