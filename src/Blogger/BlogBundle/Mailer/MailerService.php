<?php

namespace Blogger\BlogBundle\Mailer;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;

class MailerService implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    private $mailer;
    private $twig;

    public function __construct(\Twig_Environment $twig, \Swift_Mailer $mailer)
    {
        $this->twig = $twig;
        $this->mailer = $mailer;
    }

    public function sendMail(Mail $mail)
    {
        $message = \Swift_Message::newInstance()
            ->setSubject($mail->getSubject())
            ->setFrom($mail->getSender())
            ->setTo($mail->getReceiver())
            ->setBody($mail->getBody());

        try {
            $this->mailer->send($message);
        } catch (\Swift_TransportException $exception) {
            $this->logger->critical('');
        }
    }

    public function renderTemplate($templateName, array $context)
    {
        try {
            return $this->twig->render(
                $templateName,
                $context
            );
        } catch (\Twig_Error_Loader $e) {
        } catch (\Twig_Error_Runtime $e) {
        } catch (\Twig_Error_Syntax $e) {
        }
    }
}