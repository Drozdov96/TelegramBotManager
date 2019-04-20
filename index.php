<?php
require_once './headerConfig.php';
require_once './vendor/autoload.php';
$jsonRequest = '{"ok":true,"result":[{"update_id":664080930,
"message":{"message_id":74,"from":{"id":354024982,"is_bot":false,"first_name":"Vellky","last_name":"Pie","language_code":"en"},"chat":{"id":354024982,"first_name":"Vellky","last_name":"Pie","type":"private"},"date":1555752288,"text":"/start"}}]}';
$App = new \Controllers\Application();
$App->run($jsonRequest);