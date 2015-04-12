<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Common
 *
 * @author sougata
 */
class Application_Model_Helpers_Common
{

    const MAXPERPAGE_API_LIST = 50;
    const MAX_RECORDS_PER_PAGE = 10;
    const STATUS_ACTIVE = 'A';
    const STATUS_INACTIVE = 'I';
    const STORE_ENCRYPT_KEY = "df_Hu76J*7-O-(js";

    public static $debugprinting;

    /**
     * 
     * @param type $date
     * @param type $withTime 0=> Date only, 1=> Date/time, 2=> time only
     * @return string
     */
    public static function mysqlDateToLocal($date, $withTime = 0)
    {
        if ($date == '' || $date == '0000-00-00')
        {
            return '';
        }
        $date = strtotime($date);
        if ($withTime == 0) // date only
        {
            return date("F d, Y", $date);
        }
        else if ($withTime == 1) // date and time
        {
            return date("F d, Y h:i A", $date);
        }
        if ($withTime == 2) // time only
        {
            return date("h:i A", $date);
        }
        if ($withTime == 3) // hour only
        {
            return date("G", $date);
        }
        if ($withTime == 4) // hour only with leading zeros
        {
            return date("H:i:s", $date);
        }
    }

    public static function localDateTomysql($date, $withTime = TRUE)
    {
        if ($withTime == 1)
        {
            return date("Y-m-d H:i:s", strtotime($date));
        }
        else
        {
            return date("Y-m-d", strtotime($date));
        }
    }

    public static function currentDateTimeMySql()
    {
        return date("Y-m-d H:i:s");
    }

    public static function validateDate($date, $format = 'Y-m-d H:i:s')
    {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }

    public static function encryptPassword($pass)
    {
        return md5($pass);
    }

    public static function urlEncode(&$value)
    {
        $value = urlencode(urldecode($value));
    }

    public static function toCurrency($amt)
    {
        $currency = new Zend_Currency(new Zend_Locale('en_US'));
        return $currency->toCurrency($amt);
    }

    public static function currencySymbol()
    {
        $currency = new Zend_Currency();
        return $currency->getSymbol();
    }

    /**
     * to print on screen
     */
    public static function doDebug()
    {
        self::$debugprinting = TRUE;
        error_reporting(E_ALL | E_STRICT);
        ini_set('display_errors', 'on');
    }

    public static function debugprint($str)
    {

        $bt = debug_backtrace(FALSE);
        $file = $bt[0]['file'];
        $line = $bt[0]['line'];
        $function = $bt[1]['function'];

        $stack = "$file:$line $function() ::";

        $strFnl = $stack . 'debugprint :: REMOTE_ADDR: ' . $_SERVER['REMOTE_ADDR'] . ": " . $str;
        if (self::$debugprinting)
        {
            echo "<pre>";
            echo $strFnl . "<br/>\n";
        }

        error_log($strFnl);
    }

    public static function apiResponse($data, $format = 'json')
    {
        return Zend_Json::encode($data);
    }

    public static function ensureLength($string, $length)
    {
        $strlen = strlen($string);
        if ($strlen < $length)
        {
            $string = str_pad($string, $length, "0");
        }
        else if ($strlen > $length)
        {
            $string = substr($string, 0, $length);
        }
        return $string;
    }

    public static function GUID()
    {
        $microTime = microtime();
        list($a_dec, $a_sec) = explode(" ", $microTime);

        $dec_hex = sprintf("%x", $a_dec * 1000000);
        $sec_hex = sprintf("%x", $a_sec);

        Application_Model_Helpers_Common::ensureLength($dec_hex, 5);
        Application_Model_Helpers_Common::ensureLength($sec_hex, 6);

        $guid = "";
        $guid .= $dec_hex;
        $guid .= Application_Model_Helpers_Common::createGuidSection(3);
        $guid .= '-';
        $guid .= Application_Model_Helpers_Common::createGuidSection(4);
        $guid .= '-';
        $guid .= Application_Model_Helpers_Common::createGuidSection(4);
        $guid .= '-';
        $guid .= Application_Model_Helpers_Common::createGuidSection(4);
        $guid .= '-';
        $guid .= $sec_hex;
        $guid .= Application_Model_Helpers_Common::createGuidSection(6);

        return $guid;
    }

    public static function createGuidSection($characters)
    {
        $return = "";
        for ($i = 0; $i < $characters; $i++)
        {
            $return .= sprintf("%x", rand(0, 15));
        }
        return $return;
    }

    public static function uniqueHash($Length = 10)
    {
        $String = self::GUID();
        $String = md5($String);
        $StringLength = strlen($String);
        srand((double) microtime() * 1000000);
        $Begin = rand(0, ($StringLength - $Length - 1)); // Pick an arbitrary starting point.
        $password = substr($String, $Begin, $Length);
        return $password;
    }

