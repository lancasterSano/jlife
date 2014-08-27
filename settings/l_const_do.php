<?php
require_once '.htpaths';
require_once(PROJECT_PATH."/php/class/ProfileAuth.php");

/************  MESSAGES  ***************
 *	Правила составления константных переменных:
 *	1. Начало переменной 
 *		- 	ME_ (MessageError) - сообщение об ошибке
 * 		- 	MW_ (MessageWarning) - сообщение предупреждение
 *		- 	MI_ (MessageInformation) - сообщение информационное
 *		- 	MS_ (MessageSuccess) - сообщение об успехе
 *	2. Вторая часть переменной должна отоброжать конкретный смысл
 *		- 	TEACHER_HAS_NO_SUBGROUPS - (Учитель не является преподавателем ни в одной группе)
 *	3. Третья часть переменной длжна содержать в себе _TITLE либо _TEXT - в зависимости от контекста
 */

/********  JOURNAL_TEACHER  ********/
	# ME_
		define('MW_TEACHER_HAS_NO_SUBGROUPS', 'Вам не назначены классы для преподавания');
		define('MW_CLASS_HAS_NO_LEARNERS', 'В классе нет учеников');
		define('MW_SCHOOL_HAS_NO_STUDYDURATION', 'Учебные месяцы школы не указаны');
	# MW_
	# MI_
	# MS_



/************  JOURNAL  ************/
	# ME_
	# MW_
		define('MW_CLASS_HAS_NO_SUBGROUPS', 'Для вашего класса еще не назначены предметы преподавания');
	# MI_
	# MS_

/************  TEST_RESULT_TEACHER  ************/
	# ME_
	# MW_
		define('MI_SUBJECT_HAS_NO_PARAGRAPHS', 'У данного класса нет параграфов');
	# MI_
	# MS_

/************  PARAGRAPH  ************/
	# ME_
	# MW_
		define('MW_PARAGRAPH_HAS_NO_PARTPARAGRAPHS', 'У параграфа нет разделов!');	
	# MI_
	# MS_

/************  PARAGRAPH  ************/
	# ME_
	# MW_
		define('MW_TEST_NOT_READY', 'Тестирование не готово для данного параграфа!');	
	# MI_
	# MS_
/************* SECTIONS *************/
		define('MW_SECTIONSMATERIAL_HAS_NO_SECTIONS', 'Разделы отсутствуют');	
                
/************  JOURNAL  ************/
	# ME_
	# MW_
	# MI_
		define('MI_SCHOOL_HAS_NO_CLASSES', 'В школе отсутствуют классы !');
	# MS_
/************  JOURNAL_RESPONSIBLE  ************/
	# ME_
	# MW_
        define('MW_CLASS_YOUR_CHILD_HAS_NO_SUBGROUPS', 'Классу, в котором учится <strong>%NAME</strong> не назначены предметы!');	
	# MI_
		define('MI_EDUCATION_NOT_START_YET', 'Учебный семестр еще не начался !');	
	# MS_

?>