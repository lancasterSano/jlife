<?php
require_once '.htpaths';
require_once(PROJECT_PATH."/php/class/Profile.php");
require_once(PROJECT_PATH."/php/class/QueryStorage.php");
require_once(PROJECT_PATH."/lib/lib_bd/safemysql.class.php");

class ProfileAuth {
    private $DB;
    private $profile;
    // private $countIterationRegistarrion = 0;

    public function __construct($db){ 
        $this->initDB($db);
        if(Yii::app()->user->hasState("profile_id")) {
            $idProfileSessionYii = Yii::app()->user->getState("profile_id");
            $this->initProfile($idProfileSessionYii);
        } else {
            if(Yii::app()->user->isGuest) throw new Exception("IsGuest");
            if(Yii::app()->session->get("profile_id"))
                throw new Exception('[hasState("profile_id")='.Yii::app()->session->get("profile_id").']');
            if(Yii::app()->user->hasState("profile_id"))
                throw new Exception('[hasState("profile_id")='.Yii::app()->user->getState("profile_id").']');
        } 
    }
    public function __destruct(){}

    public function initDB($db) {
        if($this->DB == null) {
            if($db != null) $this->DB = $db;
            else { 
                require PROJECT_PATH.'/include/dbsocial.php'; /****** CONNECT TO DB_SOCIAL ******/
                $this->DB = $DB;
            }
        }
        else return $this->DB;
    }    
    private function initProfile($ProfileID) { 
        $p = $this->DB->getRow(QS::$getProfileContext, $ProfileID);
        $this->profile = new Profile($ProfileID, $p); 
    }
    public function getProfile(){
        if( ProfileAuth::isAuthorizate() )  {
            if(!isset($this->profile)) {
                $this->initProfile($_SESSION['id']);
            }
            $this->profile->SetAuthorizate(true);
        }
        return $this->profile;
    }

    #: Authentification :# 
        // deleted will be future
            public function Logout ($redirect = null) {
                throw new Exception('unrelised function ProfileAuth::Logout');
                // unset($_SESSION['id']); //удаляем переменную сессии     
                // unset($_SESSION['jlin']); //удаляем переменную сессии     
                // SetCookie("jlin", "", time() - 1, '/');               
                // if(!empty($redirect)) {
                //     $_SESSION['REQUEST_URI'] = $redirect;
                // }
                // else {
                //     session_unset(); // осов все переменные
                //     session_destroy(); // всю информацию убиваем
                // }
                // header("Location: ".PROJECT_PATH.SETTING_LOGIN_REDIRECT);    
            }
        // deleted will be future
            public function Login ($saveInAuth) {
                throw new Exception('unrelised function ProfileAuth::Login');
                // // echo 2;
                // // var_dump($_POST);
                // $error = array(); //массив для ошибок   
                // if ($_POST['login'] != "" && $_POST['password'] != "") //если поля заполнены    
                // {       
                // // echo 3;
                //     $login = $_POST['login']; 
                //     $password = $_POST['password'];

                //     $row = $this->DB->getRow(QS::$q1, $login);   
                //     // var_dump($row); exit();         
                //     if (true) //если нашлась одна строка, значит такой юзер существует в БД         
                //     {
                //         $password = $row['private'] ? md5_jlife($password) : $password;
                //         if ($password == $row['password']) //сравниваем хэшированный пароль из БД с хэшированными паролем, введённым пользователем и солью             
                //         {
                //             $jlin = md5(md5($row['id'].MD5_SOLT));
                //             setcookie ("jlin", $jlin,  time() + SETTING_TIME_COOCKIE_ONLINE, "/");                        
                //             $_SESSION['id'] = $row['id'];   //записываем в сессию id пользователя               
                //             $_SESSION['jlin'] = $jlin;   //записываем в сессию id пользователя + solt

                //             //  //удаляем jlin-сессия из БД по idProfile
                //             // $datecreate = date("Y-m-d H:i:s");
                //             // $datevalid = date("Y-m-d H:i:s" , strtotime($datecreate." +4 minutes"));
                //             // //записываем в БД jlin
                //             // $query = $this->DB->query(QS::$q_set_session, $jlin, $row['id'], $datevalid);

                //             return $error;          
                //         }           
                //         else //если пароли не совпали           
                //         {               
                //             $error[] = "Неверный пароль";       
                //             return $error;
                //         }
                //     }
                //     else //если такого пользователя не найдено в БД
                //     {
                //         $error[] = "Неверный логин и пароль";
                //         return $error;
                //     }
                // }
                // else
                // {
                //     $error[] = "Поля не должны быть пустыми!";
                //     return $error;
                // }
            }
        public static function isAuthorizate() { 
            // throw new Exception('unrelised function ProfileAuth::isAuthorizate');
            if ( isset($_SESSION['jlin']) && isset($_COOKIE['jlin']) && $_SESSION['jlin']==$_COOKIE['jlin'] ) { return true; }
            return false;       
        }
    
