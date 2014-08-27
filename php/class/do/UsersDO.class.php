<?php
require_once '.htpaths';
require_once(PROJECT_PATH."/php/class/do/People.php");

class Learner extends People {
	public $idLearner;
	public $idSchool;
	public $idClass;

	private $School;
	private $Class;
	private $Subgroup;
	private $Subjects;

	public function __construct($idLearner, $l = null){
		if(empty($l))
		{
			$DB = self::connectToDB();
			$l = $DB->getRow(QSDO::$getLearner, $idLearner);
		}
		parent::__construct($idLearner, $l);
		$this->idLearner = $idLearner;
		$this->idSchool = $l['schoolS_id'];
		$this->idClass = $l['classS_id'];
	}
	# LearnerArray #
		public function LearnerArray()
		{
			return array_merge( 
				array(
					"idLearner"=>$this->idLearner, 
					"idSchool"=>$this->idSchool, 
					"idClass"=>$this->idClass), 
				$this->PeopleArray());
		}

	# one getSchool #
		public function getSchool(){
			if (is_null($this->School)) {
				$this->School = new School($this->idSchool);
			}
			return $this->School;
		}
	# one getClass #
		public function getClass(){
			if (is_null($this->Class)) {
				$this->Class = new ClassS($this->idClass);
			}
			return $this->Class;
		}
	# two getSubgroup #
		public static function getSubgroupSTATIC($idLearner){
			$DB = self::connectToDB();
			$sg = $DB->getAll(QSDO::$getSubgroups, $idLearner);
			$subgroup = array();
			foreach ($sg as $key => $value) {
				$subgroup[] = $value["subgroupS_id"];
			}
			return $subgroup;
		}
		public function getSubgroup(){

			return $this->getSubgroupSTATIC($this->idLearner);
		}
	# two getSubjectStudy #
	# two getSubjectStudy #
	public function getHometaskAll($date, $idSubject){}
	public function getHometask($date){}
	public function getSubject(){}
}
class Teacher extends People{
	public $idTeacher;
	public $idSchool;
	public $category;
	public $absentstart;
	public $absentfinish;

	public function __construct($idTeacher, $t = null){
		if(empty($t))
		{
			$DB = self::connectToDB();
			$t = $DB->getRow(QSDO::$getTeacher, $idTeacher);
		}
		parent::__construct($idTeacher, $t);
		$this->idTeacher = $idTeacher;
		$this->idSchool = $t['schoolS_id'];
		$this->category = $t['category'];
		$this->absentstart = $t['absentstart'];
		$this->absentfinish = $t['absentfinish'];
	}
	# TeacherArray #
		public function TeacherArray()
		{
			return array_merge(
				array(
					"idTeacher"=>$this->idTeacher, 
					"idSchool"=>$this->idSchool, 
					"category"=>$this->category,
					"absentstart"=>$this->absentstart, 
					"absentfinish"=>$this->absentfinish
					),$this->PeopleArray());
		}

	# one getSchool #
		public function getSchool(){
			if (is_null(self::$School)) {
				$DB = self::connectToDB();
				self::$School = new School(self::$idSchool);
			}
		}
	# two getSubjectStudy #
	# two getSubjectStudy #
    public static function getMaterialAllSTATIC($idTeacher){
            $DB = self::connectToDB();
            $m = $DB->getAll(QSDO::$getAllMaterial, $idTeacher);
            $material = array();
            foreach ($m as $value) {
                    $material[] = $value["id"];
            }
            return $material;
    }	
    public function getMaterialAll(){
            return $this->getMaterialAllSTATIC($this->idTeacher);
    }
	public function getStudyClassAll($idSubject){}
        
        # two getSubjectNamesStudy
        public function getStudySubjectNamesSTATIC($idTeacher){
            $DB = self::connectToDB();
            $s = $DB->getAll(QSDO::$getAllSubjectNames, $idTeacher);
            foreach ($s as $value) {
                $subjectnames[] = $value["name"];
            }
            return $subjectnames;
        }
        public function getStudySubjectNames(){
            return $this->getStudySubjectNamesSTATIC($this->idTeacher);
        }
        
}
class Responsible extends People {
	public $idResponsible;
	public $idSchool;

	public function __construct($idResponsible, $r = null){
		if(empty($r))
		{
			$DB = self::connectToDB();
			$r = $DB->getRow(QSDO::$getResponsible, $idResponsible);
		}
		parent::__construct($idResponsible, $r);
		$this->idResponsible = $idResponsible;
		$this->idSchool = $r['schoolS_id'];
	}
}
class Ko extends People {
	public $idKo;
	public $idSchool;

