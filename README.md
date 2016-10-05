# Amazon PhP
### A PhP Soap Library to Amazon Product Advertising API 
[![@psbhanu on Twitter](https://img.shields.io/badge/twitter-%40psbhanu16-blue.svg)](https://twitter.com/psbhanu16)
[![Total Downloads](https://img.shields.io/badge/downloads-1K-blue.svg)](https://packagist.org/packages/psbhanu/Amazon-PhP)
[![Build Status](https://img.shields.io/badge/build-passing-green.svg)](https://travis-ci.org/psbhanu/Amazon-PhP)
[![MIT License](https://img.shields.io/badge/license-MIT-green.svg)](https://github.com/psbhanu/Amazon-PhP/blob/master/LICENSE)
[![Gitter](https://badges.gitter.im/psbhanu/Amazon-PhP.svg)](https://gitter.im/psbhanu/Amazon-PhP?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge)

The **Amazon Soap Library for PHP** makes it easy for developers to implement Amazon Product Advertising API in their PHP Application, and build robust applications including a e-commerce shop. You can get started in minutes by installing the Library through Composer or by downloading a single zip or phar file from our latest release.

You can use it with your Core PhP application or you can implement it with your Framework like with CodeIgniter. To Use with CodeIgniter just copy Amazon.php to your library directory. To use with Core Application or with any other framework just include or require the file and start by instantiating the Amazon Class. 

## Prerequisite
* PhP extension - SOAP

## Available Methods
### Normal Operation Methods
* search - Get Amazon Products with respect to the searched term.
* lookup – Get detail of a Product using its ASIN.
* browseNodeLookup – Get information about nodes (children anchestors, etc.)
* similarityLookup – Get information about product related to another product (using ASIN).

### Cart Operation Methods
* cartGet – Get your cart detail (using HMAC & CartId).
* cartCreate – Create a new cart and add the given product to it.
* cartAdd – Add a product to your cart.
* cartModify – Modify your cart (Ex: modify quantity value of a product in cart).
* cartClear – Clear/Delete your cart.

### Methods to set parameter(s) of Operation Methods
* country – If Country argument is null it will return the current country, otherwise it will set the country and return itself.
* category – Set Category/SearchIndex parameter (useful to get filtered records). 
* responseGroup – Set responseGroup parameter (notice that Amazon response will depends upon the value set with responseGroup).
* associateTag - Set associateTag parameter. This could be used for late bindings of this attribute.
* page - Allows to browse resultsets which have more than one page.

### Miscellaneous Methods
* returnType - Set to get response as an object or an array. Default will be an object.

## Quick Examples

### Create an Amazon Class object - Core
```php
<?php
// Require the Class File.
require 'Amazon.php';

// Instantiate an Amazon PhP Library object.
$params = array(
  'AWS_API_KEY'         => 'XXXX-XXXX-XXXX-XXXX', 
  'AWS_API_SECRET_KEY'  => 'XXXX-XXXX-XXXX-XXXX', 
  'AWS_API_LOCALE'      => 'com', 
  'AWS_ASSOCIATE_TAG'   => 'XXXX-XXXX-XXXX-XXXX' 
);

$amazon = new Amazon($params);
```

### Create an Amazon Class object (Load Library) - CodeIgniter 
```php
<?php

// Instantiate an Amazon PhP Library object.
$params = array(
  'AWS_API_KEY'         => 'XXXX-XXXX-XXXX-XXXX', 
  'AWS_API_SECRET_KEY'  => 'XXXX-XXXX-XXXX-XXXX', 
  'AWS_API_LOCALE'      => 'com', 
  'AWS_ASSOCIATE_TAG'   => 'XXXX-XXXX-XXXX-XXXX' 
);

$this->load->library('amazon', $params);

// Now you can access library methods using $this->amazon

```

### Set Category or Response Group to Amazon Class object - Core
```php
<?php
// Require the Class File.
require 'Amazon.php';

// Instantiate an Amazon PhP Library object.
$params = array(
  'AWS_API_KEY'         => 'XXXX-XXXX-XXXX-XXXX', 
  'AWS_API_SECRET_KEY'  => 'XXXX-XXXX-XXXX-XXXX', 
  'AWS_API_LOCALE'      => 'com', 
  'AWS_ASSOCIATE_TAG'   => 'XXXX-XXXX-XXXX-XXXX' 
);

$amazon = new Amazon($params);

$amazon->category('CATEGORY'); 
// Ex: $amazon->category('Books');

$amazon->responseGroup('RESPONSE GROUPS'); 
// Ex: $amazon->category('Small,Images,Offers');

```

### Set Category or Response Group to Amazon Class object - CodeIgniter
```php
<?php
// Instantiate an Amazon PhP Library object.
$params = array(
  'AWS_API_KEY'         => 'XXXX-XXXX-XXXX-XXXX', 
  'AWS_API_SECRET_KEY'  => 'XXXX-XXXX-XXXX-XXXX', 
  'AWS_API_LOCALE'      => 'com', 
  'AWS_ASSOCIATE_TAG'   => 'XXXX-XXXX-XXXX-XXXX' 
);

$this->load->library('amazon', $params);

$this->amazon->category('CATEGORY'); 
// Ex: $amazon->category('Books');

$this->amazon->responseGroup('RESPONSE GROUPS'); 
// Ex: $amazon->category('Small,Images,Offers');

```

### Calling Operation Method(s) over Amazon Class object - Core
```php
<?php
// Require the Class File.
require 'Amazon.php';

// Instantiate an Amazon PhP Library object.
$params = array(
  'AWS_API_KEY'         => 'XXXX-XXXX-XXXX-XXXX', 
  'AWS_API_SECRET_KEY'  => 'XXXX-XXXX-XXXX-XXXX', 
  'AWS_API_LOCALE'      => 'com', 
  'AWS_ASSOCIATE_TAG'   => 'XXXX-XXXX-XXXX-XXXX' 
);

$amazon = new Amazon($params);

$amazon->category('CATEGORY'); 
// Ex: $amazon->category('Books');

$amazon->responseGroup('RESPONSE GROUPS'); 
// Ex: $amazon->category('Small,Images,Offers');

$response = $amazon->search('SEARCH TERM'); 
// Ex: $response = $amazon->search('iphone');
// Ex: $response = $amazon->search('');

var_dump( $response ); // Response from Amazon with resulted products

$response = $amazon->lookup($ASIN); 
// Replace $ASIN with actual ASIN value of Amazon product

var_dump( $response ); // Response from Amazon with resulted product

```

### Calling Operation Method(s) over Amazon Class object - CodeIgniter
```php
<?php
// Instantiate an Amazon PhP Library object.
$params = array(
  'AWS_API_KEY'         => 'XXXX-XXXX-XXXX-XXXX', 
  'AWS_API_SECRET_KEY'  => 'XXXX-XXXX-XXXX-XXXX', 
  'AWS_API_LOCALE'      => 'com', 
  'AWS_ASSOCIATE_TAG'   => 'XXXX-XXXX-XXXX-XXXX' 
);

$this->load->library('amazon', $params);

$this->amazon->category('CATEGORY'); 
// Ex: $amazon->category('Books');

$this->amazon->responseGroup('RESPONSE GROUPS'); 
// Ex: $amazon->category('Small,Images,Offers');

$response = $this->amazon->search('SEARCH TERM'); 
// Ex: $this->response = $amazon->search('iphone');
// Ex: $this->response = $amazon->search('');

var_dump( $response ); // Response from Amazon with resulted products

$response = $this->amazon->lookup($ASIN); 
// Replace $ASIN with actual ASIN value of Amazon product

var_dump( $response ); // Response from Amazon with resulted product

```

### Calling Cart Operation Method - cartCreate - over Amazon Class object - Core
```php
<?php
// Require the Class File.
require 'Amazon.php';

// Instantiate an Amazon PhP Library object.
$params = array(
  'AWS_API_KEY'         => 'XXXX-XXXX-XXXX-XXXX', 
  'AWS_API_SECRET_KEY'  => 'XXXX-XXXX-XXXX-XXXX', 
  'AWS_API_LOCALE'      => 'com', 
  'AWS_ASSOCIATE_TAG'   => 'XXXX-XXXX-XXXX-XXXX' 
);

$amazon = new Amazon($params);

$amazon->responseGroup('Cart');  // DO NOT FORGOT TO SET THIS
$response = $amazon->cartCreate($ASIN, $qty = 1); 
// Replace $ASIN with actual ASIN value of Amazon product

$purchaseUrl      = $response->Cart->PurchaseURL;
$cartId 		      = $response->Cart->CartId;	
$HMAC 			      = $response->Cart->HMAC;	
$URLEncodedHMAC   = $response->Cart->URLEncodedHMAC;	
$MobileCartURL    = $response->Cart->MobileCartURL;	
$SubTotal		      = $response->Cart->SubTotal;	
$CartItems		    = $response->Cart->CartItems;	

```

### Calling Cart Operation Method - cartCreate - over Amazon Class object - CodeIgniter
```php
<?php
// Instantiate an Amazon PhP Library object.
$params = array(
  'AWS_API_KEY'         => 'XXXX-XXXX-XXXX-XXXX', 
  'AWS_API_SECRET_KEY'  => 'XXXX-XXXX-XXXX-XXXX', 
  'AWS_API_LOCALE'      => 'com', 
  'AWS_ASSOCIATE_TAG'   => 'XXXX-XXXX-XXXX-XXXX' 
);

$this->load->library('amazon', $params);

$this->amazon->responseGroup('Cart');  // DO NOT FORGOT TO SET THIS
$response = $this->amazon->cartCreate($ASIN, $qty = 1); 
// Replace $ASIN with actual ASIN value of Amazon product

$purchaseUrl      = $response->Cart->PurchaseURL;
$cartId 		      = $response->Cart->CartId;	
$HMAC 			      = $response->Cart->HMAC;	
$URLEncodedHMAC   = $response->Cart->URLEncodedHMAC;	
$MobileCartURL    = $response->Cart->MobileCartURL;	
$SubTotal		      = $response->Cart->SubTotal;	
$CartItems		    = $response->Cart->CartItems;	

```

### Calling Cart Operation Method - cartAdd - over Amazon Class object - Core
```php
<?php
// Require the Class File.
require 'Amazon.php';

// Instantiate an Amazon PhP Library object.
$params = array(
  'AWS_API_KEY'         => 'XXXX-XXXX-XXXX-XXXX', 
  'AWS_API_SECRET_KEY'  => 'XXXX-XXXX-XXXX-XXXX', 
  'AWS_API_LOCALE'      => 'com', 
  'AWS_ASSOCIATE_TAG'   => 'XXXX-XXXX-XXXX-XXXX' 
);

$amazon = new Amazon($params);

$amazon->responseGroup('Request,Cart'); // DO NOT FORGOT TO SET THIS
$response = $amazon->cartAdd($ASIN, $qty = 1, $HMAC, $cartId); 
// Replace $ASIN with actual ASIN value of Amazon product
// createCart methid will return HMAC and cartId that you need to supply 
// here in place of $HMAC and $cartId respectively.

$purchaseUrl      = $response->Cart->PurchaseURL;
$cartId 		      = $response->Cart->CartId;	
$HMAC 			      = $response->Cart->HMAC;	
$URLEncodedHMAC   = $response->Cart->URLEncodedHMAC;	
$MobileCartURL    = $response->Cart->MobileCartURL;	
$SubTotal		      = $response->Cart->SubTotal;	
$CartItems		    = $response->Cart->CartItems;	

```

### Calling Cart Operation Method - cartAdd - over Amazon Class object - CodeIgniter
```php
<?php
// Instantiate an Amazon PhP Library object.
$params = array(
  'AWS_API_KEY'         => 'XXXX-XXXX-XXXX-XXXX', 
  'AWS_API_SECRET_KEY'  => 'XXXX-XXXX-XXXX-XXXX', 
  'AWS_API_LOCALE'      => 'com', 
  'AWS_ASSOCIATE_TAG'   => 'XXXX-XXXX-XXXX-XXXX' 
);

$this->load->library('amazon', $params);

$this->amazon->responseGroup('Request,Cart'); // DO NOT FORGOT TO SET THIS
$response = $this->amazon->cartAdd($ASIN, $qty = 1, $HMAC, $cartId); 
// Replace $ASIN with actual ASIN value of Amazon product
// createCart methid will return HMAC and cartId that you need to supply 
// here in place of $HMAC and $cartId respectively.

$purchaseUrl      = $response->Cart->PurchaseURL;
$cartId 		      = $response->Cart->CartId;	
$HMAC 			      = $response->Cart->HMAC;	
$URLEncodedHMAC   = $response->Cart->URLEncodedHMAC;	
$MobileCartURL    = $response->Cart->MobileCartURL;	
$SubTotal		      = $response->Cart->SubTotal;	
$CartItems		    = $response->Cart->CartItems;	

```

### Calling Cart Operation Method - cartGet - over Amazon Class object - Core
```php
<?php
// Require the Class File.
require 'Amazon.php';

// Instantiate an Amazon PhP Library object.
$params = array(
  'AWS_API_KEY'         => 'XXXX-XXXX-XXXX-XXXX', 
  'AWS_API_SECRET_KEY'  => 'XXXX-XXXX-XXXX-XXXX', 
  'AWS_API_LOCALE'      => 'com', 
  'AWS_ASSOCIATE_TAG'   => 'XXXX-XXXX-XXXX-XXXX' 
);

$amazon = new Amazon($params);

$amazon->responseGroup('Request,Cart'); // DO NOT FORGOT TO SET THIS
$basket = $amazon->cartGet($HMAC, $cartId);
// createCart methid will return HMAC and cartId that you need to supply 
// here in place of $HMAC and $cartId respectively.

var_dump($basket); 

```

### Calling Cart Operation Method - cartGet - over Amazon Class object - CodeIgniter
```php
<?php
// Instantiate an Amazon PhP Library object.
$params = array(
  'AWS_API_KEY'         => 'XXXX-XXXX-XXXX-XXXX', 
  'AWS_API_SECRET_KEY'  => 'XXXX-XXXX-XXXX-XXXX', 
  'AWS_API_LOCALE'      => 'com', 
  'AWS_ASSOCIATE_TAG'   => 'XXXX-XXXX-XXXX-XXXX' 
);

$this->load->library('amazon', $params);

$this->amazon->responseGroup('Request,Cart'); // DO NOT FORGOT TO SET THIS
$basket = $this->amazon->cartGet($HMAC, $cartId);
// createCart methid will return HMAC and cartId that you need to supply 
// here in place of $HMAC and $cartId respectively.

var_dump($basket); 

```

### Calling Cart Operation Method - cartModify - over Amazon Class object - Core
```php
<?php
// Require the Class File.
require 'Amazon.php';

// Instantiate an Amazon PhP Library object.
$params = array(
  'AWS_API_KEY'         => 'XXXX-XXXX-XXXX-XXXX', 
  'AWS_API_SECRET_KEY'  => 'XXXX-XXXX-XXXX-XXXX', 
  'AWS_API_LOCALE'      => 'com', 
  'AWS_ASSOCIATE_TAG'   => 'XXXX-XXXX-XXXX-XXXX' 
);

$amazon = new Amazon($params);

$amazon->responseGroup('Request,Cart'); // DO NOT FORGOT TO SET THIS
$response = $amazon->cartModify($cartItemId, $qty = 2, $HMAC, $cartId);
// createCart methid will return HMAC and cartId that you need to supply 
// here in place of $HMAC and $cartId respectively.
// Once product get added to cart it can only be refer using cartItemId. 
// You can get cartItemId response of operations like cartCreate, cartAdd and cartGet. 
// You need to replace that value in place of $cartItemId.
// To delete product from cart - set $qty = 0

var_dump($response); 

```

### Calling Cart Operation Method - cartModify - over Amazon Class object - CodeIgniter
```php
<?php
// Instantiate an Amazon PhP Library object.
$params = array(
  'AWS_API_KEY'         => 'XXXX-XXXX-XXXX-XXXX', 
  'AWS_API_SECRET_KEY'  => 'XXXX-XXXX-XXXX-XXXX', 
  'AWS_API_LOCALE'      => 'com', 
  'AWS_ASSOCIATE_TAG'   => 'XXXX-XXXX-XXXX-XXXX' 
);

$this->load->library('amazon', $params);

$this->amazon->responseGroup('Request,Cart'); // DO NOT FORGOT TO SET THIS
$response = $this->amazon->cartModify($cartItemId, $qty = 2, $HMAC, $cartId);
// createCart methid will return HMAC and cartId that you need to supply 
// here in place of $HMAC and $cartId respectively.
// Once product get added to cart it can only be refer using cartItemId. 
// You can get cartItemId response of operations like cartCreate, cartAdd and cartGet. 
// You need to replace that value in place of $cartItemId.
// To delete product from cart - set $qty = 0

var_dump($response); 

```

### Calling Cart Operation Method - cartClear - over Amazon Class object - Core
```php
<?php
// Require the Class File.
require 'Amazon.php';

// Instantiate an Amazon PhP Library object.
$params = array(
  'AWS_API_KEY'         => 'XXXX-XXXX-XXXX-XXXX', 
  'AWS_API_SECRET_KEY'  => 'XXXX-XXXX-XXXX-XXXX', 
  'AWS_API_LOCALE'      => 'com', 
  'AWS_ASSOCIATE_TAG'   => 'XXXX-XXXX-XXXX-XXXX' 
);

$amazon = new Amazon($params);

$amazon->responseGroup('Request,Cart'); // DO NOT FORGOT TO SET THIS
$response = $amazon->cartClear($HMAC, $cartId);
// createCart methid will return HMAC and cartId that you need to supply 
// here in place of $HMAC and $cartId respectively.

var_dump($response); 

```

### Calling Cart Operation Method - cartClear - over Amazon Class object - CodeIgniter
```php
<?php
// Instantiate an Amazon PhP Library object.
$params = array(
  'AWS_API_KEY'         => 'XXXX-XXXX-XXXX-XXXX', 
  'AWS_API_SECRET_KEY'  => 'XXXX-XXXX-XXXX-XXXX', 
  'AWS_API_LOCALE'      => 'com', 
  'AWS_ASSOCIATE_TAG'   => 'XXXX-XXXX-XXXX-XXXX' 
);

$this->load->library('amazon', $params);

$this->amazon->responseGroup('Request,Cart'); // DO NOT FORGOT TO SET THIS
$response = $this->amazon->cartClear($HMAC, $cartId);
// createCart methid will return HMAC and cartId that you need to supply 
// here in place of $HMAC and $cartId respectively.

var_dump($response); 

```

### Set/Get Return Type to Amazon Class object - Core
```php
<?php
// Possible return types:
// 1 for Object (Default)
// 2 for Array

$returnType = $amazon->returnType(); // Get return type
var_dump($returnType);

$amazon->returnType(1); // Set return type to Object
$returnType = $amazon->returnType(); // Get changed return type
var_dump($returnType);

$amazon->returnType(2); // Set return type to Array
$returnType = $amazon->returnType(); // Get changed return type
var_dump($returnType);

```

### Set/Get Return Type to Amazon Class object - CodeIgniter
```php
<?php
// Possible return types:
// 1 for Object (Default)
// 2 for Array

$returnType = $this->amazon->returnType(); // Get return type
var_dump($returnType);

$this->amazon->returnType(1); // Set return type to Object
$returnType = $this->amazon->returnType(); // Get changed return type
var_dump($returnType);

$this->amazon->returnType(2); // Set return type to Array
$returnType = $this->amazon->returnType(); // Get changed return type
var_dump($returnType);

```
## Author:
[psbhanu](http://psbhanu.com) psbhanu@outlook.com

## Special Credits:
Exeu exeu65@googlemail.com, Julien Chaumond chaumond@gmail.com and Stu Baker stu@fountless.com
