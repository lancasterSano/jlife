<?php
require_once '.htpaths';
require_once(PROJECT_PATH."/php/class/do/QueryStorageDO.php");
require_once(PROJECT_PATH."/php/class/QueryStorage.php");
abstract class ConnectDB {
    ### PRIVATE METHODS ###
        /**
    	 * Подключение к БД.  
    	 * Examples:
         *      $DB = self::connectToDB(['social'/'do'(default)]);
    	 * @param int $db - имя базы в нижнем регистре {'social', 'do'}
    	 */
        public static function connectToDB($dbname = 'devsjlife_do'){
            require_once(PROJECT_PATH.'/lib/lib_bd/safemysql.class.php');
            unset($DB);
			switch($dbname)
			{
				case 'devsjlife_do':
					$DB = new SafeMySQL(array(
                                            'host'      => 'localhost',
                                            'user'      => JLIFE_DB_DO_USERNAME,
                                            'pass'      => '1800',
                                            'db'        => JLIFE_DB_DO_NAME,
                                            'port'      => NULL,
                                            'socket'    => NULL,
                                            'pconnect'  => FALSE,
                                            'charset'   => 'utf8',
                                            'errmode'   => 'error', //or exception
                                            'exception' => 'Exception', //Exception class name
                                        ));
					break;
				case 'devsjlife_social':			   		
					$DB = new SafeMySQL(array(
                                            'host'      => 'localhost',
                                            'user'      => JLIFE_DB_SOCIAL_USERNAME,
                                            'pass'      => '1800',
                                            'db'        => JLIFE_DB_SOCIAL_NAME,
                                            'port'      => NULL,
                                            'socket'    => NULL,
                                            'pconnect'  => FALSE,
                                            'charset'   => 'utf8',
                                            'errmode'   => 'error', //or exception
                                            'exception' => 'Exception', //Exception class name
                                        ));
					 break;
			}
			return $DB;
        }
        public static function prepareIDArray($arr)
        {
        	$norm = array();
        	foreach ($arr as $key => $value) { $norm[] = $value["id"]; }
        	return $norm; 
        }
}

class People extends ConnectDB{
	var $Profile;
	var $idProfile; 	// id from Social
    var $FirstName;
    var $LastName;
    var $MiddleName;

    public function __construct($id, $p){
    	$this->FirstName = $p["firstname"];
    	$this->LastName = $p["lastname"];
    	$this->MiddleName = $p["middlename"];
    	$this->idProfile = $p["iduser"];
    }
    ### PRIVATE METHODS ###

	### PUBLIC GET PROPERTY METHOD ###
	    /** * Возвращает "Фамилия Имя Отчество"*/
	    public function FIO(){
	    	return $this->LastName." ".$this->FirstName." ".$this->MiddleName;
	    }
            /** * Возвращает "Фамилия И.О." */
            public function FIOInitials(){
                if($this->MiddleName == ""){
                    $resultFIO = $this->LastName." ".mb_substr($this->FirstName, 0, 1).".";
                } else {
                    $resultFIO = $this->LastName." ".mb_substr($this->FirstName, 0, 1).".".mb_substr($this->MiddleName, 0, 1).".";
                }
	    	return $resultFIO;
	    }
	    /** * Возвращает "Фамилия Имя"*/
	    public function FI(){
	    	return $this->LastName." ".$this->FirstName;
	    }
	    /** * Возвращает "Имя"*/
	    public function F(){
	        return $this->FirstName;
	    }

	    public function getProfile(){
    		if (is_null(self::$Profile)) {
    			$DB = self::connectToDB('social');
    			$p = $DB->getRow(QS::$q3, $this->idProfile);
    			self::$Profile = new Profile($this->idProfile, $p);
    		}
    		return self::$Profile;
	    }
	    public function PeopleArray()
	    {
	    	return array(
    			"idLearner"=>$this->idProfile, 
    			"FirstName"=>$this->FirstName, 
    			"LastName"=>$this->LastName, 
    			"MiddleName"=>$this->MiddleName
			);
	    }

	### PUBLIC OTHER METHOD ###

}

?>