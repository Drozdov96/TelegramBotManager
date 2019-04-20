<?php

namespace Controllers;

use Models\storage;
use Models\TelegramBotRequest;

class SpendingCounterBot implements bot
{
    private $request;
    private $storage;


    public function __construct(TelegramBotRequest $request, storage $storage)
    {
        $this->request = $request;
        $this->storage = $storage;
    }

    public function doRoute(): array
    {
        switch($this->request->getCommand()){
            case '/start':
                $response ['text'] = $this->doStart();
                $response ['chat_id'] = $this->request->getChatId();
                break;
            default:
                throw new \Exception('Not valid command');
        }
        return $response;
    }

    protected function doStart(): string
    {
        return 'Здрася';
    }
}