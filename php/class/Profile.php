<?php
require_once '.htpaths';
//require_once(PROJECT_PATH.'/lib/lib_bd/safemysql.class.php');
require_once(PROJECT_PATH."/settings/settings.php");
require_once(PROJECT_PATH."/php/class/ProfDir.php");
require_once(PROJECT_PATH."/php/class/FileUploader.php");
class Profile {
    var $ID;
    var $FirstName;
    var $LastName;
    var $MiddleName;
    var $sex;
    var $telephone;
    var $mobile;
    var $city;
    var $country;
    var $birthday;
    var $isAuth = false;
    var $countcontact;
    var $countalbum;
    var $countblog;
    var $countblogcomment;
    var $countwall;
    var $countwall_my;
    var $isdefaultava;
    var $isdefaulthb;
    var $role;
    var $countinbox;
    var $countoutbox;
    var $deleted;
    var $lock;
    var $valid;
    var $acceptlicense;
    var $private;
    var $email;
    //var $isOnOffLine;
    var $is_acceptlicense;

    ### PRIVATE METHODS ###
        private static function connectToDB(){
            require_once(PROJECT_PATH.'/lib/lib_bd/safemysql.class.php');
            require_once(PROJECT_PATH."/php/class/QueryStorage.php");
            /****** CONNECT TO DB_SOCIAL ******/ require PROJECT_PATH.'/include/dbsocial.php';
            return $DB;
        }

    ### CONSTRUCT ###
        /**
    	 * Конструктор. Создает объект профиля из ID и ассоциативного массива полей 
    	 * Examples:
         *      $ProfileLoad = new Profile($ProfileID, $DB);
    	 * @param int $id - ID профайла, объект которого необходимо создать
    	 * @param associate array $DB - объект подключения к БД
    	 */
        public function __construct($id, $p){
            $this->ID = $id;
            $this->FirstName = $p["firstname"];
            $this->LastName = $p["lastname"];
            $this->MiddleName = $p["middlename"];
            $this->telephone = $p["telephone"];
            $this->mobile = $p["mobile"];
            $this->city = $p["city"];
            $this->country = $p["country"];
            $this->birthday = $p["birthday"];
            // $this->PathAvatarInDB = $p["pathavatar"];
            // $this->PathHeadbandInDB = $p["pathheadband"];
            $this->countcontact = $p["countcontact"];
            $this->countalbum = $p["countalbum"];
            $this->countblog = $p["countblog"];
            $this->countblogcomment = $p["countblogcomment"];
            $this->countwall = $p["countwall"];
            $this->countwall_my = $p["countwallmy"];
            $this->isdefaultava = $p["isdefaultava"] === NULL ? true : $p["isdefaultava"];
            $this->isdefaulthb = $p["isdefaulthb"] === NULL ? true : $p["isdefaulthb"];
            $this->role = $p["role"];
            $this->countinbox = $p["countinbox"];
            $this->countoutbox = $p["countoutbox"];

            $this->sex = $p["sex"];
            $this->deleted = $p["deleted"];
            $this->lock = $p["lock"];
            $this->valid = $p["valid"];
            $this->acceptlicense = ($p["acceptlicense"]==null)?0:$p["acceptlicense"];
            $this->is_acceptlicense = ($p["acceptlicense"]==null)?0:1;
            
            $this->private = $p["private"];
            $this->email = $p["email"];
        }

    ### PUBLIC GET PROPERTY - FIO, FI, F, ProfilePathAvatar, ProfilePathHeadband, hiddenEmail ###
        /** * Возвращает "Фамилия Имя Отчество"*/
        public function FIO(){
            return $this->LastName." ".$this->FirstName." ".$this->MiddleName;
        }
        /** * Возвращает "Фамилия Имя"*/
        public function FI($len=-1){
            if($len!=-1) return mb_strcut($this->LastName." ".$this->FirstName, 0, $len * 2)."...";
        return $this->LastName." ".$this->FirstName;
        }
        /** * Возвращает "Имя"*/
        public function F(){
            return $this->FirstName;
        }
        /** * Путь к аватару из this(object) */
        public function ProfilePathAvatar($size = 0, $reload=false){
            return $this->getProjectPathAvatar($this->ID, $this->isdefaultava, $size, $this->sex, $reload);
        }
        /** * Путь к подложке из this(object)*/
        public function ProfilePathHeadband($size = 4){
            return $this->getProjectPathHeadBand($this->ID, $this->isdefaulthb, $size);
        }
        /** * Безопасный email адресс*/
        public function hiddenEmail() {
            return hiddenEmail($this->email);
        }

