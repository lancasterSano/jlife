var SETTING_COUNT_FIRST_LOAD_ENTITY = 10,		// количество записей для первой загрузки
SETTING_COUNT_CONTINUATION_LOAD_ENTITY = 5,		// количество записей при дозагрузки/скрола
SETTING_COUNT_FIRST_LOAD_COMMENTS = 2,			// количество комментариев для первой загрузки
SETTING_COUNT_FIRST_LOAD_MESSAGES = 5,			// количество сообщений для первой загрузки
SETTING_COUNT_CONTINUATION_LOAD_MESSAGES = 3,	// количество сообщений при дозагрузке/скроллинге
SETTING_ADRESS_COMMENT = ', ',
SETTING_COUNT_FIRST_LOAD_FRIENDS = 12,
SETTING_COUNT_FIRST_LOAD_REQUESTS = 3,
SETTING_COUNT_FIRST_LOAD_SUBSCRIBERS = 3,
SETTING_COUNT_FIRST_LOAD_PHOTOALBUMCOMMENTS = 20;

var PR_PATH_PICTURE = '/data/',
PR_P_F_AVAA = 'AVAA/',
PR_P_F_AVAS = 'AVAS/',
PR_P_F_HB = 'HBB/',
PR_P_F_PHOTO = 'p/';

// # (relative for PROJECT FOLDER)
// # 		s - userpic комменты, 	посты	m - перспектива фотографии		a - полноценная ава 	b - фотография аолного размера
// # Placeholder:
// #		IDP - id profile 		IDA - id album		IDPHOTO - name photo file

// # PROFILE PATH to any photo
	var P_PHOTO_B = '/data/pIDP/Photo/alIDAb/IDPHOTO'/*.'.jpeg'*/,
	P_PHOTO_M = '/data/pIDP/Photo/alIDAm/IDPHOTO'/*.'.jpeg'*/,
	P_PHOTO_AVA_B = '/data/pIDP/Photo/avaIDAb/IDPHOTO'/*.'.jpeg'*/,
	P_PHOTO_AVA_M = '/data/pIDP/Photo/avaIDAm/IDPHOTO'/*.'.jpeg'*/,
	P_AVAA = '/data/AVAA/IDPHOTO'/*.'.jpeg'*/,
	P_AVAS = '/data/AVAS/IDPHOTO'/*.'.jpeg'*/,
	P_HBB = '/data/HBB/IDPHOTO'/*.'.jpeg'*/,
	P_DEF_AVAA = '/data/d/AVAA/IDPHOTO'/*.'.jpeg'*/,
	P_DEF_AVAS = '/data/d/AVAS/IDPHOTO'/*.'.jpeg'*/,
	P_DEF_HBB = '/data/d/HBB/IDPHOTO'/*.'.jpeg'*/,	
	P_DEF_AVA_PHOTO = 'd1.jpg',
	P_DEF_HBB_PHOTO = 'd2.jpg';

// \u0030-\u0039	: 0-9
// \u0410-\u044f	: russion letters upper/notupper
// \u0041-\u005a	: english letters upper
// \u0061-\u007a	: english letters notupper
// \u0020			: space
// \u0401\u0451		: Ёё
var REG_LCN_START_ADRESS_COMMENT = new RegExp("([\u0030-\u0039\u0410-\u044f\u0041-\u005a\u0061-\u007a\u0401\u0451]{1,}\u0020{1}[\u0030-\u0039\u0410-\u044f\u0041-\u005a\u0061-\u007a\u0401\u0451]{1,}[,]{1}\u0020{1}){1}","g"),
ALL_LETTER_LCN = "[\u0030-\u0039\u0410-\u044f\u0041-\u005a\u0061-\u007a\u0401\u0451]",
// полное обращение
REG_LCN_START_ADRESS_COMMENT_FULL = new RegExp("^[\u0030-\u0039\u0410-\u044f\u0041-\u005a\u0061-\u007a\u0401\u0451]{1,}\u0020{1}[\u0030-\u0039\u0410-\u044f\u0041-\u005a\u0061-\u007a\u0401\u0451]{1,}[,]{1}\u0020{1}",""),
// Обращение безя запятой (SETTING_ADRESS_COMMENT)
REG_LCN_START_ADRESS_COMMENT_WITHOUT_SAC = new RegExp("^[\u0030-\u0039\u0410-\u044f\u0041-\u005a\u0061-\u007a\u0401\u0451]{1,}\u0020{1}[\u0030-\u0039\u0410-\u044f\u0041-\u005a\u0061-\u007a\u0401\u0451]{1,}",""),
// текст без обращения и запятой
REG_LCN_TEXT_COMMENT_WITHOUT_ADRESS_AND_SAC = new RegExp("^[\u0030-\u0039\u0410-\u044f\u0041-\u005a\u0061-\u007a\u0401\u0451]{1,}\u0020{1}[\u0030-\u0039\u0410-\u044f\u0041-\u005a\u0061-\u007a\u0401\u0451]{1,}[,]{1}\u0020{1}","");



/********************/
// ERRORS MESSAGES
var ERROR_ADD_COMMENT = "Добавление не выполнено, произошла ошибка. Попробуйте позже.",
ERROR = "",
ERROR = "",
ERROR = "",
ERROR = "",
ERROR = "",
ERROR = "",
ERROR = "";