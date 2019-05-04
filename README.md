# HtmlElement
A PHP library giving you the ability to generate HTML elements. This library supports self-closing tags and boolean attributes.

## Why did I create this ?
I used to work on a non-MVC PHP project and sometimes I needed to output few lines of HTML directly from the functions.
Having to mix HTML code in PHP code was inconsistent to me and it was hard to keep the code easily readable and easy to maintain in the longterm because of the crazy and ugly concatenations. 
That's why I came up with the idea of generating HTML elements directly in PHP.    
There are few existing libraries on Packagist that have the same purpose but I wasn't really satisfied and I also wanted to create this one for learning purpose.

## Requirements
* PHP 7.0
* Composer

## Installation
```
composer require artyuum/html-element
```

## Usage example
An example of login form creation.

```php
$formElement = new HtmlElement('form');
$divElement = new HtmlElement('div');
$labelElement = new HtmlElement('label');
$usernameInputElement = new HtmlElement('input');
$passwordInputElement = new HtmlElement('input');
$buttonElement = new HtmlElement('button');
$spanElement = new HtmlElement('span');

$formElement
    ->setAttributes([
        'action' => '/login',
        'method' => 'post'
    ])
    ->setContent(
        $divElement
            ->setAttributes([
                'class' => 'form-group'
            ])
            ->setContent(
                $labelElement
                    ->setAttributes([
                        'for' => 'username'
                    ])
                    ->setContent('Username'),
                $usernameInputElement
                    ->setAttributes([
                        'type'          => 'text',
                        'class'         => 'form-control',
                        'id'            => 'username',
                        'name'          => 'username',
                        'placeholder'   => 'Username',
                        'style'         => 'border: none; background-color: rgba(100, 100, 255, .1)',
                        'required'      => true
                    ]),
                $passwordInputElement
                    ->setAttributes([
                        'type'          => 'password',
                        'class'         => 'form-control',
                        'id'            => 'password',
                        'name'          => 'password',
                        'placeholder'   => 'Password',
                        'style'         => 'border: none; background-color: rgba(100, 100, 255, .1)',
                        'required'      => true
                    ]),
                $buttonElement
                    ->setAttributes([
                        'type' => 'submit'
                    ])
                    ->setContent(
                        $spanElement
                            ->setAttributes([
                                'class'=> 'fa fa-sign-in-alt'
                            ])
                            ->setContent('Login')
                    )
            )
    );

echo $formElement;
```

## Output
```html
<form action="/login" method="post">
    <div class="form-group">
        <label for="username">Username</label>
        <input type="text" class="form-control" id="username" name="username" placeholder="Username" style="border: none; background-color: rgba(100, 100, 255, .1); padding: 10px 15px" required="required">
        <input type="password" class="form-control" id="password" name="password" placeholder="Password" style="border: none; background-color: rgba(100, 100, 255, .1); padding: 10px 15px" required="required">
        <button type="submit"><span class="fa fa-sign-in-alt">Login</span></button>
    </div>
</form>
```

## API
```php
getAttributes(): array
```
**Description**  
Will return an array of attributes assigned to the HTML element.

---
```php
setAttributes(array $attributes): HtmlElement
```
**Description**  
Takes an associative array of attributes.  
**Example**  
```php
setAttributes([
    'class' => 'is-red',
    'style' => 'font-size: 2em'
]);
```

---
```php
getContent(): string
```
**Description**  
Will return the content of the HTML element.

---
```php
setContent(...$content): HtmlElement
```
**Description**  
You can pass a string or a HtmlElement instance. Note the splat operator (...), this means that you can pass as much argument as you want.  
**Example** 
```php
// by passing HtmlElement instances (using variables)
setContent([
    $inputElement,
    $buttonElement
]);

// by passing HtmlElement instances (without using variables)
setContent([
    (new HtmlElement('input')),
    (new HtmlElement('button')),
]);

// by passing a string
setContent([
    'Lorem ipsum...'
]);

// by passing all three
setContent([
    $inputElement,
    (new HtmlElement('button')),
    'Lorem ipsum...'
]);
```

---
```php
build(): string
```
**Description**  
Will return the HTML code. Note that you can also simply do an `echo` on the instance and it will automatically calls the `build()` method. This is possible thanks to the `__toString()` magic method.  
**Example**  
```php
// both will return the same result
echo $formElement->build();
echo $formElement;
```

## Contributing
If you'd like to contribute, please fork the repository and make changes as you'd like. Pull requests are warmly welcome.
