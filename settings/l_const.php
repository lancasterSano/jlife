<?php
require_once '.htpaths';
require_once(PROJECT_PATH."/php/class/ProfileAuth.php");

/************  ERRORS MESSAGES  ***************/
define('NOTES_CREATE_NOTE', 'Создать запись...');
define('NOTES_CREATE_NOTE_COMMENT', 'Написать комментарий...');
define('NOTES_COUNT', 'заметок');
define('NOTES_COUNT_EMPTY', 'Нет заметок');

/************  MESSAGES  ***************
 *	Правила составления константных переменных:
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
	define('MI_REQUEST_CREATE_MAIL_TITLE', 'На почту (%MAIL) было выслано письмо для создания почтового адресса (<span data="%HEX">повторить</span>)');
	define('MI_REQUEST_CREATE_MAIL_TEXT', 'Письмо отправлено %DATE_SEND и будет действительно в течении суток.');
	define('MI_REQUEST_CHANGE_MAIL_TITLE', 'На почту (%MAIL) было выслано письмо для изменения почтового адресса (<span data="%HEX">повторить</span>)');
	define('MI_REQUEST_CHANGE_MAIL_TEXT', 'Письмо отправлено %DATE_SEND и будет действительно в течении суток.');
	define('ME_VALID_PSWD_O_N_EMPTY_TITLE', 'пароль не указан');
	define('ME_VALID_PSWD_FAIL_TITLE', 'Неправильно указан пароль');
	define('ME_VALID_EMAIL_EMPTY_TITLE', 'email не указан');
	define('ME_VALID_EMAIL_EXIST_TITLE', 'email уже занят');
	define('ME_VALID_EMAIL_COPY_TITLE', 'данный email у Вас уже установлен');
	define('ME_VALID_EMAIL_FAIL_TITLE', 'Проверьте правильность электронной почты');
	define('ME_VALID_EMAIL_FAIL_TEXT', 'Можно использовать буквы латинского алфавита, цифры, точки');
	define('ME_ERROR_SEND_DATA', 'Произошла ошибка. Попробуйте позже.');
	// define('MS_ACCEPT_REQUEST_CREATE_MAIL_TITLE', 'Изменение email на новый (%MAIL).');
	// define('MS_ACCEPT_REQUEST_CREATE_MAIL_TEXT', 'Для подтверждения укажите ваш действующий пароль.');
	define('MS_CREATE_MAIL_SUCCESS_TITLE', 'email успешно изменен на (%MAIL).');
	define('ME_VALID_PSWD_LENGTH_TITLE', 'Пароль не соответствует требованиям по длине');
	define('ME_VALID_PSWD_LENGTH_TEXT', 'длина должна быть минимум 6 и не более 32 символов');
	define('ME_VALID_PSWD_CHAR_TITLE', 'В пароле указыны недопустимые символы');
	define('ME_VALID_PSWD_CHAR_TEXT', 'можно использовать цифры, буквы русского и латинского алфавита');

	define('MI_CHANGE_PSWD_TITLE', 'Обратите внимание, что в целях безопасности старые пароли повторно использовать нельзя.');
	define('MI_INFO_CHANE_PLEASE_MAIL_TITLE', 'Привяжите свой почтовый адрес к учетной записи в целях безопасноти');
	define('MI_INFO_CHANE_PLEASE_MAIL_TEXT', 'После создания вход можно будет произвести только через почтовый адрес');
	define('MI_', '');
  /***************** SCHEDULE LEARNER *******/
        define('MW_SCHEDULE_LEARNER_HAS_NO_STUDYDURATION_TITLE', 'Отсутствует расписание');
        define('MW_SCHEDULE_LEARNER_HAS_NO_STUDYDURATION_TEXT', 'Для класса, в котором Вы учитесь, отсутствует расписание');
  /***************** SCHEDULE RESPONSIBLE *******/
        define('MW_SCHEDULE_RESPONSIBLE_HAS_NO_STUDYDURATION_TEXT', 'Для класса, в котором учится <strong>%NAME</strong>, отсутствует расписание');
  /***************** SCHEDULE TEACHER *******/
        define('ME_SCHEDULE_TEACHER_HAS_NO_STUDYDURATION_TITLE', 'Отсутствует расписание');
        define('ME_SCHEDULE_TEACHER_HAS_NO_STUDYDURATION_TEXT', 'Для классов, в которых Вы преподаёте, не установлены учебные семестры');
  /***************** TESTRESULT LEARNER  *******/        
        define('MW_TESTRESULT_LEARNER_HAS_NO_SUBJECTS_TITLE', 'Нет предметов');
        define('MW_TESTRESULT_LEARNER_HAS_NO_SUBJECTS_TEXT', 'Вашему классу не назначены предметы');
  /***************** TESTRESULT RESPONSIBLE  *******/        
        define('MW_TESTRESULT_RESPONSIBLE_HAS_NO_SUBJECTS_TEXT', 'Классу, в котором учится <strong>%NAME</strong>, не назначены предметы');
  /***************** SUBJECTS LEARNER  *******/        
        define('MW_SUBJECTS_LEARNER_HAS_NO_SUBJECTS_TITLE', 'Нет предметов');
        define('MW_SUBJECTS_LEARNER_HAS_NO_SUBJECTS_TEXT', '%CLASS классу не назначены предметы');
  /***************** TESTRESULT TEACHER  *******/              
        define('ME_TESTRESULT_TEACHER_HAS_NO_SUBGROUPS_TITLE', 'Нет классов');
        define('ME_TESTRESULT_TEACHER_HAS_NO_SUBGROUPS_TEXT', 'Вам не назначены классы для преподавания');
        define('MI_TESTRESULT_TEACHER_HAS_NO_LEARNERS_TEXT', 'В классе нет учеников');
        
        define('MW_SUBJECTS_TEACHER_HAS_NO_SUBJECTS_TITLE', 'Нет предметов');
        define('MW_SUBJECTS_TEACHER_HAS_NO_SUBJECTS_TEXT', 'У вас нет предметов для преподавания');
        
        define('MI_SUBJECTS_TEACHER_HAS_NO_ACTIVEMATERIALS_TITLE', 'Нет материалов');
        define('MI_SUBJECTS_TEACHER_HAS_NO_ACTIVEMATERIALS_TEXT', 'В данный момент у Вас нет активных материалов');
        define('MI_SUBJECTS_TEACHER_HAS_NO_ARCHIVEMATERIALS_TITLE', 'Нет материалов');
        define('MI_SUBJECTS_TEACHER_HAS_NO_ARCHIVEMATERIALS_TEXT', 'В данный момент у Вас нет материалов в архиве');
        
        define('ME_SCHEDULE_LEARNER_ID_MISMATCH_TITLE', 'Системная ошибка');
        
        define('ME_SCHEDULE_LEARNER_ID_MISMATCH_TEXT', 'Произошла ошибка. Обратитесь за помощью к администратору. Код - #1376');
        
        define('MI_CONTACTS_USER_HAS_NO_INBOX_REQUESTS_TEXT', 'В группе <strong>Входящие</strong> нет контактов');
        define('MI_CONTACTS_USER_HAS_NO_OUTBOX_REQUESTS_TEXT', 'В группе <strong>Исходящие</strong> нет контактов');
        
        define('MI_CONTACTS_USER_HAS_NO_FRIENDS_IN_GROUP_TEXT', 'В группе <strong>%GROUPNAME</strong> нет контактов');
        define('MI_CONTACTS_USER_HAS_NO_GROUPS_TEXT', 'У Вас нет групп');
        define('MI_CONTACTS_FRIEND_HAS_NO_FRIENDS_TEXT', 'У пользователя нет друзей');
        define('MI_SUBSCRIBERS_NO_SUBSCRIBERS_TEXT', 'У Вас нет подписчиков');
        define('MI_SUBSCRIBERS_NO_SUBSCRIPTIONS_TEXT', 'Вы ни на кого не подписаны');
        define('MI_FRIENDSSEARCH_EMPTY_SEARCHKEY_TEXT', 'Введите, пожалуйста, текст для поиска');
        define('MI_NO_MESSAGES_TEXT', 'У Вас нет сообщений');
        
?>