<?php

namespace guolei\php\util\common;
/**
 * guolei php util
 * @author 郭磊
 * @email 174000902@qq.com
 * @phone 15210720528
 * @github https://github.com/guolei19850528/guolei-php-util
 */
class MailUtil
{
    protected $smtpServer = "";

    protected $smtpPort = 25;

    protected $senderAccount = null;

    protected $senderPassword = null;

    protected $senderAddress = null;

    protected $toAddresses = [];

    protected $ccAddresses = [];

    protected $bccAddresses = [];

    /**
     * @return string
     */
    public function getSmtpServer(): string
    {
        return $this->smtpServer;
    }

    /**
     * @param string $smtpServer
     */
    public function setSmtpServer(string $smtpServer)
    {
        $this->smtpServer = $smtpServer;
    }

    /**
     * @return int
     */
    public function getSmtpPort(): int
    {
        return $this->smtpPort;
    }

    /**
     * @param int $smtpPort
     */
    public function setSmtpPort(int $smtpPort)
    {
        $this->smtpPort = $smtpPort;
    }

    /**
     * @return null
     */
    public function getSenderAccount()
    {
        return $this->senderAccount;
    }

    /**
     * @param null $senderAccount
     */
    public function setSenderAccount($senderAccount)
    {
        $this->senderAccount = $senderAccount;
    }

    /**
     * @return null
     */
    public function getSenderPassword()
    {
        return $this->senderPassword;
    }

    /**
     * @param null $senderPassword
     */
    public function setSenderPassword($senderPassword)
    {
        $this->senderPassword = $senderPassword;
    }

    /**
     * @return null
     */
    public function getSenderAddress()
    {
        return $this->senderAddress;
    }

    /**
     * @param null $senderAddress
     */
    public function setSenderAddress($senderAddress)
    {
        $this->senderAddress = $senderAddress;
    }

    /**
     * @return array
     */
    public function getToAddresses(): array
    {
        return $this->toAddresses;
    }

    /**
     * @param array $toAddresses
     */
    public function setToAddresses(array $toAddresses)
    {
        $this->toAddresses = $toAddresses;
    }

    /**
     * @return array
     */
    public function getCcAddresses(): array
    {
        return $this->ccAddresses;
    }

    /**
     * @param array $ccAddresses
     */
    public function setCcAddresses(array $ccAddresses)
    {
        $this->ccAddresses = $ccAddresses;
    }

    /**
     * @return array
     */
    public function getBccAddresses(): array
    {
        return $this->bccAddresses;
    }

    /**
     * @param array $bccAddresses
     */
    public function setBccAddresses(array $bccAddresses)
    {
        $this->bccAddresses = $bccAddresses;
    }


    public function openBySmtp($smtpServer = "smtp.exmail.qq.com", $smtpPort = 25)
    {
        $this->setSmtpServer($smtpServer);
        $this->setSmtpPort($smtpPort);
    }

