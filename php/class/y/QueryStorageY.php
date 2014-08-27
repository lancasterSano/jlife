<?php
class QSY{
    /** Создает учебную группу в системе Y. Плэйсхолдеры(18): 
     *  IDsubgroupDO, absolutecomplexity, IDschoolDO, IDsubjectDO
     *  kpriority, kreviewlag, kactivestudydurationlag, 
     *  kreviewactual, kactivestudydurationactual, kactivesectionactual, 
     *  kreviewadvance, kactivestudydurationadvance, kactivesectionadvance, kexternadvance,
     *  kteacherpopular, kteacherunpopular, kborrowedpopular, kborrowedunpopular
     */
    static public $createSubgroup = "CALL cSubgroup(?i, ?i, ?i, ?i, ?i, ?i, ?i, ?i,
        ?i, ?i, ?i, ?i, ?i, ?i, ?i, ?i, ?i, ?i);";
    /**
     * Создает ученика в системе Y. Плэйсхолдеры(1):
     * IDlearnerDO
     * @returns IDlearnerY(SELECT @idLearner)
     */
    static public $createLearner = "CALL cLearner(?i, @idLearner);";
    /**
     * Добавляет указанного ученика в указанную группу в системе Y. Плэйсхолдеры(2): 
     * IDlearnerY, IDsubgroupY
     */
    static public $addLearnerToGroup = "CALL addLearnerToGroup(?i, ?i);";
    /** Получаем идентификатор группы из Y по идентификатору группы из DO. Плэйсхолдеры(1): IDsubgroupDO */
    static public $getSubgroupYIDFromSubgroupDOID = "SELECT `id` FROM `y_subgroup` WHERE `idsubgroupdo` = ?i;";
    /** Добавляет вопрос в Y. Плэйсхолдеры(8): IDownerDO, IDquestionDO, IDparagraphDO, IDsectionDO, IDmaterialDO, relcomplexity, abscomplexity, IDsubjectDO */
    static public $addQuestionMaterial = "CALL addQuestionMaterial(?i, ?i, ?i, ?i, ?i, ?i, ?i, ?i);";
    /** Получает вопрос из Y по вопросу из DO. Плэйсхолдеры(1): IDquestionDO */
    static public $getQuestionTextY = "SELECT * FROM `y_questiontext` WHERE `idquestiondo` = ?i;";
    /** Устанавливает выбранный материал указанной группе. Плэйсхолдеры(3): IDsubgroupY, IDmaterialDO, IDownerDO */
    static public $setMaterialGroup = "CALL setMaterialGroup(?i, ?i, ?i);";
    /** Завершает обучение указанного ученика в указанной группе. Плэйсхолдеры(2): IDlearnerY, IDsubgroupY */
    static public $deleteLearnerFromGroup = "CALL deleteLearnerFromGroup(?i, ?i);";
    /** Получаем идентификатор ученика из Y по идентификатору ученика из DO. Плэйсхолдеры(1): IDlearnerDO */
    static public $getLearnerYIDFromLearnerDOID = "SELECT `id` FROM `y_learner` WHERE `idlearnerdo` = ?i;";
    /** Удаляет ученика из системы Y. Плэйсхолдеры(1): IDlearnerY  */
    static public $deleteLearner = "CALL deleteLearner(?i);";
    /** Добавляет запись в таблицу секций, которые будут открываться учебным группам. Плэйсхолдеры(5): IDsectionDO, numbersectionDO, IDsubgroupY, IDsubjectDO, date */
    static public $addSectionSubgroupAvalaible = "INSERT INTO `y_sectiontiming`(`idsectiondo`, `numbersectiondo`, `st_subgroup_id`, `idsubjectdo`, `levelstart`, `levelcount`, `date`) VALUES(?i, ?i, ?i, ?i, 0, 0, ?s)";


    ########## SECTION_LEARNER_COMPLETED ##########
        static public $addCompletedSection = "INSERT INTO `y_sectionlearnercompleted`(`slc_learner_id`,
                                              `slc_subgroup_id`, `idsectiondo`, `date`, `idsubjectdo`)
                                              VALUES(?i, ?i, ?i, NOW(), ?i)";
        static public $checkCompletedSection = "SELECT id FROM y_sectionlearnercompleted WHERE slc_learner_id = ?i
                                                AND idsectiondo = ?i";

        static public $getDateFromSectiontiming = "SELECT date FROM y_sectiontiming WHERE idsectiondo = ?i
                                                    AND st_subgroup_id IN (
                                                    SELECT splg_subgroup_id FROM y_splearnergroup
                                                    WHERE splg_learner_id IN (
                                                    SELECT id FROM y_learner WHERE idlearnerdo = ?i) AND deleted = 0)";

        static public $getSubgroupY = "SELECT id FROM y_subgroup WHERE idsubjectdo = ?i
                                        AND idsubgroupdo IN (
                                        SELECT splg_subgroup_id FROM y_splearnergroup WHERE splg_learner_id = ?i)";
}
?>
