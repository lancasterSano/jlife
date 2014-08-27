<?php
class QS {
    ########## PROFILE ########## 
        # INSERT PROFILE
            # INSERT INTO TABLE PROFILE
                static public $insertProfile = "INSERT INTO `profile` (`login`, `password`, `email`, `firstname`, `lastname`, `middlename`, `telephone`,`mobile`,`email2`,`city`,`country`, `birthday`) VALUES (?s,?s,?s,?s,?s,?s,?s,?s,?s,?s,?s,?s);";
            
            # INSERT INTO TABLE PROFILE
                static public $insertProfileWithID = "INSERT INTO `profile` (`id`, `login`, `password`, `email`, `firstname`, `lastname`, `middlename`, `telephone`,`mobile`,`email2`,`city`,`country`, `birthday`) VALUES (?i,?s,?s,?s,?s,?s,?s,?s,?s,?s,?s,?s,?s);";
            
            # SELECT FROM TABLE PROFILE CHANGE EMAIL
                static public $q_set_changeRequestEmail = "INSERT INTO changeprivatedata (entity ,datecreate ,idProfile ,olddata ,newdata ,datevalid ,hex ) VALUES (11,?s,?i,?s,?s,?s,?s);";
        # DELETE PROFILE
            # DELETE FROM TABLE PROFILE
                // static public $deleteProfile = "DELETE FROM changeprivatedata WHERE email = ?s;";

        # SELECT PROFILE
            # SELECT FROM TABLE PROFILE
                static public $q0 = "SELECT count(*) as count FROM `profile` where login=?s;";

            # SELECT FROM TABLE PROFILE
                static public $q1 = "SELECT id, login, password, private FROM `profile` where login=?s";

            # SELECT FROM TABLE PROFILE
                static public $q1_auth = "SELECT id, login, password, private FROM `profile` where `id`=?i";

            # SELECT FROM TABLE PROFILE    Exist profile(user) with [id]    
                static public $q2 = "SELECT count(*) as count FROM `profile` where id=?i";

            # SELECT FROM TABLE PROFILE    Exist profile(user) with [id]    
                static public $q_MAX_ID = "SELECT MAX(id) as maxid FROM `profile`";

            # SELECT FROM TABLE PROFILE    Поля профайла *  
                static public $q3 = "SELECT firstname, lastname, middlename, telephone, mobile, city, country, birthday, countcontact, countalbum, countblog, countblogcomment, countwall, countwallmy, isdefaultava, isdefaulthb, role, countinbox, countoutbox, sex, deleted, `profile`.`lock`, valid, acceptlicense, private, email FROM `profile` WHERE id=?i";
                static public $getProfileContext = "SELECT firstname, lastname, middlename, telephone, mobile, city, country, birthday, countcontact, countalbum, countblog, countblogcomment, countwall, countwallmy, isdefaultava, isdefaulthb, role, countinbox, countoutbox, sex, deleted, `profile`.`lock`, valid, acceptlicense, private, email FROM `profile` WHERE id=?i";
        
            # SELECT FROM TABLE PROFILE CHANGE EMAIL
                static public $q_get_changeRequestEmail = "SELECT id, olddata, newdata, datecreate, hex FROM changeprivatedata WHERE idprofile=?i AND resultchange=0 AND entity=11 AND datevalid > NOW();";
                static public $q_get_changeRequestEmailByHex = "SELECT id, olddata, newdata, datecreate, hex FROM changeprivatedata WHERE idprofile=?i AND resultchange=0 AND entity=11 AND datevalid > NOW() AND hex=?s;";
                static public $q_get_changeRequestEmailByHexSomebody = "SELECT id, idprofile FROM changeprivatedata WHERE resultchange=0 AND entity=11 AND datevalid > NOW() AND hex=?s;";
                
                static public $q_get_changePSWDByPSWDProfile = "SELECT id, idprofile , datevalid FROM changeprivatedata WHERE idprofile=?i AND entity=55 AND olddata=?s;";

                static public $q_get_repeatSendChangeRequestLogin = "SELECT id, olddata, newdata, datecreate, datevalid, hex FROM changeprivatedata WHERE resultchange=0 AND hex=?s;";

        # UPDATE PROFILE
            # DELETE FROM TABLE PROFILE
                static public $updateProfile = "UPDATE `profile` SET `profile`.`valid` = 0, `profile`.`login` = CONCAT_WS('|', `profile`.`login`, `profile`.`id`), `profile`.`email` = CONCAT_WS('|', `profile`.`email`, `profile`.`id`) WHERE `profile`.`id` = ?i;";
                
                static public $updateProfileNotDefaultAva = "UPDATE `profile` SET `profile`.`isdefaultava` = 0 WHERE `profile`.`id` = ?i;";
                
                static public $updateProfileAcceptLicense = "UPDATE `profile` SET `profile`.`acceptlicense` = NOW() WHERE `profile`.`id` = ?i;";

        # SELECT FROM TABLE PROFILE CHANGE EMAIL
            static public $q_clearLatterRequestChangeEntityNotSuccess = "UPDATE `changeprivatedata` SET `resultchange` = -1, `resultchangedate` = NOW() WHERE `idprofile`=?i AND `entity`=?i;";
            static public $q_clearLatterRequestChangeEmailByBusy = "UPDATE `changeprivatedata` SET `resultchange` = -2, `resultchangedate` = NOW() WHERE `newdata`=?s AND resultchange=0;";

    ########## ROLES ########## 
        # SELECT RELES
            # SELECT ROLES
                static public $q_roles_1 = "SELECT `roles`.`id`,`roles`.`profile_id`,`roles`.`role`,`roles`.`idadress`,`roles`.`idschool`,`roles`.`info`,`roles`.`datestart`,`roles`.`datefinish`FROM `roles` WHERE `roles`.`profile_id`=?i AND `roles`.`datefinish` IS NULL ORDER BY `roles`.`role` ASC ;";
                static public $q_roles_2 = "SELECT `roles`.`id`,`roles`.`profile_id`,`roles`.`role`,`roles`.`idadress`,`roles`.`idschool`,`roles`.`info`,`roles`.`datestart`,`roles`.`datefinish`FROM `roles` WHERE `roles`.`profile_id`=?i AND `roles`.`role`=?i AND `roles`.`datefinish` IS NULL;";
                static public $q_role_by_school = "SELECT `roles`.`id`,`roles`.`profile_id`,`roles`.`role`,`roles`.`idadress`,`roles`.`idschool`,`roles`.`info`,`roles`.`datestart`,`roles`.`datefinish`FROM `roles` WHERE `roles`.`profile_id`=?i AND `roles`.`role`=?i AND `roles`.`idschool`=?i AND `roles`.`datefinish` IS NULL;";
                static public $q_roles_by_school = "SELECT `roles`.`id`,`roles`.`profile_id`,`roles`.`role`,`roles`.`idadress`,`roles`.`idschool`,`roles`.`info`,`roles`.`datestart`,`roles`.`datefinish`FROM `roles` WHERE `roles`.`profile_id`=?i AND `roles`.`idschool`=?i AND `roles`.`datefinish` IS NULL;";
    ########## NOTES ########## 
        # INSERT NOTES
            # INSERT INTO TABLE WALL
                static public $insertWall = "INSERT INTO `wall?i` (`text`, `datetime`, `idauthor`, `extension`) VALUES (?s, ?s, ?i, ?i);";

