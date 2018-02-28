<?php

namespace App\Event;

use Symfony\Component\EventDispatcher\Event;
use App\Entity\Enquiry;

class EnquiryEvent extends Event
{
    private $code;

    public function __construct($code)
    {
        $this->code = $code;
    }

    public function getCode()
    {
        return $this->code;
    }
}