	public function __construct($idKo, $k = null){
		if(empty($k))
		{
			$DB = self::connectToDB();
			$k = $DB->getRow(QSDO::$getKo, $idKo);
		}
		parent::__construct($idKo, $k);
		$this->idKo = $idKo;
		$this->idSchool = $k['schoolS_id'];
	}
}
class Yoda extends People {
	public $idYoda;
	public $idSchool;

	public function __construct($idYoda, $y = null){
		if(empty($y))
		{
			$DB = self::connectToDB();
			$y = $DB->getRow(QSDO::$getYoda, $idYoda);
		}
		parent::__construct($idYoda, $y);
		$this->idYoda = $idYoda;
		$this->idSchool = $y['schoolS_id'];
	}
}
class School extends ConnectDB{
	public $idSchool;
	public $number;
	public $name;
	public $directoruser;
	public $countclass;
	public $countlearner;
	public $countko;
	public $countclassroom;
	public $countteacher;
	public $description;
	public $countresponsible;

	public function __construct($idSchool, $s = null){
		if(empty($s))
		{
			$DB = self::connectToDB();
			$s = $DB->getRow(QSDO::$getSchool, $idSchool);
		}
		$this->idSchool = $idSchool;
		$this->number = $s['number'];
		$this->name = $s['name'];
		$this->directoruser = $s['directoruser'];
		$this->countclass = $s['countclass'];
		$this->countlearner = $s['countlearner'];
		$this->countko = $s['countko'];
		$this->countclassroom = $s['countclassroom'];
		$this->countteacher = $s['countteacher'];
		$this->description = $s['description'];
		$this->countresponsible = $s['countresponsible'];
	}
	# SchoolArray #
		public function SchoolArray()
		{
			return array(
				"idSchool"=>$this->idSchool,
				"number"=>$this->number,
				"name"=>$this->name,
				"directoruser"=>$this->directoruser,
				"countclass"=>$this->countclass,
				"countlearner"=>$this->countlearner,
				"countko"=>$this->countko,
				"countclassroom"=>$this->countclassroom,
				"countteacher"=>$this->countteacher,
				"description"=>$this->description,
				"countresponsible"=>$this->countresponsible,
				);
		}

	# two getSubjectStudy #
	# two getSubjectStudy #
	# two getSubjectStudy #
	public function getSchool(){}
}
class ClassS extends ConnectDB{
	public $idSchool;
	public $idClass;
	public $level;
	public $letter;
	public $name;
	public $startentry;
	public $endentry;
	public $countlearner;
	public $idYoda;

	private $School;
	private $Yoda;

	public function __construct($idClass, $c = null){
		// parent::__construct($idClass, $c);
		if(empty($c))
		{
			$DB = self::connectToDB();
			$c = $DB->getRow(QSDO::$getClass, $idClass);
		}
		$this->idClass = $idClass;
		$this->idSchool = $c['schoolS_id'];
		$this->level = $c['level'];
		$this->letter = $c['letter'];
		$this->name = $c['name'];
		$this->startentry = $c['startentry'];
		$this->endentry = $c['endtentry'];
		$this->countlearner = $c['countlearner'];
		$this->idYoda = $c['yodaS_id'];	
	}
	# ClassArray #
		public function ClassArray()
		{
			return array(
				"idSchool"=>$this->idSchool,
				"idClass"=>$this->idClass,
				"level"=>$this->level,
				"letter"=>$this->letter,
				"name"=>$this->name,
				"startentry"=>$this->startentry,
				"endentry"=>$this->endentry,
				"countlearner"=>$this->countlearner,
				"idYoda"=>$this->idYoda
				);
		}

	# two getLearnersClass #
		public static function getLearnersClassSTATIC($idClass){
			$DB = self::connectToDB();
			$l = $DB->getAll(QSDO::$getLearnersClass, $idClass);
			$learners_class = array();
			foreach ($l as $key => $value) {
				$learner = new Learner($value["id"], $value);
				$learners_class[$value["id"]] = $learner;
			}
			return $learners_class;
		}	
		public function getLearnersClass(){
			return $this->getLearnersClassSTATIC($this->idClass);
		}	
	
