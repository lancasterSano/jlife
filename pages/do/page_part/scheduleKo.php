<?php

$data = array();
if(isset($_GET["ac"])) {$type = $_GET["ac"];}
else {$type = 1;}
//echo "Тип - ".$type;

// GET WHAT WE WANT TO SHOW (SUBJECTS/ CLASSES/ TEACHERS/ CABINETS)
switch($type){
    case null:
    case 1:
        // GET ALL CLASSES OF SCHOOL
        $classesFromDB = $DB_DO->getAll(QSDO::$getAllClassesOfSchool, $Ko->idSchool);
        foreach ($classesFromDB as $value) {
            $data[] = array(
                "id"    => $value["id"],
                "name"  => $value["name"]
            );
        }
        if(isset($_GET["i"])) {$currentSelectedId = $_GET["i"];}
        else {$currentSelectedId = $data[0]["id"];}
        break;
    case 2:
        // GET ALL TEACHERS OF SCHOOL
        $teachersFromDB = $DB_DO->getAll(QSDO::$getAllTeachersIDs, $Ko->idSchool);
        foreach ($teachersFromDB as $value) {
            $Teacher = new Teacher($value["id"]);
            $data[] = array(
                "id"    => $value["id"],
                "name"   => $Teacher->FIOInitials()
            );
        }
        if(isset($_GET["i"])) {$currentSelectedId = $_GET["i"];}
        else {$currentSelectedId = $data[0]["id"];}
        break;
    case 3:
        // GET ALL SUBJECTS OF SCHOOL
        $subjectsIDs = array();
        $subjectsIDsFromDB = $DB_DO->getAll(QSDO::$getAllSubjectsIDsOfSchool, $Ko->idSchool);
        foreach ($subjectsIDsFromDB as $value) {
            $subjectsIDs[] = $value["subjects_id"];
        }
        $data = $DB_DO->getAll(QSDO::$getAllSubjectsNamesOfSchool, $subjectsIDs);
        if(isset($_GET["i"])) {$currentSelectedId = $_GET["i"];}
        else {$currentSelectedId = $data[0]["id"];}
        break;
    case 4:
        // GET ALL CABINETS OF SCHOOL
        $cabinetsFromDB = $DB_DO->getAll(QSDO::$getAllCabinetsOfSchool, $Ko->idSchool);
        foreach ($cabinetsFromDB as $value) {
            $data[] = array(
                "id"    => $value["id"],
                "name"  => $value["number"]
            );
        }
        if(isset($_GET["i"])) {$currentSelectedId = $_GET["i"];}
        else {$currentSelectedId = $data[0]["id"];}
        break;
    default:

}
foreach ($data as $value) {
    if($value["id"] == $currentSelectedId) {
        $currentSelectedName = $value["name"];
    }
}
//echo "<br>Выбранный ID - ".$currentSelectedId;
//echo "<br>Выбранное имя - ".$currentSelectedName;

// SET COUNT OF WORKING DAYS
//[TODO] this value should be taken from db
$r = $DB_DO->getRow(QSDO::$getCountStudyDays, $Ko->idSchool);
$COUNT_WORK_DAYS = $r["countstudyday"];

