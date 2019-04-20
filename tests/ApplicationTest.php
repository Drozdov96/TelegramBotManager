<?php

require __DIR__."/../controllers/Application.php";

use Controllers\Application;
use PHPUnit\Framework\TestCase;

class ApplicationTest extends TestCase
{
    /**
     * @param array $params
     * @dataProvider validateRequestProvider
     */
//    public function testValidateRequest(array $params)
//    {
//        $method = new \ReflectionMethod(Application::class,
//            'validateRequest');
//        $method->setAccessible(true);
//        $this->assertTrue($method->invoke(new Application(), $params));
//    }
//
//    public function validateRequestProvider() : array
//    {
//        return array(
//            array(array('/start', 'asvWERerv(e)90', '123456')),
//            array(array('/start', 'asvWERerv(e)90', '123r4w5a6')),
//            array(array('/start', '<html>asvWERerv(e)90', '123456'))
//        );
//    }
}