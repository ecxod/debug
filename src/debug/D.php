<?php

declare(strict_types=1);

namespace Ecxod\Debug;

use function \Ecxod\Funktionen\{isMobile, m};

class D
{


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
     *  null
     *  unknown type
     *
     */

    protected const ignoreArray  = array(
        'SSL_SERVER_CERT',
        'REDIRECT_SSL_SERVER_CERT',
        'SSL_CLIENT_CERT',
        'REDIRECT_SSL_CLIENT_CERT'
    );
    /** varArray = headers, get, post, request, cookie, session, files, server, env, glob, result */
    protected const varArray     = array("headers", "get", "post", "request", "cookie", "session", "files", "server", "env", "glob", "result");
    /** notInGlob = get, post, request, cookie, session, files, server */
    protected const notInGlob    = array("get", "post", "request", "cookie", "session", "files", "server");
    /** showDetails = get, post, request, cookie, session "*/
    protected const showDetails  = array("get", "post", "request", "cookie", "session");

    // protected const get          = array();
    // protected const post         = array();
    // protected const cookie       = array();
    // protected const session      = array();
    // protected const files        = array();
    // protected const server       = array();
    // protected const request      = array();
    // protected const env          = array();
    // protected const glob         = array();

    private function details($v)
    {
        if (in_Array($v, SELF::showDetails)) {
            return ' open="" ';
        }
    }



    /**
     * @param string|int $k   anzahl
     * @param bool $v         wert
     * @param string $t       typ okorie
     * @param string $c       farbe
     * @return string 
     */
    private function boolDiv(string $ok, string|int $k, bool $v, string $t, string $c)
    {
        // "text-light","bg-primary","text-primary","bg-light
        $txt = '';
        $txt .= '<div data-class="' . __METHOD__ . ':' . __LINE__ . '" class="table-responsive-sm">';
        $txt .= '<span data-class="' . __METHOD__ . ':' . __LINE__ . '" class="text-light bg-' . $c . '">&nbsp;' . $t . '&nbsp;</span>';
        $txt .= '<code><span data-class="' . __METHOD__ . ':' . __LINE__ . '" class="debug">["' . strval($k) . '"]=</span></code>';
        $txt .= '<span data-class="' . __METHOD__ . ':' . __LINE__ . '" class="text-' . $c . ' bg-light font-weight-bolder">&nbsp;' . ($v ? "true" : "false") . '&nbsp;</span>';
        $txt .= '</div>';
        return $txt;
    }

    // "text-light","bg-dark","text-dark","bg-light"
    private function stringDiv(string $ok, string|int $k, string|float $v, string $t, string $c)
    {
        $txt = '';
        $txt .= '<div data-class="' . __METHOD__ . ':' . __LINE__ . '" class="table-responsive-sm">';
        $txt .= '<span data-class="' . __METHOD__ . ':' . __LINE__ . '" class="d-inline" id="mike1">';
        // $txt .= $this->typ($t,"bulina");
        // $txt .= (empty($m)?"":"\t");
        $txt .= '<B>' . $this->typ('$_' . strtoupper($ok), "bulina") . '</B>';
        // $txt .= '<B>[</B>"'.$k.'"<B>]</B>';
        $txt .= '<B>["<code>' . $k . '</code>"] = </B>';
        if (!in_array($k, SELF::ignoreArray)) {
            //$txt .= htmlspecialchars($v); 
            $txt .= '<span data-class="' . __METHOD__ . ':' . __LINE__ . '" class="text-' . $c . ' bg-light font-weight-bolder">&nbsp;' . htmlentities((strval($v))) . '&nbsp;</span>';
        }
        $txt .= '</span>';

        $txt .= '</div>';
        return $txt;
    }