	// classS.getGroupsWithLearnersAndMarksBySubject
	# two getGroupsWithLearnersBySubject #
		public static function getGroupsWithLearnersBySubjectSTATIC($idClass, $idSubject, $idTeacher, $idSubgroup){
			// группы класса
			$DB = self::connectToDB();

			# Если запрос подает ученик
			if(!$idTeacher)
			# Запрашиваем все подгруппы конкретного class_id, subject_id
			$g = $DB->getAll(QSDO::$getSubgroupsClassBySubject, $idClass, $idSubject);

			# Если запрос подает учитель
			else
			# Запрашиваем подгруппу конкретного class_id, subject_id, teacher_id, subgroup_id
			// 2 вариант $g = $DB->getAll(QSDO::$getSubgroupClassTeacher, $idClass, $idSubject, $idTeacher, $idSubgroup);
			$g = $DB->getAll(QSDO::$getSubgroupClassTeacher, $idSubgroup);
			foreach ($g as $key => $value) 
			{
				# Темповый массив для дальнейшего использования в запросе $getLearnersIdSubgroupsId
				# содержит id групп
				$subgroupsClass_temp[] = $value["id"];
				$subgroupsClass[$value["id"]] = $value;
			}
			// print_r($subgroupsClass_temp);
			// print_r($subgroupsClass);exit();
			# Cписок учеников класса в группе(ах) по предмету
			if($subgroupsClass_temp != NULL)
			{
				$s = $DB->getAll(QSDO::$getLearnersIdSubgroupsId, $subgroupsClass_temp);
				// print_r($s);exit();
				foreach ($s as $key => $value) 
				{
					# Получаем все idLearners в один массив
					$learnersIds[] = $value["learnerS_id1"];
					$learners_group[$value["subgroupS_id"]][] = $value["learnerS_id1"];
				}
			}
			else
			{
				header("Location: ".PROJECT_PATH."/pages/404.php");
    			return;
			}
			// print_r($learners_group);exit();
			# Список учителей конкретного класса конкретной группы(групп)
			$t = $DB->getAll(QSDO::$getTeacherByGroups, $subgroupsClass_temp);
			// print_r($t);
			// foreach($t as $key => $value) 
			// {
			// 	$TeacherObject = new Teacher($value["id"]);
			// 	$FIOInitials = $TeacherObject->FIOInitials();
			// 	print_r($FIOInitials);
			// 	// exit();
			// 	# Обрезаем имя и отчество до заглавной буквы
			// 	$value['firstname'] = mb_substr($t[$key]['firstname'], 0, 1).".";
			// 	$value['middlename'] = mb_substr($t[$key]['middlename'], 0, 1).".";
				// $teacherByGroup[$value["id"]] = $value;
			// 	print_r($teacherByGroup);
			// }

			foreach($t as $key => $value) 
			{
				$TeacherObject = new Teacher($value["id"]);
				# Обрезаем имя и отчество до заглавной буквы
				$FIOInitials = $TeacherObject->FIOInitials();
				
				$value['FIOInitials'] = $FIOInitials;
				$teacherByGroup[$value["id"]] = $value;
			}

			# Получаем список учеников по id
			if($learnersIds != NULL)
			{
				foreach($learners_group as $idGroup => $idLearner)
				{
					// print_r($key);
					$l = $DB->getAll(QSDO::$getLearnersClassArray, $idLearner);
					// print_r($l);exit();
					foreach ($l as $key => $value) 
					{
						$learner = new Learner($value["id"], $value);
						// $learners[$value["id"]] = $learner;
						$learners[$idGroup][$learner->idLearner] = $learner;
					}
				}
				// print_r($learners);exit();
			}

			$groups_learners = array();

			# Цикл создает массив Learner(s), Group(s), Teacher(s)
			foreach ($s as $key => $value)
			{
				foreach($learners[$value["subgroupS_id"]] as $key => $valueLearner)
				$groups_learners[$value["subgroupS_id"]]["learners"][$key] = $valueLearner;
				
				$groups_learners[$value["subgroupS_id"]]["group"] = $subgroupsClass[$value["subgroupS_id"]];
				$groups_learners[$value["subgroupS_id"]]["teacher"] = $teacherByGroup[$subgroupsClass[$value["subgroupS_id"]]["teacherS_id"]];
			}
			return $groups_learners;
		}	
		public function getGroupsWithLearnersBySubject(){
			return $this->getLearnersWithGroupsBySubjectSTATIC($this->idClass, $this->idSubject);
		}
	
