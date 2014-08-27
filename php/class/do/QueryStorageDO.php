<?php
class QSDO {    
    #>>>>> CLASS <<<<<
        ########## LEARNER ##########
            static public $getLearner = "SELECT * FROM `learners` WHERE `id` = ?i;";
            static public $getSubgroups = "SELECT * FROM `spisoklearnergroups` WHERE `learnerS_id1` = ?i;";
            static public $getSubgroupBySubject = "SELECT `subgroupS_id` FROM `spisoklearnergroups` WHERE `subgroupS_id` IN( SELECT `id` FROM `subgroups` WHERE `subjectS_id` = ?i AND `classS_id1` = ?i) AND `learnerS_id1` = ?i;";

        ########## RESPONSIBLE ##########
            static public $getResponsible = "SELECT * FROM `responsibles` WHERE `id` = ?i;";
            static public $getLearnersOfResponsible = "SELECT learnerS_id1 FROM `spisokresponsiblelearners` WHERE `responsibleS_id1` = ?i ORDER BY schoolS_id, responsibleS_id1, relationS_id ASC;";

        ########## Ko ##########
            static public $getKo = "SELECT * FROM `kos` WHERE `id` = ?i AND `deleted` = 0;";
        ######### SUBJECT ########
            static public $getSubjectIDsByName = "SELECT `id` FROM `subjects` WHERE `id` IN (SELECT `subjects_id` FROM `spisoksubjectschools` WHERE `schools_id` = ?i) AND `name` = ?s AND `deleted` = 0;";
            static public $getSubjectsBySchoolAndLevel = "SELECT `id`, `name`, `counthoursfirstsem` FROM `subjects` WHERE `id` IN( SELECT `subjects_id` FROM `spisoksubjectschools` WHERE `schools_id` = ?i AND `deleted` = 0) AND `level` = ?i AND `deleted` = 0;";
            static public $getSubjectsIDsInSubgroups = "SELECT `id`, `name` FROM `subjects` WHERE `id` IN(SELECT `subjectS_id` FROM `subgroups` WHERE `id` IN (?a))";
            static public $getSubjectsIDs = "SELECT `id`, `subjectS_id` FROM `subgroups` WHERE `id` IN (?a)";
        ########## Yoda ##########
            static public $createYoda = "CALL cYoda(?i, ?i, ?s, ?s, ?s);";
            static public $getYoda = "SELECT * FROM `yodas` WHERE `id` = ?i AND `deleted` = 0;";
            static public $setClassToYoda = "CALL setYodaClass(?i, NULL, ?i);";
            static public $getYodaByIdSocial = "SELECT * FROM `yodas` WHERE `iduser` = ?i AND `deleted` = 0";
            static public $setYodaClass = "CALL setYodaClass(?i, ?i, ?i);";
            static public $getTeachersIDByIDYoda = "SELECT `id` FROM `teachers` WHERE `iduser` = (SELECT `iduser` FROM `yodas` WHERE `id` = ?i)";


        ########## TEACHERSUBJECTS ##########
            static public $getTeacherSubjectsID = "SELECT `id` FROM `teachersubjects` WHERE `deleted` = 0 AND `teacherS_id1` = ?i AND `subjectS_id1` = ?i;";
            static public $setTeacherSubject = "CALL setTeachersubject(?i, ?i);";
            
        ########## TEACHER ##########
            /** Запрос создает преподавателя в DO. Плэйсхолдеры(6): IDuserSOC, firstname, lastname, middlename, IDschoolDO, category. Можно вернуть ID созданного препода запросом SELECT @idTeacher */
            static public $cTeacher = "CALL cTeacher(?i, ?s, ?s, ?s, ?i, ?i, @idTeacher);";
            /** Запрос удаляет преподавателя из DO. Плэйсхолдеры(1): IDteacherDO */
            static public $dTeacher = "CALL dTeacherOne(?i)";
            static public $getTeacher = "SELECT * FROM `teachers` WHERE `id` = ?i;";
            static public $getAllTeachersIDs = "SELECT `id` FROM `teachers` WHERE `deleted` = 0 AND schoolS_id = ?i;";
            static public $getAllTeachers = "SELECT * FROM `teachers` WHERE `deleted` = 0 AND schoolS_id = ?i;";
            
            // get all material (active and deleted)
            static public $getAllMaterial = "SELECT * FROM `materials` WHERE `teacherS_id1` = ?i;";
            /** Назначить материал группе. Плэйсхолдеры (2): idmaterial, idgroup*/
            static public $setMaterialGroup = "UPDATE `subgroups` SET `materialS_id` = ?i WHERE `id` = ?i;";
            /** Узнать количество групп, которым можно назначить материал конкретного учителя. Плэйсхолдеры (2): idteacher, idsubject*/
            static public $getCountAvalaibleGroupsToAssign = "SELECT COUNT(*) FROM `subgroups` WHERE `teacherS_id` = ?i AND `subjectS_id` = ?i AND deleted = 0;";
            static public $unsetMaterialGroup = "UPDATE `subgroups` SET `materialS_id` = NULL WHERE `id` = ?i;";
            static public $deactivateMaterial = "UPDATE `materials` SET `notstudy` = 1 WHERE `id` = ?i;";
            static public $activateMaterial = "UPDATE `materials` SET `notstudy` = 0 WHERE `id` = ?i;";
            static public $getCountGroupsMaterial = "SELECT COUNT(*) FROM `subgroups` WHERE `deleted` = 0 AND `materialS_id` = ?i";
            static public $getTeacherSubgroups = "SELECT id, name, subjectS_id AS idsubject, classS_id1 AS idclass, materialS_id AS idmaterial FROM `subgroups` WHERE `teacherS_id` = ?i AND deleted = 0;";
            static public $getTeacherSubgroupsMaterial = "SELECT id, name, subjectS_id AS idsubject, classS_id1 AS idclass, materialS_id AS idmaterial FROM `subgroups` WHERE `teacherS_id` = ?i AND deleted = 0 AND subjectS_id = ?i;";
            static public $getTeacherFirstSubgroup = "SELECT min(id) FROM `subgroups` WHERE `teacherS_id` = ?i;";
            static public $getTeacherActualSubjects = "SELECT `id`, `name`, `level`, `complexity` FROM `subjects` WHERE id IN (SELECT `subjectS_id1` FROM `teachersubjects` WHERE `teacherS_id1` = ?i) ORDER BY `name`, `level`, `complexity`";
            static public $getTeacherLevelsBySubject = "SELECT `level` FROM `subjects` WHERE `id` IN (SELECT `subjectS_id1` FROM `teachersubjects` WHERE `teacherS_id1` = ?i ) AND `name` = ?s AND `deleted` = 0 GROUP BY `level` ORDER BY `level` ASC";
            static public $getTeacherComplexitiesBySubjectAndLevel = "SELECT `complexity` FROM `subjects` WHERE `id` IN (SELECT `subjectS_id1` FROM `teachersubjects` WHERE `teacherS_id1` = ?i ) AND `name` = ?s AND `level` = ?s AND `deleted` = 0 ORDER BY `complexity` ASC";

