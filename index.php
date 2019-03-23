<?php
require_once './vendor/autoload.php';

$App = new \Models\Application();
if(!$App->requestIsEmpty()){
    $App->run();
}

