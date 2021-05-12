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
use Artyum\HtmlElement\Element;
use Artyum\HtmlElement\Attribute;

$divElement = new Element('div');

$divElement
    ->addAttributes(
        new Attribute('title', 'This is an editable DIV with a red background color'),
        new Attribute('style', [
                'width: 100px',
                'height: 100px',
                'background-color: red'
        ]),
        new Attribute('contenteditable', true)
    )
    ->addContent('This is an editable DIV with a red background color.')
;

echo $divElement;
// or 
echo $divElement->toHtml();
```

**Output** 
```html
<div title="This is an editable DIV with a red background color" style="width: 100px;height: 100px;background-color: red;" contenteditable>
    This is an editable DIV with a red background color.
</div>
```

### Advanced
An example of a login form that contains children.

```php
use Artyum\HtmlElement\Element;
use Artyum\HtmlElement\Attribute;

$formElement = new Element('form');
$divElement = new Element('div');
$labelElement = new Element('label');
$usernameInputElement = new Element('input');
$passwordInputElement = new Element('input');
$buttonElement = new Element('button');
$spanElement = new Element('span');

$formElement
    ->addAttributes(
        new Attribute('action', '/login'),
        new Attribute('method', 'post')
    )
    ->addContent(
        $divElement
            ->addAttributes(new Attribute('class', 'form-group'))
            ->addContent(
                $labelElement
                    ->addAttributes(new Attribute('for', 'username'))
                    ->addContent('Username'),
                $usernameInputElement
                    ->addAttributes(
                        new Attribute('type', 'text'),
                        new Attribute('class', 'form-control'),
                        new Attribute('id', 'username'),
                        new Attribute('name', 'username'),
                        new Attribute('placeholder', 'Username'),
                        new Attribute('style', [
                            'border: none',
                            'background-color: rgba(100, 100, 255, .1)'
                        ]),
                        new Attribute('required', true)
                    ),
                $passwordInputElement
                    ->addAttributes(
                        new Attribute('type', 'password'),
                        new Attribute('class', 'form-control'),
                        new Attribute('id', 'password'),
                        new Attribute('name', 'password'),
                        new Attribute('placeholder', 'Password'),
                        new Attribute('style', [
                            'border: none',
                            'background-color' => 'rgba(100, 100, 255, .1)'
                        ]),
                        new Attribute('required', true)
                    ),
                $buttonElement
                    ->addAttributes(new Attribute('type', 'submit'))
                    ->addContent(
                        $spanElement
                            ->addAttributes(new Attribute('class', 'fa fa-sign-in-alt'))
                            ->addContent('Login')
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
        <input type="text" class="form-control" id="username" name="username" placeholder="Username" style="border: none;background-color: rgba(100, 100, 255, .1)" required>
        <input type="password" class="form-control" id="password" name="password" placeholder="Password" style="border: none;rgba(100, 100, 255, .1)" required>
        <button type="submit"><span class="fa fa-sign-in-alt">Login</span></button>
    </div>
</form>
```

## API

### Artyum\HtmlElement\Element
When instantiating the `Element` class, you can optionally provide the name of the element as first argument, and an array of options as second argument.
```php
__construct(?string $name = null, ?array $options = null)
```  

---
Gets the HTML code of the element.
```php
toHtml(): string
```  
If you call this method without setting the name of the element first, a `LogicException` will be thrown.

Note that you can also simply `echo` the instance and it will internally call the `toHtml()` method. This is possible thanks to the `__toString()` magic method.  

**Example**  
```php
// both will return the same result
echo $element->toHtml();
echo $element;
```

---
Gets the name of the element.
```php
getName(): ?string
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

| Name      | Type    | Description                                         |
|-----------|---------|-----------------------------------------------------|
| autoclose | boolean | Whether the element should have closing tag or not. |

---
Gets the attributes assigned to the element.
```php
getAttributes(): Attribute[]
```

---
Adds one or multiple attributes to the element.
```php
addAttributes(... Attribute $attribute): self
```  
Thanks to the splat operator (...), you can pass as much argument as you want. You can also call this method multiple times to add additional attributes.

---
Returns the content of the element.
```php
getContent(): ?string
```

---
Adds one or multiple contents to the element. You can pass a string, an integer, a float or an instance of the Element class.  
```php
addContent(...$content): self
```  

### Artyum\HtmlElement\Attribute
When instantiating the `Attribute` class, you must provide the name of the attribute and its value. You can optinally pass the separator that will be used to separate the values if you pass an array of values. 
```php
__construct(?string $name = null, mixed $value = null, string $separator = ';')
```  

---
Gets the name.
```php
getName(): ?string
```

---
Gets the value.
```php
getValue(): mixed
```

---
Gets the separator.
```php
getSeparator(): string
```

---
Sets the attribute values separator.
```php
setSeparator(string $separator): self
```

---
Builds & returns the HTML representation of the attribute.
```php
build(): string
```
You can also `echo` the instance and it will internally call the `build()` method.


## Changelog
This library follows [semantic versioning](https://semver.org/).

* **3.0.0** - (2020-09-21)
    * Renamed HtmlElement to Element.
    * Added a new Attribute class.
    * Renamed setContent to addContent().
    * Removed setName() and made $name required when instantiating the Element class.
    * Removed native support of style attribute in favor of a new way to handle attributes using the Attribute class.
    * Removed WrongAttributeValueException in favor of InvalidArgumentException.
    * addAttributes() can now accept one or multiple arguments.
    * Updated tests according to the new changes.

* **2.0.1** - (2020-01-22)
    * Simplified buildAttributes() & validateAttributes() methods.
    * Added proper validation for attribute with an array as value.
    * Updated tests to be easier to debug.

* **2.0.0** - (2019-12-29)
    * Re-arranged the code.
    * Now requiring PHP 7.2 or above.
    * Removed an unneeded exception and added a new one.
    * Renamed `setAttributes()` to `addAttributes()` and implemented the ability to merge attributes.
    * Renamed `build()` to `toHtml()` (more explicit).
    * Added the ability to set an array as the attribute's value (for the "style" attribute).
    * The name of the element is now being automatically trimmed to remove any space around.
    * Fixed the return type for methods that can return a null value.
    * `setContent()` now accepts integer and float values.
    * It's no longer required to pass the name of the element in the constructor when instantiating.
    * Added `setName()` & `setOptions()` methods.

* **1.1.0** - (2019-05-05)
    * You can now pass an array of $options[] to the constructor when instantiating the HtmlElement class.

* **1.0.0** - (2019-05-04)
    * The library is fully functional and ready to use.

## Contributing
If you'd like to contribute, please fork the repository and make changes as you'd like. Be sure to follow the same coding style & naming used in this library to produce a consistent code.