            static public $getAllSubjectNames = "SELECT `name` FROM `subjects` WHERE `id` IN (SELECT `subjectS_id1` FROM `teachersubjects` WHERE `teacherS_id1` = ?i AND `deleted` = 0) GROUP BY `name`;";
            static public $getSubjectByNameAndLevel = "SELECT `id` FROM `subjects` WHERE `name` = ?s AND `level` = ?i AND `deleted` = 0;";
            static public $getSubjectByNameAndLevelAndComplexity = "SELECT `id` FROM `subjects` WHERE `name` = ?s AND `level` = ?i AND complexity = ?i AND `deleted` = 0;";
            static public $getCountSection = "SELECT COUNT(*) FROM sections WHERE subjectS_id1 = ?i AND deleted = 0;;";
            static public $getSubjectIdByNameAndLevel = "SELECT `id` FROM `subjects` WHERE `name` = ?s AND `level` = ?i;";
            static public $getIDsOfMaterialsSubject = "SELECT `id` FROM `materials` WHERE `subjectS_id1` = ?i AND `teacherS_id1` = ?i AND `deleted` = ?i";
            static public $getSubjectColor = "SELECT `color` FROM `subjects` WHERE `name` = ?s AND `deleted` = 0 GROUP BY `name`;";
            static public $getTeachersWhoCanTeachSubject = "SELECT id FROM `teachers` WHERE id IN (SELECT teacherS_id1 FROM teachersubjects WHERE `subjectS_id1` = ?i AND `deleted` = 0) AND schoolS_id = ?i";


        ########## SCHOOL ##########
            static public $getSchool = "SELECT * FROM `schools` WHERE `id` = ?i";
            static public $getActualStudyDuration = "SELECT * FROM `studyduration` WHERE `schoolS_id` = ?i AND `used` = 1;";
            static public $getCountStudyDays = "SELECT `countstudyday` FROM `schools` WHERE `id` = ?i AND `deleted` = 0;";
            static public $getSubjectsSchool = "SELECT `name` FROM `subjects` WHERE `id` IN (SELECT `subjects_id` FROM `spisoksubjectschools` WHERE `deleted` = 0 AND `schools_id` = ?i) GROUP BY `name`;";
            static public $getLevelsOfSubjectInSchool = "SELECT `level`, `complexity` FROM `subjects` WHERE `id` IN (SELECT `subjects_id` FROM `spisoksubjectschools` WHERE `deleted` = 0 AND `schools_id` = ?i AND name = ?s) ORDER BY `level`;";
            static public $getSubjectsOfSchoolForSchedulePattern = "SELECT `id`, `name`, `counthoursfirstsem` FROM `subjects` WHERE `id` IN (SELECT `subjects_id` FROM `spisoksubjectschools` WHERE `deleted` = 0 AND `schools_id` = ?i);";
            static public $getLinkedGroupsOfSchool = "SELECT * FROM `spisoksubgroups` WHERE `idschool` = ?i;";
            /** Получает коэффициенты определенной школы. Плэйсхолдеры(1): IDschoolDO */
            static public $getSchoolRatio = "SELECT * FROM `schoolratio` WHERE `idschool` = ?i";
            /** Получает инфу о предмете, изучаемом в определенной школе. Плэйсхолдеры(2): IDschoolDO, IDsubjectDO */
            static public $getSubjectInSchool = "SELECT * FROM `spisoksubjectschools` WHERE `schools_id` = ?i AND `subjects_id` = ?i AND `deleted` = 0;";


        ########## SUBGROUP ##########
            static public $getSubgroup = "SELECT `id`, `name`, `teacherS_id`, `countownsubgroup`, `subjectS_id` FROM `subgroups` WHERE `id` = ?i AND `deleted` = 0;";
            static public $getSubgroupFull = "SELECT * FROM `subgroups` WHERE `id` = ?i AND `deleted` = 0;";
            static public $updateSubgroupsTeacher = "UPDATE `subgroups` SET `teacherS_id` = ?i WHERE `id` = ?i AND `deleted` = 0";
            static public $saveTeachingSubjectToHistory = "UPDATE `spisokteachersubjectsubgroups` SET `deleted` = 1, `datefinish` = ?s WHERE `id` = ?i;";
            static public $getIDOfCurrentTeachingSubject = "SELECT `id` FROM `spisokteachersubjectsubgroups` WHERE `teachersubjectS_id1` = ?i AND `subgroupS_id1` = ?i AND `deleted` = 0;";
            static public $setNewTeacherToGroup = "INSERT INTO `spisokteachersubjectsubgroups` (`datestart`, `deleted`, `subgroupS_id1`, `teachersubjectS_id1`) VALUES (?s, 0, ?i, ?i);";
            static public $createSubgroup = "CALL cSubgroupDefault(?i, ?i, ?i, @idgroup, ?i, ?i);"; // idclass, idsubject, isdefault, idgroup, idteacher
            static public $getMinMaxIDSubgroupInClassBySubject = "SELECT MIN(`id`) as min, MAX(`id`) as max FROM `subgroups` WHERE `deleted` = 0 AND `classS_id1` = ?i AND `subjectS_id` = ?i;";
            static public $deleteSubgroup = "CALL dSubgroupOne(?i);";
            static public $deleteClass = "CALL dClassOne(?i);";
            static public $getSubgroupOfNewClass = "SELECT `id` FROM `subgroups` WHERE `classS_id1` = ?i AND `subjectS_id` = ?i AND `deleted` = 0";
            static public $getSubgroupDefaultIDBySubjectAndClass = "SELECT `id` FROM `subgroups` WHERE `subjectS_id` = ?i AND `classS_id1` = ?i AND `deleted` = 0 AND isdefault = 1;";

