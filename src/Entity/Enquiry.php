<?php

namespace App\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

class Enquiry
{
    /**
     * @Assert\NotBlank()
     */
    protected $name;
    protected $email;
    protected $subject;
    protected $body;

    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('name', new Assert\NotBlank());
        $metadata->addPropertyConstraint('email', new Assert\Email([
            'message' => 'symblog does not like invalid emails. Give me a real one!',
        ]));
        $metadata->addPropertyConstraint('subject', new Assert\NotBlank());
        $metadata->addPropertyConstraint('subject', new Assert\Length([
            'max' => 50,
        ]));
        $metadata->addPropertyConstraint('body', new Assert\Length([
            'min' => 50,
        ]));
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getSubject()
    {
        return $this->subject;
    }

    public function setSubject($subject)
    {
        $this->subject = $subject;
    }

    public function getBody()
    {
        return $this->body;
    }

    public function setBody($body)
    {
        $this->body = $body;
    }
}