	# two getGroupsWithLearnersAndMarksBySubject #
		public static function getGroupsWithLearnersAndMarksBySubjectSTATIC($idClass, $idSubject, $idTeacher, $idSubgroup, $currentMonthStart,
																		    $currentMonthEnd){
			# Вызов метода для формирвоания массива Learner(s), Group(s), Teacher(s)
			$groups_learners = self::getGroupsWithLearnersBySubjectSTATIC($idClass, $idSubject, $idTeacher, $idSubgroup);
			# заполнить уроки + типы
			$DB = self::connectToDB();

			# Если запрос подает ученик
			if(!$idTeacher)
			# Запрашиваем все нужные нам уроки класса
			$l = $DB->getAll(QSDO::$getLessons, $idSubject, $idSubject, $idClass, $currentMonthStart, $currentMonthEnd );
			
			# Если запрос подает учитель
			else
			# Запрашиваем все нужные нам уроки учителя по конкретной группе 
			$l = $DB->getAll(QSDO::$getLessonsOneGroupTeacher, $idSubject, $idSubject, $idClass, $currentMonthStart, $currentMonthEnd, $idSubgroup);
			
			# Выбираем только id уроков
			$l_id = self::prepareIDArray($l); // $l_id (Lesson(s)_id)

			# Находим id, lesson_id, lessonType_id
			$slt = $DB->getAll(QSDO::$getSpisokLessonstypeArray, $l_id); // $slt (SpisokLessonTypes)
			$lt_id = array(); // $lt_id (LessonType(s)_id)


			foreach ($slt as $key => $value) 
			{
				# Массив типов уроков
				$spisoklessontypes[$value["id"]] = $value;
				# Темповый массив с lessonTypes_id для дальнейшего использования в запросе $getLessonstypeArray
				$lt_id[] = $value["lessontypeS_id"]; // $lt_id (lessonType_id)
			}

			# Получаем тип урока по id
			$lt = $DB->getAll(QSDO::$getLessonstypeArray, $lt_id);

			foreach ($lt as $key => $value) 
			{ 
				$lessontype[$value["id"]] = $value; 
			}
			
			$groups_learners["lessons"] = array();
			$groups_learners["marks"] = array();
			$groups_learners["hometasks"] = array();

			foreach ($groups_learners as $idGroup => $group) {
				if($idGroup != "marks" AND $idGroup != "lessons" AND $idGroup != "hometasks") 
				{
					foreach ($group["learners"] as $idLearner => $LearnerObject) 
					{
						# Подготовка для ученика места для оценок
						$groups_learners["marks"][$idLearner] = array();
					}
						# Подготовка для ученика места для домашнего задания
						$groups_learners["hometasks"][$idGroup] = array();
				}
			}


			foreach ($l as $key => $value) {
				$lesson = new Lesson($value["id"], $value, $slt, $lessontype);
				if($lesson->spisoklessonstypes != NULL)
				# Заполняем общий массив уроками
				$groups_learners["lessons"][$value["id"]] = $lesson;
			}

			# Запрашиваем список оценок по массиву $l_id
			$sm = $DB->getAll(QSDO::$getSchoolmarkArray, $l_id);

			$schoolmarks = array();
			# Формируем массив $schoolmarks
			foreach ($sm as $key => $value) {
				$schoolmarks[$value["learnerS_id1"]][$value["lessonS_id"]][$value["spisoklessontypeS_id1"]] = $value;
			}

			$hometasks = array();

			# Получаем поле hometask из таблицы Lessons
			$hometasksFromLesssons = $DB->getAll(QSDO::$getHometasksEachLesson, $l_id);

			foreach($hometasksFromLesssons as $value)
			{
				$hFL[$value["id"]] = $value["hometask"];
			}
			$hometasksFromLesssons = $hFL;

				# Цикл формирует элемент hometasks массива groups_learners с отметками true или false для каждого урока
				foreach($groups_learners["hometasks"] as $idGroup => $lessons) 
				{
					foreach($l_id as $key => $idLesson)
					{
						$paragraphsQuery = $DB->getAll(QSDO::$getLessonsAllParagraphs, $idLesson, $idGroup);
						
						if($paragraphsQuery || $hometasksFromLesssons[$idLesson])
							$status = array(
								"status" => "true"
								);
						else $status = array(
								"status" => "false"
								);
						$hometasks[$idGroup][$idLesson]= $status;	
					}
				}
			
			/******************************************/

			# Если запрос подает ученик
			if(!$idTeacher)
			# Запрашиваем все подгруппы конкретного class_id, subject_id
			$g = $DB->getAll(QSDO::$getSubgroupsClassBySubject, $idClass, $idSubject);

			# Если запрос подает учитель
			else
			# Запрашиваем подгруппу конкретного class_id, subject_id, teacher_id, subgroup_id
			// 2 вариант $g = $DB->getAll(QSDO::$getSubgroupClassTeacher, $idClass, $idSubject, $idTeacher, $idSubgroup);
			$g = $DB->getAll(QSDO::$getSubgroupClassTeacher, $idSubgroup);

			foreach ($g as $key => $value) {
				$subgroupsClass_temp[] = $value["id"];
			}

			# Cписок учеников класса в группах по предмету
			$s = $DB->getAll(QSDO::$getLearnersIdSubgroupsId, $subgroupsClass_temp);

			# Формирование массива $avgCountAbsent с индексами CountMarks, AverageMark, CountAbsent
			foreach ($s as $key => $value)
			{
				$avgCountAbsent[$value["learnerS_id1"]] = array(
					"countMarks" => $value["countmark"],
					"averageMark" => round($value["averagemark"],1),
					"countAbsent" => $value["countabsent"]
					// "rating" => $value["rating"]
					);
			}

			# Добавляем в массив $schoolmarks для каждого ученика массив $avgCountAbsent
			foreach ($schoolmarks as $key => $value)
			{
				$schoolmarks[$key]["avg"] = $avgCountAbsent[$key];
			}

			# Формирвоание вспомагательных массивов для проверки в дальнейшем является ли конкретный урок подходящим к конкретной группе
			foreach ($groups_learners as $idGroup => $group)
			{
				if($idGroup != "marks" AND $idGroup != "lessons" AND $idGroup != "hometasks")
				{
					//foreach ($group["learners"] as $idLearner => $LearnerObject) 
					//{
						# подготовить для ученика место для оценок
						$massGroupsForLearners[] = $group["group"]["id"];
					//}
						# подготовить для ученика место для домашнего задания
						$massGroupsForHometask[] = $group["group"]["id"];

						# подсчитываем количество учеников в каждой группе
						$countLearners[] = count($group["learners"]);
				}
			}

			$count = 1;
			foreach ($groups_learners["lessons"] as $idLesson => $lesson) {
				
				foreach ($lesson->spisoklessonstypes as $idSpisoklessontypeLesson => $lessontype) {
					$i = 0;// Счетчик для массивов $massGroupsForLearners и $massGroupsForHometask
					$count = 1;
					foreach ($groups_learners["marks"] as $idLearner => $lessons) {
						$checking = $DB->getAll(QSDO::$checkLessonByGroup, $massGroupsForLearners[$i], $idLesson);
						// print_r($massGroupsForLearners[$i]);
						// print_r($checking);
						// exit();
						
						$mark = $schoolmarks[$idLearner][$idLesson][$idSpisoklessontypeLesson];
						
						if($checking != NULL)
						$groups_learners["marks"][$idLearner][$idLesson][$idSpisoklessontypeLesson] =
								$mark ? $mark : NULL;

						else
						$groups_learners["marks"][$idLearner][$idLesson][$idSpisoklessontypeLesson] = array("error" => "Нет урока");

					# Конструкция для правильной проверки существования урока и тип урока в данной группе
					// если счетчик $count == количеству учеников в группе
					if($count == $countLearners[$i])
					// тогда инкриментируем счетчик $i для проверки уже другой группы
						$i++;
					else 
					// или увеличиваем счетчик $count
						$count++;
					}

					$j = 0;
					foreach($groups_learners["hometasks"] as $idGroup => $lessons) {
						$checking = $DB->getAll(QSDO::$checkLessonByGroup, $massGroupsForHometask[$j], $idLesson);
						$hometask = $hometasks[$idGroup][$idLesson];
						// print_r($j."-");
						// print_r($massGroupsForHometask[$j]);
						// print_r("<br>");
						if($checking != NULL)
						$groups_learners["hometasks"][$idGroup][$idLesson][$idSpisoklessontypeLesson] = $hometask;
								//$mark ? $mark : NULL;
						else
						$groups_learners["hometasks"][$idGroup][$idLesson][$idSpisoklessontypeLesson] = array("error" => "Нет урока");
								//$mark ? $mark : array("error" => "Нет урока");
					$j++;
					}
				}
			}	
			// print_r($groups_learners["hometasks"]);
			// 		exit();
			/******************************************/
			# Извлекаем баллы из поля rating в отдельный массив
			// foreach ($groups_learners["marks"] as $idLearner => $value) {
			// 	$ratingNumber[$idLearner] = $avgCountAbsent[$idLearner]["rating"];
			// }
			
			# Сортируем массив с баллами - "[$idLearner] => $rating" по значению
			# с сохранением ключа
			// arsort($ratingNumber);

			// $position = 1;
			# Вместо значения $rating для каждого ученика подставляем его позицию
			# в Рейтинге
			// foreach($ratingNumber as $idLearner => $value)
			// {
			// 	$ratingNumber[$idLearner] = $position++;
			// }

			foreach ($groups_learners["marks"] as $idLearner => $value)
			{
				$mark = $schoolmarks[$idLearner]["avg"];
					$countMarks = $avgCountAbsent[$idLearner]["countMarks"];
					$averageMark = $avgCountAbsent[$idLearner]["averageMark"];
					$countAbsent = $avgCountAbsent[$idLearner]["countAbsent"];
					// $rating = $avgCountAbsent[$idLearner]["rating"];
				$groups_learners["marks"][$idLearner]["avg"] = $mark ? $mark : array("countMarks" => $countMarks, "averageMark" => $averageMark, "countAbsent" => $countAbsent/*, "rating" => $rating*/);
				// $groups_learners["marks"][$idLearner]["avg"]["ratingNumber"] = $ratingNumber[$idLearner];
			}
			// var_dump($groups_learners);
			// print_r($groups_learners["hometasks"]);
			// print_r($groups_learners["marks"]);
			// print_r($groups_learners);
			// exit();

			/******************************************/
			return $groups_learners;
		}
		public function getGroupsWithLearnersAndMarksBySubject(){
			$this->getGroupsWithLearnersAndMarksBySubjectSTATIC($this->idClass, $this->idSubject);
		}