    #:: REGISTRATION ::# 
        public static function Registration($login, $pswd, $email, $firstName, $lastName, $middleName, $redirect=true, $DB=null, $iteration=0){
            if($iteration < 10) {
                $iteration++;
                // Проверка входящих переменных
                if (isset($login) && isset($pswd) && isset($firstName) && isset($lastName) && isset($email) 
                    && $login != "" && $pswd != "" && $firstName != "" && $lastName != "" && $email != ""
                    )
                {
                    $err = array();
                    # делаем двойное шифрование
                    $pswd = md5_jlife($pswd);
                    # проверяем, не сущестует ли пользователя с таким именем
                    $count = $DB->getOne(QS::$q0, $login);
                    if($count > 0)
                    {
                        $err[] = "Пользователь с таким логином уже существует в базе данных";
                    }

                    # Если нет ошибок, то добавляем в БД нового пользователя
                    if(count($err) == 0)
                    {
                        # Получыить MAX_ID in db
                            $max = $DB->getOne(QS::$q_MAX_ID);
                            if($max == null || ($max != null && $max < SETTING_START_REGISTRATION)) $max = SETTING_START_REGISTRATION;
                            $IDN = ProfileAuth::GetValidID($max); // IDN = MAX_ID + 1;

                        # Создать Profile 
                            $telephone=null; $mobile=null; $email2=null; $city=null; $country=null; $birthday=null;
                            $res = $DB->query(
                                QS::$insertProfileWithID, $IDN, $login, $pswd, $email, $firstName, $lastName, $middleName, $telephone, $mobile, $email2, $city, $country, $birthday);
                        // Получить созданый профиль (ProfileID)
                        if($res) {
                            $res_profile = $DB->getRow(QS::$q1, $login);
                            $idp = $res_profile["id"];
                            if(!ProfileAuth::createFolderProfile($idp)) $err[] = "Folders does not create!";
                            
                            if(count($err) == 0) {
                                ProfileAuth::createTableFollow($idp, $DB);
                                ProfileAuth::validateTableFollow($idp, $DB);
                            } else {
                                // update profile valid = false & repeat Registration
                                $res_profile = $DB->query(QS::$updateProfile,$idp);
                                $err = null;
                                ProfileAuth::Registration($login, $pswd, $email, $firstName, $lastName, $middleName, $redirect, $DB, $iteration);
                            }
                        }
                        if($redirect) {
                            if(count($err) == 0)
                            {
                                header("Location: ".PROJECT_PATH."/pages/register.php?er=0"); exit();
                            }
                            else {
                                $form_data = $form_data.(isset($login)? "l=".$login."&":"");
                                $form_data = $form_data.(isset($pswd)? "p=".$pswd."&":"");
                                $form_data = $form_data.(isset($email)? "e=".$email."&":"");
                                $form_data = $form_data.(isset($firstName)? "f=".$firstName."&":"");
                                $form_data = $form_data.(isset($lastName)? "s=".$lastName."&":"");
                                $form_data = $form_data.(isset($middleName)? "m=".$middleName."&":"");
                                header("Location: ".PROJECT_PATH."/pages/register.php?".$form_data."er=1"); exit();
                            }
                        }
                        else {
                            if(count($err)) return false;
                            return $idp;
                        }
                    }
                }
            }
            if($redirect)
            {
                $form_data = $form_data.(isset($login)? "l=".$login."&":"");
                $form_data = $form_data.(isset($pswd)? "p=".$pswd."&":"");
                $form_data = $form_data.(isset($email)? "e=".$email."&":"");
                $form_data = $form_data.(isset($firstName)? "f=".$firstName."&":"");
                $form_data = $form_data.(isset($lastName)? "s=".$lastName."&":"");
                $form_data = $form_data.(isset($middleName)? "m=".$middleName."&":"");

                header("Location: ".PROJECT_PATH."/pages/register.php?".$form_data."er=123456");
            }
            else return false;
        }
        private static function GetValidID($id){
            include(PROJECT_PATH."/settings/reservID.php");
            $IDN = $id + 1;
            $next = isset($reserv[$IDN]);
            while($next){ 
                $IDN = $IDN + 1;
                $next = isset($reserv[$IDN]);
            }
            return $IDN;
        }
        # Создать сопровождающие таблицы
            public static function createTableFollow($idProfile, $DB=null){
                require_once(PROJECT_PATH."/php/class/QueryStorageCreate.php");
                $id = $idProfile;
                $execute = array();

                ########## WALL ##########
                    #
                    # Wall create with trigger
                        $res_tWall = $DB->query(QS_CREATE::$createTableWallN, $id, $id, $id, $id, $id, $id);
                        //if($res_tWall != true) return;                
                        $triggerWallN_AINS = $DB->query(QS_CREATE::$createTriggerWallN_AINS, $id, $id, $id, $id, $id, $id);
                        //if($triggerWallN_AINS != true) return;
                        $triggerWallN_ADEL = $DB->query(QS_CREATE::$createTriggerWallN_ADEL, $id, $id, $id, $id, $id, $id);
                        //if($triggerWallN_ADEL != true) return;
                    #            
                    # CommentWall create with trigger
                        $res_tCommentWall = $DB->query(QS_CREATE::$createTableCommentwallN, $id, $id, $id, $id, $id, $id, $id, $id,
                                                    $id, $id, $id, $id, $id, $id, $id, $id, $id, $id, $id, $id, $id, $id, $id, $id);
                        //if($res_tCommentWall != true) return;
                        
                        $triggerCommentwallN_AINS = $DB->query(QS_CREATE::$createTriggerCommentwallN_AINS, $id, $id, $id, $id, $id);
                        //if($triggerCommentwallN_AINS != true) return; 
                        $triggerCommentwallN_ADEL = $DB->query(QS_CREATE::$createTriggerCommentwallN_ADEL, $id, $id, $id, $id, $id);
                        //if($triggerCommentwallN_ADEL != true) return;
                    #
                    # LikeCommentWall create with trigger
                        $res_tLikeCommentWall = $DB->query(QS_CREATE::$createTableLikecommentwallN, $id, $id, $id, $id, $id, $id,$id, $id, $id, $id, $id);
                        //if($res_tLikeCommentWall != true) return;
                        
                        $triggerLikeCommentwallN_AINS = $DB->query(QS_CREATE::$createTriggerLikeCommentwallN_AINS, $id, $id, $id, $id, $id);
                        //if($triggerLikeCommentwallN_AINS != true) return; 
                        $triggerLikeCommentwallN_ADEL = $DB->query(QS_CREATE::$createTriggerLikeCommentwallN_ADEL, $id, $id, $id, $id, $id);
                        //if($triggerLikeCommentwallN_ADEL != true) return;
                    #
                    # LikeWall create with trigger
                        $res_tLikeWall = $DB->query(QS_CREATE::$createTableLikewallN,  $id, $id, $id, $id, $id, $id, $id, $id, $id, $id, $id);
                        //if($res_tLikeWall != true) return;
                        
                        $triggerLikeWallN_AINS = $DB->query(QS_CREATE::$createTriggerLikewallN_AINS, $id, $id, $id, $id, $id);
                        //if($triggerLikeWallN_AINS != true) return; 
                        $triggerLikeWallN_ADEL = $DB->query(QS_CREATE::$createTriggerLikewallN_ADEL, $id, $id, $id, $id, $id);
                        //if($triggerLikeWallN_ADEL != true) return;
               
                ########## BLOG ##########
                    #
                    # Blog create with trigger
                        $res_tBlog = $DB->query(QS_CREATE::$createTableBlogN, $id, $id, $id, $id, $id, $id);
                        //if($res_tBlog != true) return;

                        $triggerBlogN_AINS = $DB->query(QS_CREATE::$createTriggerBlogN_AINS, $id, $id, $id, $id, $id, $id);
                        //if($triggerBlogN_AINS != true) return;
                        $triggerBlogN_ADEL = $DB->query(QS_CREATE::$createTriggerBlogN_ADEL, $id, $id, $id, $id, $id, $id);
                        //if($triggerBlogN_ADEL != true) return;
                    #
                    # LikeBlog create with trigger
                        $res_tLikeBlog = $DB->query(QS_CREATE::$createTableLikeblogN, $id, $id, $id, $id, $id, $id, $id, $id, $id, $id, $id);
                        //if($res_tLikeBlog != true) { return; }

                        $triggerLikeBlogN_AINS = $DB->query(QS_CREATE::$createTriggerLikeBlogN_AINS, $id, $id, $id, $id, $id);
                        //if($triggerLikeBlogN_AINS != true) return;
                        $triggerLikeBlogN_ADEL = $DB->query(QS_CREATE::$createTriggerLikeBlogN_ADEL, $id, $id, $id, $id, $id);
                        //if($triggerLikeBlogN_ADEL != true) return;
                    #
                    # CommentBlog create with trigger
                        $res_tCommentBlog = $DB->query(QS_CREATE::$createTableCommentblogN, $id, $id, $id, $id, $id, $id, $id, $id, $id, $id, $id, $id, $id, $id, $id, $id, $id, $id, $id, $id, $id, $id, $id, $id);
                        //if($res_tCommentBlog != true) return;

                        $triggerCommentBlogN_AINS = $DB->query(QS_CREATE::$createTriggerCommentBlogN_AINS, $id, $id, $id, $id, $id
                            , $id, $id, $id, $id);
                        //if($triggerCommentBlogN_AINS != true) return;
                        $triggerCommentBlogN_ADEL = $DB->query(QS_CREATE::$createTriggerCommentBlogN_ADEL, $id, $id, $id, $id, $id
                            , $id, $id, $id, $id);
                        //if($triggerCommentBlogN_ADEL != true) return;
                    #
                    # LikeCommentBlog create with trigger
                        $res_tLikeCommentBlog = $DB->query(QS_CREATE::$createTableLikecommentblogN, $id, $id, $id, $id, $id, $id, $id, $id, $id, $id, $id);
                        //if($res_tLikeCommentBlog != true) return;

                        $triggerLikeCommentBlogN_AINS = $DB->query(QS_CREATE::$createTriggerLikeCommentBlogN_AINS, $id, $id, $id, $id, $id);
                        //if($triggerLikeCommentBlogN_AINS != true) return;
                        $triggerLikeCommentBlogN_ADEL = $DB->query(QS_CREATE::$createTriggerLikeCommentBlogN_ADEL, $id, $id, $id, $id, $id);
                        //if($triggerLikeCommentBlogN_ADEL != true) return;

                ########## ALBUM ##########
                    #
                    # Album create with trigger
                        $res_tAlbum = $DB->query(QS_CREATE::$createTableAlbumN, $id);
                        //if($res_tAlbum != true) return;
                        
                        $triggerAlbumN_AINS = $DB->query(QS_CREATE::$createTriggerAlbumN_AINS, $id, $id, $id);
                        //if($triggerAlbumN_AINS != true) return;
                        $triggerAlbumN_ADEL = $DB->query(QS_CREATE::$createTriggerAlbumN_ADEL, $id, $id, $id);
                        //if($triggerAlbumN_ADEL != true) return;
                    #
                    # LikeAlbum create with trigger
                        $res_tLikeAlbum = $DB->query(QS_CREATE::$createTableLikealbumN, $id, $id, $id, $id, $id, $id, $id, $id, $id, $id, $id);
                        //if($res_tLikeAlbum != true) return;
                        
                        $triggerLikeAlbumN_AINS = $DB->query(QS_CREATE::$createTriggerLikeAlbumN_AINS, $id, $id, $id, $id, $id);
                        //if($triggerLikeAlbumN_AINS != true) return;
                        $triggerLikeAlbumN_ADEL = $DB->query(QS_CREATE::$createTriggerLikeAlbumN_ADEL, $id, $id, $id, $id, $id);
                        //if($triggerLikeAlbumN_ADEL != true) return;
                    #
                    # CommentAlbum create with trigger
                        $res_tCommentAlbum = $DB->query(QS_CREATE::$createTableCommentalbumN, 
                            $id, $id, $id, $id, $id, $id, $id, $id, $id, $id, $id, $id, $id, $id, $id, $id, $id, $id, $id, $id, $id, $id, $id, $id);
                        //if($res_tCommentAlbum != true) return;
                        
                        $triggerCommentAlbumN_AINS = $DB->query(QS_CREATE::$createTriggerCommentAlbumN_AINS, $id, $id, $id, $id, $id);
                        //if($triggerCommentAlbumN_AINS != true) return;
                        $triggerCommentAlbumN_ADEL = $DB->query(QS_CREATE::$createTriggerCommentAlbumN_ADEL, $id, $id, $id, $id, $id);
                        //if($triggerCommentAlbumN_ADEL != true) return;
                    #
                    # LikeCommentAlbum create with trigger
                        $res_tLikeCommentAlbum = $DB->query(QS_CREATE::$createTableLikecommentalbumN, $id, $id, $id, $id, $id, $id, $id, $id, $id, $id, $id);
                        //if($res_tLikeCommentAlbum != true) return;
                        
                        $triggerLikeCommentAlbumN_AINS = $DB->query(QS_CREATE::$createTriggerLikeCommentAlbumN_AINS, $id, $id, $id, $id, $id);
                        //if($triggerLikeCommentAlbumN_AINS != true) return;
                        $triggerLikeCommentAlbumN_ADEL = $DB->query(QS_CREATE::$createTriggerLikeCommentAlbumN_ADEL, $id, $id, $id, $id, $id);
                        //if($triggerLikeCommentAlbumN_ADEL != true) return;
                
                ########## SPISOKALBUMPHOTO ##########
                    #
                    # SpisokAlbumPhoto create with trigger
                        $res_tSpisokAlbumPhoto = $DB->query(QS_CREATE::$createTableSpisokalbumphotoN, $id, $id, $id, $id, $id, $id, $id, $id, $id);
                        //if($res_tSpisokAlbumPhoto != true) return;
                        
                        $triggerSpisokAlbumPhotoN_AINS = $DB->query(QS_CREATE::$createTriggerSpisokAlbumPhotoN_AINS, $id, $id, $id, $id, $id, $id, $id);
                        //if($triggerSpisokAlbumPhotoN_AINS != true) return;
                        $triggerSpisokAlbumPhotoN_ADEL = $DB->query(QS_CREATE::$createTriggerSpisokAlbumPhotoN_ADEL, $id, $id, $id, $id, $id, $id, $id);
                        //if($triggerSpisokAlbumPhotoN_ADEL != true) return;
                    #
                    # LikeSpisokAlbumPhoto create with trigger
                        $res_tLikeSpisokAlbumPhoto = $DB->query(QS_CREATE::$createTableLikespisokalbumphotoN, $id, $id, $id, $id, $id, $id, $id, $id, $id, $id, $id);
                        //if($res_tLikeSpisokAlbumPhoto != true) return;
                        
                        $triggerLikeSpisokAlbumPhotoN_AINS = $DB->query(QS_CREATE::$createTriggerLikeSpiSokAlbumphotoN_AINS, $id, $id, $id, $id, $id);
                        //if($triggerLikeSpisokAlbumPhotoN_AINS != true) return;
                        $triggerLikeSpisokAlbumPhotoN_ADEL = $DB->query(QS_CREATE::$createTriggerLikespiSokAlbumphotoN_ADEL, $id, $id, $id, $id, $id);
                        //if($triggerLikeSpisokAlbumPhotoN_ADEL != true) return;
                    #
                    # CommnetSpisokAlbumPhoto create with trigger
                        $res_tCommnetSpisokAlbumPhoto = $DB->query(QS_CREATE::$createTableCommentspisokalbumphotoN, $id, $id, $id, $id, $id, $id, $id, $id, $id, $id, $id, $id, $id, $id, $id, $id, $id, $id, $id, $id, $id, $id, $id, $id);
                        //if($res_tCommnetSpisokAlbumPhoto != true) return;
                        
                        $triggerCommnetSpisokAlbumPhotoN_AINS = $DB->query(QS_CREATE::$createTriggerCommentSpisokAlbumphotoN_AINS, $id, $id, $id, $id, $id);
                        //if($triggerCommnetSpisokAlbumPhotoN_AINS != true) return;
                        $triggerCommnetSpisokAlbumPhotoN_ADEL = $DB->query(QS_CREATE::$createTriggerCommentSpisokAlbumphotoN_ADEL, $id, $id, $id, $id, $id);
                        //if($triggerCommnetSpisokAlbumPhotoN_ADEL != true) return;
                    #
                    # LikeCommentSpisokAlbumPhoto create with trigger
                        $res_tLikeCommentSpisokAlbumPhoto = $DB->query(QS_CREATE::$createTableLikecommentspisokalbumphotoN, $id, $id, $id, $id, $id, $id, $id, $id, $id, $id, $id);
                        //if($res_tLikeCommentSpisokAlbumPhoto != true) return;
                        
                        $triggerLikeCommentSpisokAlbumPhotoN_AINS = $DB->query(QS_CREATE::$createTriggerLikeCommentSpisokAlbumphotoN_AINS, $id, $id, $id, $id, $id);
                        //if($triggerLikeCommentSpisokAlbumPhotoN_AINS != true) return;
                        $triggerLikeCommentSpisokAlbumPhotoN_ADEL = $DB->query(QS_CREATE::$createTriggerLikeCommentSpisokAlbumphotoN_ADEL, $id, $id, $id, $id, $id);
                        //if($triggerLikeCommentSpisokAlbumPhotoN_ADEL != true) return;
                
                ########## AVATAR ##########
                    #
                    # Avatar create 
                        $res_tAvatar = $DB->query(QS_CREATE::$createTableAvatarN, $id);
                        //if($res_tAvatar != true) return;
                    #
                    # LikeAvatar create with trigger
                    #
                    # CommnetAvatar create with trigger
                    #
                    # LikeCommentAvatar create with trigger

                ########## MESSAGE ##########
                    #
                    ## Message create with trigger
                       $res_tMessage = $DB->query(QS_CREATE::$createTableMeassageN, $id, $id, $id, $id, $id, $id, $id, $id, $id, $id, $id, $id);
                       //if($res_tMessage != true) return;
                       #
                    ## Triggers updating count inbox/outbox in profile
                       $res_tMessageAINS = $DB->query(QS_CREATE::$createTriggerMessageNNN_AINS, $id, $id, $id);
                       #
                       $res_tMessageADEL = $DB->query(QS_CREATE::$createTriggerMessageNNN_ADEL, $id, $id, $id);
                       //if($res_tMessage != true) return;
                
                ########## ORGANIZER ##########
                    #
                    # Organizer create with trigger
                        $res_tOrganizer = $DB->query(QS_CREATE::$createTableOrganizerN, $id);
                        //if($res_tOrganizer != true) return;
                
                ########## CONTACTS ##########
                    #
                    # ContactGroupN create with trigger
                        $res_tcontactgroup = $DB->query(QS_CREATE::$createTableContactgroupN, $id);
                        //if($res_tcontactgroup != true) return;
                        $res_tcontactgroup = $DB->query(QS_CREATE::$createTriggerContactGroupNNN_ADEL, $id, $id, $id, $id);
                        //if($res_tcontactgroup != true) return;
                    #
                    # SpisokContactGroupUser create with trigger
                        $res_tSpisokcontactgroupuser = $DB->query(QS_CREATE::$createTableSpisokcontactgroupuserN, $id, $id, $id, $id, $id, $id, $id, $id, $id, $id, $id);
                        //if($res_tSpisokcontactgroupuser != true) return;
                        $res_tSpisokcontactgroupuserTrigger_BINS = $DB->query(QS_CREATE::$createTriggerSpisokContactGroupUserNNN_BINS, $id, $id, $id, $id, $id, $id, $id, $id);
                        //if($res_tSpisokcontactgroupuserTrigger_BINS != true) return;
                        // $res_tSpisokContactGroupUserNNN_BUPD = $DB->query(QS_CREATE::$createTriggerSpisokContactGroupUserNNN_BUPD, $id, $id, $id, $id, $id);
                        
                        $res_tSpisokcontactgroupuserTrigger_ADEL = $DB->query(QS_CREATE::$createTriggerSpisokContactGroupUserNNN_ADEL, $id, $id, $id, $id, $id, $id, $id, $id);
                        //if($res_tSpisokcontactgroupuserTrigger_ADEL != true) return;
                        $res_tSpisokContactGroupUserNNN_AUPD = $DB->query(QS_CREATE::$createTriggerSpisokContactGroupUserNNN_AUPD, $id, $id, $id, $id, $id, $id, $id, $id, $id, $id, $id, $id, $id, $id, $id, $id, $id, $id, $id, $id, $id, $id, $id, $id, $id, $id, $id, $id, $id, $id, $id, $id, $id, $id, $id, $id, $id, $id, $id, $id, $id, $id);
                        //if($res_tSpisokContactGroupUserNNN_AUPD != true) return;
                        
                         # SpisokSubscriber create with trigger
                        $res_tSpisokSubscriber = $DB->query(QS_CREATE::$createTableSpisokSubscriberNNN, $id, $id, $id);
                        //if($res_tSpisokSubscriber != true) return;
                    
                ########## BLOGMETKA ##########
                    #
                    # SpisokBlogMetkaN create with trigger
                        $res_tspisokblogmetka = $DB->query(QS_CREATE::$createTableSpisokblogmetkaN, $id, $id, $id, $id, $id, $id, $id, $id, $id, $id, $id);
                        
                return $execute;
            }
            public static function validateTableFollow($id, $DB=null){
                $bugs = array();

                $bugs['meassage'] = ProfileAuth::existtable('message'.$id, $DB);
                $bugs['avatar'] = ProfileAuth::existtable('avatar'.$id, $DB);
                $bugs['contactgroup'] = ProfileAuth::existtable('contactgroup'.$id, $DB);
                $bugs['spisokcontactgroupuser'] = ProfileAuth::existtable('spisokcontactgroupuser'.$id, $DB);
                $bugs['wall'] = ProfileAuth::existtable('wall'.$id, $DB);
                $bugs['blog'] = ProfileAuth::existtable('blog'.$id, $DB);
                $bugs['spisokblogmetka'] = ProfileAuth::existtable('spisokblogmetka'.$id, $DB);
                $bugs['album'] = ProfileAuth::existtable('album'.$id, $DB);
                $bugs['spisokalbumphoto'] = ProfileAuth::existtable('spisokalbumphoto'.$id, $DB);
                $bugs['commentavatar'] = ProfileAuth::existtable('commentavatar'.$id, $DB);
                $bugs['spisoksubscriber'] = ProfileAuth::existtable('spisoksubscriber'.$id, $DB);
                $bugs['commentwall'] = ProfileAuth::existtable('commentwall'.$id, $DB);
                $bugs['commentblog'] = ProfileAuth::existtable('commentblog'.$id, $DB);
                $bugs['commentalbum'] = ProfileAuth::existtable('commentalbum'.$id, $DB);
                $bugs['commentspisokalbumphoto'] = ProfileAuth::existtable('commentspisokalbumphoto'.$id, $DB);
                $bugs['likeavatar'] = ProfileAuth::existtable('likeavatar'.$id, $DB);
                $bugs['likewall'] = ProfileAuth::existtable('likewall'.$id, $DB);
                $bugs['likeblog'] = ProfileAuth::existtable('likeblog'.$id, $DB);
                $bugs['likealbum'] = ProfileAuth::existtable('likealbum'.$id, $DB);
                $bugs['likespisokalbumphoto'] = ProfileAuth::existtable('likespisokalbumphoto'.$id, $DB);
                $bugs['likecommentavatar'] = ProfileAuth::existtable('likecommentavatar'.$id, $DB);
                $bugs['likecommentwall'] = ProfileAuth::existtable('likecommentwall'.$id, $DB);
                $bugs['likecommentblog'] = ProfileAuth::existtable('likecommentblog'.$id, $DB);
                $bugs['likecommentalbum'] = ProfileAuth::existtable('likecommentalbum'.$id, $DB);
                $bugs['likecommentspisokalbumphoto'] = ProfileAuth::existtable('likecommentspisokalbumphoto'.$id, $DB);
                $bugs['headband'] = ProfileAuth::existtable('headband'.$id, $DB);
                $bugs['news'] = ProfileAuth::existtable('news'.$id, $DB);
                $bugs['spisoknewsphoto'] = ProfileAuth::existtable('spisoknewsphoto'.$id, $DB);
                $bugs['spisoknewsavatar'] = ProfileAuth::existtable('spisoknewsavatar'.$id, $DB);
                $bugs['spisoknewsblog'] = ProfileAuth::existtable('spisoknewsblog'.$id, $DB);
                $bugs['personalnews'] = ProfileAuth::existtable('personalnews'.$id, $DB);
                $bugs['organizer'] = ProfileAuth::existtable('organizer'.$id, $DB);

                return $bugs;
            }
            private function existTable($tName, $DB=null){
                $checktable = $DB->getAll("SHOW TABLES LIKE ?s", $tName); 
                return count($checktable);
            }

