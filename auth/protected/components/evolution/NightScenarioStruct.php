<?php
Yii::import('application.components.evolution.subsidiaryNightScenario.*');
// Yii::import('application.components.common.DTimer');

// DTimer::run();
/**
* 
*/
class NightScenarioStruct extends LogManager
{
	public $debug = false;
	private $opp = null;
	private $startMain = null;
	private $endMain = null;
	private $cleareLog = null;

	private $number = 0;

	function __construct($start=1, $end=6, $debug='false', $cleareLog=true)
	{
		parent::__construct();
		// $this->$_groupunic = 'ns_'.date("Y_m_d_|_H_i_s");
		
		$this->cleareLog = $cleareLog;
		$this->debug = ($debug === 'true') ? 1 : 0;
		$this->startMain = ($start >=1 && $start <= 6) ? $start : 1;
		$this->endMain = ($end >=1 && $end<=6 && $end >= $start) ? $end : 6;

		// echo 'Run from fun['.$this->startMain.'] to fun['.$this->endMain.']'.PHP_EOL;
	}
	private function number()
	{
		return ++$this->number;
	}

	public function run($nopp=1)
	{
		if($this->cleareLog && $nopp==$this->startMain) $this->cleareLog();
		$rez_fun = null;
		$rez_fun = array(
			'status'=>array(
				'task'=>$nopp, 
				'success'=>true, 
				'number'=>$this->number, 
				'errors'=>array(),
				'data'=>array(),
			), 
			'opp'=>'start',
		);
		if($nopp >= $this->startMain && $nopp <= $this->endMain) {
			switch ($nopp) {
				case 1: $rez_fun = $this->clearDataPreviousDay($nopp); break;
				case 2: $rez_fun = $this->summingDataPreviousDay($nopp); break;
				case 3: $rez_fun = $this->addMissingData($nopp); break;
				case 4: $rez_fun = $this->internalTaskEvoluation($nopp); break;
				case 5: $rez_fun = $this->updateSets($nopp); break;
				case 6: $rez_fun = $this->analysisAdequacyIssues($nopp); break;
				default:
					throw new Exception("Error Processing Request", 1);					
					break;
			}

			if($rez_fun !== null)
			{
				/*$this->number = $rez_fun['status']['number'];

					self::_log(
						$rez_fun['status']['task'], 
						$this->number(), 
						$rez_fun['opp'].' End', 
						null, 
						$rez_fun['status']['success'],
						''
					);
				*/
				$this->number = $rez_fun['status']['number'];
				echo $this->number.") ";
				self::_log(
					$rez_fun['status']['task'], 
					$this->number(), 
					$rez_fun['opp'].' End', 
					null, 
					$rez_fun['status']['success'],
					$rez_fun['status']['data']
				);			
			}
		}
		if($nopp <= $this->endMain)
			if(is_array($rez_fun) && count($rez_fun) && ($rez_fun['status']['success'] === true))
				$this->run(++$nopp);
		return true;
	}
	/*
	 * 1.Очистка данных прошедшего дня
	 */
	public function clearDataPreviousDay($task=111)
	{
            self::_log($task, $this->number(), __FUNCTION__.' Start', null, true, null);
            $res = new ClearDataPreviousDay;
            // return array($res->finish, 'opp'=>__FUNCTION__);
            return array(
            	'status'=>array('task'=>$task, 'success'=>true, 'number'=>$this->number, 'errors'=>array(), 'data'=>array() ), 
            	'opp'=>__FUNCTION__,
            );
	}
	/*
	 * 2. Подведение итогов за прошедший день
	 */
	public function summingDataPreviousDay($task=222)
	{
		self::_log($task, $this->number(), __FUNCTION__.' Start', null, true, null);
                $res = new SummingDataPreviousDay;
		return array(
			'status'=>array('task'=>$task, 'success'=>true, 'number'=>$this->number, 'errors'=>array(), 'data'=>array() ), 
			'opp'=>__FUNCTION__,
		);
	}
	/*
	 * 3. Дополнить данными, которых не хватает 
	 *
	 * 1. Добавить новых учеников в группы
	 * 2. Перевести всех учеников, которых требуется
	 * 3. Проверить subgroupratio для всех групп (issync)
	 */
	public function addMissingData($task=333)
	{
		self::_log($task, $this->number(), __FUNCTION__.' Start', null, true, null);
                $res = new AddMissingData;
		return array(
			'status'=>array('task'=>$task, 'success'=>true, 'number'=>$this->number, 'errors'=>array(), 'data'=>array() ), 
			'opp'=>__FUNCTION__,
		);
	}
	/*
	 * 4. Внутренние задачи Y (Системы роста)
	 */
	public function internalTaskEvoluation($task=444)
	{
		self::_log($task, $this->number(), __FUNCTION__.' Start', null, true, null);
		$fun = new InternalTaskEvoluation($task, $this->number, $this->debug);
		return array('status'=>$fun->finish, 'opp'=>__FUNCTION__,);
	}
	/*
	 * 5. Обновление множест
	 */
	public function updateSets($task=555)
	{
		self::_log($task, $this->number(), __FUNCTION__.' Start', null, true, null);
		return array(
			'status'=>array('task'=>$task, 'success'=>true, 'number'=>$this->number, 'errors'=>array(), 'data'=>array() ), 
			'opp'=>__FUNCTION__,
		);
	}
	/*
	 * 6. Анализ достаточности вопросов всех учеников
	 */
	public function analysisAdequacyIssues($task=666)
	{
		self::_log($task, $this->number(), __FUNCTION__.' Start', null, true, null);
		return array(
			'status'=>array('task'=>$task, 'success'=>true, 'number'=>$this->number, 'errors'=>array(), 'data'=>array() ), 
			'opp'=>__FUNCTION__,
		);
	}

}

?>