        ########## CLASS ##########
            static public $getClassesSchool = "SELECT * FROM `classs` WHERE `schoolS_id` = ?i AND `deleted` = 0 ORDER BY `level`, `letter`;";
            static public $createClass = "CALL cClass(?i, ?s, ?i, ?s);";
            static public $getClass = "SELECT * FROM `classs` WHERE `id` = ?i";
            static public $getClassSubjects = "SELECT `subjectS_id` AS subjectId, COUNT(*) AS countGroup FROM `subgroups` WHERE `classS_id1` = ?i AND `deleted` = 0 GROUP BY `subjectS_id`;";
            static public $getSubjects = "SELECT * FROM `subjects` WHERE `deleted` = 0";
            static public $getAllSubjectsIDsOfSchool = "SELECT `subjects_id` FROM `spisoksubjectschools` WHERE `schools_id` = ?i AND `deleted` = 0;";
            static public $getAllSubjectsNamesOfSchool = "SELECT `id`, `name` FROM `subjects` WHERE `id` IN (?a) AND `deleted` = 0 GROUP BY `name`;";
            static public $getAllCabinetsOfSchool = "SELECT `id`, `number` FROM `classrooms` WHERE `schoolS_id` = ?i;";
            static public $getAllSubjectsOfClass = "SELECT `id`, `name`, `complexity` FROM `subjects` WHERE `level` = (SELECT `level` FROM `classs` WHERE `id` = ?i) AND `id` IN (SELECT `subjects_id` FROM `spisoksubjectschools` WHERE `schools_id` = ?i);";
            static public $getSubjectTeacherMaterialStudy = "SELECT * FROM `subgroups` WHERE `classS_id1` = ?i AND deleted = 0 ORDER BY `subjectS_id` ASC;";
            static public $getTeachersArray = "SELECT * FROM `teachers` WHERE `id` IN (?a)";
            static public $getLearnersClass = "SELECT * FROM `learners` WHERE `classS_id` = ?i AND `deleted` = 0 ORDER BY `lastname` ASC, `firstname` ASC, `middlename` ASC";
            static public $getLearnersClassArray = "SELECT * FROM `learners` WHERE `id` IN (?a) AND `deleted` = 0 ORDER BY `lastname` ASC, `firstname` ASC, `middlename` ASC";
            static public $getSubgroupsClassBySubject = "SELECT * FROM `subgroups` WHERE `classS_id1` = ?i AND `subjectS_id` = ?i AND `deleted` = 0";
            static public $getSubgroupsClass = "SELECT * FROM `subgroups` WHERE `classS_id1` = ?i AND `deleted` = 0";
            static public $getSubgroupsClassIDs = "SELECT `id` FROM `subgroups` WHERE `classS_id1` = ?i AND `deleted` = 0";
            static public $getLessons = "SELECT * FROM `lessons` WHERE `subjectS_id1` = ?i AND `subgroupS_id1` IN (SELECT id FROM `subgroups` WHERE `subjectS_id` = ?i AND `classS_id1` = ?i) AND `date` BETWEEN ?s AND ?s  AND `deleted` = 0 ORDER BY `date`";
            static public $getSchoolmarkArray = "SELECT * FROM `schoolmarks` WHERE `lessonS_id` IN (?a) AND `deleted` = 0;";
            static public $getTeacherByGroups = "SELECT * FROM `teachers` WHERE `id` IN (SELECT teacherS_id FROM `subgroups` WHERE `id` IN(?a)) AND `deleted` = 0";
            static public $getClassByIdGroup = "SELECT `id`, `name` FROM `classs` WHERE `id` = (SELECT `classS_id1` FROM `subgroups` WHERE `id` = ?i) AND deleted = 0;";
            static public $getAllClassesOfSchool = "SELECT `id`, `name`, `yodaS_id`, `countlearner`, `level` FROM `classs` WHERE `deleted` = 0 AND `schoolS_id` = ?i ORDER BY `level`, `name`;";
            static public $getMinimumLetterOfClass = "SELECT MIN(`letter`) FROM `classs` WHERE `level` = ?i AND `schoolS_id` = ?i AND `deleted` = 0;";
            static public $checkClassExists = "SELECT `letter`, `name` FROM `classs` WHERE `level` = ?i AND `schoolS_id` = ?i AND `letter` = ?s AND `deleted` = 0;";
            static public $setSubjectClass = "CALL setSubjectClass(?i, ?i, ?i);";
            static public $getClassYoda = "SELECT `yodaS_id` FROM `classs` WHERE `deleted` = 0 AND `id` = ?i";

        ########## CLASSROOM ##########
            static public $getClassroom = "SELECT * FROM `classrooms` WHERE `id` = ?i;";
        ########## LESSON ##########
            static public $getSpisokLessonstypeArray = "SELECT * FROM `spisoklessontypes` WHERE `lessonS_id` IN (?a);";
            static public $getLessonsTypeLesson = "SELECT `lessontypeS_id` FROM `spisoklessontypes` WHERE `lessonS_id` = ?i ORDER BY `lessontypeS_id` ASC;";
            static public $getLesson = "SELECT * FROM `lessons` WHERE `id` = ?i";
            static public $getLessonstypeArray = "SELECT * FROM `lessontypes` WHERE `id` IN (?a)";
            static public $checkLessonByGroup = "SELECT id FROM `lessons` WHERE `subgroupS_id1` = ?i AND `id` = ?i AND `deleted` = 0";
            static public $getLessonsBySubjectname = "SELECT * FROM `lessons` WHERE `date` LIKE ?s AND `subjectS_id1` IN (?a) AND `deleted` = 0 ORDER BY `number`;";
            static public $getLessonsByCabinet = "SELECT * FROM `lessons` WHERE `date` LIKE ?s AND `classroomS_id1` = ?i AND `deleted` = 0 ORDER BY `number`;";
            static public $createLesson = "CALL cLesson(?i, ?i, ?i, ?i, ?i, ?s, @idLesson);";
            

