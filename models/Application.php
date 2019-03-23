<?php

namespace Models;


class Application
{
    private $database;
    private $requestArr;
    const BOT_TOKEN = '899053884:AAGbwi3l7_FodfZC2knlxUkYiqsAhJTXNio';

    public function __construct()
    {
        //отправка запроса
        $inputOffsetFile = fopen('/home/user/code/AnimeListBot/files/offset.txt', 'r');
        $offset = (int)fgets($inputOffsetFile) + 1;
        fclose($inputOffsetFile);

        $updateRequest = curl_init("https://api.telegram.org/bot".$this::BOT_TOKEN."/getUpdates?offset={$offset}");
        curl_setopt($updateRequest, CURLOPT_RETURNTRANSFER, 1);
        $inputResp = curl_exec($updateRequest);
        curl_close($updateRequest);
        $this->requestArr = json_decode($inputResp, true);
        //$this->database
    }

    public function run()
    {
        foreach ($this->requestArr['result'] as $value){
            $messageRequest = curl_init("https://api.telegram.org/bot".$this::BOT_TOKEN."/sendMessage?chat_id={$value['message']['chat']['id']}&text={$value['message']['text']}");
            curl_setopt($messageRequest, CURLOPT_RETURNTRANSFER, 1);
            curl_exec($messageRequest);
            curl_close($messageRequest);
        }
        end($this->requestArr['result']);
        $outputOffsetFile = fopen('/home/user/code/AnimeListBot/files/offset.txt', 'w');
        fwrite($outputOffsetFile, current($this->requestArr['result'])['update_id']);
        fflush($outputOffsetFile);
        fclose($outputOffsetFile);
    }

    public function requestIsEmpty()
    {
        return empty($this->requestArr['result']);
    }
}