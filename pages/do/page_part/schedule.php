<?php
// обьявление нужных переменных
$lessons = array();
if(!$sysErrLearnerID){
    // SET COUNT OF WORKING DAYS
    $r = $DB_DO->getRow(QSDO::$getCountStudyDays, $Learner->idSchool);
    $COUNT_WORK_DAYS = $r["countstudyday"];

    // SET START EDUCATION AND END EDUCATION OF SEMESTRE
    $r = $DB_DO->getRow(QSDO::$getActualStudyDuration, $Learner->idSchool);
    if(!$r){
        $isStudyDuration = false;
        $notices['MW_SCHEDULE_LEARNER_HAS_NO_STUDYDURATION']["type"] = 3;
        $notices['MW_SCHEDULE_LEARNER_HAS_NO_STUDYDURATION']["messages"][] = array(
            "title" => MW_SCHEDULE_LEARNER_HAS_NO_STUDYDURATION_TITLE,
            "text" => MW_SCHEDULE_LEARNER_HAS_NO_STUDYDURATION_TEXT
        );
    } else {
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

        // GET LEARNER SUBGROUPS (only IDs)
        $learnerSubgroups = $Learner->getSubgroup();
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
                $lessonsByDayFromDb = $DB_DO->getAll(QSDO::$getLessonsArray, $dateForQuery, $learnerSubgroups);

                //get last lesson number (needed for right css displaying)
                $lastlesson = $lessonsByDayFromDb[count($lessonsByDayFromDb) - 1]["number"];

                if($lessonsByDayFromDb){
                    foreach ($lessonsByDayFromDb as $lessonByDayFromDb) {
                        $idSubject = $lessonByDayFromDb["subjectS_id1"];
                        $subjectInfo = $DB_DO->getRow(QSDO::$getSubjectById, $idSubject);
                        if($lessonByDayFromDb["number"] == $lastlesson) { $islast = true; } else { $islast = false; }

                        // get paragraphs of some lesson by id
                        $paragraphsFromDb = $DB_DO->getAll(QSDO::$getLessonParagraphs, $lessonByDayFromDb["id"]);

                        // get classroom by id
                        $classroomFromDB = $DB_DO->getRow(QSDO::$getClassroom, $lessonByDayFromDb["classroomS_id1"]);
                        $numberclassroom = $classroomFromDB["number"];

                        //form array of paragraphs
                        foreach($paragraphsFromDb as $paragraph) {
                            // get partparagraphs of paragraphs, studied at this lesson
                            $partparagraphsFromDb = $DB_DO->getAll(QSDO::$getLessonPartParagraphs, $lessonByDayFromDb["id"], $paragraph["id"]);
                            foreach($partparagraphsFromDb as $partparagraph) {
                                $partparagraphs[] = array(
                                    "id" => $partparagraph["id"],
                                    "number" => $partparagraph["number"]
                                );
                            }

                            $paragraphs[] = array(
                                "id" => $paragraph["id"],
                                "number" => $paragraph["number"],
                                "name" => $paragraph["name"],
                                "partparagraphs" => $partparagraphs    
                            );
                            unset($partparagraphs);
                        }
                        $lessons[$lessonByDayFromDb["number"]][$lessonByDayFromDb["subgroupS_id1"]] = array(
                            "id" => $lessonByDayFromDb["id"],
                            "number" => $lessonByDayFromDb["number"],
                            "hometask" => $lessonByDayFromDb["hometask"],
                            "cabinet" => $numberclassroom,
                            "idsubgroup" => $lessonByDayFromDb["subgroupS_id1"],
                            "color" => $subjectInfo["color"],
                            "idsubject" => $idSubject,
                            "subjectname" => $subjectInfo["name"],
                            "islast" => $islast,
                            "paragraphs" => $paragraphs
                        );
                        unset($paragraphs);
                    }
                    for($i = 1; $i < intval($lastlesson); $i++){
                        if($lessons[$i]){

                        } else {
                            $lessons[$i][0] = array("idsubgroup" => 0, "cabinet" => "", "color" => "#e6e6e6", "subjectname" => "Нет урока");
                        }
                    }
                    ksort($lessons);
                }

            }
        //    print_r($lessons);

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
    }
}
$smarty->assign("days", $days);
$smarty->assign("isfirstweek", $isfirstweek);
$smarty->assign("islastweek", $islastweek);
$smarty->assign("nameduration", $NAME_DURATION);
$smarty->assign("nextprevweekyear", $nextprevweekyear);
$smarty->assign("notices", $notices);
$smarty->assign("isStudyDuration", $isStudyDuration);
$smarty->assign("sysErrLearnerID", $sysErrLearnerID);

/************* MAIN_CONTENT *************/
require_once("./../page_part/smarty_incut.php");
$block_include_head = $smarty->fetch("./include_head/do/USMF/inc_schedule.tpl"); $smarty->assign('block_include_head', $block_include_head);

$topUnionSub_subl = $smarty->fetch("./mainContent/do/union/unionSub/topUnionStudy.tpl"); $smarty->assign('topUnionSub_subl', $topUnionSub_subl);
$topUGUSMF_tpl = $smarty->fetch("./mainContent/do/union/topUGDOUSMF_STUDY.tpl"); $smarty->assign('block_union_general', $topUGUSMF_tpl);

$scheduleMainField_tpl = $smarty->fetch("./mainContent/do/mainfield/schedule.tpl"); $smarty->assign('block_mainField', $scheduleMainField_tpl);
/************ запускаем показ шаблона smarty *************/
$smarty->display("pageUBMF.tpl");
?>
