<?php

namespace App\Service;

use Psr\Log\LoggerInterface;

class MessageGenerator
{
    private $logger;

    private $isRandom = false;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }
    
    private $messages = [
        'You did it! You updated the system! Amazing!',
        'That was one of the coolest updates I\'ve seen all day!',
        'Great work! Keep going!',
    ];

    public function getRandomMessage()
    {
        if ($this->isRandom) {
            $message = $this->messages[array_rand($this->messages)];
        } else {
            $message = 'Action effectuée avec succès';
        }

        $this->logger->info('Random message', [
            'message' => $message,
        ]);

        return $message;
    }
}