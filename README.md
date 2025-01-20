# Ecxod/Debug

Part of the Ecxod framework

```php
php8.2 composer.phar require ecxod/debug
```

you will need to set the following variables
$_ENV[MYIP] = "192.168.178.2,192.168.178.2,23.34.45.56, ..."

cat .htaccess
```sh
SetEnv MYIP "192.168.178.2,192.168.178.2,23.34.45.56, ... "

```


```tree
├── composer.json
├── LICENSE
├── README.md
├── scss
│   ├── __data.scss
│   └── debug.scss
└── src
    └── debug
        └── D.php
```

load the debug class at the end of your index.php


```php
/**
D E B U G
*/
if( isMe() ){
  $oDEBUG = new D;
  echo $oDEBUG->debug($_SESSION['lang'], "UTF-8");
  unset($oDEBUG);
}
```
