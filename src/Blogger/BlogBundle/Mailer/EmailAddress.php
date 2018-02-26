<?php

namespace Blogger\BlogBundle\Mailer;

class EmailAddress
{
    private $email;
    private $name;

    public function __construct($email, $name = null)
    {
        // This is only a soft validation to reduce headaches in
        // You SHOULD sanitize & validate email before using it as a value object!
        if ($email && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException('Given e-mail address '.$email.' is not a valid');
        }

        $this->email = $email;
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    public static function createEmailAddress($email, $name = null)
    {
        return new self($email, $name);
    }

    public function __toString()
    {
        return implode(', ', [
            $this->getEmail(),
            $this->getName()
        ]);
    }
}