	# two getSubjectStudy # 
		public static function getSubjectsStudyClassSTATIC($idClass){
			$DB = self::connectToDB();
			$cs = $DB->getAll(QSDO::$getClassSubjects, $idClass);
			$s = $DB->getAll(QSDO::$getSubjects);
			foreach ($s as $key => $value) {
				$subjects[$value["id"]] = $value["name"];
			}

			foreach ($cs as $key => $value) {
				$cs[$key]["name"] = $subjects[$value["subjectId"]];
			}
			$classs_subject = array();
			foreach ($cs as $key => $value) {
				$classs_subject[$value["subjectId"]] = $value;
			}
			return $classs_subject;
		}
		public function getSubjectStudy(){
			return $this->getSubjectStudySTATIC($this->idClass);
		}

		public static function getSubjectsStudyTeacherSTATIC($idTeacher){
			$DB = self::connectToDB();
			$cs = $DB->getAll(QSDO::$getTeacherSubjects, $idTeacher);
			$s = $DB->getAll(QSDO::$getSubjects);
			foreach ($s as $key => $value) {
				$subjects[$value["id"]] = $value["name"];
			}

			foreach ($cs as $key => $value) {
				$cs[$key]["name"] = $subjects[$value["subjectId"]];
			}
			$classs_subject = array();
			foreach ($cs as $key => $value) {
				$classs_subject[$value["subjectId"]] = $value;
			}
			return $classs_subject;
		}
	// subjects
	# two getSubjectTeacherMaterialStudy #
		public static function getSubjectTeacherMaterialStudySTATIC($idClass){
			$DB = self::connectToDB();
			$cs = $DB->getAll(QSDO::$getSubjectTeacherMaterialStudy, $idClass);
			$s = $DB->getAll(QSDO::$getSubjects);
			
			// в массив всех teacher из выборки
			$teachersId = array();
			foreach ($cs as $key => $value) { 
				// if($teachersId[$value["teacherS_id"]] == NULL) 
					$teachersId[] = $value["teacherS_id"];
			}

			$t = $DB->getAll(QSDO::$getTeachersArray, $teachersId);

			// Array формат 
			$teachers = array();
			foreach ($t as $key => $value) { 
				$teacher_t = new Teacher($value["id"], $value);
				// $teachers[$value["id"]] = $teacher_t->TeacherArray();
				$teachers[$value["id"]]["TeacherObject"] = $teacher_t;
			}
                                
			// Array формат 
			// в массив все предметы [name, color]
			foreach ($s as $key => $value) {
				$subjects[$value["id"]] = array("idSubject"=>$value["id"], "name"=>$value["name"], "color"=>$value["color"]);
			}
			// группа & предмет & преподаватель [name, color]
			$classs_subject = array();
			foreach ($cs as $key => $value) {
				// echo $key." ".print_r($value)."\n";
				$classs_subject[$value["id"]]["subject"] = $subjects[$value["subjectS_id"]];
				$classs_subject[$value["id"]]["teacher"] = $teachers[$value["teacherS_id"]];
				$classs_subject[$value["id"]]["material"] = array("idMaterial"=>$value["materialS_id"]);
			}
			return $classs_subject;
		}
		public function getSubjectTeacherMaterialStudy(){
			return $this->getSubjectTeacherMaterialStudySTATIC($this->idClass);
		}
	