    // "text-light","bg-purple","text-purple","bg-light"
    private function arrayDiv(string $ok, string|int $k, array|object $v, string $t, string $c)
    {
        $txt = '';
        $txt .= '<div data-class="' . __METHOD__ . ':' . __LINE__ . '" class="table-responsive-sm">';
        $txt .= '<span class="text-light bg-' . $c . '">&nbsp;' . $t . '&nbsp;</span>';
        $txt .= '<code><span>["' . $k . '"]</span></code>';
        $txt .= '<span class="text-' . $c . ' bg-light font-weight-bolder">&nbsp;';
        $txt .= '<span class="ms-5">' . $this->arrayDisplay($ok, $k, $v, $t) . '</span>&nbsp;';
        $txt .= '</span>';
        $txt .= '</div>';
        return $txt;
    }




    /**
     * @param string|int $keyy  array key
     * @param array $arr        array name
     * @param mixed $m          soll eine multidimensionelle array sein
     * @return string 
     */
    private function arrayDisplay(string $ok, string|int $keyy, array|object $arr, $m = null, string|null $oberkeyy = null)
    {
        $txt = F::m(__METHOD__);
        
        $txt .= '<div id="schachtel1" data-class="' . __METHOD__ . ':' . __LINE__ . '" class="d-flex flex-column align-items-start" style="white-space: pre;">';

        if (isset($arr) && is_array($arr)) {
            foreach ($arr as $k => $v) {

                $t = gettype($v);

                if ($t === 'array' || $t === 'object') {
                    if ($k != "GLOBALS" && (gettype($k) === 'string' || gettype($k) === 'int')) {
                        $txt .= $this->arrayDisplay($ok, $k, $v, "s", $keyy);
                    }
                } else {

                    $txt .= '<span data-class="' . __METHOD__ . ':' . __LINE__ . '" class="d-inline" id="mike1">';
                    $txt .= (empty($m) ? "" : "\t");
                    $txt .= '<b>' . $this->typ('$_' . strtoupper($ok), "bulina") . '</b>';
                    if ($oberkeyy) $txt .= '<B>["<code>' . $oberkeyy . '</code>"]</b>';
                    $txt .= '<b>["</b>' . ($oberkeyy ? $keyy : '<code>' . $keyy . '</code>') . '<b>"]</b>';
                    $txt .= '<b>["</b>' . $k . '<b>"] = </b>';
                    if (!in_array($k, SELF::ignoreArray)) {
                        $txt .= htmlspecialchars(strval($v));
                    }
                    $txt .= '</span>';

                    unset($t);
                }
            } // ende foreach
        } // is_array arr

        $txt .= '</div>';
        return $txt;
    }




    /** zeichnet einen Span eintrag
     * @param string $c     color
     * @param string $t     variable type
     * @return string 
     */
    private function sp(string $c, string $t)
    {
        $txt = "";
        $txt .= '<span data-class="' . __METHOD__ . ':' . __LINE__ . '" class="text-light bg-' . $c . '">&nbsp;' . htmlspecialchars($t) . '&nbsp;</span>';
        return $txt;
    }






    private function typ(string $t, string|bool $design = null)
    {
        $designs = array("sp", "bulina");
        if (in_array($design, $designs)) {
            $d = $design;
        } else {
            $d = "sp";
        }
        $txt = "";
        switch (gettype($t)) {
            case "boolean":
                $txt .= $this->$d("dark", $t);
                break;
            case "integer":
                $txt .= $this->$d("primary", $t);
                break;
            case "double":
                $txt .= $this->$d("danger", $t);
                break;
            case "string":
                $txt .= $this->$d("success", $t);
                break;
            case "array":
                $txt .= $this->$d("secondary", $t);
                break;
            case "object":
                $txt .= $this->$d("danger", $t);
                break;
            case "resource":
                $txt .= $this->$d("warning", $t);
                break;
            case "null":
                $txt .= $this->$d("info", $t);
                break;
            case "NULL":
                $txt .= $this->$d("info", $t);
                break;
            default:
                $txt .= $t;
                break;
        }
        return $txt;
    }


    private function bulina(string $c, string $t)
    {
        $txt = "";
        $txt .= '<span data-class="' . __METHOD__ . ':' . __LINE__ . '" class="badge text-bg-' . $c . '">&nbsp;' . htmlspecialchars($t) . '&nbsp;</span>';
        return $txt;
    }