    ### PUBLIC SET METHOD ###
        /** * Установить что ПРОФАЙЛ авторизирован.
    	 * @param bool $is - указать в объекте, что Profile авторизирован. По умолчанию $is = false */        
        public function SetAuthorizate($is = false){
            return $this->isAuth = $is;
        }

    ### PUBLIC OTHER METHOD ###
        public function iAm($idProfile){
            return ($this->ID == $idProfile);
        }

        static function getProjectPathAvatar($id, $default = true, $size = 0, $sex = null, $reload=false){
            if($default || ($id === NULL))
            {
                // default
                // $path = P_DEF_AVA_PHOTO;                
                if($id == null || $sex == null) { $id = -1; $sex = 1; }
                $path = 'd'.(($id % 9)+1).'.jpg';
                if($size == 1) return preg_replace("/SEX/", $sex, preg_replace("/IDPHOTO/", $path, P_DEF_AVAA));
                else if($size == 0) return preg_replace("/SEX/", $sex, preg_replace("/IDPHOTO/", $path, P_DEF_AVAS));
            }
            else
            {
                $path = 'a'.$id.PR_TYPE_PHOTO;
                $reload_place = $reload?'?ord='.time():'';
                // not default
                if($size == 1) return preg_replace("/IDPHOTO/", $path, P_AVAA).$reload_place;
                else if($size == 0) return preg_replace("/IDPHOTO/", $path, P_AVAS).$reload_place;
            }
        }

        static function getProjectPathHeadBand($id, $default = true, $size = 0){
            if($default || ($id === NULL))
            {
                // default
                $path = P_DEF_HBB_PHOTO;
                return preg_replace("/IDPHOTO/", $path, P_DEF_HBB);
            }
            else 
            {
                $path = 'b'.$id.PR_TYPE_PHOTO;
                return preg_replace("/IDPHOTO/", $path, P_HBB);
            }
        }

        /**
         * changeAvatar_checkRequirementsDiretory
         * Проверка требуемых директорий валидности системных данных
         */
        static public function checkSystemDataRequirementsDiretory(){
            // /data/
            // /data/AVAA/
            // /data/AVAS/
            $jlife_data = ProfDir::getProfDir(0, ProfDir::$JLIFEDATA);
            if(file_exists($jlife_data)) {
                if(!is_writable($jlife_data)) $writable = chmod($jlife_data, 0777);
                
                $jlife_avaa = ProfDir::getProfDir(0, ProfDir::$AVAA);
                $jlife_avas = ProfDir::getProfDir(0, ProfDir::$AVAS);
                if(ProfDir::existCreateDirectory($jlife_avaa, $writable)
                    && ProfDir::existCreateDirectory($jlife_avas, $writable) ){
                    return true;
                }
            }
            return false;
        }
        /**
         * changeAvatar_checkRequirementsDiretory
         * Проверка требуемых директорий для валидности профиля
         */
        public function checkPofileRequirementsDiretory(){
            // /data/p1010/
            // /data/p1010/p/
            // /data/p1010/p/ava1010/
            // /data/p1010/p/ava1010m/
            if(!Profile::checkSystemDataRequirementsDiretory()) return false;
            $jlife_profile = ProfDir::getProfDir($this->ID, ProfDir::$P);

            if(ProfDir::existCreateDirectory($jlife_profile, true)){
                $jlife_albums = ProfDir::getProfDir($this->ID, ProfDir::$ALBUMS);
                if(ProfDir::existCreateDirectory($jlife_albums, true)){
                    $jlife_album_ava = ProfDir::getProfDir($this->ID, ProfDir::$ALBUMAVA);
                    $jlife_album_avam = ProfDir::getProfDir($this->ID, ProfDir::$ALBUMAVAM);
                    if(ProfDir::existCreateDirectory($jlife_album_ava, true)
                        && ProfDir::existCreateDirectory($jlife_album_avam, true) ){
                        return true;
                    }
                }
            }
            return false;
        }
        /**
         * changeAvatar_checkRequirementsDiretory
         * Проверка требуемых директорий для смены аватарки
         */
        public function changeAvatar_checkRequirementsDiretory(){
            if(!$this->checkPofileRequirementsDiretory()) return false;
            // получить шаблон пути к папке с фото по катигориям
            $dir = array (
                "albumava" => array(ProfDir::getProfDir($this->ID, ProfDir::$ALBUMAVA)),
                "avas" => array(ProfDir::getProfDir($this->ID, ProfDir::$AVAS)),
                "avaa" => array(ProfDir::getProfDir($this->ID, ProfDir::$AVAA)),
                "albumavam" => array(ProfDir::getProfDir($this->ID, ProfDir::$ALBUMAVAM))
            );
            /**
              * проверка, а есть ли папка вообще на сервере (если нет - создать)
              * результат массив по типу = array([0]=>'путь к директории',[1]=>'есть ли она') 
              */ 
            $dir["albumava"][1] = ProfDir::existCreateDirectory($dir["albumava"][0], 1);
            $dir["avas"][1] = ProfDir::existCreateDirectory($dir["avas"][0], 1);
            $dir["avaa"][1] = ProfDir::existCreateDirectory($dir["avaa"][0], 1);
            $dir["albumavam"][1] = ProfDir::existCreateDirectory($dir["albumavam"][0], 1);
            if($dir["albumava"][1] && $dir["avas"][1] && $dir["avaa"][1] && $dir["albumavam"][1]){
                return $dir;
            }
            return false;
        }
        /**
         * Проверка что достаточно файлов пришло с front-end'a для смены авки
         */
        public function changeAvatar_checkFrontendData($files){
            if(isset($files["userpic"]) 
                && isset($files["avatar"]) 
                && isset($files["aspect"]) 
                && isset($files["original"]))
            {
                return true;
            }
            return false;
        }