	# two getSubjectStudy #
	
	public function getSchedule($date){}
	public function getInfoClass(){}
	public function getLearnersByGroup($idGroup){}
	public function getMarksByLearner($idLearner){}
	public function getTeacherByGroup($idGroup){}
}
class Paragraph extends ConnectDB{
	public $idParagraph;
	public $name;
	public $number;
	public $state;
	public $countquestion;
	public $countpart;
	public $datecreate;
	public $dateupdate;
	public $notstudy;
	public $countdiscussion;
	public $countextention;
	public $idMaterial;
	public $idSection;
        public $isTestReady;

	public function __construct($idParagraph, $p = null){
		// parent::__construct($idClass, $c);
		if(empty($p))
		{
			$DB = self::connectToDB();
			$p = $DB->getRow(QSDO::$getParagraph, $idParagraph);
		}
                $DB = self::connectToDB();
                $s = $DB->getOne(QSDO::$getSoftQuestions, $idParagraph);
                $m = $DB->getOne(QSDO::$getMediumQuestions, $idParagraph);
                $h = $DB->getOne(QSDO::$getHardQuestions, $idParagraph);
                if(($s >=3) && ($m >=2) && ($h >= 1)) {$istestready = true;} else {
                    $istestready = false;
                }  
                //
                //
		$this->idParagraph = $idParagraph;
		$this->name = $p['name'];
		$this->number = $p['number'];
		$this->state = $p['state'];
		$this->countquestion = $p['countquestion'];
		$this->countpart = $p['countpart'];
		$this->datecreate = $p['datecreate'];
		$this->dateupdate = $p['dateupdate'];
		$this->notstudy = $p['notstudy'];
		$this->countdiscussion = $p['countdiscussion'];
		$this->countextention = $p['countextention'];
		$this->idMaterial = $p['materialS_id1'];
		$this->idSection = $p['sectionS_id1'];
                $this->isTestReady = $istestready;
	}

