# Ecxod/Debug

Part of the Ecxod framework

```php
php8.2 composer.phar require ecxod/debug
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
