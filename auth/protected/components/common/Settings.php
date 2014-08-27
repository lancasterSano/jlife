<?php
	class Settings
	{
	// throw new Exception("Error Processing Request", 1;

		// #### CONST ####
		const SETTING_md5 = false;
		const MD5_SOLT = 'CHSKJLIFE';
		const SETTING_START_REGISTRATION = 1000;

		const SETTING_HOME_REDIRECT = "/pages/index.php"; 	// куда перенаправлять при входе. Без параметра $_SESSION['REQUEST_URI']
		const SETTING_LOGIN_REDIRECT = "/auth/auth/login"; 	// куда перенаправлять при входе. Без параметра $_SESSION['REQUEST_URI']
		const SETTING_PROFILE_ADRESS = "/pages/index.php"; 	// страница авторизированного пользователя

		const SETTING_COUNT_FIRST_LOAD_FRIENDS = 12;			// количество друзей в группе для первой загрузки
		const SETTING_COUNT_FIRST_LOAD_REQUESTS = 3;			// количество запросов в друзья для первой загрузки
		const SETTING_COUNT_FIRST_LOAD_SUBSCRIBERS = 3;			// количество запросов в друзья для первой загрузки
		const SETTING_COUNT_FIRST_LOAD_ENTITY = 10;			// количество записей для первой загрузки
		const SETTING_COUNT_CONTINUATION_LOAD_ENTITY = 5;	// количество записей при дозагрузки/скрола
		const SETTING_COUNT_FIRST_LOAD_COMMENTS = 2;			// количество комментариев для первой загрузки
		const SETTING_COUNT_FIRST_LOAD_MESSAGES = 5;			// количество сообщений для первой загрузки
		const SETTING_COUNT_CONTINUATION_LOAD_MESSAGES = 3;	// количество сообщений при дозагрузке/скроллинге
		const SETTING_ADRESS_COMMENT = ', ';

		const PR_PATH_PICTURE = '/data/';
		const PR_P_F_AVAA = 'AVAA/';
		const PR_P_F_AVAS = 'AVAS/';
		const PR_P_F_HB = 'HBB/';
		const PR_P_F_PHOTO = 'p/';

		const PR_TYPE_PHOTO = '.jpeg';

		# (relative for PROJECT FOLDER)
		# 		s - userpic комменты, 	посты	m - перспектива фотографии		a - полноценная ава 	b - фотография аолного размера
		# Placeholder:
		#		IDP - id profile 		IDA - id album		IDPHOTO - name photo file

		# PROFILE PATH to any photo
			public static function P_PHOTO_B(){ return Settings::PR_PATH_PICTURE.'pIDP/Photo/alIDAb/IDPHOTO'; }
			public static function P_PHOTO_M(){ return Settings::PR_PATH_PICTURE.'pIDP/Photo/alIDAm/IDPHOTO'; }
			public static function P_PHOTO_AVA_B(){ return Settings::PR_PATH_PICTURE.'pIDP/Photo/avaIDAb/IDPHOTO'; }
			public static function P_PHOTO_AVA_M(){ return Settings::PR_PATH_PICTURE.'pIDP/Photo/avaIDAm/IDPHOTO'; }
			public static function P_AVAA(){ return Settings::PR_PATH_PICTURE.'AVAA/IDPHOTO'; }
			public static function P_AVAS(){ return Settings::PR_PATH_PICTURE.'AVAS/IDPHOTO'; }
			public static function P_HBB(){ return Settings::PR_PATH_PICTURE.'HBB/IDPHOTO'; }
			public static function P_DEF_AVAA(){ return Settings::PR_PATH_PICTURE.'d/AVAA/SEX/IDPHOTO'; }
			public static function P_DEF_AVAS(){ return Settings::PR_PATH_PICTURE.'d/AVAS/SEX/IDPHOTO'; }
			public static function P_DEF_HBB(){ return Settings::PR_PATH_PICTURE.'d/HBB/IDPHOTO'; }
			public static function P_DEF_AVA_PHOTO(){ return 'd1.jpg'; }
			public static function P_DEF_HBB_PHOTO(){ return 'd2.jpg'; }

		// public static function REG_LITERAL_EMAIL(){ return Settings::'/^([a-z0-9_\.-]+)@([a-z0-9_\.-]+)\.([a-z\.]{2,6})$/'; }
		// public static function REG_LITERAL_PSWD(){ return Settings::'/^[a-z0-9A-Za-z_-]{6,32}$/'; }
	}
?>