<?php

namespace Blogger\BlogBundle\Utils;

use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;

class Recaptcha
{
    private $recaptcha_secretkey;

    public function __construct($recaptcha_secretkey)
    {
        $this->recaptcha_secretkey = $recaptcha_secretkey;
    }

    public function isValid(Request $request)
    {
        try {
            $data = [
                'secret'   => $this->recaptcha_secretkey,
                'response' => $request->get('g-recaptcha-response')
            ];

            $options = [
                'http' => [
                    'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                    'method'  => 'POST',
                    'content' => http_build_query($data)
                ]
            ];

            $result = file_get_contents(
                'https://www.google.com/recaptcha/api/siteverify',
                false,
                stream_context_create($options)
            );

            return json_decode($result)->success;
        }
        catch (Exception $e) {
            return null;
        }
    }
}