            # INSERT INTO TABLE COMMENTWALL
                static public $insertCommentWall = "INSERT INTO `commentwall?i` (`profile_id`,`datetime`,`text`, `wall?i_id`,`commentwall?i_id`, `extension`) VALUES( ?i, ?s, ?s, ?i, ?i, ?i);";
                
            # INSERT INTO TABLE LIKEWALL
                /** * Count placeholder: 4 */
                static public $insertLikeWall = "INSERT INTO `likewall?i`(`wall?i_id`,`profile_id`) VALUES(?i,?i);";
            
            # INSERT INTO TABLE LIKECOMMENTWALL
                /** * Count placeholder: 4 */
                static public $insertLikeCommentWall = "INSERT INTO `likecommentwall?i`(`commentwall?i_id`,`profile_id`)VALUES(?i,?i);";

        # DELETE NOTES
            # DELETE FROM TABLE WALL
                static public $deleteWall = "DELETE FROM `wall?i` WHERE `wall?i`.id = ?i;";

            # DELETE FROM TABLE COMMENTWALL
                static public $deleteCommentWall = "DELETE FROM `commentwall?i` WHERE `commentwall?i`.id = ?i;";

            # DELETE FROM TABLE LIKEWALL
                /** * Count placeholder: 6 */
                static public $deleteLikeWall = "DELETE FROM `likewall?i` WHERE `likewall?i`.`wall?i_id`=?i and `likewall?i`.`profile_id`=?i;";

            # DELETE FROM TABLE LIKECOMMENTWALL
                /** * Count placeholder: 6 */
                static public $deleteLikeCommentWall = "DELETE FROM `likecommentwall?i` WHERE `likecommentwall?i`.`commentwall?i_id`=?i and `likecommentwall?i`.`profile_id`=?i;";

        # SELECT NOTES
            # SELECT INTO TABLE
                static public $q4 = "SELECT id, text, datetime, idauthor, countlike, countcomment, extension FROM `wall?i` as W WHERE W.id >= (select min(s.id) as max from (select id from wall?i where extension is null order by id desc limit 0,?i) as s) ORDER BY W.`id` DESC;";

            # SELECT INTO TABLE 
                static public $q4_m = "SELECT id, text, datetime, idauthor, countlike, countcomment, extension FROM `wall?i` as W WHERE W.id >= (select min(s.id) as max from (select id from wall?i where extension is null AND idauthor = ?i order by id desc limit 0,?i) as s) AND idauthor = ?i ORDER BY W.`id` DESC;";

            # SELECT INTO TABLE     # PRE 5
                static public $q21 = "SELECT id, text, datetime, idauthor, countlike, countcomment, extension FROM `wall?i` as W WHERE W.id >= (select min(s.id) as max from (select id from wall?i where id< ?i and extension is null ORDER BY id DESC limit 0,?i) as s) AND W.id < ?i ORDER BY W.`id` DESC;";

            # SELECT INTO TABLE 
                static public $q19 = "SELECT id, text, datetime, idauthor, countlike, countcomment, extension FROM `wall?i` as W WHERE W.id = ?i";

            # SELECT INTO TABLE 
                static public $q20 = "SELECT id, profile_id , datetime, text, countlike, wall?i_id, commentwall?i_id, extension FROM commentwall?i WHERE wall?i_id=?i and id = ?i ORDER BY datetime ASC;";

            # SELECT INTO TABLE 
                static public $q5 = "SELECT firstname, lastname, isdefaultava, isdefaulthb, role FROM `profile` WHERE id=?i;";

            # SELECT INTO TABLE 
                static public $q6 = "SELECT count(wall?i_id) as count FROM likewall?i WHERE profile_id=?i and wall?i_id=?i";

            # SELECT INTO TABLE commentwall ЗАПИСИ: Все комменты
                static public $q7 = "SELECT id, profile_id , datetime, text, countlike, wall?i_id, commentwall?i_id, extension FROM commentwall?i WHERE wall?i_id=?i ORDER BY datetime ASC;";

            # SELECT INTO TABLE 
                // ЗАПИСИ: Все до указанных комменты
                static public $q17 = "SELECT id, profile_id , datetime, text, countlike, wall?i_id, commentwall?i_id, extension FROM commentwall?i WHERE wall?i_id=?i AND id<?i ORDER BY datetime ASC;";

            # SELECT INTO TABLE 
                static public $q18 = "SELECT profile_id FROM commentwall?i WHERE id=?i";

            # SELECT INTO TABLE 
                static public $q8 = "SELECT count(commentwall?i_id) as count FROM `likecommentwall?i` WHERE profile_id=?i and commentwall?i_id=?i;";

            # SELECT INTO TABLE 
                static public $q12 = "SELECT max(`wall?i`.`id`) as maxid FROM `wall?i` WHERE idauthor=?i;";

            # SELECT INTO TABLE 
                static public $q13 = "SELECT max(`commentwall?i`.`id`) as maxid FROM `commentwall?i` WHERE profile_id=?i;";

            # SELECT INTO TABLE commentwall ЗАПИСИ: N опследних комментариев
                static public $q15_1 = "SELECT id, profile_id , datetime, text, countlike, wall?i_id, commentwall?i_id, extension FROM commentwall?i WHERE wall?i_id=?i and id >=(SELECT min(s.id) FROM (SELECT id FROM commentwall?i WHERE extension is null AND wall?i_id=?i ORDER BY id DESC LIMIT 0,?i ) as s ) ORDER BY id ASC";

            # SELECT INTO TABLE commentwall ЗАПИСИ: предыдущая перед текущим комментом
                static public $q16 = "SELECT `id`,`datetime`,`text`,`countlike`,`wall?i_id`,`commentwall?i_id`,`profile_id`,`extension` FROM `commentwall?i` WHERE `wall?i_id` = ?i AND `id` >= (SELECT `id` FROM `commentwall?i` WHERE `wall?i_id` = ?i AND `extension` is null AND `id` < ?i ORDER BY `id` DESC LIMIT 1 ) AND `id` < ?i;";
    
    ########## ALBUM ########## 
        # INSERT ALBUM
        # DELETE ALBUM
        # SELECT ALBUM

            static public $getAlbumsPhotos = "SELECT * FROM `spisokalbumphoto?i` WHERE `album?i_id` = ?i;";
    
    ########## ALBUMS ########## 
        # INSERT ALBUMS
            