        ########## LESSONTYPE ##########
            static public $getLessontype = "SELECT * FROM `lessontypes` WHERE `id` = ?i";
        ########## TIMETABLE ##########
            static public $getTimetableSchool = "SELECT * FROM `timetables` WHERE `schoolS_id` = ?i;";
            static public $getTimetableByClassAndDay = "SELECT * FROM `timetables` WHERE `schoolS_id` = ?i AND classs_id = ?i AND dayweek = ?i;";
            static public $getLessonsThisDayAndNumber = "SELECT `subgroupS_id1` as `idsubgroup` FROM `timetables` WHERE `schoolS_id` = ?i AND `classs_id` = ?i AND `dayweek` = ?i AND `number` = ?i;";
            static public $deleteTimetableClassDay = "DELETE FROM `timetables` WHERE `classs_id` = ?i AND `dayweek` = ?i;";
            static public $insertTimetable = "INSERT INTO `timetables` (`number`, `dayweek`, `timestart`, `timefinish`, `subgroupS_id1`, `classroomS_id1`, `schoolS_id`, `classs_id`) VALUES (?i, ?i, ?s, ?s, ?i, ?i, ?i, ?i);";
            static public $updateIsTimetableGenerated = "UPDATE `studyduration` SET `isLessons` = 1 WHERE `used` = 1 AND `schoolS_id` = ?i;";

        ##########  ##########

    #>>>>> PHP PAGES <<<<<
        ########## STUDY.PHP ##########
            static public $getSchoolsArray = "SELECT id, number, name FROM `schools` WHERE `id` IN (?a)";

        ########## JOURNAL ##########
            static public $getInfoClass = "SELECT * FROM `classs` WHERE `id` = ?i";
            
            static public $getLearnersIdSubgroupsId = "SELECT * FROM `spisoklearnergroups` WHERE `subgroupS_id` IN (?a) AND `deleted` = 0";
            static public $getLearnersOfSubgroup = "SELECT learnerS_id1, rating FROM `spisoklearnergroups` WHERE `subgroupS_id` = ?i AND deleted = 0;";
            
            static public $getLessonsAllParagraphs = "SELECT id, name, number FROM `paragraphs` WHERE `id` IN (SELECT DISTINCT paragraphS_id1 FROM `partparagraphs` WHERE `id` IN ( SELECT partparagraphS_id1 FROM `spisokpartparagraphlessons` WHERE `lessonS_id1` = ?i AND `lessonS_id1` IN (SELECT id FROM `lessons` WHERE `subgroupS_id1` = ?i ) ) AND `deleted` = 0 AND `notstudy` = 0)";
            
            static public $getHometasksEachLesson = "SELECT id, hometask FROM `lessons` WHERE `id` IN (?a) AND `deleted` = 0";

            static public $getHometaskOneLesson = "SELECT id, hometask FROM `lessons` WHERE `id` = ?i";

        ########## JOURNAL_TEACHER ##########
            # INSERT JOURNAL_TEACHER
                static public $addNewPartParagraph = "INSERT INTO `spisokpartparagraphlessons` (`lessonS_id1`, `partparagraphS_id1`)
                                                      VALUES(?i,?i);";

                static public $addMark = "INSERT INTO `schoolmarks` (`value`, spisoklessontypeS_id1, learnerS_id1, lessonS_id) VALUES (?i,?i,?i,?i)";

                static public $addAbsent = "INSERT INTO `schoolmarks` (spisoklessontypeS_id1, learnerS_id1, absentS_id, lessonS_id) VALUES (?i,?i,?i,?i)";

                static public $addNewLessonType = "INSERT INTO `spisoklessontypes` (`lessonS_id`, `lessontypeS_id`) VALUES(?i,?i);";

            # DELETE JOURNAL_TEACHER
                static public $deleteParagraph = "DELETE FROM `spisokpartparagraphlessons` WHERE `lessonS_id1` = ?i AND `partparagraphS_id1` IN
                                                    (SELECT id FROM `partparagraphs` WHERE `paragraphS_id1` = ?i )";

                static public $deleteLessonType = "DELETE FROM `spisoklessontypes` WHERE `id` = ?i";


            # UPDATE JOURNAL_TEACHER
                static public $addHometaskInLessonsTable = "UPDATE `lessons` SET `hometask` = ?s WHERE `id` = ?i";

                static public $deleteMark = "UPDATE `schoolmarks` SET `deleted` = 1, `datedeleted` = now() WHERE `spisoklessontypeS_id1` = ?i AND `learnerS_id1` = ?i";

                static public $updateMark = "UPDATE `schoolmarks` SET  `value` = ?i WHERE `spisoklessontypeS_id1` = ?i AND `learnerS_id1` = ?i";

                static public $updateMarkOnAbsent = "UPDATE `schoolmarks` SET `value` = ?i, `absentS_id` = ?i WHERE `spisoklessontypeS_id1` = ?i AND `learnerS_id1` = ?i";

                static public $updateMarkOnAbsentDelete = "UPDATE `schoolmarks` SET `absentS_id` = ?i WHERE `spisoklessontypeS_id1` = ?i AND `learnerS_id1` = ?i";

                static public $updateCountAbsent = "UPDATE `spisoklearnergroups` SET `countabsent` = `countabsent` + 1 WHERE `learnerS_id1` = ?i AND `subgroupS_id` = ?i";

                static public $updateCountAbsentSubstr = "UPDATE `spisoklearnergroups` SET `countabsent` = `countabsent` - 1 WHERE `learnerS_id1` = ?i AND `subgroupS_id` = ?i";

                static public $updateLessonType = "UPDATE `spisoklessontypes` SET  `lessontypeS_id` = ?i WHERE `id` = ?i";

                static public $summRating = "UPDATE `spisoklearnergroups` SET `rating` = `rating` + ?i WHERE `learnerS_id1` = ?i AND `subgroupS_id` = ?i";

                static public $substrRating = "UPDATE `spisoklearnergroups` SET `rating` = `rating` - ?i WHERE `learnerS_id1` = ?i AND `subgroupS_id` = ?i";

                static public $substrCountAbsent = "UPDATE `spisoklearnergroups` SET `countabsent` = `countabsent` - 1 WHERE `subgroupS_id` = ?i AND `learnerS_id1` = ?i";

                static public $substrCountMarkSumMark = "UPDATE `spisoklearnergroups` SET `countmark` = `countmark` - 1, `summark` = `summark` - ?i WHERE `subgroupS_id` = ?i AND `learnerS_id1` = ?i";

