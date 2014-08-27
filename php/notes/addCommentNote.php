<?php
require_once '.htpaths';
require_once(PROJECT_PATH."/php/class/ProfileAuth.php");
require_once(PROJECT_PATH."/php/common.php");
require_once(PROJECT_PATH."/settings/settings.php");
/**** AuthentificateProfile ***/ require_once PROJECT_PATH."/php/authorization.php";

if (isset ($_POST['idAuth']))   { $idProfAuth=$_POST['idAuth'];}
if (isset ($_POST['idLoad']))   { $idProfLoad=$_POST['idLoad'];}
if (isset ($_POST['textCN']))   { $textCommentNote=$_POST['textCN'];}
if (isset ($_POST['idNote']))   { $idNote=$_POST['idNote'];}
if (isset ($_POST['idAnswer']))   { $idAnswer=$_POST['idAnswer'];}

if(isset ($idProfAuth) && isset ($idProfLoad) && isset ($textCommentNote) && isset ($idNote) && isset ($idAnswer)
    && !empty ($idProfAuth) && !empty ($idProfLoad) && !empty ($textCommentNote) && !empty ($idNote)
    )
{
    if($idAnswer==null) $idAnswer = null; 
    $len_msg = 256;
    $textCommentNote = normalize(htmlspecialchars($textCommentNote));
    if( ((mb_strlen($textCommentNote)-$len_msg)) > 0 )
    {
        //echo ((strlen($textCommentNote)/$len_msg)+1); //exit();
        $c = (int)(mb_strlen($textCommentNote)/$len_msg); // сколько полных минимальных text
        $str = array();
        for($i=0; $i<=$c; $i++)
        {
            $text = null;
            $text = mb_substr($textCommentNote, $i*$len_msg, $len_msg);
            //echo "C ".$i*$len_msg."  L=".$len_msg." L(b)=".strlen($text)."   ".$text."<br/><br/>";
            $str[$i] = $text;            
        }
        for($i=0; $i<=$c; $i++)
        {
            if($i==0)
            {
                //echo $str[$i]."<br/><br/>";
                $p = $DB->query(QS::$insertCommentWall, $idProfLoad, $idProfLoad, $idProfLoad, $idProfAuth, date("Y-m-d H-i-s"), $str[$i], $idNote, $idAnswer, null); 
                // get $idFirstInsert
                $idFirstInsert = $DB->getOne(QS::$q13, $idProfLoad, $idProfLoad, $idProfAuth);
            }
            else 
            {
                //echo $str[$i]."<br/><br/>";
                $p = $DB->query(QS::$insertCommentWall, $idProfLoad, $idProfLoad, $idProfLoad, $idProfAuth, date("Y-m-d H-i-s"), $str[$i], $idNote, $idAnswer, $idFirstInsert); 
            } 
        }
    }
    else 
    {
        $p = $DB->query(QS::$insertCommentWall, $idProfLoad, $idProfLoad, $idProfLoad, $idProfAuth, date("Y-m-d H-i-s"), $textCommentNote, $idNote, $idAnswer, null);
        $idFirstInsert = $DB->getOne(QS::$q13, $idProfLoad, $idProfLoad, $idProfAuth);
    }
    if(!empty ($idFirstInsert))
    {
        $q = $DB->getAll(QS::$q20, $idProfLoad, $idProfLoad, $idProfLoad, $idProfLoad, $idNote, $idFirstInsert);
        require("prepareCommentToPost.php");  
        //var_dump($comments);      
        $rez = array("insertedcomment", $comments, $idFirstInsert);
    }else $rez = array("insertedcomment", null); 
} else $rez = array("unkmnown", null);
print json_encode($rez);
?>