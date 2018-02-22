<?php

namespace Blogger\BlogBundle\Utils;


class Recaptcha
{
    public function isValid(Request $request)
    {
        return $request->get('g-recaptcha-response') !== null;
    }
}