    /**
     * 
     * @param type $string
     * @param type $key
     * @return type
     */
    public static function encrypt($string, $key = self::STORE_ENCRYPT_KEY)
    {
        #201407301723:arup #5462 remove unnecessary log
        return self::urlsafe_b64encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($key)
                                , $string, MCRYPT_MODE_CBC, md5(md5($key))));
    }

    /**
     * decrypt given encrypted string using given salt
     * @param <string> $string
     * @param <string> $key
     * @return <string>
     */
    public static function decrypt($string, $key = self::STORE_ENCRYPT_KEY)
    {
        #201407301723:arup #5462 remove unnecessary log
        return rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($key), self::urlsafe_b64decode($string), MCRYPT_MODE_CBC, md5(md5($key))), "\0");
    }

    /**
     * Create url safe base64_encode string
     * @param string $string
     * @return string
     */
    public static function urlsafe_b64encode($string)
    {
        $data = base64_encode($string);
        $data = str_replace(array('+', '/', '='), array('-', '_', '|'), $data);
        return $data;
    }

    /**
     * Create url safe urlsafe_b64decode string
     * @param string $string
     * @return string
     */
    public static function urlsafe_b64decode($string)
    {
        $data = str_replace(array('-', '_', '|'), array('+', '/', '='), $string);
        $mod4 = strlen($data) % 4;
        if ($mod4)
        {
            $data .= substr('====', $mod4);
        }
        return base64_decode($data);
    }

    /**
     * 
     * @param string $name
     * @param string $id
     * @return string
     */
    public static function hourDom($name, $id, $value = "")
    {
        $str = '<select name="' . $name . '" id="' . $id . '">'
                . '<option value="">--</option>';

        for ($i = 0; $i <= 23; $i++)
        {
            $str .= '<option value="' . $i . '" ' . (($i == $value && $value != '') ? 'selected="selected"' : '') . '>' . $i . '</option>';
        }

        $str .= '</select>';

        return $str;
    }

    /**
     * 
     * @param string $name
     * @param string $id
     * @return string
     */
    public static function miniuteDom($name, $id, $value = "")
    {
        $str = '<select name="' . $name . '" id="' . $id . '">'
                . '<option value="">--</option>';

        for ($i = 0; $i <= 59; $i++)
        {
            $str .= '<option value="' . $i . '" ' . (($i == $value && $value != '') ? 'selected="selected"' : '') . '>' . $i . '</option>';
        }

        $str .= '</select>';

        return $str;
    }

    public static function uniqueFileName($fileName)
    {
        return self::uniqueHash(8) . str_replace(" ", "_", $fileName);
    }

    /**
     * 
     * @param type $tempFile
     * @param type $filename
     * @param type $targetPath
     * @param type $allowed
     * @return type
     * @throws Exception
     */
    public static function upload($tempFile, $filename, $targetPath, $allowed = array())
    {


        if (count($allowed) > 0)
        {
            $ext = pathinfo($tempFile, PATHINFO_EXTENSION);
            if (!in_array($ext, $allowed))
            {
                throw new Exception("Invalid Extension", 101);
            }
        }

        try
        {

            if (!is_dir($targetPath))
            {
                mkdir($targetPath, 0777, true);
            }

            $targetFile = $targetPath . DIRECTORY_SEPARATOR . $filename;

            if (file_exists($targetFile))
            {
                $path_parts = pathinfo($targetFile);
                $pos = strrpos($path_parts['basename'], ".");
                if ($pos === false)
                {
                    $fname = $path_parts['basename'] . '_bak';
                }
                else
                {
                    $fname = substr($path_parts['basename'], 0, $pos) . '_bak.' . $path_parts['extension'];
                }
                $bakFile = $path_parts['dirname'] . DIRECTORY_SEPARATOR . $fname;
                rename($targetFile, $bakFile);
            }

            return move_uploaded_file($tempFile, $targetFile);
        }
        catch (Exception $e)
        {
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }

    public static function weekDayDom()
    {
        return array(0 => "Mon", 1 => "Tue", 2 => "Wed", 3 => "Thu", 4 => "Fri", 5 => "Sat", 6 => "Sun");
    }

    /**
     * 
     * @param type $var
     * @return string
     */
    public static function number_format($var)
    {
        if (is_numeric($var))
        {
            return number_format($var, 2, '.', '');
        }
        else
        {
            return $var;
        }
    }

    /**
     * This function X crypt the credit card
     * Exm: 12345678912346 to 1234XXXXXXXX2345 
     * 
     * @param string $txt
     * @return string
     */
    public static function xCryptText($txt)
    {

        $cardLen = strlen(self::formatCCNumber($txt));

        if ($cardLen > 8)
        {

            $retSt = substr($txt, 0, 4);

            for ($i = 0; $i < $cardLen - 8; $i++)
            {
                $retSt.='X';
            }

            $retSt .= substr($txt, $cardLen - 4, 4);
        }
        else
        {
            for ($i = 0; $i < $cardLen; $i++)
            {
                $retSt.='X';
            }
        }

        unset($txt);
        return $retSt;
    }

    /**
     * format cc number remove space and "-"
     * @param string $ccNumber 
     */
    public static function formatCCNumber($ccNumber)
    {
        return str_replace("-", "", trim(str_replace(" ", "", $ccNumber)));
    }

    /**
     * function to return type of card
     * @param type $number
     * @return string
     */
    public static function cardType($number)
    {
        $number = preg_replace('/[^\d]/', '', $number);
        if (preg_match('/^3[47][0-9]{13}$/', $number))
        {
            return 'American Express';
        }
        elseif (preg_match('/^3(?:0[0-5]|[68][0-9])[0-9]{11}$/', $number))
        {
            return 'Diners Club';
        }
        elseif (preg_match('/^6(?:011|5[0-9][0-9])[0-9]{12}$/', $number))
        {
            return 'Discover';
        }
        elseif (preg_match('/^(?:2131|1800|35\d{3})\d{11}$/', $number))
        {
            return 'JCB';
        }
        elseif (preg_match('/^5[1-5][0-9]{14}$/', $number))
        {
            return 'MasterCard';
        }
        elseif (preg_match('/^4[0-9]{12}(?:[0-9]{3})?$/', $number))
        {
            return 'Visa';
        }
        else
        {
            return 'Unknown';
        }
    }

    /**
     * 
     * @param date $date mysql dateformat(Y-m-d )
     * @param int $interval
     * @param type $designator default 'D', D=Days, H=Hour, M=Miniute, Y=Year
     * @return date mysql format (Y-m-d)
     * @throws Exception
     */
    public static function phpDateAdd($date, $interval, $designator = 'D', $withTime = FALSE)
    {
        $dateObj = new DateTime($date);

        if ($designator == 'D')
        {
            $dateObj->add(new DateInterval('P' . $interval . 'D'));
        }
        elseif ($designator == 'MN')
        {
            $dateObj->add(new DateInterval('P' . $interval . 'M'));
        }
        elseif ($designator == 'Y')
        {
            $dateObj->add(new DateInterval('P' . $interval . 'Y'));
        }
        elseif ($designator == 'H' || $designator == 'M')
        {
            $dateObj->add(new DateInterval('PT' . $interval . $designator));
        }
        else
        {
            throw new Exception("Invalid designator specifiled in " . __FUNCTION__);
        }

        if ($withTime)
        {
            return $dateObj->format('Y-m-d H:i:s');
        }
        else
        {
            return $dateObj->format('Y-m-d');
        }
    }

    /**
     * return the no of days between 2 dates
     * it is oppossite of mysql datediff
     * if first pram is greater and second is lesser then it will return negative value
     * e.g. 2013-05-07 , 2013-05-05 will return -2 whereas mysql return +2
     * @param <mysql date> $d1
     * @param <mysql date> $d2
     * @return <int>
     */
    public static function  phpDateDiff($d1, $d2)
    {
        $datetime1 = new DateTime($d1);
        $datetime2 = new DateTime($d2);
        $interval = $datetime1->diff($datetime2);
        $days = $interval->format('%R%a');
        return intval($days);
    }

     /**
     * 
     * @param date $date mysql dateformat(Y-m-d )
     * @param int $interval
     * @param type $designator default 'D', D=Days, H=Hour, M=Miniute, Y=Year
     * @return date mysql format (Y-m-d)
     * @throws Exception
     */
    public static function phpDateSub($date, $interval, $designator = 'D', $withTime = FALSE)
    {
        $dateObj = new DateTime($date);

        if ($designator == 'D')
        {
            $dateObj->sub(new DateInterval('P' . $interval . 'D'));
        }
        elseif ($designator == 'MN')
        {
            $dateObj->sub(new DateInterval('P' . $interval . 'M'));
        }
        elseif ($designator == 'Y')
        {
            $dateObj->sub(new DateInterval('P' . $interval . 'Y'));
        }
        elseif ($designator == 'H' || $designator == 'M')
        {
            $dateObj->sub(new DateInterval('PT' . $interval . $designator));
        }
        else
        {
            throw new Exception("Invalid designator specifiled in " . __FUNCTION__);
        }

        if ($withTime)
        {
            return $dateObj->format('Y-m-d H:i:s');
        }
        else
        {
            return $dateObj->format('Y-m-d');
        }
    }
    
    public static function  isUrlExist($url)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_NOBODY, TRUE);
        curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
  
        if($code >= 200 && $code < 400)
        {
            $status = TRUE;
        }
        else
        {
            $status = FALSE;
        }
        curl_close($ch);
        return $status;
    }
    
    public static function uppercaseFirst($str)
    {
        return ucfirst($str);
    }
    
}