                static public $updateAverageMark = "UPDATE `spisoklearnergroups` SET `averagemark` = `summark` / `countmark` WHERE `subgroupS_id` = ?i AND `learnerS_id1` = ?i";
                                                    
            # SELECT JOURNAL_TEACHER
                static public $getTeacherSubjects = "SELECT id as subjectId, name FROM `subjects` WHERE `id` IN (SELECT subjectS_id FROM `subgroups` WHERE `teacherS_id` = ?i)";

                static public $getClassesTeacher = "SELECT id as classId, letter, level FROM `classs` WHERE id IN (SELECT DISTINCT classS_id1 FROM `subgroups` WHERE `teacherS_id` = ?i) AND schoolS_id = ?i AND `deleted` = 0";

                static public $getSubgroupsTeacher = "SELECT id, name as groupName, classS_id1 as classId, subjectS_id as subjectId FROM `subgroups` WHERE `teacherS_id` = ?i AND classS_id1 IN
                                                        (SELECT id FROM `classs` WHERE `schoolS_id` = ?i) AND `deleted` = 0";

                static public $getSubjectsName = "SELECT id as subjectId, name FROM `subjects` WHERE id IN (SELECT subjectS_id FROM `subgroups` WHERE `teacherS_id` = ?i) AND `deleted` = 0";

                # Аналог - $getSubgroupsClass
                # 2 вариант
            // static public $getSubgroupClassTeacher = "SELECT * FROM `subgroups` WHERE `subgroups`.`classS_id1` = ?i AND `subgroups`.`subjectS_id` = ?i AND `subgroups`.`teacherS_id` = ?i AND `subgroups`.`id` = ?i";

                static public $getSubgroupClassTeacher = "SELECT * FROM `subgroups` WHERE `id` = ?i AND `deleted` = 0";

                # Аналог - $getLessons
                static public $getLessonsOneGroupTeacher = "SELECT * FROM `lessons` WHERE `subjectS_id1` = ?i AND `subgroupS_id1` IN (SELECT id FROM `subgroups` WHERE `subjectS_id` = ?i AND `classS_id1` = ?i) AND `date` BETWEEN ?s AND ?s AND `subgroupS_id1` = ?i AND `deleted` = 0 ORDER BY `date`";

                static public $getDateLesson = "SELECT date FROM `lessons` WHERE id = ?i";

                static public $getSectionsInSubject = "SELECT id, name FROM `sections`
                                                    WHERE `subjectS_id1` = ?i AND `deleted` = 0";

                // static public $getParagraphsInSection = "SELECT id, name, number, notstudy FROM `paragraphs` WHERE `sectionS_id1` = ?i";

                static public $getParagraphsInSection = " SELECT id, name, number, notstudy FROM `paragraphs` WHERE `sectionS_id1` = ?i AND `materialS_id1` IN (SELECT `materialS_id` FROM `subgroups` WHERE `subgroups`.`id` = ?i) AND `deleted` = 0";

                static public $getPartParagraphsInParagraph = "SELECT id, name, number FROM `partparagraphs` WHERE `paragraphS_id1` = ?i AND `deleted` = 0";

                static public $getAllPartParagraphsOneParagraph = "SELECT id FROM `partparagraphs` WHERE `paragraphS_id1` = ?i AND `deleted` = 0";

                static public $getAllParagraphsOneSection = "SELECT id FROM `partparagraphs` WHERE `paragraphS_id1` IN
                                                            (SELECT id FROM `paragraphs` WHERE `sectionS_id1` = ?i )";

                static public $checkIssetMarkInTable = "SELECT `value`, `id` FROM `schoolmarks` WHERE spisoklessontypeS_id1 = ?i AND learnerS_id1 = ?i AND `deleted` = 0";

                static public $getAverageResult = "SELECT `countmark`, `averagemark`, `countabsent` FROM `spisoklearnergroups` WHERE subgroupS_id = ?i AND learnerS_id1 = ?i";

                static public $getAllLessonTypes = "SELECT * FROM `lessontypes`";

                static public $getCurrentLessonType = "SELECT * FROM `lessontypes` WHERE id =
                                                        (SELECT `lessontypeS_id` FROM `spisoklessontypes` WHERE id = ?i )";

                static public $getOneLessonType = "SELECT * FROM `lessontypes` WHERE id = ?i";

                static public $getStepSchool = "SELECT `step` FROM `schools` WHERE `id` = ?i";

                static public $getSummRating = "SELECT `rating` FROM `spisoklearnergroups` WHERE `learnerS_id1` = ?i AND `subgroupS_id` = ?i";

                static public $checkRealTeacher = "SELECT `teacherS_id` FROM `subgroups` WHERE classS_id1 = ?i AND subjectS_id = ?i AND `id` = ?i";

                static public $checkCountLessonTypesCurrentLesson = "SELECT count(*) as countLessonTypes FROM `spisoklessontypes` WHERE `lessonS_id` = ?i";

        ########## JOURNAL_RESPONSIBLE ##########
            # INSERT JOURNAL_RESPONSIBLE

            # DELETE JOURNAL_RESPONSIBLE

            # UPDATE JOURNAL_RESPONSIBLE

            # SELECT JOURNAL_RESPONSIBLE
                static public $getStudyDurationInfo = "SELECT * FROM studyduration WHERE schoolS_id = ?i AND `used` = 1";

                static public $getSubjectsIdArray = "SELECT `subjectS_id` FROM `subgroups` WHERE id IN (SELECT `subgroups_id` FROM `spisoklearnergroups` WHERE `learnerS_id1` = ?i) ORDER BY `id`";

                static public $getSubjectsOneLearner = "SELECT `id`, `name` FROM `subjects` WHERE `id` IN (SELECT `subjectS_id` FROM `subgroups` WHERE `id` IN (SELECT `subgroupS_id` FROM `spisoklearnergroups` WHERE `learnerS_id1` = ?i) ) AND `deleted` = 0";

                static public $getMaxCountMarks = "SELECT max(`value`) as valueMax, count(*) as count FROM `schoolmarks` WHERE `learnerS_id1` = ?i AND `deleted` = 0 AND `lessonS_id` in (SELECT id FROM `lessons` WHERE DATE_FORMAT(`date`,'%Y.%m.%d')= ?s AND `subjectS_id1` = ?i) GROUP BY `lessonS_id`";

                static public $checkLessonByDate = "SELECT id FROM `lessons` WHERE DATE_FORMAT(`date`,'%Y.%m.%d')= ?s AND `subjectS_id1` = ?i";