            static public $insertLikeAlbum = "INSERT INTO `likealbum?i`(`album?i_id`,`profile_id`)VALUES(?i,?i);";

            static public $insertLikeCommentAlbum = "INSERT INTO `likecommentalbum?i`(`commentalbum?i_id`,`profile_id`)VALUES(?i,?i);";

            static public $insertCommentAlbum = "INSERT INTO `commentalbum?i` (`profile_id`,`datetime`,`text`, `album?i_id`,`commentalbum?i_id`, `extension`) VALUES( ?i, ?s, ?s, ?i, ?i, ?i);";

        # DELETE ALBUMS

            // Удалить "Лайк" из под альбома
            static public $deleteLikeAlbum = "DELETE FROM `likealbum?i` WHERE `likealbum?i`.`album?i_id`=?i and `likealbum?i`.`profile_id`=?i;";

            static public $deleteLikeCommentAlbum = "DELETE FROM `likecommentalbum?i` WHERE `likecommentalbum?i`.`commentalbum?i_id`=?i and `likecommentalbum?i`.`profile_id`=?i;";

            static public $deleteAlbum = "DELETE FROM `album?i` WHERE `album?i`.id = ?i;";

            static public $deleteCommentAlbum = "DELETE FROM `commentalbum?i` WHERE `commentalbum?i`.`id` = ?i;";

        # SELECT ALBUMS
            
            static public $getCommentsAlbum = "SELECT * FROM `commentalbum?i` WHERE album?i_id=?i and id >=(SELECT min(s.id) FROM (SELECT id FROM commentalbum?i WHERE extension is null AND album?i_id=?i ORDER BY id DESC LIMIT 0,?i ) as s ) ORDER BY `datetime` ASC";

            static public $getLastCommentsAlbum = "SELECT * FROM `commentalbum?i` WHERE id >=(SELECT min(s.id) FROM (SELECT id FROM commentalbum?i WHERE extension is null ORDER BY id DESC LIMIT 0,?i ) as s ) ORDER BY id ASC";

            static public $getLastCommentsPhoto = "SELECT * FROM `commentspisokalbumphoto?i` WHERE id >=(SELECT min(s.id) FROM (SELECT id FROM `commentspisokalbumphoto?i` WHERE extension is null ORDER BY id DESC LIMIT 0,?i ) as s ) ORDER BY id ASC";

            static public $getAllAlbums = "SELECT * FROM `album?i` ORDER BY id DESC LIMIT 0,?i";

            static public $getNameAlbum = "SELECT name FROM `album?i` WHERE `id` = ?i;";

            static public $getLoadedAlbums = "SELECT * FROM `album?i` where id in (select id from album?i where id < ?i) ORDER BY id DESC limit 0,?i";

            static public $getLikeAlbum = "SELECT count(album?i_id) as count FROM `likealbum?i` WHERE profile_id=?i and album?i_id=?i";

            static public $getLikeCommentAlbum = "SELECT count(commentalbum?i_id) as count FROM `likecommentalbum?i` WHERE profile_id=?i and commentalbum?i_id=?i;";

            static public $getOneMoreCommentAfterDelete = "SELECT * FROM `commentalbum?i` WHERE album?i_id = ?i AND `id` >= (SELECT `id` FROM `commentalbum?i` WHERE `album?i_id` = ?i AND `extension` is null AND `id` < ?i ORDER BY `id` DESC LIMIT 1 ) AND `id` < ?i;";

            static public $getExtansion = "SELECT `extension` FROM `commentalbum?i` WHERE id=?i";

            // Получить id профиля, который написал комментарий
            static public $getProfileId_2 = "SELECT profile_id FROM `commentalbum?i` WHERE id=?i";

            // Получить Имя и Фамилию
            static public $getProfileFI_2 = "SELECT firstname, lastname FROM `profile` WHERE id=?i;";

            static public $idFirstInsert = "SELECT max(`commentalbum?i`.`id`) as maxid FROM `commentalbum?i` WHERE profile_id=?i;";

            static public $getNewStringComment = "SELECT * FROM `commentalbum?i` WHERE album?i_id=?i and id = ?i ORDER BY datetime ASC;";

            static public $getCommentsExpand = "SELECT * FROM `commentalbum?i` WHERE album?i_id=?i AND id<?i ORDER BY datetime ASC;";

    ########## PHOTO ########## 
        # INSERT PHOTO

            static public $insertLikeCommentPhoto = "INSERT INTO `likecommentspisokalbumphoto?i`(`commentspisokalbumphoto?i_id`,`profile_id`)VALUES(?i,?i);";

        # DELETE PHOTO

            static public $deleteLikeCommentPhoto = "DELETE FROM `likecommentspisokalbumphoto?i` WHERE `likecommentspisokalbumphoto?i`.`commentspisokalbumphoto?i_id`=?i and `likecommentspisokalbumphoto?i`.`profile_id`=?i;";

        # SELECT PHOTO
            public static $deletePhotoAlbumComment = "DELETE FROM `commentspisokalbumphoto?i` WHERE `commentspisokalbumphoto?i`.`id` = ?i;";

            static public $getLikeCommentPhoto = "SELECT count(commentspisokalbumphoto?i_id) as count FROM `likecommentspisokalbumphoto?i` WHERE profile_id=?i and commentspisokalbumphoto?i_id=?i;";

    
    ########## MESSAGE ########## 
        # INSERT MESSAGE
            static public $insertMessage = "INSERT INTO `message?i`(`text`,`datetime`,`idrecepient`,`idsender`,`extension`,`new`) VALUES (?s, ?s, ?i, ?i, ?i, ?i)";
        
        # UPDATE MESSAGE
            static public $updateStatusRead = "UPDATE `message?i` SET new = 0 WHERE id=?i";
        
        # DELETE MESSAGE
            static public $deleteMessage = "DELETE FROM `message?i` WHERE id=?i";
            static public $deleteMessages = "DELETE FROM `message?i` WHERE id IN (?a)";
        
        # SELECT MESSAGE
            static public $getCountInbox = "SELECT count(*) FROM `message?i` WHERE idrecepient=?i AND extension is NULL";
            static public $getCountOutbox = "SELECT count(*) FROM `message?i` WHERE idsender=?i AND extension is NULL";
            static public $getInboxMessages = "SELECT * FROM `message?i` WHERE idrecepient=?i AND extension is NULL ORDER by id DESC LIMIT 0,?i";
            static public $getOutboxMessages = "SELECT * FROM `message?i` WHERE idsender=?i AND extension is NULL ORDER by id DESC LIMIT 0,?i";
            static public $getCountUnread = "SELECT count(*) FROM `message?i` WHERE idrecepient=?i AND new > 0 ";
            static public $getMessage = "SELECT * FROM `message?i` WHERE id=?i OR extension=?i";
            static public $getInboxContinuationMessages = "SELECT * FROM message?i as W WHERE W.id >= (SELECT min(s.id) as max FROM (SELECT id FROM message?i WHERE id< ?i AND extension IS NULL AND idrecepient=?i ORDER BY id DESC LIMIT 0,?i )  AS s ) AND W.id < ?i AND idrecepient = ?i AND extension IS NULL ORDER BY W.id DESC;";
            static public $getOutboxContinuationMessages = "SELECT * FROM message?i as W WHERE W.id >= (SELECT min(s.id) as max FROM (SELECT id FROM message?i WHERE id< ?i AND extension IS NULL AND idsender = ?i ORDER BY id DESC LIMIT 0,?i )  AS s ) AND W.id < ?i AND idsender = ?i AND extension IS NULL ORDER BY W.id DESC;";

