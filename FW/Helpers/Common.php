<?php

namespace FW\Helpers;
class Common {
   
    public static function normalize($data,$types){
        $types = explode('|', $types);
        if(is_array($types)){
            foreach($types as $v){
                if($v=='int'){
                    $data=(int)$data;
                }
                if($v=='float'){
                    $data=(float)$data;
                }
                if($v=='double'){
                    $data=(double)$data;
                }
                if($v=='bool'){
                    $data=(bool)$data;
                }
                if($v=='string'){
                    $data=(string)$data;
                }
                if($v=='trim'){
                    $data=trim($data);
                }
                if($v=='xss'){
                    $data=  self::xss_clean($data);
                }
            }
        }
        return $data;
    }

    public static function getBaseURL() {
        return $_SERVER["REQUEST_SCHEME"] . '://'.$_SERVER["HTTP_HOST"].$_SERVER["SCRIPT_NAME"];
    }

    public static function getBaseDir() {
        if (self::endsWith(self::getBaseURL(), 'index.php')) {
            return substr(self::getBaseURL(), 0, strlen(self::getBaseURL()) - strlen('index.php'));
        }
        return self::getBaseURL();
    }

    public static function getPublicFilesDir(){
        $arr = explode(DIRECTORY_SEPARATOR, __DIR__);
        $arr[count($arr) - 2] = 'app';
        $arr[count($arr) - 1] = 'public' . DIRECTORY_SEPARATOR;
        return join(DIRECTORY_SEPARATOR, $arr);
    }

    /**
     * Code is taken from https://gist.github.com/1098477
     * @param type $data
     * @return type
     */
    public static function xss_clean($data) {
        // Fix &entity\n;
        $data = str_replace(array('&amp;', '&lt;', '&gt;'), array('&amp;amp;', '&amp;lt;', '&amp;gt;'), $data);
        $data = preg_replace('/(&#*\w+)[\x00-\x20]+;/u', '$1;', $data);
        $data = preg_replace('/(&#x*[0-9A-F]+);*/iu', '$1;', $data);
        $data = html_entity_decode($data, ENT_COMPAT, 'UTF-8');

        // Remove any attribute starting with "on" or xmlns
        $data = preg_replace('#(<[^>]+?[\x00-\x20"\'])(?:on|xmlns)[^>]*+>#iu', '$1>', $data);

        // Remove javascript: and vbscript: protocols
        $data = preg_replace('#([a-z]*)[\x00-\x20]*=[\x00-\x20]*([`\'"]*)[\x00-\x20]*j[\x00-\x20]*a[\x00-\x20]*v[\x00-\x20]*a[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2nojavascript...', $data);
        $data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*v[\x00-\x20]*b[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2novbscript...', $data);
        $data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*-moz-binding[\x00-\x20]*:#u', '$1=$2nomozbinding...', $data);

        // Only works in IE: <span style="width: expression(alert('Ping!'));"></span>
        $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?expression[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
        $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?behaviour[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
        $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:*[^>]*+>#iu', '$1>', $data);

        // Remove namespaced elements (we do not need them)
        $data = preg_replace('#</*\w+:\w[^>]*+>#i', '', $data);

        do {
            // Remove really unwanted tags
            $old_data = $data;
            $data = preg_replace('#</*(?:applet|b(?:ase|gsound|link)|embed|frame(?:set)?|i(?:frame|layer)|l(?:ayer|ink)|meta|object|s(?:cript|tyle)|title|xml)[^>]*+>#i', '', $data);
        } while ($old_data !== $data);

        // we are done...
        return htmlspecialchars($data);
    }
    
    public static function headerStatus($code) {
        $codes = array(
            100 => 'Continue',
            101 => 'Switching Protocols',
            102 => 'Processing',
            200 => 'OK',
            201 => 'Created',
            202 => 'Accepted',
            203 => 'Non-Authoritative Information',
            204 => 'No Content',
            205 => 'Reset Content',
            206 => 'Partial Content',
            207 => 'Multi-Status',
            300 => 'Multiple Choices',
            301 => 'Moved Permanently',
            302 => 'Found',
            303 => 'See Other',
            304 => 'Not Modified',
            305 => 'Use Proxy',
            307 => 'Temporary Redirect',
            400 => 'Bad Request',
            401 => 'Unauthorized',
            402 => 'Payment Required',
            403 => 'Forbidden',
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            406 => 'Not Acceptable',
            407 => 'Proxy Authentication Required',
            408 => 'Request Timeout',
            409 => 'Conflict',
            410 => 'Gone',
            411 => 'Length Required',
            412 => 'Precondition Failed',
            413 => 'Request Entity Too Large',
            414 => 'Request-URI Too Long',
            415 => 'Unsupported Media Type',
            416 => 'Requested Range Not Satisfiable',
            417 => 'Expectation Failed',
            422 => 'Unprocessable Entity',
            423 => 'Locked',
            424 => 'Failed Dependency',
            426 => 'Upgrade Required',
            500 => 'Internal Server Error',
            501 => 'Not Implemented',
            502 => 'Bad Gateway',
            503 => 'Service Unavailable',
            504 => 'Gateway Timeout',
            505 => 'HTTP Version Not Supported',
            506 => 'Variant Also Negotiates',
            507 => 'Insufficient Storage',
            509 => 'Bandwidth Limit Exceeded',
            510 => 'Not Extended'
        );
        if (!$codes[$code]) {
            $code = 500;
        }
        header($_SERVER['SERVER_PROTOCOL'] . ' ' . $code . ' ' . $codes[$code], true, $code);
    }

    public static function startsWith($haystack, $needle) {
        // search backwards starting from haystack length characters from the end
        return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== FALSE;
    }
    public static function endsWith($haystack, $needle) {
        // search forward starting from end minus needle length characters
        return $needle === "" || (($temp = strlen($haystack) - strlen($needle)) >= 0 && strpos($haystack, $needle, $temp) !== FALSE);
    }

    public static function hash($string) {
        $salt = substr(strtr(base64_encode(openssl_random_pseudo_bytes(22)), '+', '.'), 0, 22);
        return crypt($string, '$2y$12$' . $salt);
    }

    public static function validateHash($string, $hash) {
        return $hash == crypt($string, $hash);
    }

    public static function hashPassword($password) {
        if (version_compare(phpversion(), '5.5.0', '>=')) {
            $password = password_hash($password, PASSWORD_BCRYPT);
        } else {
            $password = Common::hash($password);
        }

        return $password;
    }

    public static function verifyPassword($password, $hash) {
        if (version_compare(phpversion(), '5.5.0', '>=')) {
            $isPasswordValid = password_verify($password, $hash);
        } else {
            $isPasswordValid = Common::validateHash($password, $hash);
        }

        return $isPasswordValid;
    }
}