                static public $getAvgCntAbsEachSubject = "SELECT `subgroupS_id`, `countmark`, `countabsent`, `averagemark` FROM `spisoklearnergroups` WHERE `learnerS_id1` = ?i";

                static public $getIdSubgroupIdSubject = "SELECT `id` AS idSubgroup, `subjectS_id` AS idSubject FROM `subgroups` WHERE `id` IN (SELECT `subgroupS_id` FROM `spisoklearnergroups` WHERE `learnerS_id1` = ?i)";

                static public $getMarksAndTypesLessons ="SELECT `value`, `spisoklessontypeS_id1` FROM `schoolmarks` WHERE `learnerS_id1` = ?i AND `lessonS_id` = ?i AND `deleted` = 0";

                static public $getSpisokLessonTypesIdLessonTypesId = "SELECT `id`, `lessontypeS_id` FROM `spisoklessontypes` WHERE `lessonS_id` = ?i AND `id` IN (?a)";

                static public $getLessonTypesNameAndId = "SELECT `id`, `description` FROM `lessontypes` WHERE id IN (SELECT `lessontypeS_id` FROM `spisoklessontypes` WHERE `lessonS_id` = ?i)";
                

        ########## JOURNAL_KO ##########
            # INSERT JOURNAL_KO

            # DELETE JOURNAL_KO
                static public $deleteUserFromResponsibleTable = "DELETE FROM `responsibles` WHERE `id` = ?i";

                static public $deleteFromSpisokResponsibleLearnerTable = "DELETE FROM `spisokresponsiblelearners` WHERE `responsibleS_id1` = ?i AND `learnerS_id1` = ?i";

            # UPDATE JOURNAL_KO
                static public $incrementCountLearner = "UPDATE `responsibles` SET `responsibles`.`countlearner`= `responsibles`.`countlearner` + 1 WHERE `id` = ?i";

                static public $decrementCountLearner = "UPDATE `responsibles` SET `responsibles`.`countlearner`= `responsibles`.`countlearner` - 1 WHERE `id` = ?i";

            # SELECT JOURNAL_KO
                static public $getClassOneSchool = "SELECT `id`, `level`, `letter`, `name` FROM `classs` WHERE `schoolS_id` = ?i AND `deleted` = 0 ORDER BY `letter`";

                static public $getSubgroupsIdClass = "SELECT `id` as subgroupId, `subjectS_id` FROM `subgroups` WHERE `classS_id1` = ?i AND `deleted` = 0";

                static public $getSubjectsNameOneSubgroup = "SELECT `id`, `name` FROM `subjects` WHERE id IN (SELECT `subjectS_id` FROM `subgroups` WHERE `id` = ?i AND `deleted` = 0) AND `deleted` = 0";

                static public $checkIdSubgroupInIdLearner = "SELECT `averagemark`, `countmark`, `countabsent`, `countabsentreason` FROM `spisoklearnergroups` WHERE `subgroupS_id` = ?i AND `learnerS_id1` = ?i AND `deleted` = 0";

                static public $getParentsOneLearner = "SELECT `id`, concat(lastname,' ',firstname,' ',middlename) AS fio FROM `responsibles` WHERE id IN (SELECT `responsibleS_id1` FROM `spisokresponsiblelearners` WHERE `learnerS_id1` = ?i)";


                
                static public $checkUserInResponsibleTable = "SELECT `id` FROM `responsibles` WHERE `iduser` = ?i AND `schoolS_id` = ?i";

                static public $getProfileIdLearner = "SELECT `iduser` FROM `learners` WHERE `id` = ?i";



                static public $getCountLearnersInResponsibleTable = "SELECT `countlearner` FROM `responsibles` WHERE `id` = ?i";

                static public $checkUserInSpisokResponsibleLearnerTable = "SELECT `id` FROM `spisokresponsiblelearners` WHERE `learnerS_id1` = ?i AND `responsibleS_id1` = ?i";


        ########## MATERIAL ########## 
            // used by Material Class, not manually
            static public $getMaterial = "SELECT * FROM `materials` WHERE `id` = ?i";
            // static public $getSectionsMaterial = "SELECT * FROM `sections` WHERE `id` in (SELECT `paragraphs`.`sectionS_id1` FROM `paragraphs` WHERE `paragraphs`.`materialS_id1`=?i GROUP BY `paragraphs`.`sectionS_id1`)";
            static public $getSectionsMaterial = "SELECT * FROM `sections` WHERE `subjectS_id1` IN (SELECT subjectS_id1 FROM `materials` WHERE `id` = ?i);";
            static public $getParagraphsSection = "SELECT * FROM `paragraphs` WHERE `materialS_id1` = ?i AND `sectionS_id1` = ?i AND `deleted` = ?i;";
            static public $getParagraphsSection_Study = "SELECT * FROM `paragraphs` WHERE `materialS_id1` = ?i AND `sectionS_id1` = ?i AND `deleted` = ?i AND `notstudy` = ?i;";
            //used manually
            static public $getClassesOfMaterial = "SELECT id, name FROM `classs` WHERE `id` IN (SELECT classS_id1 FROM `subgroups` WHERE `materialS_id` = ?i AND `deleted` = 0)";
            //not used
            static public $getParagraphsSectionDeleted = "SELECT * FROM `paragraphs` WHERE `materialS_id1` = ?i AND `sectionS_id1` = ?i AND `deleted` = 0;";
            static public $getParagraphsAllSections = "SELECT * FROM `paragraphs` WHERE `materialS_id1` = ?i AND `deleted` = 0 AND `notstudy` = 0;";
            static public $getNameOneSection = "SELECT `name` FROM `sections` WHERE `id` = ?i AND `deleted` = 0;";
            static public $getMaterialByIdGroup = "SELECT `materialS_id` FROM `subgroups` WHERE `id` = ?i";
            static public $createMaterial = "CALL cMaterialTeacher(NULL, ?i, ?i, 1, 1, NULL);";
            static public $getTopicsSectionNames = "SELECT `name` FROM `topicssection` WHERE `sections_id` = ?i ORDER BY `number`;";
            static public $getRequirementsSection = "SELECT * FROM `activitiessection` WHERE `idSection` = ?i";