        # создать директории пользователя при регистрации
            public static function createFolderProfile($idProfile){
                $dir_path_p = ProfDir::getProfDir($idProfile, ProfDir::$P); // $dir_path_p = ../../data/p1010/
                if(!is_dir($dir_path_p) && mkdir($dir_path_p, 0777, true))
                {
                    $dir_path_p_photo = ProfDir::getProfDir($idProfile, ProfDir::$ALBUMS);  // $dir_path_p_photo = ../../data/p1010/p/
                    if(mkdir($dir_path_p_photo, 0777, true))
                    {
                        // $dir_path_p_photo_ava = ../../data/p1010/p/ava1010/
                        $dir_path_p_photo_ava = ProfDir::getProfDir($idProfile, ProfDir::$ALBUMAVA);
                        if(!mkdir($dir_path_p_photo_ava, 0777, true)) $error[] = $dir_path_p_photo_ava." - already exist!";

                        // $dir_path_p_photo_avam = ../../data/p1010/p/ava1010m/
                        $dir_path_p_photo_avam = ProfDir::getProfDir($idProfile, ProfDir::$ALBUMAVAM);
                        if(!mkdir($dir_path_p_photo_avam, 0777, true)) $error[] = $dir_path_p_photo_avam." - already exist!";
                    } else $error[] = $dir_path_p_photo." - already exist!";
                }
                else $error[] = $dir_path_p." - already exist!";
                if(count($error)) {
                    // ProfDir::rrmdir($dir_path_p); 
                    return false;
                }
                return true;
            }



