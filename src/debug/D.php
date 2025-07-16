<?php

declare(strict_types=1);

namespace Ecxod\Debug;

use function \Ecxod\Funktionen\{m, isMobile, userAgent};

class D
{
    protected array $me;
    public string   $remote;

    public function __construct()
    {
        $this->me     = str_getcsv(string: strval(value: $_ENV['MYIP']) ?? "192.168.78.20,217.244.9.139");
        $this->remote = strval(value: $_SERVER['REMOTE_ADDR']);
    }

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
    protected const ignoreArray = [ 'SSL_SERVER_CERT', 'REDIRECT_SSL_SERVER_CERT', 'SSL_CLIENT_CERT', 'REDIRECT_SSL_CLIENT_CERT' ];
    /** varArray = headers, get, post, request, cookie, session, files, server, env, glob, result */
    protected const varArray = [ "headers", "get", "post", "request", "cookie", "session", "files", "server", "env", "glob", "result" ];
    /** notInGlob = get, post, request, cookie, session, files, server */
    protected const notInGlob = [ "get", "post", "request", "cookie", "session", "files", "server" ];
    /** showDetails = get, post, request, cookie, session "*/
    protected const showDetails = [ "get", "post", "request", "cookie", "session" ];

    private function details($v): string
    {
        return \in_array(needle: $v, haystack: self::showDetails) ? ' open="" ' : '';
    }

    /**
     * @param string|int $k   anzahl
     * @param bool $v         wert
     * @param string $t       typ okorie
     * @param string $c       farbe
     * @return string 
     */
    private function bool_Div(string $ok, string|int $k, bool $v, string $t, string $c): string
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
    private function string_Div(string $ok, string|int $k, string|float $v, string $t, string $c): string
    {
        $txt = '';
        $txt .= '<div data-class="' . __METHOD__ . ':' . __LINE__ . '" class="table-responsive-sm">';
        $txt .= '<span data-class="' . __METHOD__ . ':' . __LINE__ . '" class="d-inline" id="mike1">';
        // $txt .= $this->typ($t,"bulina");
        // $txt .= (empty($m)?"":"\t");
        $txt .= '<B>' . $this->typ(t: '$_' . strtoupper(string: $ok), design: "bulina") . '</B>';
        // $txt .= '<B>[</B>"'.$k.'"<B>]</B>';
        $txt .= "<B>[\"<code>$k</code>\"] = </B>";
        if(!in_array(needle: $k, haystack: self::ignoreArray))
        {
            $enc_v = \htmlentities(string: \strval(value: $v));
            $txt .= '<span data-class="' . __METHOD__ . ':' . __LINE__ . '" class="text-' . $c . ' font-weight-bolder">&nbsp;' . $enc_v . '&nbsp;</span>';
        }
        $txt .= '</span>';
        $txt .= '</div>';
        return $txt;
    }


    /** 
     *  
     * "text-light","bg-purple","text-purple","bg-light"
     * 
     * @param string $ok 
     * @param string|int $k 
     * @param array|object $v 
     * @param string $t 
     * @param string $c 
     * @return string 
     */
    private function array_Div(string $ok, string|int $k, array|object $v, string $t, string $c): string
    {
        $txt = '';
        $txt .= '<div data-class="' . __METHOD__ . ':' . __LINE__ . '" class="table-responsive-sm">';
        $txt .= "<span class=\"text-light bg-$c\">&nbsp;$t&nbsp;</span>";
        $txt .= "<code><span>[\"$k\"]</span></code>";
        $txt .= "<span class=\"text-$c bg-light font-weight-bolder\">&nbsp;";
        $txt .= '<span class="ms-5">' . $this->array_Display(ok: $ok, keyy: $k, arr: $v, m: $t) . '</span>&nbsp;</span>';
        $txt .= '</div>';
        return $txt;
    }

