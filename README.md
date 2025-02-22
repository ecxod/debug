# Ecxod/Debug

This Library is a part of the Ecxod framework, but it can also be used in other projects as a standalone module

### Install  
In Ecxod there is no need to install it as it will be required automatically if you need debugging.

```php
php8.2 composer.phar require ecxod/debug
```
the above command requires you to set the following variables in dotenv
and it will compile and install also the debug.min.css in the `$_ENV["CSSPATH"]` path 
### Dotenv: you will need to set the following variables 
```php
$_ENV['MYIP']=...       # ex. 192.168.178.10,192.168.178.20, optional ..
$_ENV['SCSSPATH']=...   # ex. /scss
$_ENV['VENDOR']=...     # ex. /vendor
$_ENV['AUTOLOAD']=...   # ex. /vendor/autoload.php
$_ENV['WORKSPACE']=...  # ex. /workspace
$_ENV['DOCROOT']=...    # ex. /workspace/public
$_ENV["STATIC"]=...     # ex. /workspace/public/static
$_ENV["CSSPATH"]=...    # ex. /workspace/public/static/css
$_ENV["JSPATH"]=...     # ex. /workspace/public/static/js
```


**Projektstruktur**
```sh

scss/

public/
  ├─static/
  │   ├─css/
  │   ├─images/
  │   ├─js/
  │ 
  └── index.php

vendor/
  ├─ecxod/
  │   ├─debug/
  │       ├── compiler.php
  │       ├── composer.json
  │       ├── composer.phar
  │       ├── LICENSE
  │       ├── README.md
  │       ├── scss
          │   └── debug.scss
          └── src
               └── debug
                   └── D.php
```

### load the debug class at the end of your index.php

#### cat `index.php`
```php
<!DOCTYPE html>
<html lang="de_DE">
<head>
<link rel="stylesheet" type="text/css" href="https://ecxample.com/static/css/debug.min.css" as="style">
</head>


<?php
/**
D E B U G
*/
if( isMe() ){
  $oDEBUG = new \Ecxod\Debug\D;
  echo $oDEBUG->debug($_SESSION['lang'], "UTF-8");
  unset($oDEBUG);
}
?>
```
