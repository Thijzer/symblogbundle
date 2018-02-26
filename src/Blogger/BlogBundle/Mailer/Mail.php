<?php

namespace Blogger\BlogBundle\Mailer;

class Mail
{
    private $body;
    private $sender;
    private $receiver;
    private $subject;

    public function __construct($subject, EmailAddress $sender, EmailAddress $receiver, $body)
    {
        $this->sender = $sender;
        $this->receiver = $receiver;
        $this->subject = $subject;
        $this->body = $body;
    }

    /**
     * @return mixed
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @return mixed
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @return EmailAddress
     */
    public function getSender()
    {
        return $this->sender;
    }

    /**
     * @return EmailAddress
     */
    public function getReceiver()
    {
        return $this->receiver;
    }
}