    /**
     * @param string|int $keyy  array key
     * @param array $arr        array name
     * @param mixed $m          soll eine multidimensionelle array sein
     * @return string 
     */
    private function array_Display(string $ok, string|int $keyy, array|object $arr, $m = null, string|null $oberkeyy = null): string
    {
        $txt = m(m: __METHOD__);
        $txt .= '<div id="schachtel1" data-class="' . __METHOD__ . ':' . __LINE__ . '" class="d-flex flex-column align-items-start" style="white-space: pre;">';
        if(isset($arr) && is_array(value: $arr))
        {
            foreach($arr as $k => $v)
            {

                $t = \gettype(value: $v);
                if($t === 'array' || $t === 'object')
                {
                    if($k != "GLOBALS" && (\gettype(value: $k) === 'string' || \gettype(value: $k) === 'int'))
                    {
                        $txt .= $this->array_Display($ok, keyy: $k, arr: $v, m: "s", oberkeyy: $keyy);
                    }
                }
                else
                {

                    $txt .= '<span data-class="' . __METHOD__ . ':' . __LINE__ . '" class="d-inline" id="mike1">';
                    $txt .= empty($m) ? "" : "\t";
                    $txt .= '<b>' . $this->typ(t: '$_' . strtoupper(string: $ok), design: "bulina") . '</b>';
                    if($oberkeyy)
                        $txt .= "<B>[\"<code>$oberkeyy</code>\"]</b>";
                    $txt .= '<b>["</b>' . ($oberkeyy ? $keyy : "<code>$keyy</code>") . '<b>"]</b>';
                    $txt .= "<b>[\"</b>$k<b>\"] = </b>";
                    if(!\in_array(needle: $k, haystack: self::ignoreArray))
                    {
                        $txt .= \htmlspecialchars(string: \strval(value: $v));
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
    private function sp(string $c, string $t): string
    {
        $enc_t = \htmlspecialchars(string: $t);
        return '<span data-class="' . __METHOD__ . ':' . __LINE__ . '" class="text-light bg-' . $c . '">&nbsp;' . $enc_t . '&nbsp;</span>';
    }

    /** zeichnet eine blase/badge
     * @param string $c color
     * @param string $t variable type
     * @return string 
     */
    private function bulina(string $c, string $t): string
    {
        $enc_t = \htmlspecialchars(string: $t);
        return '<span data-class="' . __METHOD__ . ':' . __LINE__ . '" class="badge text-bg-' . $c . '">&nbsp;' . $enc_t . '&nbsp;</span>';
    }

    /** 
     * färbt die spans sp und bulina je nach typ der variable
     * 
     * @param string $t die variable
     * @param null|string|bool $design die span [sp oder bulina] 
     * @return string 
     */
    private function typ(string $t, string|bool $design = null): string
    {
        $designs = [ "sp", "bulina" ];
        $d       = \in_array(needle: $design, haystack: $designs) ? $design : "sp";
        $txt     = match (gettype(value: $t))
        {
            "boolean"  => $this->$d("dark", $t),
            "integer"  => $this->$d("primary", $t),
            "double"   => $this->$d("danger", $t),
            "string"   => $this->$d("success", $t),
            "array"    => $this->$d("secondary", $t),
            "object"   => $this->$d("danger", $t),
            "resource" => $this->$d("warning", $t),
            "null"     => $this->$d("info", $t),
            "NULL"     => $this->$d("info", $t),
            default    => $t,
        };
        return $txt;
    }



    public function debug(string|null $lang = null, string|null $charset = null, int $width = 0): string
    {
        global $row;

        $txt = m(m: __METHOD__);
        userAgent();

        $lang    = $_SESSION['lang'] ?? 'de_DE';
        $charset = strval(value: $_ENV['CHARSET']) ?? 'UTF-8';
        // failsafe
        $get     = $_GET ?? [];
        $post    = $_POST ?? [];
        $cookie  = $_COOKIE ?? [];
        $session = $_SESSION ?? [];
        $files   = $_FILES ?? [];
        $server  = $_SERVER ?? [];
        $request = $_REQUEST ?? [];
        $env     = $_ENV ?? [];
        $glob    = $GLOBALS ?? [];
        $result  = $row ?? [];
        $headers = getallheaders();

        if(
            !empty($this->remote)
            and
            in_array(needle: $this->remote, haystack: $this->me)
            and
            !isMobile()
            and
            !empty($_SESSION['useragent']['1']['browserlist'])
            and
            stristr(haystack: $_SESSION['useragent']['1']['browserlist'], needle: 'Firefox')
            // TODO Auch auf anderen Browser testen.
        )
        {
            $debug_style = empty($width) ? '' : " style=\"width:{$width}px;\" ";
            $txt .= '<div data-class="' . __METHOD__ . ':' . __LINE__ . '" class="debug overflow-auto" ' . $debug_style . '>';
            foreach(self::varArray as $varVar)
            {
                $cvv = (\is_array(value: ${$varVar}) || \is_object(value: ${$varVar})) ? \count(value: ${$varVar}) : false;
                if($cvv)
                {
                    $details = $this->details(v: $varVar);
                    $txt .= sprintf(
                        "<details %s><summary data-class=\"%s:%d\" class=\"alert alert-secondary m-1 p-1\" role=\"alert\"><code><span data-class=\"%s:%d\" class=\"font-weight-bold\">%s</span> [%d]</code></summary>",
                        $details,
                        __METHOD__,
                        __LINE__,
                        __METHOD__,
                        __LINE__,
                        $varVar,
                        $cvv
                    );

                    foreach(${$varVar} as $key => $val)
                    {
                        // wir zeigen keine keys
                        if(\in_array(needle: $val, haystack: self::ignoreArray))
                            break;
                        // im glob zeigen wir keine system variablen
                        #if (  $varVar=="glob" && in_array(preg_replace('/^\_/i','',strtolower($key)),SELF::notInGlob)) {break;}
                        $ok  = $varVar ?: '';
                        $t   = \gettype(value: $val);
                        $txt .= match ($t)
                        {
                            "boolean"  => $this->bool_Div(ok: $ok, k: $key, v: $val, t: $t, c: "primary"),
                            "integer"  => $this->string_Div(ok: $ok, k: $key, v: $val, t: $t, c: "dark"),
                            "double"   => $this->string_Div(ok: $ok, k: $key, v: $val, t: $t, c: "danger"),
                            "string"   => $this->string_Div(ok: $ok, k: $key, v: $val, t: $t, c: "success"),
                            "resource" => $this->string_Div(ok: $ok, k: $key, v: $val, t: $t, c: "success"),
                            "array"    => $this->array_Div(ok: $ok, k: $key, v: $val, t: $t, c: "secondary"),
                            "object"   => $this->array_Div(ok: $ok, k: $key, v: $val, t: $t, c: "purple"),
                            "null"     => $this->bool_Div(ok: $ok, k: $key, v: $val, t: $t, c: "primary"),
                            default    => $t,
                        };
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