    public function send($subject,$content,$contentType,$charset="UTF-8")
    {
        if (!$fp = fsockopen($this->smtpServer, $this->smtpPort, $errNo, $errStr, 30)) {
            return false;
        }

        stream_set_blocking($fp, true);
        $lastMessage = fgets($fp, 512);

        if (substr($lastMessage, 0, 3) != '220')
        {
            return false;
        }



        fputs($fp, "EHLO GUOLEI-PHP-UTIL\r\n");

        $lastMessage = fgets($fp, 512);

        if (substr($lastMessage, 0, 3) != 220 && substr($lastMessage, 0, 3) != 250)
        {
            return false;
        }


        while(1)
        {
            if (substr($lastMessage, 3, 1) != '-' || empty($lastMessage))
            {
                break;
            }
            $lastMessage = fgets($fp, 512);
        }

        fputs($fp, "AUTH LOGIN\r\n");
        $lastMessage = fgets($fp, 512);
        if (substr($lastMessage, 0, 3) != 334)
        {
            return false;
        }

        fputs($fp, base64_encode($this->senderAccount)."\r\n");
        $lastMessage = fgets($fp, 512);
        if (substr($lastMessage, 0, 3) != 334)
        {
            return false;
        }

        fputs($fp, base64_encode($this->senderPassword)."\r\n");
        $lastMessage = fgets($fp, 512);
        if (substr($lastMessage, 0, 3) != 235)
        {
            return false;
        }





        fputs($fp, "MAIL FROM: <" . preg_replace("/.*\<(.+?)\>.*/", "\\1", $this->senderAddress) . ">\r\n");
        $lastMessage = fgets($fp, 512);
        if (substr($lastMessage, 0, 3) != 250)
        {
            fputs($fp, "MAIL FROM: <" . preg_replace("/.*\<(.+?)\>.*/", "\\1", $this->senderAddress) . ">\r\n");
            $lastMessage = fgets($fp, 512);

            if (substr($lastMessage, 0, 3) != 250)
            {
                return false;
            }
        }





        foreach ($this->toAddresses as $toAddress)
        {
            $toAddress = trim($toAddress);
            if ($toAddress)
            {
                fputs($fp, "RCPT TO: <" . preg_replace("/.*\<(.+?)\>.*/", "\\1", $toAddress) . ">\r\n");
                $lastMessage = fgets($fp, 512);
                if (substr($lastMessage, 0, 3) != 250)
                {
                    fputs($fp, "RCPT TO: <" . preg_replace("/.*\<(.+?)\>.*/", "\\1", $toAddress) . ">\r\n");
                    $lastMessage = fgets($fp, 512);
                    return false;
                }
            }

        }

        foreach ($this->ccAddresses as $ccAddress)
        {
            $ccAddress = trim($ccAddress);
            if ($ccAddress)
            {
                fputs($fp, "RCPT CC: <" . preg_replace("/.*\<(.+?)\>.*/", "\\1", $ccAddress) . ">\r\n");
                $lastMessage = fgets($fp, 512);
                if (substr($lastMessage, 0, 3) != 250)
                {
                    fputs($fp, "RCPT CC: <" . preg_replace("/.*\<(.+?)\>.*/", "\\1", $ccAddress) . ">\r\n");
                    $lastMessage = fgets($fp, 512);
                    return false;
                }
            }

        }

        foreach ($this->bccAddresses as $bccAddress)
        {
            $bccAddress = trim($bccAddress);
            if ($bccAddress)
            {
                fputs($fp, "RCPT BCC: <" . preg_replace("/.*\<(.+?)\>.*/", "\\1", $bccAddress) . ">\r\n");
                $lastMessage = fgets($fp, 512);
                if (substr($lastMessage, 0, 3) != 250)
                {
                    fputs($fp, "RCPT BCC: <" . preg_replace("/.*\<(.+?)\>.*/", "\\1", $bccAddress) . ">\r\n");
                    $lastMessage = fgets($fp, 512);
                    return false;
                }
            }

        }


        fputs($fp, "DATA\r\n");
        $lastMessage = fgets($fp, 512);
        if (substr($lastMessage, 0, 3) != 354)
        {
            return false;
        }

        $maildelimiter = "\r\n";


        $mailDelimiter="\r\n";
        $subject = '=?'.$charset.'?B?'.base64_encode(str_replace("\r", '', $subject)).'?=';

        $content = chunk_split(base64_encode(str_replace("\r\n.", " \r\n..", str_replace("\n", "\r\n", str_replace("\r", "\n", str_replace("\r\n", "\n", str_replace("\n\r", "\r", $content)))))));

        $senderAddress = preg_match('/^(.+?) \<(.+?)\>$/', $this->senderAddress, $matched) ? '=?' . $charset . '?B?' . base64_encode($matched[1]) . "?= <$matched[2]>" : '=?'.$charset.'?B?'.base64_encode(str_replace("\r", '', $this->senderAddress)).'?=';

        $headers  = "From: {$senderAddress}{$mailDelimiter}X-Priority: 3{$mailDelimiter}X-Mailer: GUOLEI-PHP-UTIL{$mailDelimiter}MIME-Version: 1.0{$mailDelimiter}Content-type: {$contentType}; charset={$charset}{$mailDelimiter}Content-Transfer-Encoding: base64{$mailDelimiter}";
        $headers .= 'Message-ID: <' . date('YmdHs') . '.' . substr(md5($content . microtime()), 0, 6) . rand(100000, 999999).">{$mailDelimiter}";

        fputs($fp, "Date: " . date('r') . "\r\n");
        fputs($fp, "To: " . implode(",",$this->toAddresses) . "\r\n");
        fputs($fp, "Cc: " . implode(",",$this->ccAddresses) . "\r\n");
        fputs($fp, "Bcc: " . implode(",",$this->bccAddresses) . "\r\n");
        fputs($fp, "Subject: " . $subject . "\r\n");
        fputs($fp, $headers . "\r\n");
        fputs($fp, "\r\n\r\n");
        fputs($fp, $content . "\r\n.\r\n");
        $lastMessage = fgets($fp, 512);
        if (substr($lastMessage, 0, 3) != 250)
        {
            return false;
        }

        fputs($fp, "QUIT\r\n");

        return true;

    }
}