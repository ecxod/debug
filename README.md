# Ecxod/Debug

This Library is a part of the Ecxod framework, but it can also be used in other projects as a standalone module

**Install**  
In Ecxod there is no need to install it as it will be required automatically if you need debugging.
```php
php8.2 composer.phar require ecxod/debug
```

you will need to set the following variables  
`cat index.php`
```php
$_ENV[MYIP] = "192.168.178.2,192.168.178.2,23.34.45.56, ..."
```
or  
`cat .htaccess`
```sh
SetEnv MYIP "192.168.178.2,192.168.178.2,23.34.45.56, ... "
```

**Projektstruktur**
```sh
cat vendor/ecxod/debug

vendor
  ├─ecxod
  │   ├─debug
  │       ├── composer.json
  │       ├── LICENSE
  │       ├── README.md
  │       ├── scss
          │   ├── __data.scss
          │   └── debug.scss
          └── src
               └── debug
                   └── D.php
```

load the debug class at the end of your index.php


```php
<!DOCTYPE html>
<html lang="de_DE">
<head>
<link rel="stylesheet" type="text/css" href="https://ecxample.com/static/css/debug.min.css" as="style">
</head>

/**
D E B U G
*/
if( isMe() ){
  $oDEBUG = new D;
  echo $oDEBUG->debug($_SESSION['lang'], "UTF-8");
  unset($oDEBUG);
}
?>
```
