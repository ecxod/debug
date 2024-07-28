<?php
class D {

  /**
  *
  *  Original befindet sich auf micro /var/web/_php/classen
  *
  *  boolean
  *  integer
  *  double (for historical reasons “double” is returned in case of float)
  *  string
  *  array
  *  object
  *  resource
  *  NULL
  *  unknown type
  * 
  *   to.do. : Gibt es Vorteile mit tags zu arbeiten ?
  *   $tags = get_meta_tags(K::HOST.'/');
  * 
  */

  const ignoreArray  = array(
    'SSL_SERVER_CERT',
    'REDIRECT_SSL_SERVER_CERT',
    'SSL_CLIENT_CERT',
    'REDIRECT_SSL_CLIENT_CERT'
  );
  const varArray     = array("headers","get","post","request","cookie","session","files","server","env","glob","result");
  const notInGlob    = array("headers","get","post","request","cookie","session","files","server");
  const showDetails  = array("headers","get","post","request","cookie","session");

  private function details($v) {
    if(in_Array($v,SELF::showDetails)) {return ' open="" ';}
  }

  // "text-light","bg-primary","text-primary","bg-light
  private function boolDiv($k,$v,$t,$c) {
    echo 
      '<div class="table-responsive-sm">',
      '<span class="text-light bg-'.$c.' debug">&nbsp;'.$t.'&nbsp;</span>',
      '<code><span class="debug">["'.$k.'"]=</span></code>',
      '<span class="text-'.$c.' bg-light font-weight-bolder debug">&nbsp;',
        ($v? "TRUE":"FALSE"),
      '&nbsp;</span>',
      '</div>';
  }

  // "text-light","bg-dark","text-dark","bg-light"
  private function stringDiv($k,$v,$t,$c) {
    echo '<div class="table-responsive-sm">',
      '<span class="text-light bg-'.$c.' debug">&nbsp;'.$t.'&nbsp;</span>',
      '<code><span class="debug">["'.$k.'"]=</span></code>',
      '<span class="text-'.$c.' bg-light font-weight-bolder debug">&nbsp;',
        htmlentities($v),
     '&nbsp;</span>',
     '</div>';
  }

  // "text-light","bg-purple","text-purple","bg-light"
  private function arrayDiv($k,$v,$t,$c){
    echo
      '<div class="table-responsive-sm">',
      '<span class="text-light bg-'.$c.' debug">&nbsp;'.$t.'&nbsp;</span>',
      '<code><span class="debug">["'.$k.'"]</span></code>',
      '<span class="text-'.$c.' bg-light font-weight-bolder debug">&nbsp;',
       '<span class="debug ms-5">',
          $this->arrayDisplay($k,$v),
       '</span>&nbsp;',
     '</span>',
     '</div>';
  }

  private function sp($c,$t){
    echo '<BR /><span class="text-light bg-'.$c.'">&nbsp;'.$t.'&nbsp;</span>';
  }


  private function arrayDisplay($keyy,$arr,$m=NULL){
    foreach($arr as $k => $v){
      $t = gettype($v);

      if( $t=='array' || $t=='object' ){
        if($k!=="GLOBALS")$this->arrayDisplay($k,$v,"s");
      }

      else{
        switch($t){
          case "boolean":  $this->sp("dark",$t);break;
          case "integer":  $this->sp("primary",$t);break;
          case "double":   $this->sp("danger",$t);break;
          case "string":   $this->sp("success",$t);break;
          case "array":    $this->sp("secondary",$t);break;
          case "object":   $this->sp("purple",$t);break;
          case "resource": $this->sp("warning",$t);break;
          case "NULL":     $this->sp("info",$t);break;
          default:         echo $t; break;
        }

        if(empty($m)){
          // eine multidimensionelle array
          echo '<code><span class="debug">'.'["'.$keyy.'"]["' .$k. '"] = </span></code>';
        }else{
          echo '<code><span class="debug">'."\t".'$'.$keyy.'["' .$k. '"] = </span></code>';
        }

        unset($t);

        if(stristr($v,PHP_EOL)){
          echo '<pre class="debug m-0 p-0 ms-5"> ';
          if(!in_array($k,SELF::ignoreArray)){ echo mb_convert_encoding($v, 'UTF-8'); }
          echo '</pre>';
        }else{
          if(!in_array($k,SELF::ignoreArray)){ echo '<span class="debug"> '.mb_convert_encoding($v, 'UTF-8').'</span>'; }
        }
        
      } 
    }
  }



  function debug( $lang="de", $charset) {

    // das ist übel aber notwendig
    $headers  = getallheaders();
    $get      = $_GET;
    $post     = $_POST;
    $cookie   = $_COOKIE;
    $session  = $_SESSION;
    $files    = $_FILES;
    $server   = $_SERVER;
    $request  = $_REQUEST;
    $env      = $_ENV;
    $glob     = $GLOBALS;

    if( in_array($_SERVER['REMOTE_ADDR'],K::MYIP) ){

    echo '<div class="debug overflow-auto">';

    foreach( SELF::varArray as $varVar ){

      if( !isset( ${$varVar} ) ){break;}

      if( is_array(${$varVar}) || is_object(${$varVar}) ){
        $cvv = count (${$varVar});
      }else{
        $cvv = FALSE;
      }
      
      if($cvv){
        
        echo '<details '.$this->details($varVar).' >';
          echo '<summary class="alert alert-secondary m-1 p-1" role="alert">';
            echo '<code class="h6"><span class="font-weight-bold">'.$varVar.'</span> ['.$cvv.']</code>';
          echo '</summary>';

          foreach (${$varVar} as $key => $val){
            // wir zeigen keine keys
            if(in_array($val,SELF::ignoreArray)){break;}
            // im glob zeigen wir keine system variablen
            #if($varVar=="glob" && in_array(preg_replace('/^\_/i','',strtolower($key)),SELF::notInGlob)){break;}
            
            
            $t = gettype($val);
            
            switch($t){
              
              case "boolean":  $this->boolDiv($key,$val,$t,"primary");
                               break;

              case "integer":  $this->stringDiv($key,$val,$t,"dark");
                               break;

              case "double":   $this->stringDiv($key,$val,$t,"danger");
                               break;

              case "string":   $this->stringDiv($key,$val,$t,"success");
                               break;

              case "array":    $this->arrayDiv($key,$val,$t,"secondary");
                               break;

              case "object":   $this->arrayDiv($key,$val,$t,"purple");
                               break;

              case "resource": $this->stringDiv($key,$val,$t,"warning");
                               break;

              case "NULL":     $this->stringDiv($key,$val,$t,"info");
                               break;

              default:         echo $t;
                               break;

            } // switch zu ende
            unset($t);
            unset($key);
            unset($val);
          } // foreach
        echo '</details>';
      } // if 
        unset(${$varVar});
        unset($cvv);
    } // foreach
    echo '</div>';
    } // end if
  } // end constructor

}


?>