    ########## AVATAR ##########
        # INSERT AVATAR
            static public $insertAvatar = "INSERT INTO `avatar?i` (`isavatar`,`datetime`,`prev`,`update`) VALUES (1,?s,?i,?s);";

        # UPDATE AVATAR
            static public $setAvaInactive = "UPDATE avatar?i SET isavatar = 0 WHERE id = ?i;";
            static public $setNewPrevToNextAva = "UPDATE avatar?i SET prev = ?i WHERE id = ?i;";
            static public $setAvaActive = "UPDATE avatar?i SET isavatar = 1, prev = ?i, `update` = ?s WHERE id = ?i;";
            static public $setAvaActiveClear = "UPDATE avatar?i SET isavatar = 1 WHERE id = ?i;";

        # DELETE AVATAR

        # SELECT AVATAR
            static public $getCurrentAvatar = "SELECT id FROM avatar?i WHERE isavatar = 1";
            static public $getNewAvaPrev = "SELECT prev FROM avatar?i WHERE id = ?i;";
            static public $getNextAvaID = "SELECT id FROM avatar?i WHERE prev = ?i;";

    ########## ORGANIZER ##########
        # INSERT,UPDATE,DELETE,SELECT TABLE organizer | holliday
        	static public $q9 = "SELECT * FROM  `organizer?i`";
            //*
        	static public $q10 = "SELECT * FROM `organizer?i` where id=?i;";
        	//*
        	static public $q11 = "SELECT count(*) as count FROM `organizer?i` where type=?i and detatimestart between ?s and ?s;";
            //*
            static public $org1 = "SELECT * FROM `organizer?i` where type=?i and detatimestart=?s;";
            //*
            static public $org2 = "SELECT * FROM `organizer?i` where detatimestart between ?s and ?s order by detatimestart desc;";
            //*
            static public $org3 = "SELECT * FROM `holliday` where date=?s;"; 
            //*
            static public $org4 = "SELECT * FROM `organizer?i` where  detatimestart between ?s and ?s order by detatimestart;"; 
            //*
            static public $org5 = "SELECT `profile`.`firstname`, `profile`.`lastname`, `profile`.`birthday`, `profile`.`pathavatar`  FROM `profile`,`spisokcontactgroupuser?i` where id=iduser and birthday=?s;";  
    
    ########## CONTACTS ##########
        # INSERT,UPDATE,DELETE,SELECT друзья
            static public $qfriend1 = "SELECT state FROM spisokcontactgroupuser?i where iduser = ?i LIMIT 0,1;";
            static public $qfriend2 = "SELECT count(*) FROM contactgroup?i;";
            static public $qfriend3 = "SELECT * FROM contactgroup?i order by countuser desc;";
            /**
             * Запрос возвращает первые X друзей пользователя (их идентификаторы) в заданной группе
             * Плэйсхолдеры(4) : idProfile, idProfile, idGroup, countFriends (сколько друзей загрузить)
             */
            static public $qfriend4 = "SELECT id from `profile` where id in (select * from (SELECT iduser FROM `spisokcontactgroupuser?i` where contactgroup?i_id = ?i and state =0 LIMIT 0, ?i) as a);";
            /**
             * Запрос возвращает id всех друзей пользователя в заданной группе
             * Плэйсхолдеры(3) : idProfile, idProfile, idGroup
             */
            static public $qfriend5 = "SELECT id from profile where id in (SELECT iduser FROM `spisokcontactgroupuser?i` where contactgroup?i_id = ?i and state =0 );";
            /**
             * Запрос возвращает имя группы (name) и количество пользователей в ней (countuser). 
             * Плэйсхолдеры (2): idProfile, idGroup
             */
            static public $qfriend6 = "SELECT name, countuser FROM `contactgroup?i` WHERE id =?i;";  
            static public $qfriend7 = "SELECT id, name, countuser FROM contactgroup?i ORDER BY countuser DESC LIMIT 0 , 3;";
            static public $qfriend8 = "SELECT id, name, countuser FROM contactgroup?i ORDER BY countuser DESC;";
            static public $qfriend9 = "SELECT id, firstname, lastname, deleted FROM `profile` WHERE id=?i";
            static public $qfriend10 = "INSERT INTO `contactgroup?i`(`name`) VALUES (?s);";
            static public $insertFriendToGroup = "INSERT INTO `spisokcontactgroupuser?i`(`iduser`, `contactgroup?i_id`, `state`) VALUES (?i,?i,?i);";
            static public $qfriend12 = "SELECT contactgroup?i_id as idgroup FROM `spisokcontactgroupuser?i` where iduser = ?i;";
            static public $deleteFriendFromGroups = "DELETE FROM `spisokcontactgroupuser?i` WHERE iduser = ?i;";
            static public $updateFriendMutualState = "UPDATE `spisokcontactgroupuser?i` SET `state`= ?i WHERE iduser = ?i;";
            static public $qfriend15 = "SELECT count(*) as count from contactgroup?i;";
            static public $qfriend16 = "SELECT count(*) as count from spisokcontactgroupuser?i WHERE iduser = ?i;";
            /** Запрос возвращает количество входящих заявок пользователя. Плэйсхолдеры(2): idProfile, idProfile */
            static public $getCountInboxRequests = "SELECT COUNT(*) AS count FROM `profile` WHERE id IN (SELECT iduser FROM `spisokcontactgroupuser?i` WHERE contactgroup?i_id IS NULL AND state = 2 OR state = 3);";
            /** Запрос возвращает количество исходящих заявок пользователя. Плэйсхолдеры(1): idProfile */
            static public $getCountOutboxRequests = "SELECT COUNT(*) AS count FROM `profile` WHERE id IN (SELECT iduser FROM `spisokcontactgroupuser?i` WHERE state = 1);";
            /** Запрос возвращает первые Х исходящих заявок пользователя. Плэйсхолдеры(2): idProfile, countToLoad */
            static public $getOutboxRequestsPart = "SELECT id, firstname, lastname from profile where id in (SELECT iduser FROM `spisokcontactgroupuser?i` where state = 1 ) LIMIT 0,?i;";
            /** Запрос возвращает исходящие заявки пользователя. Плэйсхолдеры(1): idProfile */
            static public $getOutboxRequestsFull = "SELECT id, firstname, lastname from profile where id in (SELECT iduser FROM `spisokcontactgroupuser?i` where state = 1 );";
            /** Запрос возвращает первые Х входящих новых заявок пользователя. Плэйсхолдеры(3): idProfile, idProfile, countToLoad */
            static public $getInboxRequestsNewPart = "SELECT id, firstname, lastname, deleted from profile where id in (SELECT iduser FROM `spisokcontactgroupuser?i` where contactgroup?i_id IS NULL and state = 2 ) LIMIT 0,?i;";
            /** Запрос возвращает новые заявки пользователя. Плэйсхолдеры(2): idProfile, idProfile */
            static public $getInboxRequestsNewFull = "SELECT id, firstname, lastname, deleted from profile where id in (SELECT iduser FROM `spisokcontactgroupuser?i` where contactgroup?i_id IS NULL and state = 2 );";
            /** Запрос возвращает первые Х входящих просмотренных заявок пользователя. Плэйсхолдеры(3): idProfile, idProfile, countToLoad */
            static public $getInboxRequestsOldPart = "SELECT id, firstname, lastname, deleted from profile where id in (SELECT iduser FROM `spisokcontactgroupuser?i` where contactgroup?i_id IS NULL and state = 3 ) LIMIT 0,?i;";
            /** Запрос возвращает просмотренные заявки пользователя. Плэйсхолдеры(2): idProfile, idProfile */
            static public $getInboxRequestsOldFull = "SELECT id, firstname, lastname, deleted from profile where id in (SELECT iduser FROM `spisokcontactgroupuser?i` where contactgroup?i_id IS NULL and state = 3 );";

