<?php
class ROLESTYPES {
    static public $Learner = 1; 	static private $LearnerRouteType = 'c'; 	static private $LearnerLabelType = 'Ученик';
    static public $Teacher = 2; 	static private $TeacherRouteType = 's'; 	static private $TeacherLabelType = 'Преподаватель';
    static public $Ko = 4; 			static private $KoRouteType = 'k'; 			static private $KoLabelType = 'Завуч';
    static public $Responsible = 8; static private $ResponsibleRouteType = 'l'; static private $ResponsibleLabelType = 'Родитель';
    static public $Yoda = 16; 		static private $YodaRouteType = ''; 		static private $YodaLabelType = 'Классный руководитель';
    static public $Director = 32; 	static private $DirectorRouteType = ''; 	static private $DirectorLabelType = 'Директор';
    static public $GOD = 128; 		static private $GODRouteType = ''; 			static private $GODLabelType = 'БОГ';

    /* routetype abbrenlabel enlabel abbrrulabel rulabel action*/
    static public $LearnerAbbreviation = 	array('c','L','Learner','У','Ученик', 'Learner');
    static public $TeacherAbbreviation = 	array('s','T','Teacher','П','Преподаватель', 'Teather');
    static public $KoAbbreviation = 		array('k','K','Ko','З','Завуч', 'Ko');
    static public $ResponsibleAbbreviation = array('l','R','Responsible','Р','Родитель', 'Responsible');
    static public $YodaAbbreviation = 		array('y','Y','Yoda','КР','Классный руководитель', 'Yoda');
    static public $DirectorAbbreviation = 	array('d','D','Director','Д','Директоа', 'Director');
    static public $GODAbbreviation = 		array('g','G','GOD','Б','БОГ', 'GOD');
    
    /* 
     * param 	$role = ROLESTYPES::{Learner, Teacher, Ko, Responsible, Yoda, Director, GOD}
     * return 	letter royte type = {c, s, k, l, y, d, g}
     */
    static public function RouteType($role)
    {
		switch ($role) {
			case ROLESTYPES::$Learner: $role = ROLESTYPES::$LearnerAbbreviation[0]; break;
			case ROLESTYPES::$Teacher: $role = ROLESTYPES::$TeacherAbbreviation[0]; break;
			case ROLESTYPES::$Ko: $role = ROLESTYPES::$KoAbbreviation[0]; break;
			case ROLESTYPES::$Responsible: $role = ROLESTYPES::$ResponsibleAbbreviation[0]; break;
			default: throw new Exception("Дописать код: Не описаны все RouteType:ROLESTYPES типы (".$role.')', 1); break;
		}
		return $role;
    }
    /*
     * param 	$role = ROLESTYPES::{Learner, Teacher, Ko, Responsible, Yoda, Director, GOD}
     * param 	$letter(bool) default: null
     * param 	$en(bool) default: null
     * return 	{routetype abbrenlabel enlabel abbrrulabel rulabel} for role
     */
    static public function LabelType($role, $letter=null, $en = null)
	{
		if($letter===null && $en===null) $letter = 0;
		else if($letter && $en) $letter = 1;
		else if(!$letter && $en) $letter = 2;
		else if($letter && !$en) $letter = 3;
		else if(!$letter && !$en) $letter = 4;
		switch ($role) {
			case ROLESTYPES::$Learner: $role = ROLESTYPES::$LearnerAbbreviation[$letter]; break;
			case ROLESTYPES::$Teacher: $role = ROLESTYPES::$TeacherAbbreviation[$letter]; break;
			case ROLESTYPES::$Ko: $role = ROLESTYPES::$KoAbbreviation[$letter]; break;
			case ROLESTYPES::$Responsible: $role = ROLESTYPES::$ResponsibleAbbreviation[$letter]; break;
			default:
				throw new Exception("Дописать код: Не описаны все LabelType:ROLESTYPES типы (".$role.')', 1);				
				break;
		}
		return $role;
    }
    /*
     * param 	$routetype = {'c', 's', 'k', 'l', 'y', 'd', 'g'}
     * return 	role - 1, 2, 4, 8, 16, 32, 128
     */
	static public function getRoleByRouteType($routetype='*')
	{
		switch ($routetype) {
			case ROLESTYPES::$LearnerAbbreviation[0]: $role = ROLESTYPES::$Learner; break;
			case ROLESTYPES::$TeacherAbbreviation[0]: $role = ROLESTYPES::$Teacher; break;
			case ROLESTYPES::$KoAbbreviation[0]: $role = ROLESTYPES::$Ko; break;
			case ROLESTYPES::$ResponsibleAbbreviation[0]: $role = ROLESTYPES::$Responsible; break;
			default:
				throw new Exception("Дописать код: Не описаны все getEnLabael:ROLESTYPES типы (".$routetype.')', 1);				
				break;
		}
		return $role;
	}
    /*
     * param 	$routetype = {'c', 's', 'k', 'l', 'y', 'd', 'g'}
     * return 	enlabel - {'Learner', Teacher, 'Ko', 'Responsible', 'Yoda', 'Director', 'GOD'}
     */
    static public function getEnLabaelByRouteType($routetype='*')
    {
    	$role = ROLESTYPES::getRoleByRouteType($routetype);
    	return ROLESTYPES::LabelType($role, false, true);
    }
    /*
     * param    $role = ROLESTYPES::{Learner, Teacher, Ko, Responsible, Yoda, Director, GOD}
     * return   action - {'Learner', Teacher, 'Ko', 'Responsible', 'Yoda', 'Director', 'GOD'}
     */
    static public function getActionByRole($role)
    {
        switch ($role) {
            case ROLESTYPES::$Learner: $role = ROLESTYPES::$LearnerAbbreviation[5]; break;
            case ROLESTYPES::$Teacher: $role = ROLESTYPES::$TeacherAbbreviation[5]; break;
            case ROLESTYPES::$Ko: $role = ROLESTYPES::$KoAbbreviation[5]; break;
            case ROLESTYPES::$Responsible: $role = ROLESTYPES::$ResponsibleAbbreviation[5]; break;
            default: throw new Exception("Дописать код: Не описаны все getActionByRole:ROLESTYPES типы (".$role.')', 1); break;
        }
        return $role;
    }
}
?>