        ########## SCHEDULE ##########
            static public $getLessonsArray = "SELECT * FROM `lessons` WHERE `date` LIKE ?s AND `subgroupS_id1` IN(?a) AND `deleted` = 0 ORDER BY `number`;";
            static public $getSubjectById = "SELECT * FROM `subjects` WHERE `id` = ?i;";
            static public $getLessonPartParagraphs = "SELECT id, name, number FROM `partparagraphs` WHERE `id` IN ( SELECT partparagraphS_id1 FROM `spisokpartparagraphlessons` WHERE `lessonS_id1` = ?i) AND `paragraphS_id1` = ?i";
            static public $getLessonParagraphs = "SELECT id, name, number FROM `paragraphs` WHERE `id` IN (SELECT DISTINCT paragraphS_id1 FROM `partparagraphs` WHERE `id` IN ( SELECT partparagraphS_id1 FROM `spisokpartparagraphlessons` WHERE `lessonS_id1` = ?i) AND `deleted` = 0) AND notstudy = 0";
            static public $getLessonParagraphsTeacher = "SELECT id, name, number, notstudy FROM `paragraphs` WHERE `id` IN (SELECT DISTINCT paragraphS_id1 FROM `partparagraphs` WHERE `id` IN ( SELECT partparagraphS_id1 FROM `spisokpartparagraphlessons` WHERE `lessonS_id1` = ?i) AND `deleted` = 0)";
            static public $getLessonsForTeacher = "SELECT * FROM `lessons` WHERE `deleted` = 0 AND `teacherS_id1` = ?i AND `date` LIKE ?s ORDER BY `number`;";


        ########## PARAGRAPH ##########
            static public $getParagraph = "SELECT * FROM `paragraphs` WHERE `id` = ?i";
            static public $getSection = "SELECT * FROM `sections` WHERE `id` = ?i";
            static public $getPartParagraphArr = "SELECT * FROM `partparagraphs` WHERE `paragraphS_id1` = ?i AND `deleted` = 0 ORDER BY `number`";
            static public $getPartParagraph = "SELECT * FROM `partparagraphs` WHERE `id` = ?i AND `deleted` = 0";
            static public $getTeacherFromParagraph = "SELECT * FROM `teachers` WHERE `id` = (SELECT `teacherS_id1` FROM `materials` WHERE `id` =?i)";
            static public $getSubjectNameColor = "SELECT id, name, color FROM `subjects` WHERE `id` = ?i";
            static public $getNumbersOfPartParagraphs = "SELECT number FROM `partparagraphs` WHERE `deleted` = 0 and `paragraphS_id1` = (SELECT paragraphS_id1 FROM `partparagraphs` WHERE `id` = ?i )  ORDER BY `number`";
            static public $getSoftQuestions = "SELECT COUNT(*) FROM `questions` WHERE `partparagraphS_id1` IN (SELECT id FROM `partparagraphs` WHERE `paragraphS_id1` = ?i ) AND `complexity` = 1 AND `extension` IS NULL AND `deleted` = 0";
            static public $getMediumQuestions = "SELECT COUNT(*) FROM `questions` WHERE `partparagraphS_id1` IN (SELECT id FROM `partparagraphs` WHERE `paragraphS_id1` = ?i ) AND `complexity` = 2 AND `extension` IS NULL AND `deleted` = 0";
            static public $getHardQuestions = "SELECT COUNT(*) FROM `questions` WHERE `partparagraphS_id1` IN (SELECT id FROM `partparagraphs` WHERE `paragraphS_id1` = ?i ) AND `complexity` = 3 AND `extension` IS NULL AND `deleted` = 0";
            static public $addParagraphToSection = "CALL insParagraphInSection(?s, ?i, ?i);";
            static public $changeStateParagraph = "CALL changeStudyStateParagraph(?i, ?s);";
            static public $delParagraph = "CALL dParagraphOne(?i);";
            static public $changeNameParagraph = "UPDATE `paragraphs` SET `name`= ?s WHERE `deleted` = 0 AND `id` = ?i;";
            
            
            

        ########## TEST ##########
            static public $getQuestionsArr = "(SELECT * FROM `questions` WHERE `partparagraphS_id1` IN (SELECT id FROM `partparagraphs` WHERE `paragraphS_id1` = ?i) AND `complexity` = 1 AND `valid` = 1  AND `extension` IS NULL AND `deleted` = 0 ORDER BY RAND() LIMIT 0,3 ) UNION (SELECT * FROM `questions` WHERE `partparagraphS_id1` IN (SELECT id FROM `partparagraphs` WHERE `paragraphS_id1` = ?i) AND `complexity` = 2 AND `valid` = 1 AND `extension` IS NULL AND `deleted` = 0 ORDER BY RAND() LIMIT 0,2) UNION (SELECT * FROM `questions` WHERE `partparagraphS_id1` IN (SELECT id FROM `partparagraphs` WHERE `paragraphS_id1` = ?i) AND `complexity` = 3 AND `valid` = 1 AND `extension` IS NULL AND `deleted` = 0 ORDER BY RAND() LIMIT 0,1);
           ";
            static public $getQuestionExtensionArr = "SELECT text FROM `questions` where `extension` = ?i";
            static public $getAnswersArr = "SELECT * FROM `answers` WHERE `questionS_id1` = ?i AND `deleted` = 0 ORDER BY RAND()";
            static public $getCorrectAnswersArr = "SELECT * FROM `answers` WHERE `questionS_id1` IN (?a) AND `right` = 1";
            static public $getComplexity = "SELECT complexity FROM `questions` where `id` = ?i";
            static public $getCountTry = "SELECT COUNT(learnerS_id1) as countTry FROM `tests` WHERE `learnerS_id1` = ?i AND `paragraphS_id1` = ?i";
            static public $getIdSubgroup = "SELECT `id` FROM `subgroups` WHERE `subjectS_id` = ?i AND `materialS_id` = ?i";
            static public $setMark = "INSERT INTO `tests`(`mark`, `datetime`, `paragraphS_id1`, `learnerS_id1`, `idsubject`, `idmaterial`) VALUES (?i, ?s, ?i, ?i, ?i, ?i);";
            static public $getMarksByDaysNSubjects = "SELECT idsubject, DATE(datetime) as ddate, max(mark) as maxmark, count(*) as counttry
                                                        FROM `tests`
                                                        WHERE `learnerS_id1` = ?i AND MONTH(datetime) = ?i AND YEAR(datetime) = ?i
                                                        GROUP BY `idsubject`, `ddate`
                                                        ORDER BY `idsubject`, `ddate`";
            static public $getParagraphsTestsByDay = "SELECT id, mark, paragraphS_id1 FROM `tests` WHERE `datetime` LIKE ?s AND `learnerS_id1` = ?i AND `idsubject` = ?i";
            static public $getLearnersTestsByDay = "SELECT learnerS_id1 as idlearner, DATE(datetime) as ddate, max(mark) as maxmark, count(*) as counttry
                                                    FROM `tests`
                                                    WHERE `idmaterial` = ?i AND MONTH(datetime) = ?i AND YEAR(datetime) = ?i 
                                                    GROUP BY `idlearner`, `ddate` 
                                                    ORDER BY `idlearner`, `ddate`";
            static public $getTestsOneLearner = "SELECT `id`, `mark`, `datetime` FROM `tests` WHERE `paragraphS_id1` = ?i AND `learnerS_id1` = ?i ORDER BY `datetime` DESC LIMIT 5";
            static public $getMaxMarkOfAllTests = "SELECT max(mark) FROM tests WHERE `learnerS_id1` = ?i AND `paragraphS_id1` = ?i";
            static public $getRatingByOwnLearnerAndSubgroups = "SELECT `rating`,`subgroupS_id`  FROM `spisoklearnergroups` WHERE `subgroupS_id` in (?a) AND `learnerS_id1` = ?i";

//            static public $massAllLearnersAllSubgroups= "SELECT `subgroupS_id`, `learnerS_id1`, `rating` FROM `admin_do`.`spisoklearnergroups` WHERE `subgroupS_id` in (SELECT `subgroupS_id` FROM `admin_do`.`spisoklearnergroups` WHERE `learnerS_id1` = ?i)";

