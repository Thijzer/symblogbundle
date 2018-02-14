<?php
// src/Blogger/BlogBundle/Entity/Enquiry.php
namespace Blogger\BlogBundle\Entity;


class Enquiry
{
    protected $name;

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }
}