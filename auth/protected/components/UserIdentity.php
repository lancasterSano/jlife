<?php
class UserIdentity extends CUserIdentity
{
	private $_id;

	
	public function authenticate()
	{
		$record = Profile::model()->findByAttributes(array('login'=>$this->username));
		if($record===null)
			$this->errorCode=self::ERROR_USERNAME_INVALID;
		elseif($record->password!==$this->password)
			$this->errorCode=self::ERROR_PASSWORD_INVALID;
		else {
			$this->_id=$record->id;
			$this->errorCode=self::ERROR_NONE;
		}		
		return !$this->errorCode;
	}
	public function getId()
	{
		return $this->_id;
	}
}