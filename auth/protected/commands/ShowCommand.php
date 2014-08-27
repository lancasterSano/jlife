<?php
class ShowCommand extends CConsoleCommand
{
    // public function actionHello($name) {
    //     echo 'Hello, ' . $name . '!' . PHP_EOL;
    // }
    public function actionHello(array $name) {
        echo 'Hello, ' . implode(', ', $name) . PHP_EOL;
    }
}