// SET START EDUCATION AND END EDUCATION OF SEMESTRE
//[TODO] this values should be taken from DB
$r = $DB_DO->getRow(QSDO::$getActualStudyDuration, $Ko->idSchool);
if($r){
    $isStudyDuration = true;
    $START_EDUCATION_DATE = $r["begin"];
    $END_EDUCATION_DATE = $r["end"];
    $NAME_DURATION = $r["name"];

    // GET STARTING WEEK, ENDING WEEK AND YEAR OF STUDYING
    $weeknumberStartStudy = intval(ltrim(date("W", strtotime($START_EDUCATION_DATE))));
    $weeknumberEndStudy = intval(ltrim(date("W", strtotime($END_EDUCATION_DATE))));
    $yearOfStudy = date("Y", strtotime($START_EDUCATION_DATE));
    //echo "Week number start study - ".$weeknumberStartStudy."<br>";
    //echo "Week number end study - ".$weeknumberEndStudy."<br>";
    //echo "Year of study - ".$yearOfStudy."<br>------------------<br>";

    // GET CURRENT YEAR AND CURRENT WEEK
    $currentyear = date("Y");
    $currentweek = intval(ltrim(date("W")));
    $currentday = date("N");
    //echo "Current week - ".$currentweek."<br>";
    //echo "Current year - ".$currentyear."<br>";
    //echo "Current day - ".$currentday."<br>-------------------------------<br>";

    // GET WEEKNUMBER WE WANT TO SHOW
    if(isset($_GET["w"])) {
        $weeknumberToShowDays = intval($_GET["w"]);
        if(!(($weeknumberToShowDays <= $weeknumberEndStudy) && ($weeknumberToShowDays >= $weeknumberStartStudy))) {
            //can't show timetable of weeks not current semestre
            header("Location: ".PROJECT_PATH."/pages/404.php");
        }
    } else {
        $weeknumberToShowDays = $currentweek;
        if(!(($weeknumberToShowDays <= $weeknumberEndStudy) && ($weeknumberToShowDays >= $weeknumberStartStudy))) {
            //can't show current timetable if current week is not study week
            $weeknumberToShowDays = $weeknumberStartStudy;
        }
    }
    //echo "Weeknumber to Show - ".$weeknumberToShowDays."<br>";

    // GET FIRSTDAY AND SECOND DAY TO SHOW TIMETABLE FOR
    if(($currentweek <= $weeknumberEndStudy) && ($currentweek >= $weeknumberStartStudy)) {
        if($currentday == 7){
            $weeknumberToShowDays++;
            $currentstudydate = date("j n Y", strtotime("next Monday"));
            $nextstudydate = date("j n Y", strtotime("next Tuesday"));
        }
        else if($currentday == $COUNT_WORK_DAYS){
            $currentstudydate = date("j n Y", strtotime("now"));
            $nextstudydate = date("j n Y", strtotime("next Monday"));
        }
        else {
            $currentstudydate = date("j n Y", strtotime("now"));
            $nextstudydate = date("j n Y", strtotime("+1 day"));
        }
    } else {
        $currentstudydate = date("j n Y", strtotime($START_EDUCATION_DATE));
        $nextstudydate = date("j n Y", strtotime("+1 day", strtotime($START_EDUCATION_DATE)));
    }
    //echo "First day to show - ".$currentstudydate."<br>";
    //echo "Second day to show - ".$nextstudydate."<br>";

    // MAIN LOOP
    for($day = 1; $day <= $COUNT_WORK_DAYS; $day++) {
        // 1. FORM DATE OF DAY OF WEEK TO SHOW
        if($weeknumberToShowDays < 10 ) {
            $date = date("j n Y", strtotime($yearOfStudy."W0".$weeknumberToShowDays.$day));
            $daynumber = date("N", strtotime($yearOfStudy."W0".$weeknumberToShowDays.$day));
            $dateForJS = date("Y-m-d", strtotime($yearOfStudy."W0".$weeknumberToShowDays.$day));
        } else {
            $date = date("j n Y", strtotime($yearOfStudy."W".$weeknumberToShowDays.$day));
            $daynumber = date("N", strtotime($yearOfStudy."W".$weeknumberToShowDays.$day));
            $dateForJS = date("Y-m-d", strtotime($yearOfStudy."W".$weeknumberToShowDays.$day));
        }
        // make this date russian
        $russianDay = getRussianDay($daynumber);
        $russianDate = setRussianDate($date);

        // set flags of current and next working days
        if($date == $currentstudydate)
            $iscurrentstudyday = true;
        else
            $iscurrentstudyday = false;
        if($date == $nextstudydate) 
            $isnextstudyday = true;
        else 
            $isnextstudyday = false;


        // 2. GET LESSONS OF TWO STUDYING DAYS
        if($iscurrentstudyday or $isnextstudyday) {
            if($weeknumberToShowDays < 10)
                $dateForQuery = date("Y-m-d", strtotime($yearOfStudy."W0".$weeknumberToShowDays.$day))."%";
            else
                $dateForQuery = date("Y-m-d", strtotime($yearOfStudy."W".$weeknumberToShowDays.$day))."%";
                
            

            // GET WHAT WE WANT TO SHOW (SUBJECTS/ CLASSES/ TEACHERS/ CABINETS)
            switch($type){
                case null:
                case 1:
                    // GET SUBGROUPS OF CLASS
                    $classsubgroupsFromDB = $DB_DO->getAll(QSDO::$getSubgroupsClassIDs, $currentSelectedId);
                    if($classsubgroupsFromDB) {
                        foreach ($classsubgroupsFromDB as $value) {
                            $classsubgroups[] = $value["id"];
                        }

                        // GET LESSONS OF CLASS
                        $lessonsByDayFromDb = $DB_DO->getAll(QSDO::$getLessonsArray, $dateForQuery, $classsubgroups);
                    }
                    unset($classsubgroups);
                    break;
                case 2:
                    // GET LESSONS OF TEACHER
                    $lessonsByDayFromDb = $DB_DO->getAll(QSDO::$getLessonsForTeacher, $currentSelectedId, $dateForQuery);
                    break;
                case 3:
                    // GET LESSONS OF SUBJECTNAME
    //                print_r($currentSelectedName);
                    $subjectIDsFromDB = $DB_DO->getAll(QSDO::$getSubjectIDsByName, $Ko->idSchool, $currentSelectedName);
                    foreach($subjectIDsFromDB as $value) {
                        $subjectIDs[] = $value["id"];
                    }
    //                print_r($subjectIDs);
                    $lessonsByDayFromDb = $DB_DO->getAll(QSDO::$getLessonsBySubjectname, $dateForQuery, $subjectIDs);
                    unset($subjectIDs);
                    break;
                case 4:
                    // GET LESSONS IN CABINET
                    $lessonsByDayFromDb = $DB_DO->getAll(QSDO::$getLessonsByCabinet, $dateForQuery, $currentSelectedId);
                    break;
                default:

            }


            //get last lesson number (needed for right css displaying)
            $lastlesson = $lessonsByDayFromDb[count($lessonsByDayFromDb) - 1]["number"];

            if($lessonsByDayFromDb) {
                foreach ($lessonsByDayFromDb as $lessonByDayFromDb) {
                    $idSubject = $lessonByDayFromDb["subjectS_id1"];
                    $classFromDb = $DB_DO->getRow(QSDO::$getClassByIdGroup, $lessonByDayFromDb["subgroupS_id1"]);
                    $subgroupFromDB = $DB_DO->getRow(QSDO::$getSubgroup, $lessonByDayFromDb["subgroupS_id1"]);
                    $countownsubgroup = $subgroupFromDB["countownsubgroup"];
                    if($countownsubgroup == 1) {
                        $nameclassgroup = $classFromDb["name"];
                    } else {
                        $nameclassgroup = $classFromDb["name"]." ".$subgroupFromDB["name"];
                    }
                    $subjectInfo = $DB_DO->getRow(QSDO::$getSubjectById, $idSubject);
                    if($lessonByDayFromDb["number"] == $lastlesson) { $islast = true; } else { $islast = false; }

                    $lessontypes = array();
                    $spisoklessontypesFromDB = $DB_DO->getAll(QSDO::$getLessonsTypeLesson, $lessonByDayFromDb["id"]);
                    $lastlessontypeID = $spisoklessontypesFromDB[count($spisoklessontypesFromDB) - 1]["lessontypeS_id"];
                    foreach ($spisoklessontypesFromDB as $value) {
                        $idLessonType = $value["lessontypeS_id"];
                        if($idLessonType == $lastlessontypeID) {$islastlessontype = 1;}
                        else {$islastlessontype = 0;}
                        $lessontypeFromDb = $DB_DO->getRow(QSDO::$getLessontype, $idLessonType);

                        $lessontypes[] = array(
                            "id" => $idLessonType,
                            "name" => $lessontypeFromDb["name"],
                            "description" => $lessontypeFromDb["description"],
                            "islastlessontype" => $islastlessontype
                        );
                    }

                    $Teacher = new Teacher($lessonByDayFromDb["teacherS_id1"]);

                    $lessons[$lessonByDayFromDb["number"]][$lessonByDayFromDb["subgroupS_id1"]] = array(
                        "id" => $lessonByDayFromDb["id"],
                        "number" => $lessonByDayFromDb["number"],
                        "hometask" => $lessonByDayFromDb["hometask"],
                        "cabinet" => $lessonByDayFromDb["classroomS_id1"],
                        "idsubgroup" => $lessonByDayFromDb["subgroupS_id1"],
                        "Teacher" => $Teacher,
                        "color" => $subjectInfo["color"],
                        "idsubject" => $idSubject,
                        "subjectname" => $subjectInfo["name"],
                        "islast" => $islast,
                        "nameclassgroup" => $nameclassgroup,
                        "lessontypes" => $lessontypes
                    );
                    unset($lessontypes);
                } // end foreach
                for($i = 1; $i < intval($lastlesson); $i++){
                    if($lessons[$i]){

                    } else {
                        $lessons[$i][0] = array("idsubgroup" => 0, "cabinet" => "", "color" => "#e6e6e6", "subjectname" => "Нет урока");
                    }
                }
                ksort($lessons);
            } // end lessons if
        }

        $days[] = array(
            "date" => $russianDate,
            "dateForJS" => $dateForJS,
            "dayofweek" => $russianDay,
            "iscurrentstudyday" => $iscurrentstudyday,
            "isnextstudyday" => $isnextstudyday,
            "lessons" => $lessons
        );

        // unset cause of split data
        unset($lessons);
    }
    // GET THE PREVIOUS MONDAY AND THE NEXT MONDAY (WEEK AND YEAR)
    if($weeknumberToShowDays == $weeknumberStartStudy) {
        $isfirstweek = true;
        $islastweek = false;
    //        echo "Week number == weeknumberstart<br>";
        //case 1: week number equals week number START education. We need NOT TO SHOW PREVIOUS week
        if($weeknumberToShowDays < 10 ) {
            $dateprevweek = strtotime($yearOfStudy."W0".$weeknumberStartStudy);
            $datenextweek = strtotime($yearOfStudy."W0".$weeknumberStartStudy." +1 week");
        } else {
            $dateprevweek = strtotime($yearOfStudy."W".$weeknumberStartStudy);
            $datenextweek = strtotime($yearOfStudy."W".$weeknumberStartStudy." +1 week");
        }
    } else if ($weeknumberToShowDays == $weeknumberEndStudy) {
        $islastweek = true;
        $isfirstweek = false;
    //        echo "Week number == weeknumberend<br>";
        //case 2: week number equals week number END education. We need NOT TO SHOW NEXT week
        if($weeknumberToShowDays < 10 ) {
            $dateprevweek = strtotime($yearOfStudy."W0".$weeknumberEndStudy." -1 week");
            $datenextweek = strtotime($yearOfStudy."W0".$weeknumberEndStudy);

        } else {
            $dateprevweek = strtotime($yearOfStudy."W".$weeknumberEndStudy." -1 week");
            $datenextweek = strtotime($yearOfStudy."W".$weeknumberEndStudy);
        }
    } else {
        $isfirstweek = false;
        $islastweek = false;
    //        echo "Week number between weeknumberstart and weeknumberend<br>";
        // case 3: week number is between start date and end date. We show both weeks
        if($weeknumberToShowDays < 10 ) {
            $dateprevweek = strtotime($yearOfStudy."W0".$weeknumberToShowDays." -1 week");
            $datenextweek = strtotime($yearOfStudy."W0".$weeknumberToShowDays." +1 week");
        } else {
            $dateprevweek = strtotime($yearOfStudy."W".$weeknumberToShowDays." -1 week");
            $datenextweek = strtotime($yearOfStudy."W".$weeknumberToShowDays." +1 week");
        }
    }
    $nextprevweekyear = getNextPrevWeekYear($dateprevweek, $datenextweek);
    //    echo "Previous week - ".$nextprevweekyear["prevweek"].". Next week - ".$nextprevweekyear["nextweek"]."<br>";
    //    echo "Previous year - ".$nextprevweekyear["prevyear"].". Next year - ".$nextprevweekyear["nextyear"]."<br>";
} else {
    $isStudyDuration = true;
}

