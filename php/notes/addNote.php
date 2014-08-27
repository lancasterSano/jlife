<?php
require_once '.htpaths';
require_once(PROJECT_PATH."/php/class/ProfileAuth.php");
require_once(PROJECT_PATH."/php/common.php");
require_once(PROJECT_PATH."/settings/settings.php");
/**** AuthentificateProfile ***/ require_once PROJECT_PATH."/php/authorization.php";

if (isset ($_POST['idAuth']))   { $idProfAuth=$_POST['idAuth'];}
if (isset ($_POST['idLoad']))   { $idProfLoad=$_POST['idLoad'];}
if (isset ($_POST['textN']))   { $textNote = $_POST['textN']; }
if(isset ($idProfAuth) && isset ($idProfLoad) && isset ($textNote) 
    && !empty ($idProfAuth) && !empty ($idProfLoad) && !empty ($textNote))
{
    
    $len_msg = 256;
    $len_msg_real = 127;
    $textNote = normalize(htmlspecialchars($textNote));
    if( ((mb_strlen($textNote)-$len_msg)) > 0 )
    {
        //echo ((strlen($textNote)/$len_msg)+1); //exit();
        $c = (int)(mb_strlen($textNote)/$len_msg); // сколько полных минимальных text
        $str = array();
        for($i=0; $i<=$c; $i++)
        {
            $text = null;
            $text = mb_substr($textNote, $i*$len_msg, $len_msg);
            //echo "C ".$i*$len_msg."  L=".$len_msg." L(b)=".strlen($text)."   ".$text."<br/><br/>";
            $str[$i] = $text;            
        }
        for($i=$c; $i>=0; $i--)
        {
            if($i==$c)
            {
                //echo $str[$i]."<br/><br/>";
                $p = $DB->query(QS::$insertWall, $idProfLoad, $str[$i], date("Y-m-d H-i-s"), $idProfAuth, null);
                // get $idFirstInsert
                $idFirstInsert = $DB->getOne(QS::$q12, $idProfLoad, $idProfLoad, $idProfAuth);
            }
            else 
            {
                //echo $str[$i]."<br/><br/>";
                $p = $DB->query(QS::$insertWall, $idProfLoad, $str[$i], date("Y-m-d H-i-s"), $idProfAuth, $idFirstInsert);
            } 
        }
    }
    else 
    { 
        $p = $DB->query(QS::$insertWall, $idProfLoad, $textNote, date("Y-m-d H-i-s"), $idProfAuth, null);
        $idFirstInsert = $DB->getOne(QS::$q12, $idProfLoad, $idProfLoad, $idProfAuth);
    }
    if(!empty ($idFirstInsert))
    {
        $q = $DB->getAll(QS::$q19, $idProfLoad, $idFirstInsert);
        require("prepareNoteToPost.php");        
        $rez = array("insertednote", $notes, $idFirstInsert);
    }else $rez = array("insertednote", null); 
} else $rez = array("unkmnown", null);
print json_encode($rez);
?>