    function debug($lang = null, $charset = null)
    {
        global $row;

        $txt = F::m(__METHOD__);
        F::userAgent();

        // das ist übel aber notwendig
        // $_SESSION['lang'],  $_ENV['CHARSET']
        $lang     = (isset($_SESSION['lang']) ? $_SESSION['lang'] : 'de_DE');
        $charset  = (isset($_ENV['CHARSET']) ?  $_ENV['CHARSET'] : 'UTF-8');
        $get      = (isset($_GET) ? $_GET : array());
        $post     = (isset($_POST) ? $_POST : array());
        $cookie   = (isset($_COOKIE) ? $_COOKIE : array());
        $session  = (isset($_SESSION) ? $_SESSION : array());
        $files    = (isset($_FILES) ? $_FILES : array());
        $server   = (isset($_SERVER) ? $_SERVER : array());
        $request  = (isset($_REQUEST) ? $_REQUEST : array());
        $env      = (isset($_ENV) ? $_ENV : array());
        $glob     = (isset($GLOBALS) ? $GLOBALS : array());
        $result   = (isset($row) ? $row : array());
        $headers  = getallheaders();

        if (
            isset($_SERVER['REMOTE_ADDR'])
            and
            in_array($_SERVER['REMOTE_ADDR'], str_getcsv($_ENV['MYIP']))
            and
            !F::isMobile()
            and
            isset($_SESSION['useragent']['1']['browserlist'])
            and
            stristr($_SESSION['useragent']['1']['browserlist'], 'Firefox')

        ) {

            $txt .= '<div data-class="' . __METHOD__ . ':' . __LINE__ . '" class="debug overflow-auto">';


            foreach (SELF::varArray as $varVar) {

                if (is_array(${$varVar}) || is_object(${$varVar})) {
                    $cvv = count(${$varVar});
                } else {
                    $cvv = false;
                }

                if ($cvv) {

                    $txt .= '<details ' . $this->details($varVar) . ' >';
                    $txt .= '<summary data-class="' . __METHOD__ . ':' . __LINE__ . '" class="alert alert-secondary m-1 p-1" role="alert">';
                    $txt .= '<code><span data-class="' . __METHOD__ . ':' . __LINE__ . '" class="font-weight-bold">' . $varVar . '</span> [' . $cvv . ']</code>';
                    $txt .= '</summary>';

                    foreach (${$varVar} as $key => $val) {
                        // wir zeigen keine keys
                        if (in_array($val, SELF::ignoreArray)) {
                            break;
                        }
                        // im glob zeigen wir keine system variablen
                        #if (  $varVar=="glob" && in_array(preg_replace('/^\_/i','',strtolower($key)),SELF::notInGlob)) {break;}
                        $ok = ($varVar ? $varVar : ''); // oberkategorie

                        $t = gettype($val);

                        switch ($t) {

                            case "boolean":
                                $txt .= $this->boolDiv($ok, $key, $val, $t, "primary");
                                break;

                            case "integer":
                                $txt .= $this->stringDiv($ok, $key, $val, $t, "dark");
                                break;

                            case "double":
                                $txt .= $this->stringDiv($ok, $key, $val, $t, "danger");
                                break;

                            case "string":
                                $txt .= $this->stringDiv($ok, $key, $val, $t, "success");
                                break;
                            case "resource":
                                $txt .= $this->stringDiv($ok, $key, $val, $t, "success");
                                break;

                            case "array":
                                $txt .= $this->arrayDiv($ok, $key, $val, $t, "secondary");
                                break;
                                #case "prepared": $txt .= $this->arrayDiv($ok,$key,$val,$t,"secondary");break;
                            case "object":
                                $txt .= $this->arrayDiv($ok, $key, $val, $t, "purple");
                                break;

                            case "null":
                                $txt .= $this->boolDiv($ok, $key, $val, $t, "primary");
                                break;

                            default:
                                $txt .= $t;
                                break;
                        } // switch zu ende
                        unset($t);
                        unset($key);
                        unset($val);
                    } // foreach
                    $txt .= '</details>';
                } // if
                unset(${$varVar});
                unset($cvv);
            } // foreach

            $txt .= '</div>';
        } // end if

        return $txt;
    } // end constructor

}
