<?php
require_once '.htpaths';

	// defined('SYS_BASE_MAINDOMAIN') or define('SYS_BASE_MAINDOMAIN', 'jlifelocal.ru');
	// defined('SYS_BASE_EVALUATION') or define('SYS_BASE_EVALUATION', 'evolution.'.SYS_BASE_MAINDOMAIN);
	// defined('SYS_BASE_SOCIAL') or define('SYS_BASE_SOCIAL', SYS_BASE_MAINDOMAIN);
	// defined('SYS_BASE_AUTH') or define('SYS_BASE_AUTH', 'auth.'.SYS_BASE_MAINDOMAIN);
	if($_SERVER['SERVER_NAME']){
		defined('SYS_BASE_MAINDOMAIN') or define('SYS_BASE_MAINDOMAIN', $_SERVER['SERVER_NAME']);		
	}
	defined('SYS_BASE_EVALUATION') or define('SYS_BASE_EVALUATION', 'evolution.'.SYS_BASE_MAINDOMAIN);
	defined('SYS_BASE_SOCIAL') or define('SYS_BASE_SOCIAL', SYS_BASE_MAINDOMAIN);
	defined('SYS_BASE_AUTH') or define('SYS_BASE_AUTH', 'auth.'.SYS_BASE_MAINDOMAIN);
	
if(($_SERVER['HTTP_HOST']=='localhost' || $_SERVER['HTTP_HOST']=='devsjlife.com' || $_SERVER['HTTP_HOST']=='jlifelocal.ru')){
	// DEV
	defined('SETTING_debug') or define('SETTING_debug', false);						// включить ли debug во всем проекте
	defined('SETTING_debug_js') or define('SETTING_debug_js', true);				// включить ли в js режим debug
	defined('SETTING_debug_js_ignore') or define('SETTING_debug_js_ignore', true);	// JS: игнорировать режим debug (для авторизации)
	defined('SETTING_debug_php') or define('SETTING_debug_php', false);				// включить ли debug в php


	// define('SETTING_TIME_COOCKIE', 60*60*24);				// время жизни куков (60 - минута) 60*60*24*30	60*60*24
	//	[SETTING_TIME_COOCKIE_ONLINE may be more SETTING_TIME_COOCKIE_REFRESH_ONLINE]
	// время жизни куков (60 - минута) 180
		defined('SETTING_TIME_COOCKIE_ONLINE') or define('SETTING_TIME_COOCKIE_ONLINE', 120*1000); 		
	// интервал обновления жизни куков (60 - минута) 120
		defined('SETTING_TIME_COOCKIE_REFRESH_ONLINE') or define('SETTING_TIME_COOCKIE_REFRESH_ONLINE', 5*1000);
} else{
	// SERVER
	defined('SETTING_debug') or define('SETTING_debug', false);						// включить ли debug во всем проекте
	defined('SETTING_debug_js') or define('SETTING_debug_js', true);				// включить ли в js режим debug
	defined('SETTING_debug_js_ignore') or define('SETTING_debug_js_ignore', false);	// JS: игнорировать режим debug (для авторизации)
	defined('SETTING_debug_php') or define('SETTING_debug_php', false);				// включить ли debug в php


	// define('SETTING_TIME_COOCKIE', 60*60*24);				// время жизни куков (60 - минута) 60*60*24*30	60*60*24
	//	[SETTING_TIME_COOCKIE_ONLINE may be more SETTING_TIME_COOCKIE_REFRESH_ONLINE]
	// время жизни куков (60 - минута) 180
		defined('SETTING_TIME_COOCKIE_ONLINE') or define('SETTING_TIME_COOCKIE_ONLINE', 120*1000); 		
	// интервал обновления жизни куков (60 - минута) 120
		defined('SETTING_TIME_COOCKIE_REFRESH_ONLINE') or define('SETTING_TIME_COOCKIE_REFRESH_ONLINE', 20*1000);

}

?>