        /**
         * Смена аватарки - changeAvatar
         */
        public function changeAvatar($files){
            $data_rez = array();
            $errors = array();
            $DB = self::connectToDB();
            $data = $this->changeAvatar_checkFrontendData($files);
            $dir = $this->changeAvatar_checkRequirementsDiretory();
            if($dir && $data) // есть все необходимые файлы и деректории для них
            {
                try{
                // Пометить старую авку, но не удалять
                    // 1. SELECT ACTIV AVATAR #id
                    $idLaterAvtar = $DB->getOne(QS::$getCurrentAvatar, $this->ID);
                    if($idLaterAvtar !== false && $idLaterAvtar !== true && $idLaterAvtar !== null)
                    {
                        // если есть уже старая(текущая авка)
                        // if($idLaterAvtar === true || $idLaterAvtar === false) $idLaterAvtar = null;
                        // 2 UPDATE current avatar(isavatar=f)
                        $DB->query(QS::$setAvaInactive, $this->ID, $idLaterAvtar);
                        if(ProfDir::existCreateDirectory($dir["avaa"][0].'a'.$this->ID.PR_TYPE_PHOTO))
                            rename ($dir["avaa"][0].'a'.$this->ID.PR_TYPE_PHOTO, $dir["avaa"][0].'a'.$this->ID.'_tmp'.PR_TYPE_PHOTO);
                        if(ProfDir::existCreateDirectory($dir["avas"][0].'a'.$this->ID.PR_TYPE_PHOTO))
                            rename ($dir["avas"][0].'a'.$this->ID.PR_TYPE_PHOTO, $dir["avaa"][0].'a'.$this->ID.'_tmp'.PR_TYPE_PHOTO);
                        // $data_rez[] = "mark old ava, byt not delete";
                    } else {
                        $idLaterAvtar = null;
                    }
                // 2. установить новую авку дляпрофиля
                    try{
                    // 2.1 установить новую аву
                        $idNewAvtar = $idLaterAvtar+1;
                        // имена и пути новой авки
                        $namePath = array (
                            "userpic" => array("path" => $dir["avas"][0], "name" => 'a'.$this->ID ),
                            "avatar" => array("path" => $dir["avaa"][0], "name" => 'a'.$this->ID ),
                            "original" => array("path" => $dir["albumava"][0], "name" => 'f'.$idNewAvtar ),
                            "aspect" => array("path" => $dir["albumavam"][0], "name" => 'f'.$idNewAvtar ),
                        );
                        // перенести новые фото для авки
                        $moved = FileUploader::FetchImages($files, $namePath);
                        if(count($moved)!=4) throw new Exception("Фото аватарки не перенесены по местам");
                        else {
                            // $data_rez[] = "move new photo for ava";
                            // создать новую авку (avatar1010) и получить ID
                            // CREATE NEW AVATAR (isavatar=t)
                            $rez = $DB->query(QS::$insertAvatar, $this->ID, date("Y-m-d H:i:s"), $idLaterAvtar, date("Y-m-d H:i:s"));
                            if($rez){
                                // SELECT NEW ACTIVE AVATAR
                                // $data_rez[] = "get id new active avatar";
                                $idRezNewAva = $DB->getOne(QS::$getCurrentAvatar, $this->ID);
                                if($idNewAvtar !== $idRezNewAva) {
                                    rename ($dir["albumava"][0].'f'.$idNewAvtar.PR_TYPE_PHOTO, $dir["albumava"][0].'f'.$idRezNewAva.PR_TYPE_PHOTO);
                                    rename ($dir["albumavam"][0].'f'.$idNewAvtar.PR_TYPE_PHOTO, $dir["albumavam"][0].'f'.$idRezNewAva.PR_TYPE_PHOTO);
                                }
                                // drop latest ava
                                @unlink($dir["avaa"][0].'a'.$this->ID.'_tmp'.PR_TYPE_PHOTO);
                                @unlink($dir["avas"][0].'a'.$this->ID.'_tmp'.PR_TYPE_PHOTO);

                                // установить что профиль не использует  стандартную авку
                                if($this->isdefaultava) $DB->query(QS::$updateProfileNotDefaultAva, $this->ID);
                                $data_rez[] = "success";
                            }
                        }
                    } catch(Exception $e){
                        // 2.2 если не получилось успешно выставить новую аву
                        // 2.2.1 выставить старую аву
                        // make ava active
                        $errors[] = "2.2 recover latter ava";
                        $DB->query(QS::$setAvaActiveClear, $this->ID, $idLaterAvtar);
                        // востановить название старой авы из времменной
                        rename ($dir["avaa"][0].'a'.$this->ID.'_tmp'.PR_TYPE_PHOTO, $dir["avaa"][0].'a'.$this->ID.PR_TYPE_PHOTO);
                        rename ($dir["avas"][0].'a'.$this->ID.'_tmp'.PR_TYPE_PHOTO, $dir["avaa"][0].'a'.$this->ID.PR_TYPE_PHOTO);
                    }
                } catch(Exception $e){

                }
            } else {
                // нет директорий или файлы не пришли коректными
                $errors[] = "not directory";
            }

            return rajax($data_rez, $errors);
        }


