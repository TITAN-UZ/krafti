<?php

namespace App\Service;

use App\Container;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

class Mail
{

    /** @var Container $container */
    public $container;
    public $tpls = [
        'register' => 'email/auth/register.tpl',
        'reset' => 'email/auth/reset.tpl',
        'feedback' => 'email/auth/feedback.tpl',
    ];


    public function __construct(&$container)
    {
        $this->container = $container;
    }


    /**
     * @param string $to
     * @param string $subject
     * @param string $body
     *
     * @return bool
     */
    public function send($to, $subject, $body)
    {

        $mail = new PHPMailer(true);
        try {
            $emogrifier = new \Pelago\Emogrifier($body);
            $body = $emogrifier->emogrify();

            $mail->CharSet = 'UTF-8';

            $mail->isSMTP();
            $mail->Host = getenv('SMTP_HOST');
            $mail->SMTPAuth = true;
            $mail->Username = getenv('SMTP_USER');
            $mail->Password = getenv('SMTP_PASS');
            $mail->SMTPSecure = getenv('SMTP_PROTO');
            $mail->Port = getenv('SMTP_PORT');
            $mail->SMTPDebug = 0;
            $mail->Debugoutput = $this->container->logger;

            //Recipients
            $mail->setFrom(getenv('SMTP_USER'), 'Krafti.ru');
            $mail->addAddress($to);

            //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');

            // Content
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $body;
            $mail->AltBody = $mail->html2text(nl2br($body));

            return $mail->send();
        } catch (Exception $e) {
            $this->container->logger->error("Could not send mail to \"{$to}\": " . $mail->ErrorInfo);
        }

        return false;
    }
}