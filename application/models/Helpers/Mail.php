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
class Application_Model_Helpers_Mail
{

     /**
     * STATIC EMAILS KEY
     * @var type 
     */
    const EMAIL_KEY_ADMIN = "admin";
    const EMAIL_KEY_SERVICE_REQUEST = "servicerequest";
    const EMAIL_KEY_MANAGER = "manager";

    /**
     * 
     * @param string $fromEmail
     * @param string $fromName
     * @param string $subject
     * @param string $body
     * @param array|string $to
     * @param array $cc
     * @param array $bcc
     * @param array $attchs
     * @return Zend_Mail
     */
    public static function send($fromEmail, $fromName, $subject, $body, $to, $cc = array(), $bcc = array(), $attchs = array())
    {
        /*Application_Model_Helpers_Common::debugprint($subject."SDSD".$body."###".  var_export($to, TRUE));
          $config = array('ssl' => 'tls',
          'auth' => 'login',
          'username' => 'XXX@gmail.com',
          'password' => 'XXX');

          $transport = new Zend_Mail_Transport_Smtp('smtp.gmail.com', $config); */

        $mail = new Zend_Mail();
        $mail->setFrom($fromEmail, $fromName);
        $mail->setSubject($subject);
        $mail->setBodyHtml($body);
        if (is_array($to))
        {
            foreach ($to as $emailto => $nameto)
            {
                $mail->addTo($emailto, $nameto);
            }
        }
        else
        {
            $mail->addTo($to);
        }

        if (is_array($cc))
        {
            foreach ($cc as $emailccto => $nameccto)
            {
                $mail->addCc($emailccto, $nameccto);
            }
        }
        else
        {
            $mail->addCc($cc);
        }
        if (is_array($bcc))
        {
            foreach ($bcc as $emailbccto => $namebccto)
            {
                $mail->addBcc($emailbccto, $namebccto);
            }
        }
        else
        {
            $mail->addBcc($bcc);
        }
        
        if(is_array($attchs) && count($attchs) > 0)
        {
            foreach ($attchs as $attch)
            {
                $at = new Zend_Mime_Part($attch['body']);
                $at->type        = $attch['type'];
                $at->disposition = Zend_Mime::DISPOSITION_ATTACHMENT;
                $at->encoding    = Zend_Mime::ENCODING_BASE64;
                $at->filename    = $attch['name'];

                $mail->addAttachment($at);
            }
        }

        return $mail->send();
    }

    /**
     * Get Email address (those are defined in emails.ini) as array by given key.
     * @param text $key
     * @return array Ex: For ini data emaildata.admin[] = "xxx@aggregatedfeed.com,xxxx" 
     *                                emaildata.admin[] = "yy@aggregatedfeed.com" ;
     *              the return 
     *              Array
      (
     *                 [xxxx] => xxx@aggregatedfeed.com
                       [1] => yy@aggregatedfeed.com
      )

     */
    public static function getIniEmailData($key)
    {
        if ($key == '')
        {
            throw new ExceptionBadData("key can not be blank");
        }
        $resArray = array();
        $rdata = Zend_Registry::get('email_' . $key);

        if (count($rdata) > 0)
        {
            foreach ($rdata as $emailkey => $emaildata)
            {
                $tdata = explode(",", $emaildata);
                if ($tdata[1] != '')
                {
                    $resArray[$tdata[1]] = $tdata[0];
                }
                else
                {
                    $resArray[$emailkey] = $tdata[0];
                }
            }
        }
        return $resArray;
    }

    public static function getStoreFromMail()
    {
         $adminEmaild = self::getIniEmailData(self::EMAIL_KEY_ADMIN);
         return key($adminEmaild);
    }
    public static function getStoreFromName()
    {
        return STORE_NAME;
    }
}
