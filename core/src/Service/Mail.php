<?php

namespace App\Service;

use Pelago\Emogrifier\CssInliner;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

class Mail
{
    public $tpls = [
        'register' => 'email/auth/register.tpl',
        'reset' => 'email/auth/reset.tpl',
        'feedback' => 'email/auth/feedback.tpl',
    ];

    public function send(string $to, string $subject, string $body, array $from = []): bool
    {
        $logger = new Logger();
        $mail = new PHPMailer(true);
        try {
            $body = CssInliner::fromHtml($body)->render();
            $mail->CharSet = 'UTF-8';
            if (getenv('SMTP_HOST')) {
                $mail->isSMTP();
                $mail->Host = getenv('SMTP_HOST');
                $mail->SMTPAuth = (bool)getenv('SMTP_USER');
                $mail->Username = getenv('SMTP_USER');
                $mail->Password = getenv('SMTP_PASS');
                $mail->SMTPSecure = getenv('SMTP_PROTO');
                $mail->Port = getenv('SMTP_PORT');
                $mail->SMTPDebug = 0;
            }
            $mail->Debugoutput = $logger;

            //Recipients
            $mail->addAddress($to);
            $baseFrom = getenv('SMTP_EMAIL');
            $baseFrom = empty($baseFrom) ? getenv('SMTP_USER') : $baseFrom;

            if (empty($from) || count($from) !== 2) {
                $mail->setFrom($baseFrom, 'Krafti.ru');
            } else {
                $mail->setFrom($baseFrom, $from[1]);
                $mail->addReplyTo($from[0], $from[1]);
            }

            // Content
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $body;
            $mail->AltBody = $mail->html2text(nl2br($body));

            return $mail->send();
        } catch (Exception $e) {
            $logger->error("Could not send mail to \"{$to}\": " . $mail->ErrorInfo);
        }

        return false;
    }
}
