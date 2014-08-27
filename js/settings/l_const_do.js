var notes='';

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

/********  JOURNAL_TEACHER  ********/

 var MS_SAVING_NEW_HOMETASK = '%DATE сохранено успешно!';
 var ME_SAVING_NEW_HOMETASK = 'Возникли проблемы при сохранении домашнего задания!';
 var MI_CONTACT_THE_ADMINISTRATOR = 'Обратитесь к администратору';
 var ME_ADDING_NEW_PARTPARAGRAPH_PARAGRAPH_SECTION = 'Возникли проблемы при добавлении %PART!';
 var MI_CHECK_ISSET_PARTPARAGRAPHS = 'Проверьте наличие разделов (содержимого) в параграфе';
 var MW_CHOOSE_PARTPARAGRAPH_PARAGRAPH_SECTION = 'Выберите %PART из списка';
 var MI_CONTACTS_EMPTY_SEARCH_TEACHERS = "По запросу <strong>%SEARCHKEY</strong> не найдено ни одного контакта";