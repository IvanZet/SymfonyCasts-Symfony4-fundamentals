<?php

namespace App\Service;

use Nexy\Slack\Client;
use Psr\Log\LoggerInterface;

class SlackClient
{
    private $slack;

    private $logger;
    
    public function __construct(Client $slack)
    {
        $this->slack = $slack;
    }
    
    // Setter injection (the same can be done via the constructor; just another way, helpful for optional services)
    /**
     * @required
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }
    
    public function sendMessage(string $from, string $message)
    {
        if ($this->logger) {
            $this->logger->info('Beaming a message to slack');
        }
        
        $slackMessage = $this->slack->createMessage()
        ->from($from)
        ->withIcon(':ghost:')
        ->setText($message)
    ;

        $this->slack->sendMessage($slackMessage);
    }
}