<?php

namespace App\Utils;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;

class Recaptcha
{
    private $secretKey;
    private $logger;

    public function __construct(string $secretKey, LoggerInterface $logger)
    {
        $this->secretKey = $secretKey;
        $this->logger = $logger;
    }

    public function isValid(Request $request)
    {
        $data = [
            'secret'   => $this->secretKey,
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

        $hasSucceeded = json_decode($result)->success;

        $parametersAsArray = [];
        if ($content = $request->getContent()) {
            $parametersAsArray[] = $content;
        }

        $name = $request->get("enquiry")["name"];
        $sender = $request->get("enquiry")["email"];
        $subject = $request->get("enquiry")["subject"];
        $body = $request->get("enquiry")["body"];

        $hasSucceeded ?
            $this->logger->info('message is send from:' .$name.
                ' with email: ' .$sender .
                ' and subject: '. $subject.
                '. The message was: '. $body):
            $this->logger->warning('reCaptcha attack from ip address: '. $request->getClientIp().
                ' on host: '. $request->getHost().
                ', sender: '.$sender.
                ', sender name: '. $name)
        ;

        return $hasSucceeded;
    }
}