$smarty->assign("days", $days);
$smarty->assign("headerScheduledata", $data);
$smarty->assign("currentpagetype", $type);
$smarty->assign("currentSelectedId", $currentSelectedId);
$smarty->assign("currentSelectedName", $currentSelectedName);
$smarty->assign("isfirstweek", $isfirstweek);
$smarty->assign("islastweek", $islastweek);
$smarty->assign("nameduration", $NAME_DURATION);
$smarty->assign("nextprevweekyear", $nextprevweekyear);
$smarty->assign("notices", $notices);
$smarty->assign("isStudyDuration", $isStudyDuration);


/************* MAIN_CONTENT *************/
require_once("./../page_part/smarty_incut.php");
require_once("../page_part/smarty_incut.php");

$block_include_head = $smarty->fetch("./include_head/do/USMF/inc_scheduleKo.tpl"); $smarty->assign('block_include_head', $block_include_head);

$topUnionSub_subl = $smarty->fetch("./mainContent/do/union/unionSub/topUnionCabinet.tpl"); $smarty->assign('topUnionSub_subl', $topUnionSub_subl);
$topUGUSMFTeacher_tpl = $smarty->fetch("./mainContent/do/union/topUGDOUSMF_CABINET.tpl"); $smarty->assign('block_union_general', $topUGUSMFTeacher_tpl);

$trTMainField_tpl = $smarty->fetch("./mainContent/do/mainfield/scheduleKo.tpl"); $smarty->assign('block_mainField', $trTMainField_tpl);
/************ запускаем показ шаблона smarty *************/
$smarty->display("pageUSMF.tpl");
?>