            static public $getAllFriends = "SELECT id, firstname, lastname, deleted FROM profile WHERE id IN (SELECT * FROM (SELECT iduser FROM `spisokcontactgroupuser?i` WHERE state = 0 AND deleted = 0 )as a );";
            static public $getCountFriendRequests = "SELECT count(id) from profile where id in (SELECT iduser FROM `spisokcontactgroupuser?i` where contactgroup?i_id IS NULL and state = 2 );";
            static public $getFriendsByGroupLikeLimit = "SELECT S.id, S.FL FROM(SELECT id, CONCAT(firstname,' ',lastname) as FL, CONCAT(lastname,' ',firstname) as LF FROM profile WHERE id IN(SELECT iduser FROM spisokcontactgroupuser?i WHERE state = 0 AND deleted = 0 AND contactgroup?i_id = ?i)) as S WHERE S.FL LIKE ?s OR S.LF LIKE ?s LIMIT 0, ?i;";
            static public $getFriendsByGroupLike = "SELECT S.id, S.FL FROM(SELECT id, CONCAT(firstname,' ',lastname) as FL, CONCAT(lastname,' ',firstname) as LF FROM profile WHERE id IN(SELECT iduser FROM spisokcontactgroupuser?i WHERE state = 0 AND deleted = 0 AND contactgroup?i_id = ?i)) as S WHERE S.FL LIKE ?s OR S.LF LIKE ?s;";
            static public $getFriendsAllGroupsLike = "SELECT S.id, S.FL FROM(SELECT id, CONCAT(firstname,' ',lastname) as FL, CONCAT(lastname,' ',firstname) as LF FROM profile WHERE id IN(SELECT iduser FROM spisokcontactgroupuser?i WHERE state = 0 AND deleted = 0)) as S WHERE S.FL LIKE ?s OR S.LF LIKE ?s;";
            static public $getGroupsOfSearchedFriends = "SELECT id, name from contactgroup?i WHERE id IN (SELECT contactgroup?i_id FROM spisokcontactgroupuser?i WHERE iduser IN (SELECT S.id FROM (SELECT id, CONCAT(firstname,' ',lastname) as FL, CONCAT(lastname,' ',firstname) as LF FROM profile WHERE id IN (SELECT iduser FROM spisokcontactgroupuser?i WHERE state = 0 AND deleted = 0 ) ) as S WHERE S.FL LIKE ?s OR S.LF LIKE ?s ) );";
            static public $getGroupById = "SELECT name FROM contactgroup?i WHERE id = ?i;";
            static public $getAllSearchedFriendsOfUser = "SELECT S.id, S.FL FROM (SELECT id, CONCAT(firstname,' ',lastname) as FL, CONCAT(lastname,' ',firstname) as LF FROM profile WHERE id IN (SELECT iduser FROM spisokcontactgroupuser?i WHERE state = 0 AND deleted = 0 ) ) as S WHERE S.FL LIKE ?s OR S.LF LIKE ?s;";
            static public $getSearchedOutboxRequests = "SELECT S.id, S.FL FROM (SELECT id, CONCAT(firstname,' ',lastname) as FL, CONCAT(lastname,' ',firstname) as LF FROM profile WHERE id IN (SELECT iduser FROM spisokcontactgroupuser?i WHERE state = 1 AND deleted = 0 ) ) as S WHERE S.FL LIKE ?s OR S.LF LIKE ?s;";
            static public $getSearchedInboxNewRequests = "SELECT S.id, S.FL FROM (SELECT id, CONCAT(firstname,' ',lastname) as FL, CONCAT(lastname,' ',firstname) as LF FROM profile WHERE id IN (SELECT iduser FROM spisokcontactgroupuser?i WHERE state = 2 AND deleted = 0 ) ) as S WHERE S.FL LIKE ?s OR S.LF LIKE ?s;";
            static public $getSearchedInboxOldRequests = "SELECT S.id, S.FL FROM (SELECT id, CONCAT(firstname,' ',lastname) as FL, CONCAT(lastname,' ',firstname) as LF FROM profile WHERE id IN (SELECT iduser FROM spisokcontactgroupuser?i WHERE state = 3 AND deleted = 0 ) ) as S WHERE S.FL LIKE ?s OR S.LF LIKE ?s;";
            /** Запрос обновляет имя группы. Плэйсхолдеры(3) idAuth, nameGroup, idGroup */
            static public $updateGroupName = "UPDATE contactgroup?i SET name = ?s WHERE id = ?i;";
            /** Запрос удаляет группу. Плэйсхолдеры(2) idAuth, idGroup */
            static public $deleteGroup = "DELETE FROM contactgroup?i WHERE id = ?i";

