<?php
require_once '.htpaths';
require_once(PROJECT_PATH."/php/class/ProfileAuth.php");
require_once(PROJECT_PATH."/settings/l_const.php");
require_once(PROJECT_PATH."/settings/l_const_do.php");
require_once(PROJECT_PATH."/settings/devs_settings.php");

// defined('SETTING_debug') or define('SETTING_debug', false);						// включить ли debug во всем проекте
// defined('SETTING_debug_js') or define('SETTING_debug_js', false);				// включить ли в js режим debug
// defined('SETTING_debug_js_ignore') or define('SETTING_debug_js_ignore', false);	// JS: игнорировать режим debug (для авторизации)
// defined('SETTING_debug_php') or define('SETTING_debug_php', false);				// включить ли debug в php

// defined('SYS_BASE_MAINDOMAIN') or define('SYS_BASE_MAINDOMAIN', 'jlifelocal.ru');
// defined('SYS_BASE_EVALUATION') or define('SYS_BASE_EVALUATION', 'evolution.'.SYS_BASE_MAINDOMAIN);
// defined('SYS_BASE_SOCIAL') or define('SYS_BASE_SOCIAL', 'social'.SYS_BASE_MAINDOMAIN);
// defined('SYS_BASE_AUTH') or define('SYS_BASE_AUTH', 'auth'.SYS_BASE_MAINDOMAIN);

// // define('SETTING_TIME_COOCKIE', 60*60*24);				// время жизни куков (60 - минута) 60*60*24*30	60*60*24
// //	[SETTING_TIME_COOCKIE_ONLINE may be more SETTING_TIME_COOCKIE_REFRESH_ONLINE]
// // время жизни куков (60 - минута) 180
// 	defined('SETTING_TIME_COOCKIE_ONLINE') or define('SETTING_TIME_COOCKIE_ONLINE', 120*1000); 		
// // интервал обновления жизни куков (60 - минута) 120
// 	defined('SETTING_TIME_COOCKIE_REFRESH_ONLINE') or define('SETTING_TIME_COOCKIE_REFRESH_ONLINE', 20*1000);

// #### CONST ####
define('SETTING_md5', false);
define('MD5_SOLT', 'CHSKJLIFE');
define('SETTING_START_REGISTRATION', 1000);

define('SETTING_HOME_REDIRECT', "/pages/index.php"); 	// куда перенаправлять при входе. Без параметра $_SESSION['REQUEST_URI']
define('SETTING_LOGIN_REDIRECT', "/auth/auth/login"); 	// куда перенаправлять при входе. Без параметра $_SESSION['REQUEST_URI']
define('SETTING_PROFILE_ADRESS', "/pages/index.php"); 	// страница авторизированного пользователя

define('SETTING_COUNT_FIRST_LOAD_FRIENDS', 12);			// количество друзей в группе для первой загрузки
define('SETTING_COUNT_FIRST_LOAD_REQUESTS', 3);			// количество запросов в друзья для первой загрузки
define('SETTING_COUNT_FIRST_LOAD_SUBSCRIBERS', 3);			// количество запросов в друзья для первой загрузки
define('SETTING_COUNT_FIRST_LOAD_ENTITY', 10);			// количество записей для первой загрузки
define('SETTING_COUNT_CONTINUATION_LOAD_ENTITY', 5);	// количество записей при дозагрузки/скрола
define('SETTING_COUNT_FIRST_LOAD_COMMENTS', 2);			// количество комментариев для первой загрузки
define('SETTING_COUNT_FIRST_LOAD_MESSAGES', 5);			// количество сообщений для первой загрузки
define('SETTING_COUNT_CONTINUATION_LOAD_MESSAGES', 3);	// количество сообщений при дозагрузке/скроллинге
define('SETTING_ADRESS_COMMENT', ', ');

define('PR_PATH_PICTURE', '/data/');
define('PR_P_F_AVAA', 'AVAA/');
define('PR_P_F_AVAS', 'AVAS/');
define('PR_P_F_HB', 'HBB/');
define('PR_P_F_PHOTO', 'p/');

define('PR_TYPE_PHOTO', '.jpeg');


# (relative for PROJECT FOLDER)
# 		s - userpic комменты, 	посты	m - перспектива фотографии		a - полноценная ава 	b - фотография аолного размера
# Placeholder:
#		IDP - id profile 		IDA - id album		IDPHOTO - name photo file

# PROFILE PATH to any photo
	define('P_PHOTO_B',PR_PATH_PICTURE.'pIDP/Photo/alIDAb/IDPHOTO'/*.'.jpeg'*/);
	define('P_PHOTO_M',PR_PATH_PICTURE.'pIDP/Photo/alIDAm/IDPHOTO'/*.'.jpeg'*/);
	define('P_PHOTO_AVA_B',PR_PATH_PICTURE.'pIDP/Photo/avaIDAb/IDPHOTO'/*.'.jpeg'*/);
	define('P_PHOTO_AVA_M',PR_PATH_PICTURE.'pIDP/Photo/avaIDAm/IDPHOTO'/*.'.jpeg'*/);
	define('P_AVAA',PR_PATH_PICTURE.'AVAA/IDPHOTO'/*.'.jpeg'*/);
	define('P_AVAS',PR_PATH_PICTURE.'AVAS/IDPHOTO'/*.'.jpeg'*/);
	define('P_HBB',PR_PATH_PICTURE.'HBB/IDPHOTO'/*.'.jpeg'*/);
	define('P_DEF_AVAA',PR_PATH_PICTURE.'d/AVAA/SEX/IDPHOTO'/*.'.jpeg'*/);
	define('P_DEF_AVAS',PR_PATH_PICTURE.'d/AVAS/SEX/IDPHOTO'/*.'.jpeg'*/);
	define('P_DEF_HBB',PR_PATH_PICTURE.'d/HBB/IDPHOTO'/*.'.jpeg'*/);	
	define('P_DEF_AVA_PHOTO','d1.jpg');
	define('P_DEF_HBB_PHOTO','d2.jpg');




define('REG_LITERAL_EMAIL', '/^([a-z0-9_\.-]+)@([a-z0-9_\.-]+)\.([a-z\.]{2,6})$/');
define('REG_LITERAL_PSWD', '/^[a-z0-9A-Za-z_-]{6,32}$/');

define('YII_REG_SUBFOLDER_REPLACER_do', '#^/pages/do#');
define('YII_REG_SUBFOLDER_REPLACER_social', '#^/pages#');

/*
 * Smarty plugin
 * -------------------------------------------------------------
 * File:     function.eightball.php
 * Type:     function
 * Name:     eightball
 * Purpose:  outputs a random magic answer
 * -------------------------------------------------------------
 */
function smarty_function_debug_out($params, &$smarty)
{
	foreach ($params['context'] as $name => $value) {
		echo "<span class='debug'><b>$name</b> <span class='debug'>".var_dump($value)."</span></span><br/>";
	}
    return $answers[$params['i']];
}
?>