        /** Update in table Avatar: create valid note avatar */
        private function setOldAvaActive($idCurrentAva, $idNewAva){
            /** Подключится к БД в этом классе*/
            $DB = self::connectToDB();
            $newAvaPrev = $DB->getOne(QS::$getNewAvaPrev, $this->ID, $idNewAva);
            //get next ava(of new)
            $idNextAva = $DB->getOne(QS::$getNextAvaID, $this->ID, $idNewAva);
            //set prev field to next ava
            $DB->query(QS::$setNewPrevToNextAva, $this->ID, $newAvaPrev, $idNextAva);
            //make ava active
            $DB->query(QS::$setAvaActive, $this->ID, $idCurrentAva, date("Y-m-d H:i:s"), $idNewAva);
        }

        public function setRole($role, $Adress, $idSchool, $info){
            /** Подключится к БД в этом классе*/
            $DB = self::connectToDB();
            $DB->query("CALL cRole(?i,?i,?i,?i,?s)", $this->ID, $role, $Adress, $idSchool, $info);
        }

        public function getRoles(){
            /** Подключится к БД в этом классе*/
            $DB = self::connectToDB();
            return $DB->getAll(QS::$q_roles_1, $this->ID);
        }
        public function getRolesByRole($role){

            /** Подключится к БД в этом классе*/
            $DB = self::connectToDB();
            return $DB->getAll(QS::$q_roles_2, $this->ID, $role);
        }        
        public function getRoleByRoleInSchool($role, $idSchool){
            /** Подключится к БД в этом классе*/
            $DB = self::connectToDB();
            return $DB->getRow(QS::$q_role_by_school, $this->ID, $role, $idSchool);
        }
        public function getRolesInSchool($idSchool){
            /** Подключится к БД в этом классе*/
            $DB = self::connectToDB();
            return $DB->getAll(QS::$q_roles_by_school, $this->ID, $idSchool);
        }


}
?>