            /** Запрос получает список групп определенного пользователя. Плэйсхолдеры(4) idAuth, idAuth, idAuth, idFriend */
            static public $getGroupsOfFriend = "SELECT contactgroup?i_id AS idgroup FROM spisokcontactgroupuser?i WHERE state = 0 AND contactgroup?i_id IS NOT NULL AND iduser = ?i;";
            /**
             * Запрос переводит в состояние входящих заявок тех друзей, которые являются 
             * единственными (есть только в этой удаляемой группе и нигде больше)
             * Плэйсхолдеры(4): idAuth, idAuth, idGroup, arrayOfUserIDs
             */
            static public $deleteFriendsFromGroup = "UPDATE spisokcontactgroupuser?i SET state = 2 WHERE contactgroup?i_id = ?i AND iduser IN(?a);";
            /** Запрос возвращает список уникальных друзей этой группы. Плэйсхолдеры(6) idAuth, idAuth, idGroup, idAuth, idAuth, idGroup */
            static public $getUniqueFriendsInGroup = "SELECT iduser FROM spisokcontactgroupuser?i WHERE contactgroup?i_id = ?i AND state = 0 AND iduser NOT IN (SELECT * FROM (SELECT DISTINCT iduser FROM spisokcontactgroupuser?i WHERE contactgroup?i_id != ?i) AS X);";
            static public $getUniqueOutboxRequestsToGroup = "SELECT iduser FROM spisokcontactgroupuser?i WHERE contactgroup?i_id = ?i AND state = 1 AND iduser NOT IN (SELECT * FROM (SELECT DISTINCT iduser FROM spisokcontactgroupuser?i WHERE contactgroup?i_id != ?i) AS X);";


    ########## SUBSCRIBER ##########
        # INSERT,UPDATE,DELETE,SELECT подписчики
            /** Запрос возвращает количество подписок пользователя. Плэйсхолдеры(1): idProfile */
            static public $getCountSubscriptions = "SELECT COUNT(*) FROM profile where id in (SELECT profile_id FROM `spisoksubscriber?i` where state = 1 or state = 0 );";
            /** Запрос возвращает количество подписчиков пользователя. Плэйсхолдеры(1): idProfile */
            static public $getCountSubscribers = "SELECT COUNT(*) FROM profile where id in (SELECT profile_id FROM `spisoksubscriber?i` where state = 2 or state = 0 );";
            static public $qsubscribe1 = "SELECT count(*) FROM spisoksubscriber?i where profile_id =?i and state=0 or profile_id =?i and state=1 LIMIT 0,1;";
            /** Запрос возвращает все подписки пользователя. Плэйсхолдеры(1) idProfile */
            static public $getSubscriptionsFull = "SELECT id, firstname, lastname, deleted from profile where id in (SELECT profile_id FROM `spisoksubscriber?i` where state = 1 or state = 0 );";
            /** Запрос возвращает первые Х подписок пользователя. Плэйсхолдеры(2) idProfile, countLoad */
            static public $getSubscriptionsPart = "SELECT id, firstname, lastname, deleted from profile where id in (SELECT profile_id FROM `spisoksubscriber?i` where state = 1 or state = 0 ) LIMIT 0, ?i;";
            /** Запрос возвращает всех невзаимных подписчиков пользователя. Плэйсхолдеры(1) idProfile */
            static public $getSubscribersFull = "SELECT id, firstname, lastname, deleted from profile where id in (SELECT profile_id FROM `spisoksubscriber?i` where state = 2 );";
            /** Запрос возвращает первые Х из всех невзаимных подписчиков пользователя. Плэйсхолдеры(2) idProfile, countToLoad */
            static public $getSubscribersPart = "SELECT id, firstname, lastname, deleted from profile where id in (SELECT profile_id FROM `spisoksubscriber?i` where state = 2 ) LIMIT 0, ?i;";
            /** Запрос возвращает всех взаимных подписчиков пользователя. Плэйсхолдеры(1) idProfile */
            static public $getMutualSubscribersFull = "SELECT id, firstname, lastname, deleted from profile where id in (SELECT profile_id FROM `spisoksubscriber?i` where state = 0 );";
            /** Запрос возвращает всех взаимных подписчиков пользователя. Плэйсхолдеры(1) idProfile */
            static public $getMutualSubscribersPart = "SELECT id, firstname, lastname, deleted from profile where id in (SELECT profile_id FROM `spisoksubscriber?i` where state = 0 ) LIMIT 0, ?i;";
            /** Запрос проверяет, есть ли пользователь X в списке подписчиков пользователя Y. Плэйсхолдеры: idProfileY, idProfileX */
            static public $checkSubscriberRelation = "SELECT count(*) FROM spisoksubscriber?i where profile_id =?i;";
             /** Запрос устанавливает пользователя Y подписанным на пользователя X, если они ранее не состояли ни в каких отношениях. Плэйсхолдеры: idProfileY, idProfileX */
            static public $setSubscriptionFirst = "INSERT INTO `spisoksubscriber?i`(`profile_id`, `state`) VALUES (?i,1);";
            /** Запрос устанавливает пользователю Y подписчика пользователя X, если они ранее не состояли ни в каких отношениях. Плэйсхолдеры: idProfileY, idProfileX */
            static public $setSubscriberFirst = "INSERT INTO `spisoksubscriber?i`(`profile_id`, `state`) VALUES (?i,2);";
            /** Запрос устанавливает пользователям Y и X взаимную подписку. Плэйсхолдеры: idProfileY, idProfileX */
            static public $setMutualSubscriber = "UPDATE  `spisoksubscriber?i` SET `state` = '0' WHERE `profile_id` =?i;";
            /** Запрос устанавливает пользователю Y подписчика пользователя X. Плэйсхолдеры: idProfileY, idProfileX */
            static public $setSubscriber = "UPDATE  `spisoksubscriber?i` SET `state` = '2' WHERE `profile_id` =?i;";
            /** Запрос устанавливает пользователя Y подписанным на пользователя X. Плэйсхолдеры: idProfileY, idProfileX */
            static public $setSubscription = "UPDATE  `spisoksubscriber?i` SET `state` = '1' WHERE `profile_id` =?i;";
            /** Запрос проверяет, являются ли пользователь Y с пользователем X взаимными подписчиками. Плэйсхолдеры: idProfileY, idProfileX */
            static public $checkMutualRelation = "SELECT count(*) FROM spisoksubscriber?i where profile_id =?i and state = 0;";
            /** Запрос проверяет, подписан ли пользователь Y на пользователя пользователя X. Плэйсхолдеры: idProfileY, idProfileX */
            static public $checkSubscriptionRelation = "SELECT count(*) FROM spisoksubscriber?i where profile_id =?i and state = 1;";
            /** Запрос выполняет удаление пользователя X из списка подписчиков пользователя Y. Плэйсхолдеры: idProfileY, idProfileX */
            static public $deleteSubscriberRelation = "DELETE FROM spisoksubscriber?i where profile_id =?i;";
            static public $getSearchedSubscriptions = "SELECT S.id, S.FL FROM (SELECT id, CONCAT(firstname,' ',lastname) as FL, CONCAT(lastname,' ',firstname) as LF FROM profile WHERE id IN (SELECT profile_id FROM spisoksubscriber?i WHERE deleted = 0 AND state = 1 OR state = 0 ) ) as S WHERE S.FL LIKE ?s OR S.LF LIKE ?s;";
            static public $getSearchedUnmutualSubscribers = "SELECT S.id, S.FL FROM (SELECT id, CONCAT(firstname,' ',lastname) as FL, CONCAT(lastname,' ',firstname) as LF FROM profile WHERE id IN (SELECT profile_id FROM spisoksubscriber?i WHERE deleted = 0 AND state = 2 ) ) as S WHERE S.FL LIKE ?s OR S.LF LIKE ?s;";
            static public $getSearchedMutualSubscribers = "SELECT S.id, S.FL FROM (SELECT id, CONCAT(firstname,' ',lastname) as FL, CONCAT(lastname,' ',firstname) as LF FROM profile WHERE id IN (SELECT profile_id FROM spisoksubscriber?i WHERE deleted = 0 AND state = 0 ) ) as S WHERE S.FL LIKE ?s OR S.LF LIKE ?s;";