            static public $getPositionLearnerFromEachSubgroup = "SELECT num FROM ( SELECT `subgroupS_id`, `learnerS_id1`, `rating`, @num := @num +1 as num FROM `spisoklearnergroups` intable WHERE `subgroupS_id` = ?i ORDER BY rating DESC )t WHERE `learnerS_id1` = ?i";

            static public $getLearnersTests = "SELECT learnerS_id1 as idlearner, DATE(datetime) as ddate, max(mark) as maxmark, count(*) as counttry
FROM `tests`
WHERE `idmaterial` = ?i AND MONTH(datetime) = ?i AND YEAR(datetime) = ?i AND learnerS_id1 = ?i
GROUP BY `idlearner`, `ddate`  
ORDER BY `idlearner`, `ddate`";
            
            static public $getParagraphsTestsByDayToTeacher = "SELECT id, mark, paragraphS_id1 FROM `tests`
                                                                WHERE `learnerS_id1` = ?i
                                                                AND `idsubject` = ?i
                                                                AND `idmaterial` = ?i
                                                                AND `datetime` LIKE ?s";

            ## vladyc9 ##
            static public $getIdSection = "SELECT sectionS_id1 FROM paragraphs WHERE id = ?i";

            static public $getCountParagraphsOneSection = "SELECT COUNT(id) FROM paragraphs WHERE sectionS_id1 = ?i
                                                            AND notstudy = 0 AND valid = 1";

            static public $getCountTestsOneLearnerInSection = "SELECT COUNT(DISTINCT paragraphS_id1) FROM tests
                                                               WHERE learnerS_id1 = ?i AND idmaterial = ?i
                                                               AND paragraphS_id1 IN (
                                                               SELECT id FROM paragraphs WHERE sectionS_id1 = ?i
                                                               AND notstudy = 0 AND valid = 1)";
            ## vladyc9 ##

        ########## ADD_QUEST ##########
            # INSERT ADD_QUEST
                static public $addAnswer = "INSERT INTO `answers` (`text`, `questionS_id1`) 
                                            VALUES(?s, ?i);";

                static public $addQuestion = "INSERT INTO `questions` (`text`, `partparagraphS_id1`, `complexity`) 
                                            VALUES(?s, ?i, ?i);";

                static public $addQuestionExtension = 'INSERT INTO `questions` (`text`, `partparagraphS_id1`, `complexity`, `extension`) 
                                            VALUES(?s, ?i, ?i, ?i);';
            # DELETE ADD_QUEST
                // static public $deleteAnswer = "DELETE FROM `answers` WHERE `answers`.`id` = ?i";
                static public $deleteExtensionPartsOfQuestion = 'DELETE FROM `questions` WHERE `extension` = ?i';

                static public $deleteSomeExtensionsPartsOfQuestions = 'DELETE FROM `questions` WHERE `extension` = ?i AND `id` = ?i';

            # UPDATE ADD_QUEST
                // static public $updateRightFalseAnswer = "UPDATE `answers` SET `right` = not `right` WHERE `id` = ?i";

                static public $updateComplexityTextQuestion = "UPDATE `questions` SET `text` = ?s, `complexity` = ?i WHERE `id` = ?i";

                static public $updateTextAnswer = "UPDATE `answers` SET `text` = ?s WHERE `id` = ?i";

                // static public $updateComplexityTextQuestionForExtensionQuestion = 'UPDATE `questions` SET `text` = ?s, `complexity` = ?i WHERE `id` = ?i';

            # SELECT ADD_QUEST
                static public $getQuestionsEachPartParagraph = "SELECT * FROM `questions` WHERE `partparagraphS_id1` = ?i 
                                                                AND `deleted` = 0";

                static public $getAnswersCurrentQuestion = "SELECT * FROM `answers` WHERE `questionS_id1` = ?i AND `deleted` = 0";

                static public $getValidQuestion = "SELECT `valid` FROM `questions` WHERE `id`= ?i";

                static public $getCountAnswerComplexityValidQuestion = "SELECT `countanswer`, `complexity`, `valid` FROM `questions` WHERE `id`= ?i";

                static public $getIdRootAndIdExtensionOneQuestion = 'SELECT id FROM questions WHERE id = ?i OR extension = ?i';
                
                static public $getQuestion = "SELECT * FROM `questions` WHERE `id` = ?i;";

            # CALL ADD_QUEST
                static public $checkValidQuestion = "CALL checkValidQuestion(?i);";

                static public $deleteAnswer = "CALL switch";

    static public $getRelations = "SELECT * FROM `relations`";
}
?>
