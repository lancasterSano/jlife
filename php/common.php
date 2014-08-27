<?php
define('CR', "\r");          // carriage return; Mac
define('LF', "\n");          // line feed; Unix
define('CRLF', "\r\n");      // carriage return and line feed; Windows
define('BR', '<br />' . LF); // HTML Break
function normalize($s) {
    //$s = preg_replace("/\v/", "", $s);
    //$s = preg_replace("/\t/", "", $s);
    // Normalize line endings using Global
    // Convert all line-endings to UNIX format
    $s = str_replace("/\r\n{1}/", "<br />", $s);
    $s = str_replace("/\r{1}/", "<br />", $s);
    // Don't allow out-of-control blank lines
    $s = preg_replace("/\n{2,}/", "<br /><br />", $s);
    $s = preg_replace("/\n{1}/", "<br />", $s);
    
    $s = preg_replace("/\s{2,}/", " ", $s);
    return $s;
}
function md5_jlife($pswd_in)
{
    // return (isset(SETTING_md5) && (SETTING_md5)==true) ? md5(md5($pswd_in)) : $pswd_in;
    // echo "SETTING_md5 = ".SETTING_md5." > ";
    // echo "pswd = ".$pswd_in." > ";
    // echo "is = ".((SETTING_md5) ? md5(md5($pswd_in)) : $pswd_in)." > ";
    // var_dump($pswd_in);
    return (SETTING_md5) ? md5(md5($pswd_in)) : $pswd_in;
}
class ROLES {
        static public $Learner = 1; 
        static public $Teacher = 2; 
        static public $Ko = 4; 
        static public $Responsible = 8; 
        static public $Yoda = 16; 
        static public $Director = 32; 
        static public $GOD = 128; 
}
function getPA($PathAvatarInDB, $IDProfile, $size = 0){
    switch($size)
    {
        case 1:
                if(preg_match("/^[a]/", $PathAvatarInDB) > 0) 
                    $PathAvatarInDB = preg_replace("/IDPHOTO/", $PathAvatarInDB, P_DEFAULT_AVATAR);
                else 
                {
                    $PathAvatarInDB = preg_replace("/IDPHOTO/", $PathAvatarInDB, P_AVA_A);
                    $PathAvatarInDB = preg_replace("/IDP/", $IDProfile, $PathAvatarInDB);
                }
                break;
        case 0: 
                $PathAvatarInDB = preg_replace("/IDPHOTO/", $PathAvatarInDB, P_AVA_S);
                $PathAvatarInDB = preg_replace("/IDP/", $IDProfile, $PathAvatarInDB);
                break;
    }
    return $PathAvatarInDB;
}

/*  FUNCTION SET RUSSIAN MONTH
 *  IN PARAMS: 
 *      DATE SUCH FORMAT (day(anyone) 
 *          month(n - number of month from 1 to 12) year(anyone)) hours (H), minutes (i)
 *  RETURN VALUE:
 *      RUSSIAN DATE SUCH FORMAT - day month(russian) year hours minutes
 *  EXAMPLE: 
 *      we have date like:
 *          $date = 13 July 2013 21:55:34;
 *      so call the function
 *          $russiandate = setRussianDate($date);
 *  NOW RUSSIAN DATE IS
 *      echo $russiandate;   13 июля 2013 21:55
 */
function setRussianDate($date){
    $tempdate = explode(" ", $date);
    switch ($tempdate[1]){
        case 1: $m='января'; break;
        case 2: $m='февраля'; break;
        case 3: $m='марта'; break;
        case 4: $m='апреля'; break;
        case 5: $m='мая'; break;
        case 6: $m='июня'; break;
        case 7: $m='июля'; break;
        case 8: $m='августа'; break;
        case 9: $m='сентября'; break;
        case 10: $m='октября'; break;
        case 11: $m='ноября'; break;
        case 12: $m='декабря'; break;
    }
    if(isset($tempdate[3]) && isset($tempdate[4])){
        $russiandate = $tempdate[0].'&nbsp'.$m.'&nbsp'.$tempdate[2].'&nbsp'.$tempdate[3].':'.$tempdate[4];
    } else {
        $russiandate = $tempdate[0].'&nbsp'.$m.'&nbsp'.$tempdate[2];
    } 
    return $russiandate;
}
function setRussianDateFromMysql($date)
{
    return setRussianDate(date("j n Y H i", strtotime($date)));
}

/*
 *  FUNCTION GET RUSSIAN DAY
 *  IN PARAMS:
 *      DAY NUMBER: day from 1(Monday) to 7(Sunday)
 *  RETURN VALUE:
 *      RUSSIAN DAY: day from Понедельник to Воскресенье
 *  EXAMPLE:
 *      we have day like:
 *          $daynumber = 4;
 *      so call the function
 *          $russianday = getRussianDay($daynumber);
 *  NOW RUSSIAN DAY IS
 *      echo $russianday; (Четверг)
 * 
 */
function getRussianDay($daynumber){
    switch ($daynumber){
        case 1: $m='Понедельник'; break;
        case 2: $m='Вторник'; break;
        case 3: $m='Среда'; break;
        case 4: $m='Четверг'; break;
        case 5: $m='Пятница'; break;
        case 6: $m='Суббота'; break;
        case 7: $m='Воскресенье'; break;
    }
    return $m;
}