    ########## RT ########## 
        # INSERT RT

        # DELETE RT
  
        # SELECT RT
		
	########## BLOG ########## 
        # INSERT BLOG
            // Добавить "Лайк" к конкретному комментарию
            static public $insertLikeCommentBlog = "INSERT INTO `likecommentblog?i`(`commentblog?i_id`,`profile_id`)VALUES(?i,?i);";

            // Добавить "Лайк" к конкретной статье
            static public $insertLikeBlog = "INSERT INTO `likeblog?i`(`blog?i_id`,`profile_id`)VALUES(?i,?i);";

        # DELETE BLOG

            // Удалить статью
            static public $deletePost = "DELETE FROM `blog?i` WHERE id = ?i";
            // Удалить комментарий
            static public $deleteComment = "DELETE FROM `commentblog?i` WHERE id = ?i";

            // Удалить "Лайк" из под комментария
            static public $deleteLikeCommentBlog = "DELETE FROM `likecommentblog?i` WHERE `likecommentblog?i`.`commentblog?i_id`=?i and `likecommentblog?i`.`profile_id`=?i;";

            // Удалить "Лайк" из под статьи
            static public $deleteLikeBlog = "DELETE FROM `likeblog?i` WHERE `likeblog?i`.`blog?i_id`=?i and `likeblog?i`.`profile_id`=?i;";

        # SELECT BLOG

            // Получить метки для конкретной статьи
            static public $getMetkas =	"SELECT `blogmetka`.`idblogmetka`, `blogmetka`.`name`, `blogmetka`.`color` FROM blogmetka WHERE idblogmetka IN(SELECT blogmetka_idblogmetka FROM spisokblogmetka?i WHERE blog?i_id = ?i);";

            // Получить статьи с определенной меткой
            static public $getPostsByMetka = "SELECT * FROM `blog?i` WHERE id IN(SELECT blog?i_id FROM spisokblogmetka?i WHERE blogmetka_idblogmetka = ?i) ORDER BY id DESC Limit 0,?i";

            // Получить дочерние статьи с extinsion != 0
            static public $getDoughterPosts = "SELECT * FROM `blog?i` WHERE extension = ?i ORDER BY id DESC";

            // Получить статьи без метки
            static public $getPostsWithoutMetka = "SELECT * FROM `blog?i` WHERE id NOT IN (SELECT blog?i_id FROM spisokblogmetka?i) AND extension is null ORDER BY id DESC Limit 0,?i";

            // Получить все статьи с лимитом в 2 (placeholders = 6)
            /*
            static public $getAllPosts = "SELECT * FROM `blog?i` as B WHERE B.id >= (select min(id) from `blog?i` as B where B.extension = (select min(s.id) as max from (select id from blog?i where extension is null order by id desc limit 0,?i) as s)OR B.extension is null AND B.id = (select min(s.id) as max from (select id from blog?i where extension is null order by id desc limit 0,?i) as s)) ORDER BY B.`id` DESC;";
            */
            static public $getAllPosts ="SELECT * FROM `blog?i` as B 
                                        WHERE B.extension is null order by B.id desc limit 0,?i";

            // Получить список только существующих меток
            static public $getMetkasInBox = "SELECT * FROM `blogmetka` WHERE idblogmetka IN (SELECT blogmetka_idblogmetka FROM spisokblogmetka?i )";

            // Получить все комментарии
            static public $getComments = "SELECT * FROM `commentblog?i` as B WHERE B.id >= (select min(s.id) as min from (select id from commentblog?i where extension is null order by id limit 0,?i) as s) AND B.id <= (select max(s.id) as max from (select id from commentblog?i where extension is null order by id limit 0,?i) as s) ORDER BY B.`id`;";

            // Получить имя статьи, к которой адресован комментарий
            static public $getPostNameInComment = "SELECT name FROM blog?i WHERE id in (SELECT blog?i_id FROM commentblog?i WHERE blog?i_id = ?i)";

            // Получить id профиля
            static public $getProfileId = "SELECT profile_id FROM `commentblog?i` WHERE id=?i";

            // Получить Имя и Фамилию
            static public $getProfileFI = "SELECT firstname, lastname FROM `profile` WHERE id=?i;";

            //Получить количество "Лайков" конкретного комментария
            static public $getLikeCommentBlog = "SELECT count(commentblog?i_id) as count FROM `likecommentblog?i` WHERE profile_id=?i and commentblog?i_id=?i;";

            // Получить количество "Лайков" конкретной статьи
            static public $getLikeBlog = "SELECT count(blog?i_id) as count FROM likeblog?i WHERE profile_id=?i and blog?i_id=?i";

            // Получить все статьи, которые догружаются по нажатию клавиши (placeholders = 11)
            /*
            static public $getLoadedPosts = "SELECT * FROM `blog?i` as B 
            WHERE B.id <= 
                            (select max(s.id) as min from
                            (select id from blog?i where id< ?i  and extension is null ORDER BY id DESC limit 0,?i) as s)
 
            AND B.id >= 
                            (select min(id) from blog?i where extension = (select min(s.id) as min from
                            (select id from blog?i where id< ?i  and extension is null ORDER BY id DESC limit 0,?i) as s)) OR B.extension is null AND B.id =
                            (select min(s.id) as min from
                            (select id from blog?i where id< ?i  and extension is null ORDER BY id DESC limit 0,?i) as s) 
                            ORDER BY B.`id` DESC;";
            */
            static public $getLoadedPosts = "SELECT * from blog?i where id in (select id from blog?i where id < ?i  and extension is null)ORDER BY id DESC limit 0,?i";

