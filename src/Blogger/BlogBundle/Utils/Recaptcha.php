<?php

namespace Blogger\BlogBundle\Utils;

use Symfony\Component\HttpFoundation\Request;

class Recaptcha
{
    public function isValid(Request $request)
    {
        return $request->get('g-recaptcha-response') !== null;
    }
}