	# TeacherArray #
		public function ParagraphArray(){
			return array(
				"idParagraph"=>$this->idParagraph,
				"name"=>$this->name,
				"number"=>$this->number,
				"state"=>$this->state,
				"countquestion"=>$this->countquestion,
				"countpart"=>$this->countpart,
				"datecreate"=>$this->datecreate,
				"dateupdate"=>$this->dateupdate,
				"notstudy"=>$this->notstudy,
				"countdiscussion"=>$this->countdiscussion,
				"countextention"=>$this->countextention,
				"idMaterial"=>$this->idMaterial,
				"idSection"=>$this->idSection,
				);
		}

	# two getSubjectStudy #
	# two getSubjectStudy #
	# two getSubjectStudy #
	# two getSubjectStudy #
}
class Material extends ConnectDB{
	public $idMaterial;
	public $name;
	public $state;
	public $countparagraph;
	public $countparagraphactive;
	public $countsection;
	public $datecreate;
	public $dateupdate;
	public $notstudy;
	public $idTeacher;
	public $idSubject;
        public $deleted;

	private $paragraphs;

	public function __construct($idMaterial, $m = null){
		// parent::__construct($idClass, $c);
		if(empty($m))
		{
			$DB = self::connectToDB();
			$m = $DB->getRow(QSDO::$getMaterial, $idMaterial);
		}
		$this->idMaterial = $idMaterial;
		$this->name = $m['name'];
		$this->state = $m['state'];
		$this->countparagraph = $m['countparagraph'];
		$this->countparagraphactive = $m['countparagraphactive'];
		$this->countsection = $m['countsection'];
		$this->datecreate = $m['datecreate'];
		$this->dateupdate = $m['dateupdate'];
		$this->notstudy = $m['notstudy'];
		$this->idTeacher = $m['teacherS_id1'];
		$this->idSubject = $m['subjectS_id1'];
                $this->deleted = $m['deleted'];
	}
	# ClassArray #
		public function ClassArray()
		{
			return array(
				"idMaterial"=>$this->idMaterial,
				"name"=>$this->name,
				"state"=>$this->state,
				"countparagraph"=>$this->countparagraph,
				"countparagraphactive"=>$this->countparagraphactive,
				"countsection"=>$this->countsection,
				"datecreate"=>$this->datecreate,
				"dateupdate"=>$this->dateupdate,
				"notstudy"=>$this->notstudy,
				"idTeacher"=>$this->idTeacher,
				"idSubject"=>$this->idSubject,
                                "deleted"=>$this->deleted
				);
		}