            // Получить догружаемые статьи с конкретной меткой (placeholders = 20)
            /*
            static public $getLoadedMetkaPosts = "SELECT * FROM `blog?i` as B 
            WHERE B.id <= 
                            (select max(s.id) as max from
                            (select id from blog?i where id < ?i AND id in (select blog?i_id FROM spisokblogmetka?i WHERE blogmetka_idblogmetka = ?i)ORDER BY id DESC LIMIT 0,?i)as s)

            AND B.id >= 
                            (select min(id) from blog?i WHERE extension = (select min(s.id) as min from
                            (select id from blog?i where id < ?i AND id in (select blog?i_id FROM spisokblogmetka?i WHERE blogmetka_idblogmetka = ?i)ORDER BY id DESC LIMIT 0,?i)as s)
                            OR extension is null and id = (select min(s.id) as min from
                            (select id from blog?i where id < ?i AND id in (select blog?i_id FROM spisokblogmetka?i WHERE blogmetka_idblogmetka = ?i)ORDER BY id DESC LIMIT 0,?i)as s)) ORDER BY id DESC";
            */
            static public $getLoadedMetkaPosts ="SELECT * from blog?i where id < ?i AND id in (select blog?i_id FROM spisokblogmetka?i WHERE blogmetka_idblogmetka = ?i)ORDER BY id DESC LIMIT 0,?i";

            // Получить догружаемые статьи без меткок (placeholders = 17)
            /*
            static public $getLoadedWithoutMetkaPosts = "SELECT * FROM `blog?i` as B 
            WHERE B.id <=
                            (select max(s.id) as max from
                            (select id from blog?i where id < ?i AND extension is null AND id not in (select blog?i_id FROM spisokblogmetka?i)ORDER BY id DESC LIMIT 0,?i)as s)

            AND B.id >=
                            (select min(id) FROM blog?i WHERE extension = (select min(s.id) as min from
                            (select id from blog?i where id < ?i AND extension is null AND id not in (select blog?i_id FROM spisokblogmetka?i)ORDER BY id DESC LIMIT 0,?i)as s)
                            OR extension is null and id = (select min(s.id) as min from
                            (select id from blog?i where id < ?i AND extension is null AND id not in (select blog?i_id FROM spisokblogmetka?i)ORDER BY id DESC LIMIT 0,?i)as s)) ORDER BY id DESC";
            */
            static public $getLoadedWithoutMetkaPosts ="SELECT * from blog?i where id < ?i AND extension is null AND id not in (select blog?i_id FROM spisokblogmetka?i)ORDER BY id DESC LIMIT 0,?i";
            
            static public $getLoadedPostsComment = "SELECT * FROM `commentblog?i` as C WHERE C.id >= (select min(s.id)as                                   min from (select id from commentblog?i where id > ?i and extension is null ORDER BY id limit 0,?i) as s) AND C.id <= (select max(s.id) as max from (select id from commentblog?i where id > ?i and extension is null order by id limit 0,?i) as s) OR C.extension = (select max(s.id) as max from (select id from commentblog?i where id > ?i and extension is null order by id limit 0,?i) as s)ORDER BY C.`id`;";

            // Получить количество статей с метками (placeholders = 4)
            static public $getCountMetkaPosts = " SELECT count(id) as count FROM blog?i WHERE extension is null AND id in (SELECT blog?i_id FROM spisokblogmetka?i WHERE blogmetka_idblogmetka = ?i)";

            // Получить количество статей без меток (placeholders = 3)
            static public $getCountWithoutMetkaPosts = "SELECT count(id) as count FROM blog?i WHERE extension is null AND id not in (SELECT blog?i_id FROM spisokblogmetka?i)";

    ########## PERSONAL ########## 
        # INSERT PERSONAL

            static public $getIdFriends = "SELECT DISTINCT iduser FROM spisokcontactgroupuser?i WHERE state = 0 ORDER BY RAND() LIMIT 9;";

        # DELETE PERSONAL
        # SELECT PERSONAL
            
    ########## ПЕРЕНЕСЕННЫЕ ЗАПРОСЫ ##########
            static public $getFIOUser = "SELECT `lastname`, `firstname`, `middlename` FROM `profile` WHERE `id` = ?i";
            static public $searchUsersIdFromProfile = "SELECT `S`.`id` FROM
                                        (
                                                        SELECT `id`, CONCAT(firstname,' ',lastname) as FL, CONCAT(lastname,' ',firstname) as LF
                                                        FROM `profile` WHERE `valid` = 1
                                        ) as S
                                        WHERE `S`.`FL` LIKE ?s OR `S`.`LF` LIKE ?s LIMIT 6;";
            static public $searchCountUsersIdFromProfile = "SELECT COUNT(`S`.`id`) AS countUsers FROM
                                        (
                                                        SELECT `id`, CONCAT(firstname,' ',lastname) as FL, CONCAT(lastname,' ',firstname) as LF
                                                        FROM `profile` WHERE `valid` = 1
                                        ) as S
                                        WHERE `S`.`FL` LIKE ?s OR `S`.`LF` LIKE ?s;";
            static public $getMoreSearchUsersIdFromProfile = "SELECT `S`.`id` FROM
                                    (
                                                    SELECT `id`, CONCAT(firstname,' ',lastname) as FL, CONCAT(lastname,' ',firstname) as LF
                                                    FROM `profile` WHERE `valid` = 1
                                    ) as S
                                    WHERE (`S`.`FL` LIKE ?s OR `S`.`LF` LIKE ?s) AND `id` > ?i LIMIT 6;";
            static public $searchCountMoreUsersIdFromProfile = "SELECT COUNT(`S`.`id`) AS countUsers FROM
                                    (
                                                    SELECT `id`, CONCAT(firstname,' ',lastname) as FL, CONCAT(lastname,' ',firstname) as LF
                                                    FROM `profile` WHERE `valid` = 1
                                    ) as S
                                    WHERE (`S`.`FL` LIKE ?s OR `S`.`LF` LIKE ?s) AND `id` > ?i;";

            static public $checkUserInRolesTable = "SELECT `role` FROM `roles` WHERE `profile_id` = ?i AND `idschool` = ?i AND `datefinish` is NULL";
            static public $checkResponsibleInRolesTable = "SELECT `idadress` FROM `roles` WHERE `idadress` = ?i AND `role` = 8 AND `datefinish` is NULL";
            static public $deleteUserFromRolesTable = "UPDATE `roles` SET `datefinish` = NOW() WHERE `idadress` = ?i AND `role` = ?i";
            /** Создает роль указанного пользователя. Плэйсхолдеры(6): IDuserSOC, role, IDadressDO, IDschoolDO, info, schoolname */
            static public $cRole = "CALL cRole(?i, ?i, ?i, ?i, ?s, ?s);";
            /** Удаляет в историю роль указанного пользователя. Плэйсхолдеры(3): IDschoolDO, IDadressDO, role */
            static public $dRole = "CALL dRole(?i, ?i, ?i);";
            /** Проверяет, есть ли указанный учитель в указанной школе. Плэйсхолдеры(2): IDprofileSOC, IDschoolDO */
            static public $checkTeacherInSchool = "SELECT COUNT(*) FROM `roles` WHERE `profile_id` = ?i AND `idschool` = ?i AND `datefinish` is NULL AND `role` = 2";
}
?>