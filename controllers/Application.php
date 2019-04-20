<?php
namespace Controllers;


use Models\Database;
use Models\TelegramBotRequest;

class Application
{
    private $bot;
    private const SPENDING_COUNTER_BOT_DB_CONNECTION = "pgsql:host=localhost port=5432 
        dbname=battleship user=www-data password=5621";

    public function __construct()
    {

    }

    public function run(string $jsonRequest)
    {
        try{
            $requestArr = $this->processRequestToArray($jsonRequest);
            $telegramRequest = new TelegramBotRequest(
                $this->parseRequestMessage($requestArr),
                (int)$requestArr['from']['id'],
                (int)$requestArr['chat']['id']
            );
            if($telegramRequest->getUserId() !== 354024982){
                echo 'Доступ закрыт.';
                return;
            }
            $dbStorage = Database::getInstance(
                self::SPENDING_COUNTER_BOT_DB_CONNECTION);

            $this->bot = new SpendingCounterBot($telegramRequest, $dbStorage);
            $response = $this->prepareResponse($this->bot->doRoute());
            echo($response);
            //$this->sendMessage($response);
        }catch (\PDOException $e){
            //TODO Response with error here
            echo $e->getMessage();
        }catch (\Exception $e){
            //TODO Response with error here
            echo $e->getMessage();
        }
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
    
    private function processRequestToArray(string $jsonRequest): array
    {
        $requestArray = json_decode($jsonRequest, true);
        return $requestArray['result'][0]['message'];
    }

    private function parseRequestMessage(array $request): array
    {
        if(empty($request['text'])){
            throw new \Exception('Пустой текст сообщения.');
        }
        $requestMessage = trim($request['text']);
        return $requestArray = preg_split('/\s+/', $requestMessage);
    }

    private function validateRequest(array $requestArr): bool
    {

        if(preg_match("/^\/[A-Za-z]{3,6}$/",array_shift($requestArr)) !== 1){
            return false;
        }

        $moneyString = array_shift($requestArr);
        if(preg_match("/^[0-9\.]+$/", $moneyString) !== 1 ||
         substr_count($moneyString, '.') > 1 ){
            return false;
        }

        foreach ($requestArr as $value){
            if(preg_match("/^[A-Za-z.()0-9]+$/", $value) !== 1){
                return false;
            }
        }
        return true;
    }

    protected function sendMessage(string $jsonResponse)
    {
        $url = 'https://api.telegram.org/bot123456:ABC-DEF1234ghIkl-zyx57W2v1u123ew11/sendMessage';
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json;charset=UTF-8','Content-Length: ' . strlen($jsonResponse)));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS,$jsonResponse);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_exec($ch);
        if(curl_errno($ch) !== 0){
            throw new \Exception(curl_error($ch));
        }
        curl_close($ch);
    }

    protected function prepareResponse(array $responseMsg): string
    {
        return json_encode($responseMsg);
    }
}