<?php
class ProfDir {
        static public $JLIFEDATA = 21; // директория данных проекта
        static public $ALBUMS = 9; // директория альбомов профиля
        static public $ALBUMM = 8; 
        static public $ALBUM = 7;
        static public $ALBUMAVAM = 6; 
        static public $ALBUMAVA = 5; 
        static public $AVAA = 4; 
        static public $AVAS = 3; 
        static public $HB = 2;
        static public $P = 1; // директория профиля
        static public function existSuccessDir($path) {
            return is_writable($path);
        }
        // When the directory is not empty:
        static public function rrmdir($dir) {
            if (is_dir($dir)) {
                $objects = scandir($dir);
                foreach ($objects as $object) {
                    if ($object != "." && $object != "..") {
                        if (filetype($dir."/".$object) == "dir") ProfDir::rrmdir($dir."/".$object); else unlink($dir."/".$object);
                    }
                }
                reset($objects);
                ProfDir::rrmdir($dir);
            }
        }
        // возвращает путь к фото по настройкам проекта
        static public function getProfDir($idProfile, $dir_type, $folder='default'){
            $dir_path = PROJECT_PATH.PR_PATH_PICTURE;
            $name_f_p = 'p'.$idProfile;
            $dir_path_p = $dir_path.$name_f_p.'/';
            $dir_path_p_photo = $dir_path_p.PR_P_F_PHOTO;
            $dir_path_p_f = null;
            switch ($dir_type) {
                case 1: # P
                    $dir_path_p_f = $dir_path_p;
                    break;
                case 2: # HB
                    $dir_path_p_f = $dir_path.PR_P_F_HB;
                    break;
                case 3: # AVAS
                    $dir_path_p_f = $dir_path.PR_P_F_AVAS;
                    break;
                case 4: # AVAA
                    $dir_path_p_f = $dir_path.PR_P_F_AVAA;
                    break;
                case 5: # ALBUMAVA
                    $dir_path_p_f = $dir_path_p_photo.'ava'.$idProfile.'/';
                    break;
                case 6: # ALBUMAVAM
                    $dir_path_p_f = $dir_path_p_photo.'ava'.$idProfile.'m/';
                    break;
                case 7: # ALBUM
                    $dir_path_p_f = $dir_path_p_photo.'album'.$folder.'/';
                    break;
                case 8: # ALBUMM
                    $dir_path_p_f = $dir_path_p_photo.'album'.$folder.'m/';
                    break;
                case 9: # ALBUM's
                    $dir_path_p_f = $dir_path_p_photo.'/';
                    break;
                case 21: # JLIFEDATA
                    $dir_path_p_f = $dir_path;
                    break;            
                default: break;
            }
            return $dir_path_p_f;
        }
        static public function existCreateDirectory($dir, $create=0){
            // if(is_dir($dir)){
                if(file_exists($dir)){
                    if(is_writable($dir)) return true;
                    else return chmod($dir, 0777);
                }
                else if($create) {
                    try{
                        return mkdir($dir, 0777, true);
                    }catch(Exception $e){
                        return false;
                    }
                }
            // }
            return false;
        }
}
?>