<?php
class NumbersPages {
	static public $pages = array
	(
		
			'core/loglp/data' => 			array( 777, 'ЛОГ', ''),
			'core/loglp/list' => 			array( 777, 'ЛОГ', ''),
			'core/loglp/night' => 			array( 777, 'ЛОГ', ''),
			'core/god/index' => 			array( 777, 'GOD', ''),
			'core/god/cr_school' => 			array( 777, 'GOD', ''),
			'core/god/cr_subject' => 			array( 777, 'GOD', ''),
			'core/god/cr_section' => 			array( 777, 'GOD', ''),
			'core/god/quotes' => 			array( 777, 'GOD', ''),
			'core/god/getQuote' => 			array( 777, 'GOD', ''),
			// 'core/loglp/night' => 			array( 777, 'ЛОГ', ''),

		
			'notes' => 						array( 1001, 'Записи', ''),
			'social/note/list' => 			array( 1001, 'Записи', ''),
			'social/profile/general' => 	array( 1002, 'Общее', ''),
			'messages' => 					array( 2004, 'Сообщения', ''),
			//******************************//
			// global or udefined DO or SOCIAL
			//******************************//
			'auth/logout' => 				array( 999, '', ''),
			'auth/login' => 				array( 998, '', ''),
			'site/index' => 				array( 997, '', ''),
			'site/error' => 				array( 996, '', ''),

			'faq/index' => 					array( 995, 'Справка', ''),
			'faq/search' => 				array( 994, 'Справка', ''),
			'faq/Learner' => 				array( 994, 'Справка', ''),
			'faq/Teacher' => 				array( 994, 'Справка', ''),
			'faq/Ko' => 					array( 994, 'Справка', ''),
			'faq/Responsible' => 			array( 994, 'Справка', ''),
			'site/contact' => 				array( 99500000, '', ''),
			//******************************//
			// do
			//******************************//
			// evolution
				'do/evolution/index' => 		array( 2001, 'Система роста', 'evolution'),
				'do/evolution/index2' => 		array( 2002, 'Система роста', 'evolution'),
				'do/evolution/indexThree' => 	array( 2003, 'Система роста', 'evolution'),
				'do/evolution/Teacher' => 		array( 2014, 'Система роста', 'evolution'),
				'do/evolution/Ko' => 			array( 2015, 'Система роста', 'evolution'),
				'do/evolution/Responsible' => 	array( 2016, 'Система роста', 'evolution'),
				'do/evolution/Learner' => 		array( 2017, 'Система роста', 'evolution'),
			// journal
				'journalResponsible' => 		array( 1003, 'Журнал', ''),
				'journal/indexResponsible' => 	array( 1003, 'Журнал', ''),
				'journalTeacher' => 			array( 1004, 'Журнал', ''),
				'journal/indexTeacher' => 		array( 1004, 'Журнал', ''),
				'journalKo' => 					array( 1005, 'Журнал', ''),
				'journal/indexKo' => 			array( 1005, 'Журнал', ''),
				'journal' => 					array( 1018, 'Журнал', ''),
			// schedule
				'do/schedule/Responsible' => 	array( 1006, 'Расписание', ''),
				'do/schedule/Teacher' => 		array( 1007, 'Расписание', ''),
				'do/schedule/Ko' => 			array( 1008, 'Расписание', ''),
				'do/schedule/Learner' => 		array( 1019, 'Расписание', ''),

				'scheduleResponsible' => 		array( 1006, 'Расписание', ''),
				'schedule/indexResponsible' => 	array( 1006, 'Расписание', ''),
				'scheduleTeacher' => 			array( 1007, 'Расписание', ''),
				'schedule/indexTeacher' => 		array( 1007, 'Расписание', ''),
				'scheduleKo' => 				array( 1008, 'Расписание', ''),
				'schedule/indexKo' => 			array( 1008, 'Расписание', ''),
				'schedule' => 					array( 1019, 'Расписание', ''),
			// testresult			
				'testResultResponsible' => 		array( 1009, 'Результаты тестов', ''),
				'resulttests/indexResponsible'=>array( 1009, 'Результаты тестов', ''),
				'testResultTeacher' => 			array( 1010, 'Результаты тестов', ''),
				'resulttests/indexTeacher' => 	array( 1010, 'Результаты тестов', ''),
				'resulttests' => 				array( 1021, 'Результаты тестов', ''),
			// subjects
				'subjectTeacher' => 			array( 1011, 'Предметы', ''),
				'do/subjects/Teacher' =>		array( 1011, 'Предметы', ''),
				'subjects/indexTeacher' => 		array( 1011, 'Предметы', ''),
				'subjectsResponsible' => 		array( 1012, 'Предметы', ''),
				'subjects/indexResponsible' => 	array( 1012, 'Предметы', ''),
				'subjects' => 					array( 1020, 'Предметы', ''),
			// class
				'groupsKo' => 					array( 1013, 'Классы', ''),
				'class/indexKo' => 				array( 1013, 'Классы', ''),
			// sections
				'sections' => 				array( 1022, 'Предметы', ''),
			// GOD
				'goduser' => 				array( 1023, 'GOD', ''),

	);

	static public function NumberPageByRoute($route)
	{
		if(!array_key_exists($route, NumbersPages::$pages))
			throw new Exception("Дописать код: NUMBERPAGES:NumberPageByRoute(route=".$route.')', 1);
		else return NumbersPages::$pages[$route][0];
	}
	static public function NamePageByRoute($route)
	{
		if(!array_key_exists($route, NumbersPages::$pages))
			throw new Exception("Дописать код: NUMBERPAGES:NamePageByRoute(route=".$route.')', 1);
		else return NumbersPages::$pages[$route][1];
	}
	// static public function NamePageByNameGroup($nameGroup)
	// {
	// 	var_dump($nameGroup);
	// 	var_dump(array_search($nameGroup, NumbersPages::$pages, true));
	// }
}
?>