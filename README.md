# HtmlElement
A PHP class giving you the ability to generate HTML elements (WIP)
In theory, you just be able to generate any HTML elements using this library and it support self-closing tags.

## Why did I create this ?
I used to work on a WordPress plugin made in MVC and I was having hard time outputting HTML code from the plugin methods without doing some crazy and ugly concatenations. Of course, I could have split the PHP and HTML code by putting the HTML code into templates files and then make PHP calls the appropried file but I didn't have time to refactor this plugin.
That's why I came up with the idea of generating HTML elements using PHP. There are some libraries on Packagist that have the same purpose but I wasn't really satisfied and I also wanted to create this one for learning purpose.

## Requirements
* PHP 7.0

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
                        'type' 			=> 'text',
                        'class' 		=> 'form-control',
                        'id'			=> 'username',
                        'name' 		    => 'username',
                        'placeholder'	=> 'Username',
                        'style'			=> 'border: none; background-color: rgba(0, 0, 0, .1)',
                        'required'		=> true
                    ]),
                $passwordInputElement
                    ->setAttributes([
                        'type' 			=> 'password',
                        'class' 		=> 'form-control',
                        'id'			=> 'password',
                        'name' 		    => 'password',
                        'placeholder'	=> 'Password',
                        'style'			=> 'border: none; background-color: rgba(0, 0, 0, .1)',
                        'required'		=> true
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

## API
```php
getAttributes(): array
```
Will return an array of attributes assigned to the HTML element.

---
```php
setAttributes(array $attributes): HtmlElement
```
**Description**  
Takes an associative array of attributes. Example :
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
Example : 
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
Will return the HTML code. Note that you can also simple do an `echo` on the instance and it will automatically calls the `build()` method, this is possible thanks to the `__toString()` magic method.  
Example :  
```php
// both will return the same result
echo $formElement->build();
echo $formElement;
```

## Contributing
If you'd like to contribute, please fork the repository and make changes as you'd like. Pull requests are warmly welcome.
