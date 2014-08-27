/* Notes */
var notes_create_note='Создать запись...',
notes_create_note_comment='Написать комментарий...',
notes_count='заметок',
notes_count_empty='Нет заметок',
notes='';

/************  ERRORS MESSAGES  ***************/
var ERROR_ADD_COMMENT = "Добавление не выполнено, произошла ошибка. Попробуйте позже.";

/************  MESSAGES  ***************/
 var MT_INFO = 1, 
 	MT_ERROR = 2,
 	MT_WARNING = 3, 
 	MT_SUCCESS = 4;
 /*	Правила составления константных переменных:
 *	1. Начало переменной 
 *		- 	ME_ (MessageError) - сообщение об ошибке
 * 		- 	MW_ (MessageWarning) - сообщение предупреждение
 *		- 	MI_ (MessageInformation) - сообщение информационное
 *		- 	MS_ (MessageSuccess) - сообщение об успехе
 *	2. Вторая часть переменной должна отоброжать конкретный смысл
 *		- 	TEACHER_HAS_NO_SUBGROUPS - (Учитель не является преподавателем ни в одной группе)
 *	3. Третья часть переменной длжна содержать в себе _TITLE либо _TEXT - в зависимости от контекста
 */

/********  CREATE/CHANGE EMAIL  ********/
var MI_REQUEST_CREATE_MAIL_TITLE = 'на почту (%MAIL) было выслано письмо для создания почтового адресса (<span data="%HEX">повторить</span>)',
	MI_REQUEST_CREATE_MAIL_TEXT = 'письмо отправлено %DATE_SEND и будет действительно в течении суток.',
	MI_REQUEST_CHANGE_MAIL_TITLE = 'На почту (%MAIL) было выслано письмо для изменения почтового адресса (<span data="%HEX">повторить</span>)',
	MI_REQUEST_CHANGE_MAIL_TEXT = 'письмо отправлено %DATE_SEND и будет действительно в течении суток.',
	ME_VALID_PSWD_O_N_EMPTY_TITLE = '%s не указан';
	ME_VALID_PSWD_NS_EMPTY_TITLE = '%s не указано';
	MEARR_I_VARIABLES_PASSWORD = ['текущий пароль','новый пароль','подтверждение пароля'];
	MEARR_In_VARIABLES_PASSWORD = ['текущем пароле','новом пароле','подтверждении пароля'];
	ME_VALID_PSWD_FAIL_TITLE = 'неправильно указан %s',
	ME_VALID_EMAIL_EMPTY_TITLE = 'email не указан';
	ME_VALID_EMAIL_EXIST_TITLE = 'email уже занят';
	ME_VALID_EMAIL_COPY_TITLE = 'данный email у Вас уже установлен',
	ME_VALID_PSWD_COPY_TITLE = 'указанный вами %s у Вас уже установлен',
	ME_VALID_EMAIL_FAIL_TITLE = 'проверьте правильность электронной почты',
	ME_VALID_EMAIL_FAIL_TEXT = 'Можно использовать буквы латинского алфавита, цифры, точки',
	ME_ERROR_SEND_DATA = 'произошла ошибка. Попробуйте позже.',
	// MS_ACCEPT_REQUEST_CREATE_MAIL_TITLE = 'Изменение email на новый (%MAIL).',
	// MS_ACCEPT_REQUEST_CREATE_MAIL_TEXT = 'Для подтверждения укажите ваш действующий пароль.',
	MS_CREATE_MAIL_SUCCESS_TITLE = 'email успешно изменен на (%MAIL).',
	MS_CHANGE_PSWD_SUCCESS_TITLE = 'пароль успешно изменен',
	ME_VALID_PSWD_LENGTH_TITLE = '%s не соответствует требованиям по длине';
	ME_VALID_PSWD_LENGTH_TEXT = 'длина должна быть минимум 6 и не более 32 символов';
	ME_VALID_PSWD_CHAR_TITLE = 'в %s указаны недопустимые символы';
	ME_VALID_PSWD_CHAR_TEXT = 'можно использовать цифры, буквы русского и латинского алфавита';
	ME_VALID_PSWDN_PSWDNS_NE = 'пароли не совпадают';
	ME_VALID_FORM = 'Чтобы продолжить, пожалуйста, заполните все поля формы';
	ME_VALID_PSWD_USEDEARLIER_TITLE  = 'старые пароли повторно использовать нельзя в целях безопасности.',
	ME_VALID_PSWD_USEDEARLIER_TEXT  = 'указанный пароль был изменен %DATE_CHPSWDD',
	MI_ = '';
	MI_ = '';
/** Контакты */
var MI_CONTACTS_USER_HAS_NO_FRIENDS_IN_GROUP_TEXT = "В группе <strong>%GROUPNAME</strong> нет контактов",
        MI_CONTACTS_EMPTY_SEARCH_FRIENDS_ALL_GROUPS = "По запросу <strong>%SEARCHKEY</strong> не найдено ни одного контакта",
        MI_CONTACTS_EMPTY_SEARCH_FRIENDS_ONE_GROUP = "В группе <strong>%GROUPNAME</strong> по запросу <strong>%SEARCHKEY</strong> не найдено ни одного контакта";
/** Заявки */
var MI_REQUESTS_EMPTY_REQUESTS = "В группе <strong>%GROUPNAME</strong> нет контактов";
var MI_REQUESTS_EMPTY_SEARCH_REQUESTS = "В группе <strong>%GROUPNAME</strong> по запросу <strong>%SEARCHKEY</strong> не найдено ни одного контакта";
/** Подписчики */
var MI_SUBSCRIBERS_NO_SUBSCRIPTIONS = "Вы ни на кого не подписаны";
var MI_SUBSCRIBERS_NO_SUBSCRIBERS = "У Вас нет подписчиков";
var MI_SUBSCRIBERS_NO_SEARCH_SUBSCRIPTIONS = "По запросу <strong>%SEARCHKEY</strong> не найдено ни одной подписки";
var MI_SUBSCRIBERS_NO_SEARCH_SUBSCRIBERS = "По запросу <strong>%SEARCHKEY</strong> не найдено ни одного подписчика";

/** Поиск друзей */
var MI_FRIENDSSEARCH_EMPTY_REQUESTS = "По запросу <strong>%SEARCHKEY</strong> не найдено ни одного контакта",
        MI_FRIENDSSEARCH_EMPTY_SEARCHKEY = "Введите, пожалуйста, текст для поиска";
/** Сообщения */
var MI_MESSAGES_NO_MESSAGES_TEXT = 'У Вас нет сообщений';
var MI_MESSAGES_NO_RECEPIENT_TEXT = 'Выберите, пожалуйста, получателя';
var MI_MESSAGES_EMPTY_MESSAGE_TEXT = 'Вы не можете отправить пустое сообщение';
var MAX_PARAGRAPH_LENGTH = 256;