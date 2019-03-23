<?php
namespace Controllers;


class Application
{
    private $database;
    private $requestArr;
    private $botToken;

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

    public function run(string $jsonRequest, string $botToken)
    {
        if($this->validateBotToken($botToken)){
            $this->botToken = $botToken;
        }else{
            return;
        }

        $this->requestArr = $this->processRequest($jsonRequest);
        if(empty($this->requestArr['result'])){
            return;
        }else{
            $requestMessageArray = $this->parseRequestMessage($this->requestArr[]);
        }

        if(!$this->validateRequest($requestMessageArray)){
            return;
        }
        
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

    private function validateBotToken (string $token): bool
    {
        $tokensFile = fopen($_SERVER['DOCUMENT_ROOT'].'/files/bots.txt', 'r');

        while (($referenceToken = fgets($tokensFile)) !== false){
            if($referenceToken === $token){
                fclose($tokensFile);
                return true;
            }
        }
        fclose($tokensFile);
        return false;
    }
    
    private function processRequest(string $jsonRequest): array
    {
        return  $requestArray = json_decode($jsonRequest);
    }

    private function parseRequestMessage(array $request): array
    {
        $requestMessage = trim($request['result']['message']['text']);
        return $requestArray = preg_split('\s+', $requestMessage);
    }

    private function validateRequest(array $requestArr): bool
    {
        if(preg_match("/[A-Za-z]+",array_shift($requestArr)) !== 1){
            return false;
        }

        foreach ($requestArr as $value){
            if(preg_match("[A-Za-z.()]+", $value) !== 1){
                return false;
            }
        }
        return true;
    }
}