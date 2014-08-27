<?php
class NightCommand extends CConsoleCommand
{
    public function actionStart($from=1, $to=6, $debug='false') {
        $NSS = new NightScenarioStruct((int)$from, (int)$to, $debug);
			$NSS->run();
    }
}