<?php

namespace Models;


class TelegramBotRequest
{
    private $userId;
    private $command;
    private $commandParams;
    private $chatId;

    public function __construct(array $command, int $userId, int $chatId)
    {
        if(empty($userId)){
            throw new \Exception;
        }
        reset($command);
        $this->command = array_shift($command);
        $this->commandParams = empty($command)? null : $command;
        $this->userId = $userId;
        $this->chatId = $chatId;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * @return string
     */
    public function getCommand(): string
    {
        return $this->command;
    }

    /**
     * @return array
     */
    public function getCommandParams(): array
    {
        return $this->commandParams;
    }

    /**
     * @return int
     */
    public function getChatId(): int
    {
        return $this->chatId;
    }

}