function getNextPrevWeekYear($dateprevweek, $datenextweek){
    $prevweek = ltrim(date("W", $dateprevweek)); 
            
    $nextweek = ltrim(date("W", $datenextweek));
    
    $ret = array(
        "prevweek" => $prevweek,
        "nextweek" => $nextweek
    );
    return $ret;
}

function setRussianMonth($date, $ending = null){
    if($ending == null)
        switch ($date){
            case 1: $m='Январь'; break;
            case 2: $m='Февраль'; break;
            case 3: $m='Март'; break;
            case 4: $m='Апрель'; break;
            case 5: $m='Май'; break;
            case 6: $m='Июнь'; break;
            case 7: $m='Июль'; break;
            case 8: $m='Август'; break;
            case 9: $m='Сентябрь'; break;
            case 10: $m='Октябрь'; break;
            case 11: $m='Ноябрь'; break;
            case 12: $m='Декабрь'; break;
        }
    else
        switch ($date){
            case 1: $m='января'; break;
            case 2: $m='февраля'; break;
            case 3: $m='марта'; break;
            case 4: $m='апреля'; break;
            case 5: $m='мая'; break;
            case 6: $m='июня'; break;
            case 7: $m='июля'; break;
            case 8: $m='августа'; break;
            case 9: $m='сентября'; break;
            case 10: $m='октября'; break;
            case 11: $m='ноября'; break;
            case 12: $m='декабря'; break;
        }
    return $m;
}
# Функция получения даты начала месяца, конца месяца и числа выбранного месяца 

    function getIntervalMonthDayOnly($month, $START_EDUCATION_YEAR){
        $choosenMonthStart = date("Y-".$month."-01");
        $choosenMonthEnd = date("t", mktime(0,0,0, $month, 1, $START_EDUCATION_YEAR));
        $massMonth = array(
                'choosenMonth' => $month,
                'choosenMonthStart' => $choosenMonthStart,
                'choosenMonthEnd' => $choosenMonthEnd
                // 'lastDay' => date("t")
                );
        return $massMonth;
    }

    function getIntervalMonthDayForStartEducationMonth($month, $START_EDUCATION_YEAR, $START_EDUCATION_DAY){
        $choosenMonthStart = date("Y-".$month."-".$START_EDUCATION_DAY);
        $choosenMonthEnd = date("t", mktime(0,0,0, $month, 1, $START_EDUCATION_YEAR));
        $massMonth = array(
                'choosenMonth' => $month,
                'choosenMonthStart' => $choosenMonthStart,
                'choosenMonthEnd' => $choosenMonthEnd
                // 'lastDay' => date("t")
                );
        return $massMonth;
    }


    function getIntervalsMonths($month, $START_EDUCATION_YEAR){
        $choosenMonthStart = date("Y-".$month."-01");
        $choosenMonthEnd = date("Y-".$month."-t", mktime(0,0,0, $month, 1, $START_EDUCATION_YEAR));
        $massMonth = array(
                'choosenMonth' => $month,
                'choosenMonthStart' => $choosenMonthStart,
                'choosenMonthEnd' => $choosenMonthEnd
                );
        return $massMonth;
    }

// # Функция получения даты начала месяца, конца месяца и числа выбранного месяца 

//     function getIntervalsMonths($month, $START_EDUCATION_YEAR){
//         $choosenMonthStart = date("Y-".$month."-01");
//         $choosenMonthEnd = date("Y-".$month."-t", mktime(0,0,0, $month, 1, $START_EDUCATION_YEAR));
//         $massMonth = array(
//                 'choosenMonth' => $month,
//                 'choosenMonthStart' => $choosenMonthStart,
//                 'choosenMonthEnd' => $choosenMonthEnd
//                 // 'lastDay' => date("t")
//                 );
//         return $massMonth;
//     }
    
function refereNotValidId() {
}

function hiddenEmail($email)
{
    preg_match("/[\w-\._\+%]/", $email, $r);
    return preg_replace("/^[\w-\._\+%]+/", $r[0]."***", $email);
}

function rajax($data, $errors=null)
{
    $r = array();
    if(!empty($errors)) {  // isset
        if(is_array($errors)) $errors = (array)$errors;
        if(count($errors) > 0) $r['errors'] = $errors;
    }
    return array_merge($r, (array)$data);
}

function converterBoolNumber(&$value){
    if(is_bool($value)){
        $value = ($value ? 1 : 0);
    }
    return $value;
}

function validateEmail ($email) { return preg_match(REG_LITERAL_EMAIL, $email) == 1 ? true : false; }
function validateTitleLength ($title, $min, $max) { if (mb_strlen($title)>=$min && mb_strlen($title)<=$max) return true; else return false; }
function validatePSWD ($pswd) { 
    return preg_match(REG_LITERAL_PSWD, $pswd)==1?true:false;
}

function transformationComplexityMark ($complexity) 
{
    switch($complexity)
    {
        case 2: $mark = 2.5;break;
        case 3: $mark = 4;break;
    }
    return $mark;
}

?>