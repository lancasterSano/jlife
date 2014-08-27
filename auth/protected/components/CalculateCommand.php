<?php
class CalculateCommand extends CConsoleCommand
{
    public $dbConnection = '';
    public function run($args) {
        echo 'Calculate command with connection=' . $this->dbConnection . PHP_EOL;
        // echo Yii::getPathOfAlias('webroot.upload');
    }
}