<?php 
# Version 1.0
require_once '.htpaths';
require_once(PROJECT_PATH."/include/inc_smarty.php"); // IN обьект $smarty
require_once(PROJECT_PATH."/php/class/ProfileAuth.php");
require_once(PROJECT_PATH."/php/common.php");
require_once(PROJECT_PATH."/php/class/QueryStorage.php");
require_once(PROJECT_PATH.'/lib/lib_bd/safemysql.class.php');
$smarty->assign('is_do', 0); $smarty->assign('block_page_sys', "addphoto");
/**** AuthentificateProfile ***/ require_once PROJECT_PATH."/php/authorization.php"; $smarty->assign('isAuth', true);

$ProfileAuth = $UA->getProfile();

$smarty->assign('ProfileAuth', $ProfileAuth);
$smarty->assign('PROJECT_PATH', PROJECT_PATH);



$ProfileLoad = $ProfileAuth;

$smarty->assign('PageTitle', 'Альбом'.$ProfileLoad->FI());
$smarty->assign('ProfileLoad', $ProfileLoad);

$photoAlbom = array(
        "idPhotoAlbom" => '15',  // id
        "namePhotoAlbom" => 'Вот так вот',  // name
    );
$smarty->assign('photoAlbom',$photoAlbom);

    $photo = array(
        "idPhoto" => '15',  // id
		"countComent" =>'20' ,  //  
        "photoPath" => '../img/34.jpg',  //  photopath
        "countLikePhoto" =>'50', //   countlike
        "isProfileAuthSetLike" =>'',
        
    );

$smarty->assign('photo',$photo);


/************* MAIN_CONTENT *************/
require_once("./page_part/smarty_incut.php");
$smarty->assign('pageFormat', 2);
$smarty->assign('numTab', 51);

$block_include_head = $smarty->fetch("./include_head/USMF/inc_addPhoto.tpl"); $smarty->assign('block_include_head', $block_include_head);

$topUnionSub_subl = $smarty->fetch("./mainContent/union/unionSub/topUnionAlbumSub.tpl"); $smarty->assign('topUnionSub_subl', $topUnionSub_subl);
$topUGUSMF_tpl = $smarty->fetch("./mainContent/union/topUGUSMF.tpl"); $smarty->assign('block_union_general', $topUGUSMF_tpl);

$wallMainField_tpl = $smarty->fetch("./mainContent/mainfield/addPhoto.tpl"); $smarty->assign('block_mainField', $wallMainField_tpl);
/************ запускаем показ шаблона smarty *************/
$smarty->display("pageUSMF.tpl");
?>