    #:: is valid cur PSWD / LOGIN ::#
        # соответствует ли пароль паролю авторизированного пользователя
            public function validCurPSWD($pswd_in)
            {
                $row = $this->DB->getRow(QS::$q1_auth, $this->profile->ID);
                return ($row['password']===$pswd_in);
            }
        # return логин авторизированного пользователя
            public function validCurLogin()
            {
                $row = $this->DB->getRow(QS::$q1_auth, $this->profile->ID);
                return $row['login'];
            }
    // Exist request create / change email
        public function getValidCreateChangeRequestEmail()
        {
            return $this->DB->getRow(QS::$q_get_changeRequestEmail, $this->profile->ID);
        }
        public function getValidCreateChangeRequestEmailByHex($hex)
        {
            return $this->DB->getRow(QS::$q_get_changeRequestEmailByHex, $this->profile->ID, $hex);
        }
        public function getValidCreateChangeRequestEmailByHexSomebody($hex)
        {
            return $this->DB->getRow(QS::$q_get_changeRequestEmailByHexSomebody, $hex);
        }
        public function getChangePSWD($pswdnew)
        {
            $pswdnew = $this->profile->privatepswd ? md5_jlife($pswdnew) : $pswdnew;
            return $this->DB->getRow(QS::$q_get_changePSWDByPSWDProfile, $this->profile->ID, $pswdnew);               
        }
    // Send request create / change email
        public function sendChangeRequestEmail($datecreate, $newdata, $datevalid, $hex)
        {
            $this->clearLatterRequestChangeLogin();
            return  $this->DB->query(QS::$q_set_changeRequestEmail, $datecreate, $this->profile->ID, $this->validCurLogin(), mb_strtolower($newdata), $datevalid, $hex);
        }            
        private function clearLatterRequestChangeLogin()
        {
            $this->DB->query(QS::$q_clearLatterRequestChangeEntityNotSuccess, $this->profile->ID, 11);   
        }
        private function cleareRequestCangeEmail($email)
        {
            $this->DB->query(QS::$q_clearLatterRequestChangeEmailByBusy, mb_strtolower($email));
        }
        public function repeatSendChangeRequestLogin($hex)
        {
            $find = $this->DB->getRow(QS::$q_get_repeatSendChangeRequestLogin, $hex);
            // var_dump($find);
            if($find != null) {

                $datecreate = date("Y-m-d H:i:s");
                $datevalid = new DateTime($datecreate);
                $datevalid->modify('+1 day');
                $datevalid = date_format($datevalid, "Y-m-d H:i:s");
                $hex = md5($this->profile->ID."11".$em.$datecreate.$datevalid);
                return $this->sendChangeRequestEmail($datecreate, $find['newdata'], $datevalid, $hex);
            }
            return false;
        }
    // Create / Change email
        public function changeEmail($hex) {
            $this->DB->query('CALL uCloseSuccessValidRequestChangeEmail(?i,?s,@REZ,@REZ_EMAIL)', $this->profile->ID, $hex);
            $t = $this->DB->getRow('SELECT @REZ as "o", @REZ_EMAIL as "rez_email"');
            if($t['o']) $this->cleareRequestCangeEmail($t['rez_email']);
            // update SESSION AUTH // update COOKIES AUTH
            $_SESSION["INFO_MAIL_CHANGED"] = true;
            return array('o'=>$t['o'],'nem'=>$t['rez_email']);
        }
    // Change pswd
        public function changePSWD($pswd)
        {
            // $this->DB->query('', $this->profile->ID, $hex);
            // insert в changeprivatedata
            // update в profile
            $this->DB->query('CALL uChangePSWD(?i,?s,@REZ)', $this->profile->ID, $pswd);
            $t = $this->DB->getRow('SELECT @REZ as "o"');
            return array('o'=>$t['o']);
        }
}
?>