	# two getSectionsMaterial #
		public static function getSectionsMaterialSTATIC($idMaterial){
			$DB = self::connectToDB();
			$sections_temp = array();
			$s = $DB->getAll(QSDO::$getSectionsMaterial, $idMaterial);
			foreach ($s as $value) {
				$sections_temp[$value["id"]] = array(
					"idSection"=>$value["id"], 
					"name"=>$value["name"], 
					"number"=>$value["number"],
                                        "deleted"=>$value["deleted"],
					"idSubject"=>$value["subjectS_id1"],
					"counthours"=>$value["counthours"],
					"requirements"=>$value["requirements"]
					);
			}
			return $sections_temp;
		}
		public function getSectionsMaterial(){
			return $this->getSectionsMaterialSTATIC($this->idMaterial);
		}
	# two getParagraphsSection #
		public static function getParagraphsSectionSTATIC($idMaterial, $idSection, $deleted = 0){
			$DB = self::connectToDB();
			$paragraphs_temp = array();
			$paragraphsFromDb = $DB->getAll(QSDO::$getParagraphsSection, $idMaterial, $idSection, $deleted);
			foreach ($paragraphsFromDb as $parFromDb) {
				$parag = new Paragraph($parFromDb["id"], $parFromDb);
				$paragraphs_temp[$parFromDb["id"]] = $parag;//.ParagraphArray();
			}
			return $paragraphs_temp;
		}
		public function getParagraphsSection($idSection, $deleted = 0){
			return $this->getParagraphsSectionSTATIC($this->idMaterial, $idSection, $deleted);
		}
	# two getParagraphsSection_Study #
		public static function getParagraphsSection_StudySTATIC($idMaterial, $idSection, $deleted = 0, $notstudy = 0){
			$DB = self::connectToDB();
			$paragraphs_temp = array();
			$paragraphsFromDb = $DB->getAll(QSDO::$getParagraphsSection_Study, $idMaterial, $idSection, $deleted, $notstudy);
			foreach ($paragraphsFromDb as $parFromDb) {
				$parag = new Paragraph($parFromDb["id"], $parFromDb);
				$paragraphs_temp[$parFromDb["id"]] = $parag;//.ParagraphArray();
			}
			return $paragraphs_temp;
		}
		public function getParagraphsSection_Study($idSection, $deleted = 0, $notstudy = 0){
			return $this->getParagraphsSection_StudySTATIC($this->idMaterial, $idSection, $deleted, $notstudy);
		}
	# two getParagraph #
		public function getParagraph($idMaterial, $idParagraph){
			
		}
}

class Lesson extends ConnectDB{
	public $idLesson;
	public $number;
	public $date;
	public $hometask;
	public $idSubgroup;
	public $idClassroom;
	public $idTeacher;
	public $idSubject;

	public $spisoklessonstypes;
	public function __construct($idLesson, $l = null, $spisoklessonstypes = null, $lessontype = null){
		if(empty($l))
		{
			$DB = self::connectToDB();
			$l = $DB->getRow(QSDO::$getLesson, $idLesson);
		}
		$this->idLesson = $idLesson;
		$this->number = $l['number'];
		$this->date = $l['date'];
		$this->hometask = $l['hometask'];
		$this->idSubgroup = $l['subgroupS_id1'];
		$this->idClassroom = $l['classroomS_id1'];
		$this->idTeacher = $l['teacherS_id1'];
		$this->idSubject = $l['subjectS_id1'];

		$this->spisoklessonstypes = array();
		if($spisoklessonstypes!=NULL)
		{
			foreach ($spisoklessonstypes as $key => $value) {
				if ($value["lessonS_id"] == $this->idLesson) {
					$this->spisoklessonstypes[$value["id"]] = new Lessontype($value["lessontypeS_id"], $lessontype[$value["lessontypeS_id"]]);
				}
			}
		}
	}
	# LessonArray #
		public function LessonArray()
		{
			return array(
				"idLesson"=>$this->idLesson,
				"number"=>$this->number,
				"date"=>$this->date,
				"hometask"=>$this->hometask,
				"idSubgroup"=>$this->idSubgroup,
				"idClassroom"=>$this->idClassroom,
				"idTeacher"=>$this->idTeacher,
				"idSubject"=>$this->idSubject,
				);
		}

	# two getSpisokLessonType #
		public static function getSpisokLessonTypeSTATIC($idLesson){

		}
		public function getSpisokLessonType(){
			$this->getSpisokLessonTypeSTATIC($this->idLesson);
		}

	# two getSectionsMaterial #

	# two getSectionsMaterial #
}
class Lessontype extends ConnectDB{
	public $idLessontype;
	public $name;

	public function __construct($idLessontype, $l = null){
		if(empty($l))
		{
			$DB = self::connectToDB();
			$l = $DB->getRow(QSDO::$getLessontype, $idLessontype);
		}
		$this->idLessontype = $idLessontype;	
		$this->name = $l["name"];	
	}
	# LessontypeArray #
		public function LessontypeArray()
		{
			return array(
				"idLessontype"=>$this->idLessontype,
				"name"=>$this->name
				);
		}

	# two getSectionsMaterial #

	# two getSectionsMaterial #

	# two getSectionsMaterial #
}


?>