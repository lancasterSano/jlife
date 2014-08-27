<?php
/**
* Класс лог менеджера.
* Проверяет есть ли новое. ($idLast)
* Добавляет новую запись лога.
* Очищает весь лог.
*/
class LogManager
{
	public $_groupunic;

	protected function __construct()
	{
		// if(!$this->$_groupunic) 
		$this->_groupunic = 'ns_'.date("Y-m-d|H:i:s");
	}

	public function _log($ns_task, $number=0, $nameopp=__FUNCTION__, $desc=null, $success = false, $debug_backtrace=null)
	{
		$model = new YNightscenario;

		$model->ns_groupunic_id = $this->_groupunic;
		$model->ns_task = $ns_task;
		$model->number = $number;
		$model->nameopp = $nameopp;
		$model->desc = $desc;
		$model->success = $success;
		$model->debug_backtrace = ($debug_backtrace) ? json_encode($debug_backtrace) : "";
		// $model->debug_backtrace = ($debug_backtrace) ? $debug_backtrace : json_encode(debug_backtrace());
		$model->datetime = date_format(new DateTime(date("Y-m-d H:i:s")), "Y-m-d H:i:s");
		$model->save(false);
	}
	public function existNewLog($idLast)
	{
		$cr_log = new CDbCriteria;
			$cr_log->condition = 'id > :dt';
			$cr_log->params=array(':dt'=>$idLast);
			$cr_log->order = 'date ASC';
		$rez = YNightscenario::model()->findAll($cr_log);
		return (is_array($rez) && count($rez)) ? $rez : null;
	}
	public function cleareLog()
	{
		YNightscenario::model()->deleteAll();
	}
}
?>