# debug

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

include this in your scss file

debug.scss
```scss
@charset "UTF-8";
/* ----------------------

	DEBUG

-------------------------*/

@media only print {
    div.debug,
    span.debug,
    pre.debug {
        display: none;
        visibility: hidden;
    }
    body.screen {
        width: 100%;
    }
}

@media only screen and (min-width:1400px) {
    :root {
        --screen: 1400px;
        --padding: 10px;
        --small-border-y: 3px;
        --small-border-x: 10px;
        --debug-top: 3px;
        --debug-right: 3px;
        --debug-width: 290px;
        --debug-width-max: 290px;
    }
    body.screen {
        width: VAR(--screen);
    }
    div.debug {
        position: fixed;
        top: VAR(--debug-top);
        right: VAR(--debug-right);
        height: CALC(100% - VAR(--small-border-y));
        /* width: VAR(--debug-width); */
        max-width: VAR(--debug-width-max);
        border: 1px solid #ddd;
        background-color: LightGoldenrodYellow;
        font-size: 0.8rem;
    }
    span.debug,
    pre.debug {
        font-size: 0.7rem;
        /* width: VAR(--debug-width); */
    }
    form, i {
        /* margin-left: -27px; */
        cursor: pointer;
    }
}

```
