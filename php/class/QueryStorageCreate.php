<?php
class QS_CREATE 
{
  ########## CREATE TRIGERS ##########
  
    ########## Wall ##########
      # CREATE TRIGER WALL
        static public $createTriggerWallN_AINS = "CREATE TRIGGER `wall?i_AINS` AFTER INSERT ON `wall?i` FOR EACH ROW BEGIN IF NEW.extension is NULL AND NEW.`idauthor` = '?i' THEN UPDATE `profile` SET `profile`.`countwall` = `profile`.`countwall` + 1 , `profile`.`countwallmy` = `profile`.`countwallmy` + 1 WHERE `profile`.`id` = '?i'; ELSE IF NEW.extension is NULL AND NEW.`idauthor` <> '?i' THEN UPDATE `profile` SET `profile`.`countwall` = `profile`.`countwall` + 1 WHERE `profile`.`id` = '?i'; END IF; END IF; END; ";
        
        static public $createTriggerWallN_ADEL = "CREATE TRIGGER `wall?i_ADEL` AFTER DELETE ON `wall?i` FOR EACH ROW BEGIN IF OLD.extension is NULL AND OLD.`idauthor` = '?i' THEN UPDATE `profile` SET `profile`.`countwall` = `profile`.`countwall` - 1, `profile`.`countwallmy` = `profile`.`countwallmy` - 1 WHERE `profile`.`id` = '?i'; ELSE IF OLD.extension is NULL AND OLD.`idauthor` <> '?i' THEN UPDATE `profile` SET `profile`.`countwall` = `profile`.`countwall` - 1 WHERE `profile`.`id` = '?i'; END IF; END IF; END; ";

      # CREATE TRIGER LikeWall
        static public $createTriggerLikewallN_AINS = "CREATE TRIGGER `likewall?i_AINS` AFTER INSERT ON `likewall?i` FOR EACH ROW BEGIN UPDATE wall?i SET countlike = countlike +1 WHERE wall?i.id = new.wall?i_id; END; ";
        
        static public $createTriggerLikewallN_ADEL = "CREATE TRIGGER `likewall?i_ADEL` AFTER DELETE ON `likewall?i` FOR EACH ROW BEGIN UPDATE wall?i SET countlike = countlike -1 WHERE wall?i.id = old.wall?i_id; END; ";

      # CREATE TRIGER CommentWall
        static public $createTriggerCommentwallN_AINS = "CREATE TRIGGER `commentwall?i_AINS` AFTER INSERT ON `commentwall?i` FOR EACH ROW BEGIN IF NEW.extension is NULL THEN UPDATE wall?i SET countcomment= countcomment + 1 WHERE wall?i.id=NEW.wall?i_id; END IF; END; ";
        
        static public $createTriggerCommentwallN_ADEL = "CREATE TRIGGER `commentwall?i_ADEL` AFTER DELETE ON `commentwall?i` FOR EACH ROW BEGIN IF OLD.extension is NULL THEN UPDATE wall?i SET countcomment= countcomment - 1 WHERE wall?i.id=OLD.wall?i_id; END IF; END; ";
      
      # CREATE TRIGER LikeCommentWall
        static public $createTriggerLikeCommentwallN_AINS = "CREATE TRIGGER `likecommentwall?i_AINS` AFTER INSERT ON `likecommentwall?i` FOR EACH ROW BEGIN UPDATE commentwall?i SET countlike = countlike +1 WHERE commentwall?i.id = New.commentwall?i_id; END; ";
        
        static public $createTriggerLikeCommentwallN_ADEL = "CREATE TRIGGER `likecommentwall?i_ADEL` AFTER DELETE ON `likecommentwall?i` FOR EACH ROW BEGIN UPDATE commentwall?i SET countlike = countlike -1 WHERE commentwall?i.id = old.commentwall?i_id; END; ";

    ########## Blog ##########
      # CREATE TRIGER Blog
        static public $createTriggerBlogN_AINS = "CREATE TRIGGER `blog?i_AINS` AFTER INSERT ON `blog?i` FOR EACH ROW BEGIN IF NEW.extension is NULL AND NEW.`idauthor` = '?i' THEN UPDATE `profile` SET `profile`.`countblog` = `profile`.`countblog` + 1 WHERE `profile`.`id` = '?i'; ELSE IF NEW.extension is NULL AND NEW.`idauthor` <> '?i' THEN UPDATE `profile` SET `profile`.`countblog` = `profile`.`countblog` + 1 WHERE `profile`.`id` = '?i'; END IF; END IF; END; ";

        static public $createTriggerBlogN_ADEL = "CREATE TRIGGER `blog?i_ADEL` AFTER DELETE ON `blog?i` FOR EACH ROW BEGIN IF OLD.extension is NULL AND OLD.`idauthor` = '?i' THEN UPDATE `profile` SET `profile`.`countblog` = `profile`.`countblog` - 1 WHERE `profile`.`id` = '?i'; ELSE IF OLD.extension is NULL AND OLD.`idauthor` <> '?i' THEN UPDATE `profile` SET `profile`.`countblog` = `profile`.`countblog` - 1 WHERE `profile`.`id` = '?i'; END IF; END IF; END; ";

      # CREATE TRIGER LikeBlog
        static public $createTriggerLikeBlogN_AINS = "CREATE TRIGGER `likeblog?i_AINS` AFTER INSERT ON `likeblog?i` FOR EACH ROW BEGIN UPDATE blog?i SET countlike = countlike +1 WHERE blog?i.id = new.blog?i_id; END; ";
        
        static public $createTriggerLikeBlogN_ADEL = "CREATE TRIGGER `likeblog?i_ADEL` AFTER DELETE ON `likeblog?i` FOR EACH ROW BEGIN UPDATE blog?i SET countlike = countlike -1 WHERE blog?i.id = old.blog?i_id; END; ";

      # CREATE TRIGER CommentBlog
        static public $createTriggerCommentBlogN_AINS = "CREATE TRIGGER `commentblog?i_AINS` AFTER INSERT ON `commentblog?i` FOR EACH ROW BEGIN IF NEW.extension is NULL THEN UPDATE blog?i SET countcomment= countcomment + 1 WHERE blog?i.id=NEW.blog?i_id; IF NEW.extension is NULL AND NEW.`profile_id` = '?i' THEN UPDATE `profile` SET `profile`.`countblogcomment` = `profile`.`countblogcomment` + 1 WHERE `profile`.`id` = '?i'; ELSE IF NEW.extension is NULL AND NEW.`profile_id` <> '?i' THEN UPDATE `profile` SET `profile`.`countblogcomment` = `profile`.`countblogcomment` + 1 WHERE `profile`.`id` = '?i'; END IF; END IF; END IF; END; ";
        
        static public $createTriggerCommentBlogN_ADEL = "CREATE TRIGGER `commentblog?i_ADEL` AFTER DELETE ON `commentblog?i` FOR EACH ROW BEGIN IF OLD.extension is NULL THEN UPDATE blog?i SET countcomment= countcomment - 1 WHERE blog?i.id=OLD.blog?i_id; IF OLD.extension is NULL AND OLD.`profile_id` = '?i' THEN UPDATE `profile` SET `profile`.`countblogcomment` = `profile`.`countblogcomment` - 1 WHERE `profile`.`id` = '?i'; ELSE IF OLD.extension is NULL AND OLD.`profile_id` <> '?i' THEN UPDATE `profile` SET `profile`.`countblogcomment` = `profile`.`countblogcomment` - 1 WHERE `profile`.`id` = '?i'; END IF; END IF; END IF; END; ";
      
      # CREATE TRIGER LikeCommentBlog
        static public $createTriggerLikeCommentBlogN_AINS = "CREATE TRIGGER `likecommentblog?i_AINS` AFTER INSERT ON `likecommentblog?i` FOR EACH ROW BEGIN UPDATE commentblog?i SET countlike = countlike +1 WHERE commentblog?i.id = New.commentblog?i_id; END; ";
        
        static public $createTriggerLikeCommentBlogN_ADEL = "CREATE TRIGGER `likecommentblog?i_ADEL` AFTER DELETE ON `likecommentblog?i` FOR EACH ROW BEGIN UPDATE commentblog?i SET countlike = countlike -1 WHERE commentblog?i.id = old.commentblog?i_id; END; ";

    ########## Album ##########
      # CREATE TRIGER Album
        static public $createTriggerAlbumN_AINS = "CREATE TRIGGER `album?i_AINS` AFTER INSERT ON `album?i` FOR EACH ROW BEGIN UPDATE `profile` SET `profile`.`countalbum` = `profile`.`countalbum` + 1 WHERE `profile`.`id` = '?i'; END; ";

        static public $createTriggerAlbumN_ADEL = "CREATE TRIGGER `album?i_ADEL` AFTER DELETE ON `album?i` FOR EACH ROW BEGIN UPDATE `profile` SET `profile`.`countalbum` = `profile`.`countalbum` - 1 WHERE `profile`.`id` = '?i'; END; ";

      # CREATE TRIGER LikeAlbum
        static public $createTriggerLikeAlbumN_AINS = "CREATE TRIGGER `likealbum?i_AINS` AFTER INSERT ON `likealbum?i` FOR EACH ROW BEGIN UPDATE album?i SET countlike = countlike +1 WHERE album?i.id = new.album?i_id; END; ";
        
        static public $createTriggerLikeAlbumN_ADEL = "CREATE TRIGGER `likealbum?i_ADEL` AFTER DELETE ON `likealbum?i` FOR EACH ROW BEGIN UPDATE album?i SET countlike = countlike -1 WHERE album?i.id = old.album?i_id; END; ";

      # CREATE TRIGER CommentAlbum
        static public $createTriggerCommentAlbumN_AINS = "CREATE TRIGGER `commentalbum?i_AINS` AFTER INSERT ON `commentalbum?i` FOR EACH ROW BEGIN IF NEW.extension is NULL THEN UPDATE album?i SET countcomment= countcomment + 1 WHERE album?i.id=NEW.album?i_id; END IF; END; ";
        
        static public $createTriggerCommentAlbumN_ADEL = "CREATE TRIGGER `commentalbum?i_ADEL` AFTER DELETE ON `commentalbum?i` FOR EACH ROW BEGIN IF OLD.extension is NULL THEN UPDATE album?i SET countcomment= countcomment - 1 WHERE album?i.id=OLD.album?i_id; END IF; END; ";

      # CREATE TRIGER LikeCommentAlbum
        static public $createTriggerLikeCommentAlbumN_AINS = "CREATE TRIGGER `likecommentalbum?i_AINS` AFTER INSERT ON `likecommentalbum?i` FOR EACH ROW BEGIN UPDATE commentalbum?i SET countlike = countlike +1 WHERE commentalbum?i.id = New.commentalbum?i_id; END; ";
        
        static public $createTriggerLikeCommentAlbumN_ADEL = "CREATE TRIGGER `likecommentalbum?i_ADEL` AFTER DELETE ON `likecommentalbum?i` FOR EACH ROW BEGIN UPDATE commentalbum?i SET countlike = countlike -1 WHERE commentalbum?i.id = old.commentalbum?i_id; END; ";

    ########## SpisokAlbumPhoto ##########
      # CREATE TRIGER SpisokAlbumPhoto
        static public $createTriggerSpisokAlbumPhotoN_AINS = "CREATE TRIGGER `spisokalbumphoto?i_AINS` AFTER INSERT ON `spisokalbumphoto?i` FOR EACH ROW BEGIN UPDATE `album?i` SET `album?i`.`countphoto` = `album?i`.`countphoto` + 1 WHERE `album?i`.`id` = NEW.album?i_id; END; ";

        static public $createTriggerSpisokAlbumPhotoN_ADEL = "CREATE TRIGGER `spisokalbumphoto?i_ADEL` AFTER DELETE ON `spisokalbumphoto?i` FOR EACH ROW BEGIN UPDATE `album?i` SET `album?i`.`countphoto` = `album?i`.`countphoto` - 1 WHERE `album?i`.`id` = OLD.album?i_id; END; ";

      # CREATE TRIGER LikeSpisokAlbumPhoto
        static public $createTriggerLikeSpiSokAlbumphotoN_AINS = "CREATE TRIGGER `likespisokalbumphoto?i_AINS` AFTER INSERT ON `likespisokalbumphoto?i` FOR EACH ROW BEGIN UPDATE spisokalbumphoto?i SET countlike = countlike +1 WHERE spisokalbumphoto?i.id = new.spisokalbumphoto?i_id; END; ";

        static public $createTriggerLikespiSokAlbumphotoN_ADEL = "CREATE TRIGGER `likespisokalbumphoto?i_ADEL` AFTER DELETE ON `likespisokalbumphoto?i` FOR EACH ROW BEGIN UPDATE spisokalbumphoto?i SET countlike = countlike -1 WHERE spisokalbumphoto?i.id = old.spisokalbumphoto?i_id; END; ";

      # CREATE TRIGER CommentSpisokAlbumPhoto
        static public $createTriggerCommentSpisokAlbumphotoN_AINS = "CREATE TRIGGER `commentspisokalbumphoto?i_AINS` AFTER INSERT ON `commentspisokalbumphoto?i` FOR EACH ROW BEGIN IF NEW.extension is NULL THEN UPDATE spisokalbumphoto?i SET countcomment= countcomment + 1 WHERE spisokalbumphoto?i.id=NEW.spisokalbumphoto?i_id; END IF; END; ";

        static public $createTriggerCommentSpisokAlbumphotoN_ADEL = "CREATE TRIGGER `commentspisokalbumphoto?i_ADEL` AFTER DELETE ON `commentspisokalbumphoto?i` FOR EACH ROW BEGIN IF OLD.extension is NULL THEN UPDATE spisokalbumphoto?i SET countcomment= countcomment - 1 WHERE spisokalbumphoto?i.id=OLD.spisokalbumphoto?i_id; END IF; END; ";

      # CREATE TRIGER LikeCommentSpisokAlbumPhoto
        static public $createTriggerLikeCommentSpisokAlbumphotoN_AINS = "CREATE TRIGGER `likecommentspisokalbumphoto?i_AINS` AFTER INSERT ON `likecommentspisokalbumphoto?i` FOR EACH ROW BEGIN UPDATE commentspisokalbumphoto?i SET countlike = countlike +1 WHERE commentspisokalbumphoto?i.id = New.commentspisokalbumphoto?i_id; END; ";

        static public $createTriggerLikeCommentSpisokAlbumphotoN_ADEL = "CREATE TRIGGER `likecommentspisokalbumphoto?i_ADEL` AFTER DELETE ON `likecommentspisokalbumphoto?i` FOR EACH ROW BEGIN UPDATE commentspisokalbumphoto?i SET countlike = countlike -1 WHERE commentspisokalbumphoto?i.id = old.commentspisokalbumphoto?i_id; END; ";

    ########## Contacts ##########
      # CREATE TRIGER SpisokContactGroupUser
        static public $createTriggerSpisokContactGroupUserNNN_BINS = "CREATE TRIGGER `spisokcontactgroupuser?i_BINS` before INSERT ON  `spisokcontactgroupuser?i` FOR EACH ROW BEGIN if NEW.contactgroup?i_id IS NOT NULL and NEW.state = 0 then UPDATE contactgroup?i SET countuser = countuser +1 WHERE contactgroup?i.id =NEW.contactgroup?i_id; SET @a= (Select count(*) from spisokcontactgroupuser?i where iduser = NEW.iduser); if @a<=0 then UPDATE profile SET countcontact = countcontact +1 WHERE profile.id =?i; END IF;  END IF; END; ";
        
        static public $createTriggerSpisokContactGroupUserNNN_ADEL = "CREATE TRIGGER `spisokcontactgroupuser?i_ADEL` AFTER DELETE ON  `spisokcontactgroupuser?i` FOR EACH ROW BEGIN if OLD.contactgroup?i_id IS NOT NULL and OLD.state = 0 then UPDATE contactgroup?i SET countuser = countuser -1 WHERE contactgroup?i.id =OLD.contactgroup?i_id; SET @a= (Select count(*) from spisokcontactgroupuser?i where iduser = OLD.iduser); if @a<=0 then UPDATE profile SET countcontact = countcontact -1 WHERE profile.id =?i; END IF; END IF; END; ";

      # CREATE TRIGER ContactGroup
        static public $createTriggerContactGroupNNN_ADEL = "CREATE TRIGGER `contactgroup?i_ADEL` AFTER DELETE ON contactgroup?i FOR EACH ROW BEGIN DELETE FROM spisokcontactgroupuser?i WHERE contactgroup?i_id is null AND state = 0 OR state = 1; END; ";

      # CREATE TRIGER SpisokContactGroupUser
        static public $createTriggerSpisokContactGroupUserNNN_AUPD = "CREATE TRIGGER `spisokcontactgroupuser?i_AUPD` after update ON  `spisokcontactgroupuser?i` FOR EACH ROW BEGIN UPDATE profile SET countcontact = (Select count(*) from (select distinct iduser as x from spisokcontactgroupuser?i where state = 0) as y) where id =?i; if OLD.contactgroup?i_id is null and NEW.contactgroup?i_id is not null then UPDATE contactgroup?i SET countuser = (Select count(*) from (select distinct iduser as x from spisokcontactgroupuser?i where state = 0 and contactgroup?i_id = NEW.contactgroup?i_id) as y) where id =NEW.contactgroup?i_id; end if;  if OLD.contactgroup?i_id is not null and NEW.contactgroup?i_id is null then UPDATE contactgroup?i SET countuser = (Select count(*) from (select distinct iduser as x from spisokcontactgroupuser?i where state = 0 and contactgroup?i_id = OLD.contactgroup?i_id) as y) where id =OLD.contactgroup?i_id; end if;  if OLD.contactgroup?i_id is not null and OLD.state = 0 and NEW.state != 0  then UPDATE contactgroup?i SET countuser = (Select count(*) from (select distinct iduser as x from spisokcontactgroupuser?i where state = 0 and contactgroup?i_id = OLD.contactgroup?i_id) as y) where id =OLD.contactgroup?i_id; end if; if OLD.contactgroup?i_id is not null and OLD.state != 0 and NEW.state = 0  then UPDATE contactgroup?i SET countuser = (Select count(*) from (select distinct iduser as x from spisokcontactgroupuser?i where state = 0 and contactgroup?i_id = OLD.contactgroup?i_id) as y) where id =OLD.contactgroup?i_id; end if; if OLD.contactgroup?i_id != NEW.contactgroup?i_id then UPDATE contactgroup?i SET countuser = (Select count(*) from (select distinct iduser as x from spisokcontactgroupuser?i where state = 0 and contactgroup?i_id = OLD.contactgroup?i_id) as y) where id =OLD.contactgroup?i_id; UPDATE contactgroup?i SET countuser = (Select count(*) from (select distinct iduser as x from spisokcontactgroupuser?i where state = 0 and contactgroup?i_id = NEW.contactgroup?i_id) as y) where id =NEW.contactgroup?i_id; end if; END; ";
    
    ########## Messages ##########
      
      /* count placeholder: 3(idProfile, idProfile, idProfile)*/  
        static public $createTriggerMessageNNN_AINS = "CREATE TRIGGER `message?i_AINS` AFTER INSERT ON `message?i` FOR EACH ROW BEGIN DECLARE idProfile integer; SET @idProfile := ?i; IF (@idProfile = NEW.idrecepient AND NEW.extension IS NULL) THEN UPDATE profile SET countInbox = countInbox + 1 WHERE id = @idProfile; END IF; IF (@idProfile = NEW.idsender AND NEW.extension IS NULL) THEN UPDATE profile SET countOutbox = countOutbox + 1 WHERE id = @idProfile; END IF; END; ";
      
      /* count placeholder: 3(idProfile, idProfile, idProfile)*/  
        static public $createTriggerMessageNNN_ADEL = "CREATE TRIGGER `message?i_ADEL` AFTER DELETE ON `message?i` FOR EACH ROW BEGIN DECLARE idProfile integer; SET @idProfile := ?i; IF (@idProfile = OLD.idrecepient AND OLD.extension IS NULL) THEN UPDATE profile SET countinbox = countinbox - 1 WHERE id = @idProfile; END IF; IF (@idProfile = OLD.idsender AND OLD.extension IS NULL) THEN UPDATE profile SET countOutbox = countoutbox - 1 WHERE id = @idProfile; END IF; END; ";

  ########## CREATE TABLES ########## 
    # 1. CREATE TABLE Meassage
      /** * Count placeholder: 12 */
      static public $createTableMeassageN = "CREATE  TABLE IF NOT EXISTS `message?i` (`id` INT UNSIGNED NOT NULL AUTO_INCREMENT , `text` VARCHAR(256) NOT NULL , `datetime` DATETIME NOT NULL , `messagegroup_id` INT UNSIGNED NULL , `idrecepient` INT UNSIGNED NULL , `idsender` INT UNSIGNED NULL , `extension` INT UNSIGNED NULL , `new` TINYINT(1) NOT NULL DEFAULT 1 , INDEX `fk_mes?i_mesgr_idx` (`messagegroup_id` ASC) , INDEX `fk_mes?i_prf1_idx` (`idrecepient` ASC) , INDEX `fk_mes?i_prf2_idx` (`idsender` ASC) , UNIQUE INDEX `id_UNIQUE` (`id` ASC) , PRIMARY KEY (`id`) , INDEX `fk_mes?i_mes?i1_idx` (`extension` ASC) , CONSTRAINT `fk_mes?i_mesgr` FOREIGN KEY (`messagegroup_id` ) REFERENCES `messagegroup` (`id` ) ON DELETE CASCADE ON UPDATE CASCADE, CONSTRAINT `fk_mes?i_prf1` FOREIGN KEY (`idrecepient` ) REFERENCES `profile` (`id` ) ON DELETE CASCADE ON UPDATE CASCADE, CONSTRAINT `fk_mes?i_prf2` FOREIGN KEY (`idsender` ) REFERENCES `profile` (`id` ) ON DELETE CASCADE ON UPDATE CASCADE, CONSTRAINT `fk_mes?i_mes?i1` FOREIGN KEY (`extension` ) REFERENCES `message?i` (`id` ) ON DELETE CASCADE ON UPDATE CASCADE) ENGINE = InnoDB;";

    # 2. CREATE TABLE Avatar
      /** * Count placeholder: 1 */
      static public $createTableAvatarN = "CREATE  TABLE IF NOT EXISTS `avatar?i` (`id` INT UNSIGNED NOT NULL AUTO_INCREMENT , `isavatar` TINYINT(1) NOT NULL , `countlike` INT UNSIGNED NOT NULL DEFAULT 0 , `countcomment` INT UNSIGNED NOT NULL DEFAULT 0 , `datetime` DATETIME NOT NULL , `prev` INT UNSIGNED NULL ,`update` DATETIME NOT NULL , PRIMARY KEY (`id`) , UNIQUE INDEX `id_UNIQUE` (`id` ASC) ) ENGINE = InnoDB;";

    # 3. CREATE TABLE Contactgroup
      /** * Count placeholder: 1 */
      static public $createTableContactgroupN = "CREATE  TABLE IF NOT EXISTS `contactgroup?i` (`id` INT UNSIGNED NOT NULL AUTO_INCREMENT , `name` VARCHAR(45) NOT NULL , `countuser` INT UNSIGNED NOT NULL DEFAULT 0 , PRIMARY KEY (`id`) , UNIQUE INDEX `id_UNIQUE` (`id` ASC) ) ENGINE = InnoDB;";
      
    # 4. CREATE TABLE Spisokcontactgroupuser
      /** * Count placeholder: 11 !!!! */
      static public $createTableSpisokcontactgroupuserN = "CREATE  TABLE IF NOT EXISTS `spisokcontactgroupuser?i` (`iduser` INT UNSIGNED NOT NULL , `contactgroup?i_id` INT UNSIGNED NULL , `deleted` TINYINT(1) NOT NULL DEFAULT 0, `state` INT UNSIGNED NOT NULL , INDEX `fk_spcongrusr?i_congr?i1_idx` (`contactgroup?i_id` ASC) , INDEX `fk_spcongrusr?i_prf1_idx` (`iduser` ASC) , CONSTRAINT `fk_spcongrusr?i_congr?i1` FOREIGN KEY (`contactgroup?i_id` ) REFERENCES `contactgroup?i` (`id` ) ON DELETE SET NULL ON UPDATE CASCADE, CONSTRAINT `fk_spcongrusr?i_prf1` FOREIGN KEY (`iduser` ) REFERENCES `profile` (`id` ) ON DELETE CASCADE ON UPDATE CASCADE) ENGINE = InnoDB;";
      
    # 5. CREATE TABLE Wall
      /** * Count placeholder: 6 */
      static public $createTableWallN = "CREATE  TABLE IF NOT EXISTS `wall?i` (`id` INT UNSIGNED NOT NULL AUTO_INCREMENT , `text` VARCHAR(256) NOT NULL , `datetime` DATETIME NOT NULL , `idauthor` INT UNSIGNED NOT NULL , `countlike` INT UNSIGNED NOT NULL DEFAULT 0 , `countcomment` INT UNSIGNED NOT NULL DEFAULT 0 , `extension` INT UNSIGNED NULL , PRIMARY KEY (`id`) , UNIQUE INDEX `id_UNIQUE` (`id` ASC) , INDEX `fk_wll?i_wll?i1_idx` (`extension` ASC) , CONSTRAINT `fk_wll?i_wll?i1` FOREIGN KEY (`extension` ) REFERENCES `wall?i` (`id` ) ON DELETE CASCADE ON UPDATE CASCADE) ENGINE = InnoDB;";
      
    # 6. CREATE TABLE Blog
      /** * Count placeholder: 6 */
      static public $createTableBlogN = "CREATE  TABLE IF NOT EXISTS `blog?i` (`id` INT UNSIGNED NOT NULL AUTO_INCREMENT , `datetime` DATETIME NOT NULL , `text` VARCHAR(256) NOT NULL , `countlike` INT UNSIGNED NOT NULL DEFAULT 0 , `countcomment` INT UNSIGNED NOT NULL DEFAULT 0 , `extension` INT UNSIGNED NULL , `name` VARCHAR(256) NOT NULL , `source` TEXT NULL , `idauthor` INT UNSIGNED NOT NULL , PRIMARY KEY (`id`) , UNIQUE INDEX `id_UNIQUE` (`id` ASC) , INDEX `fk_blg?i_blg?i1_idx` (`extension` ASC) , CONSTRAINT `fk_blg?i_blg?i1` FOREIGN KEY (`extension` ) REFERENCES `blog?i` (`id` ) ON DELETE CASCADE ON UPDATE CASCADE) ENGINE = InnoDB;";
      
    # 7. CREATE TABLE Spisokblogmetka
      /** * Count placeholder: 11 */
      static public $createTableSpisokblogmetkaN = "CREATE  TABLE IF NOT EXISTS `spisokblogmetka?i` (`blog?i_id` INT UNSIGNED NOT NULL , `blogmetka_idblogmetka` INT UNSIGNED NOT NULL , INDEX `fk_spblgmtk?i_blg?i1_idx` (`blog?i_id` ASC) , INDEX `fk_spblgmtk?i_blgmtk1_idx` (`blogmetka_idblogmetka` ASC) , CONSTRAINT `fk_spblgmtk?i_blg?i1` FOREIGN KEY (`blog?i_id` ) REFERENCES `blog?i` (`id` ) ON DELETE CASCADE ON UPDATE CASCADE, CONSTRAINT `fk_spblgmtk?i_blgmtk1` FOREIGN KEY (`blogmetka_idblogmetka` ) REFERENCES `blogmetka` (`idblogmetka` ) ON DELETE CASCADE ON UPDATE CASCADE) ENGINE = InnoDB;";
      
    # 8. CREATE TABLE Album
      /** * Count placeholder: 6 */
      static public $createTableAlbumN = "CREATE  TABLE IF NOT EXISTS `album?i` (`id` INT UNSIGNED NOT NULL AUTO_INCREMENT , `name` VARCHAR(150) NOT NULL , `description` VARCHAR(256) NULL , `countphoto` INT UNSIGNED NOT NULL DEFAULT 0 , `countcomment` INT UNSIGNED NOT NULL DEFAULT 0 , `countlike` INT UNSIGNED NOT NULL DEFAULT 0 , `coverpagephoto` INT UNSIGNED NULL , PRIMARY KEY (`id`) , UNIQUE INDEX `id_UNIQUE` (`id` ASC) ) ENGINE = InnoDB;";
      
    # 9. CREATE TABLE Spisokalbumphoto
      /** * Count placeholder: 7 */
      static public $createTableSpisokalbumphotoN = "CREATE  TABLE IF NOT EXISTS `spisokalbumphoto?i` (`id` INT UNSIGNED NOT NULL AUTO_INCREMENT , `date` DATETIME NOT NULL , `album?i_id` INT UNSIGNED NOT NULL , `countcomment` INT UNSIGNED NOT NULL DEFAULT 0 , `countlike` INT UNSIGNED NOT NULL DEFAULT 0 , `name` VARCHAR(256) NULL , `description` VARCHAR(256) NULL , PRIMARY KEY (`id`) , UNIQUE INDEX `id_UNIQUE` (`id` ASC) , INDEX `fk_spisokalbumphoto?i_album?i1_idx` (`album?i_id` ASC) , CONSTRAINT `fk_spisokalbumphoto?i_album?i1` FOREIGN KEY (`album?i_id` ) REFERENCES `album?i` (`id` ) ON DELETE CASCADE ON UPDATE CASCADE) ENGINE = InnoDB;";
      
    # 10. CREATE TABLE Commentavatar
      /** * Count placeholder: 24 */
      static public $createTableCommentavatarN = "CREATE  TABLE IF NOT EXISTS `commentavatar?i` (`id` INT UNSIGNED NOT NULL AUTO_INCREMENT , `datetime` DATETIME NOT NULL , `text` VARCHAR(256) NOT NULL , `countlike` INT UNSIGNED NOT NULL DEFAULT 0 , `avatar?i_id` INT UNSIGNED NOT NULL , `commentavatar?i_id` INT UNSIGNED NULL , `profile_id` INT UNSIGNED NOT NULL , `extension` INT UNSIGNED NULL , PRIMARY KEY (`id`) , UNIQUE INDEX `id_UNIQUE` (`id` ASC) , INDEX `fk_comava?i_ava?i1_idx` (`avatar?i_id` ASC) , INDEX `fk_comava?i_comava?i1_idx` (`commentavatar?i_id` ASC) , INDEX `fk_comava?i_prf_idx` (`profile_id` ASC) , INDEX `fk_comava?i_comava?i2_idx` (`extension` ASC) , CONSTRAINT `fk_comava?i_ava?i1` FOREIGN KEY (`avatar?i_id` ) REFERENCES `avatar?i` (`id` ) ON DELETE CASCADE ON UPDATE CASCADE, CONSTRAINT `fk_comava?i_comava?i1` FOREIGN KEY (`commentavatar?i_id` ) REFERENCES `commentavatar?i` (`id` ) ON DELETE SET NULL ON UPDATE CASCADE, CONSTRAINT `fk_comava?i_prf1` FOREIGN KEY (`profile_id` ) REFERENCES `profile` (`id` ) ON DELETE CASCADE ON UPDATE CASCADE, CONSTRAINT `fk_comava?i_comava?i2` FOREIGN KEY (`extension` ) REFERENCES `commentavatar?i` (`id` ) ON DELETE CASCADE ON UPDATE CASCADE) ENGINE = InnoDB;";
      
    # 11. CREATE TABLE SpisokSubscriber
      /** */
      static public $createTableSpisokSubscriberNNN = "CREATE  TABLE IF NOT EXISTS `spisoksubscriber?i` (`profile_id` INT UNSIGNED NOT NULL , `deleted` TINYINT(1) NOT NULL DEFAULT 0, `state` INT UNSIGNED NOT NULL , INDEX `fk_spss?i_prf1_idx` (`profile_id` ASC) , CONSTRAINT `fk_spss?i_prf1` FOREIGN KEY (`profile_id` ) REFERENCES `profile` (`id` ) ON DELETE CASCADE ON UPDATE CASCADE) ENGINE = InnoDB;";
      
    # 12. CREATE TABLE Commentwall
      /** * Count placeholder: 24 */
      static public $createTableCommentwallN = "CREATE  TABLE IF NOT EXISTS `commentwall?i` (`id` INT UNSIGNED NOT NULL AUTO_INCREMENT , `datetime` DATETIME NOT NULL , `text` VARCHAR(256) NOT NULL , `countlike` INT UNSIGNED NOT NULL DEFAULT 0 , `wall?i_id` INT UNSIGNED NOT NULL , `commentwall?i_id` INT UNSIGNED NULL , `profile_id` INT UNSIGNED NOT NULL , `extension` INT UNSIGNED NULL , PRIMARY KEY (`id`) , UNIQUE INDEX `id_UNIQUE` (`id` ASC) , INDEX `fk_comwll?i_wall?i1_idx` (`wall?i_id` ASC) , INDEX `fk_comwll?i_comwll?i1_idx` (`commentwall?i_id` ASC) , INDEX `fk_comwll?i_prf1_idx` (`profile_id` ASC) , INDEX `fk_comwll?i_comwll?i2_idx` (`extension` ASC) , CONSTRAINT `fk_comwll?i_wall?i1` FOREIGN KEY (`wall?i_id` ) REFERENCES `wall?i` (`id` ) ON DELETE CASCADE ON UPDATE CASCADE, CONSTRAINT `fk_comwll?i_comwll?i1` FOREIGN KEY (`commentwall?i_id` ) REFERENCES `commentwall?i` (`id` ) ON DELETE SET NULL ON UPDATE CASCADE, CONSTRAINT `fk_comwll?i_prf1` FOREIGN KEY (`profile_id` ) REFERENCES `profile` (`id` ) ON DELETE CASCADE ON UPDATE CASCADE, CONSTRAINT `fk_comwll?i_comwll?i2` FOREIGN KEY (`extension` ) REFERENCES `commentwall?i` (`id` ) ON DELETE CASCADE ON UPDATE CASCADE) ENGINE = InnoDB;";
      
    # 13. CREATE TABLE Commentblog
      /** * Count placeholder: 24 */
      static public $createTableCommentblogN = "CREATE  TABLE IF NOT EXISTS `commentblog?i` (`id` INT UNSIGNED NOT NULL AUTO_INCREMENT , `datetime` DATETIME NOT NULL , `text` VARCHAR(256) NOT NULL , `countlike` INT UNSIGNED NOT NULL DEFAULT 0 , `blog?i_id` INT UNSIGNED NOT NULL , `commentblog?i_id` INT UNSIGNED NULL , `profile_id` INT UNSIGNED NOT NULL , `extension` INT UNSIGNED NULL , PRIMARY KEY (`id`) , UNIQUE INDEX `id_UNIQUE` (`id` ASC) , INDEX `fk_comblg?i_blg?i1_idx` (`blog?i_id` ASC) , INDEX `fk_comblg?i_comblg?i1_idx` (`commentblog?i_id` ASC) , INDEX `fk_comblg?i_prf1_idx` (`profile_id` ASC) , INDEX `fk_comblg?i_comblg?i2_idx` (`extension` ASC) , CONSTRAINT `fk_comblg?i_blg?i1` FOREIGN KEY (`blog?i_id` ) REFERENCES `blog?i` (`id` ) ON DELETE CASCADE ON UPDATE CASCADE, CONSTRAINT `fk_comblg?i_comblg?i1` FOREIGN KEY (`commentblog?i_id` ) REFERENCES `commentblog?i` (`id` ) ON DELETE SET NULL ON UPDATE CASCADE, CONSTRAINT `fk_comblg?i_prf1` FOREIGN KEY (`profile_id` ) REFERENCES `profile` (`id` ) ON DELETE CASCADE ON UPDATE CASCADE, CONSTRAINT `fk_comblg?i_comblg?i2` FOREIGN KEY (`extension` ) REFERENCES `commentblog?i` (`id` ) ON DELETE CASCADE ON UPDATE CASCADE) ENGINE = InnoDB;";
      
    # 14. CREATE TABLE Commentalbum
      /** * Count placeholder: 6 */
      static public $createTableCommentalbumN = "CREATE  TABLE IF NOT EXISTS `commentalbum?i` (`id` INT UNSIGNED NOT NULL AUTO_INCREMENT , `datetime` DATETIME NOT NULL , `text` VARCHAR(256) NOT NULL , `countlike` INT UNSIGNED NOT NULL DEFAULT 0 , `album?i_id` INT UNSIGNED NOT NULL , `commentalbum?i_id` INT UNSIGNED NULL , `profile_id` INT UNSIGNED NOT NULL , `extension` INT UNSIGNED NULL , PRIMARY KEY (`id`) , UNIQUE INDEX `id_UNIQUE` (`id` ASC) , INDEX `fk_commentalbum?i_albumN1_idx` (`album?i_id` ASC) , INDEX `fk_commentalbum?i_ommentalbum?i1_idx` (`commentalbum?i_id` ASC) , INDEX `fk_commentalbum?i_profile1_idx` (`profile_id` ASC) , INDEX `fk_commentalbum?i_commentalbum?i2_idx` (`extension` ASC) , CONSTRAINT `fk_commentalbum?i_album?i1` FOREIGN KEY (`album?i_id` ) REFERENCES `album?i` (`id` ) ON DELETE CASCADE ON UPDATE CASCADE, CONSTRAINT `fk_commentalbum?i_commentalbum?i1` FOREIGN KEY (`commentalbum?i_id` ) REFERENCES `commentalbum?i` (`id` ) ON DELETE SET NULL ON UPDATE CASCADE, CONSTRAINT `fk_commentalbum?i_profile?i` FOREIGN KEY (`profile_id` ) REFERENCES `profile` (`id` ) ON DELETE CASCADE ON UPDATE CASCADE, CONSTRAINT `fk_commentalbum?i_commentalbum?i2` FOREIGN KEY (`extension` ) REFERENCES `commentalbum?i` (`id` ) ON DELETE CASCADE ON UPDATE CASCADE) ENGINE = InnoDB;";
      
    # 15. CREATE TABLE Commentspisokalbumphoto
      /** * Count placeholder: 24 */
      static public $createTableCommentspisokalbumphotoN = "CREATE  TABLE IF NOT EXISTS `commentspisokalbumphoto?i` (`id` INT UNSIGNED NOT NULL AUTO_INCREMENT , `datetime` DATETIME NOT NULL , `text` VARCHAR(256) NOT NULL , `countlike` INT UNSIGNED NOT NULL DEFAULT 0 , `spisokalbumphoto?i_id` INT UNSIGNED NOT NULL , `commentspisokalbumphoto?i_id` INT UNSIGNED NULL , `profile_id` INT UNSIGNED NOT NULL , `extension` INT UNSIGNED NULL , PRIMARY KEY (`id`) , INDEX `fk_comspalbpht?i_spalbpht?i1_idx` (`spisokalbumphoto?i_id` ASC) , INDEX `fk_comspalbpht?i_comspalbpht?i1_idx` (`commentspisokalbumphoto?i_id` ASC) , UNIQUE INDEX `id_UNIQUE` (`id` ASC) , INDEX `fk_comspalbpht?i_prf1_idx` (`profile_id` ASC) , INDEX `fk_comspalbpht?i_comspalbpht?i2_idx` (`extension` ASC) , CONSTRAINT `fk_comspalbpht?i_spalbpht?i1` FOREIGN KEY (`spisokalbumphoto?i_id` ) REFERENCES `spisokalbumphoto?i` (`id` ) ON DELETE CASCADE ON UPDATE CASCADE, CONSTRAINT `fk_comspalbpht?i_comspalbpht?i1` FOREIGN KEY (`commentspisokalbumphoto?i_id` ) REFERENCES `commentspisokalbumphoto?i` (`id` ) ON DELETE SET NULL ON UPDATE CASCADE, CONSTRAINT `fk_comspalbpht?i_prf1` FOREIGN KEY (`profile_id` ) REFERENCES `profile` (`id` ) ON DELETE CASCADE ON UPDATE CASCADE, CONSTRAINT `fk_comspalbpht?i_comspalbpht?i2` FOREIGN KEY (`extension` ) REFERENCES `commentspisokalbumphoto?i` (`id` ) ON DELETE CASCADE ON UPDATE CASCADE) ENGINE = InnoDB;";
      
    # 16. CREATE TABLE Likeavatar
      /** * Count placeholder: 11 */
      static public $createTableLikeavatarN = "CREATE  TABLE IF NOT EXISTS `likeavatar?i` (`avatar?i_id` INT UNSIGNED NOT NULL , `profile_id` INT UNSIGNED NOT NULL , INDEX `fk_ctlkava?i_avatar?i1_idx` (`avatar?i_id` ASC) , INDEX `fk_lkava?i_prf1_idx` (`profile_id` ASC) , CONSTRAINT `fk_ctlkava?i_ava?i1` FOREIGN KEY (`avatar?i_id` ) REFERENCES `avatar?i` (`id` ) ON DELETE CASCADE ON UPDATE CASCADE, CONSTRAINT `fk_lkava?i_prf1` FOREIGN KEY (`profile_id` ) REFERENCES `profile` (`id` ) ON DELETE CASCADE ON UPDATE CASCADE) ENGINE = InnoDB;";
      
    # 17. CREATE TABLE Likewall
      /** * Count placeholder: 11 */
      static public $createTableLikewallN = "CREATE  TABLE IF NOT EXISTS `likewall?i` (`wall?i_id` INT UNSIGNED NOT NULL , `profile_id` INT UNSIGNED NOT NULL , INDEX `fk_lkwll?i_wll?i1_idx` (`wall?i_id` ASC) , INDEX `fk_lkwll?i_prf1_idx` (`profile_id` ASC) , CONSTRAINT `fk_lkwll?i_wll?i1` FOREIGN KEY (`wall?i_id` ) REFERENCES `wall?i` (`id` ) ON DELETE CASCADE ON UPDATE CASCADE, CONSTRAINT `fk_lkwll?i_prf1` FOREIGN KEY (`profile_id` ) REFERENCES `profile` (`id` ) ON DELETE CASCADE ON UPDATE CASCADE) ENGINE = InnoDB;";
      
    # 18. CREATE TABLE Likeblog
      /** * Count placeholder: 11 */
      static public $createTableLikeblogN = "CREATE  TABLE IF NOT EXISTS `likeblog?i` (`blog?i_id` INT UNSIGNED NOT NULL , `profile_id` INT UNSIGNED NOT NULL , INDEX `fk_lkblg?i_blg?i1_idx` (`blog?i_id` ASC) , INDEX `fk_lkblg?i_prf1_idx` (`profile_id` ASC) , CONSTRAINT `fk_lkblg?i_blg?i1` FOREIGN KEY (`blog?i_id` ) REFERENCES `blog?i` (`id` ) ON DELETE CASCADE ON UPDATE CASCADE, CONSTRAINT `fk_lkblg?i_prf1` FOREIGN KEY (`profile_id` ) REFERENCES `profile` (`id` ) ON DELETE CASCADE ON UPDATE CASCADE) ENGINE = InnoDB;";
      
    # 19. CREATE TABLE Likealbum
      /** * Count placeholder: 11 */
      static public $createTableLikealbumN = "CREATE  TABLE IF NOT EXISTS `likealbum?i` (`album?i_id` INT UNSIGNED NOT NULL , `profile_id` INT UNSIGNED NOT NULL , INDEX `fk_lkalb?i_alb?i1_idx` (`album?i_id` ASC) , INDEX `fk_lkalb?i_prf1_idx` (`profile_id` ASC) , CONSTRAINT `fk_lkalb?i_alb?i1` FOREIGN KEY (`album?i_id` ) REFERENCES `album?i` (`id` ) ON DELETE CASCADE ON UPDATE CASCADE, CONSTRAINT `fk_lkalb?i_prf1` FOREIGN KEY (`profile_id` ) REFERENCES `profile` (`id` ) ON DELETE CASCADE ON UPDATE CASCADE) ENGINE = InnoDB;";
      
    # 20. CREATE TABLE Likespisokalbumphoto
      /** * Count placeholder: 11 */
      static public $createTableLikespisokalbumphotoN = "CREATE  TABLE IF NOT EXISTS `likespisokalbumphoto?i` (`spisokalbumphoto?i_id` INT UNSIGNED NOT NULL , `profile_id` INT UNSIGNED NOT NULL , INDEX `fk_lkssalbpht?i_spalbpht?i1_idx` (`spisokalbumphoto?i_id` ASC) , INDEX `fk_lkssalbpht?i_prf1_idx` (`profile_id` ASC) , CONSTRAINT `fk_lkssalbpht?i_spalbpht?i1` FOREIGN KEY (`spisokalbumphoto?i_id` ) REFERENCES `spisokalbumphoto?i` (`id` ) ON DELETE CASCADE ON UPDATE CASCADE, CONSTRAINT `fk_lkssalbpht?i_prf1` FOREIGN KEY (`profile_id` ) REFERENCES `profile` (`id` ) ON DELETE CASCADE ON UPDATE CASCADE) ENGINE = InnoDB;";
      
    # 21. CREATE TABLE Likecommentavatar
      /** * Count placeholder: 11 */
      static public $createTableLikecommentavatarN = "CREATE  TABLE IF NOT EXISTS `likecommentavatar?i` (`commentavatar?i_id` INT UNSIGNED NOT NULL , `profile_id` INT UNSIGNED NOT NULL , INDEX `fk_lkcomava?i_comava?i1_idx` (`commentavatar?i_id` ASC) , INDEX `fk_lkcomava?i_prf1_idx` (`profile_id` ASC) , CONSTRAINT `fk_lkcomava?i_comava?i1` FOREIGN KEY (`commentavatar?i_id` ) REFERENCES `commentavatar?i` (`id` ) ON DELETE CASCADE ON UPDATE CASCADE, CONSTRAINT `fk_lkcomava?i_prf1` FOREIGN KEY (`profile_id` ) REFERENCES `profile` (`id` ) ON DELETE CASCADE ON UPDATE CASCADE) ENGINE = InnoDB;";
      
    # 22. CREATE TABLE Likecommentwall
      /** * Count placeholder: 11 */
      static public $createTableLikecommentwallN = "CREATE  TABLE IF NOT EXISTS `likecommentwall?i` (`commentwall?i_id` INT UNSIGNED NOT NULL , `profile_id` INT UNSIGNED NOT NULL , INDEX `fk_lkcomwll?i_comwll?i1_idx` (`commentwall?i_id` ASC) , INDEX `fk_lkcomwll?i_prf1_idx` (`profile_id` ASC) , CONSTRAINT `fk_lkcomwll?i_comwll?i1` FOREIGN KEY (`commentwall?i_id` ) REFERENCES `commentwall?i` (`id` ) ON DELETE CASCADE ON UPDATE CASCADE, CONSTRAINT `fk_lkcomwll?i_prf1` FOREIGN KEY (`profile_id` ) REFERENCES `profile` (`id` ) ON DELETE CASCADE ON UPDATE CASCADE) ENGINE = InnoDB;";
      
    # 23. CREATE TABLE Likecommentblog
      /** * Count placeholder: 11 */
      static public $createTableLikecommentblogN = "CREATE  TABLE IF NOT EXISTS `likecommentblog?i` (`commentblog?i_id` INT UNSIGNED NOT NULL , `profile_id` INT UNSIGNED NOT NULL , INDEX `fk_lkcomblg?i_comblg?i1_idx` (`commentblog?i_id` ASC) , INDEX `fk_lkcomblg?i_prf1_idx` (`profile_id` ASC) , CONSTRAINT `fk_lkcomblg?i_comblg?i1` FOREIGN KEY (`commentblog?i_id` ) REFERENCES `commentblog?i` (`id` ) ON DELETE CASCADE ON UPDATE CASCADE, CONSTRAINT `fk_lkcomblg?i_prf1` FOREIGN KEY (`profile_id` ) REFERENCES `profile` (`id` ) ON DELETE CASCADE ON UPDATE CASCADE) ENGINE = InnoDB;";
      
    # 24. CREATE TABLE Likecommentalbum
      /** * Count placeholder: 11 */
      static public $createTableLikecommentalbumN = "CREATE  TABLE IF NOT EXISTS `likecommentalbum?i` (`commentalbum?i_id` INT UNSIGNED NOT NULL , `profile_id` INT UNSIGNED NOT NULL , INDEX `fk_lkcomalb?i_comalb?i1_idx` (`commentalbum?i_id` ASC) , INDEX `fk_lkcomalb?i_prf1_idx` (`profile_id` ASC) , CONSTRAINT `fk_lkcomalb?i_comalb?i1` FOREIGN KEY (`commentalbum?i_id` ) REFERENCES `commentalbum?i` (`id` ) ON DELETE CASCADE ON UPDATE CASCADE, CONSTRAINT `fk_lkcomalb?i_prf1` FOREIGN KEY (`profile_id` ) REFERENCES `profile` (`id` ) ON DELETE CASCADE ON UPDATE CASCADE) ENGINE = InnoDB;";
      
    # 25. CREATE TABLE Likecommentspisokalbumphoto
      /** * Count placeholder: 11 */
      static public $createTableLikecommentspisokalbumphotoN = "CREATE  TABLE IF NOT EXISTS `likecommentspisokalbumphoto?i` (`commentspisokalbumphoto?i_id` INT UNSIGNED NOT NULL , `profile_id` INT UNSIGNED NOT NULL , INDEX `fk_lkcomspalbpht?i_comspalbpht?i1_idx` (`commentspisokalbumphoto?i_id` ASC) , INDEX `fk_lkcomspalbpht?i_prf1_idx` (`profile_id` ASC) , CONSTRAINT `fk_lkcomspalbpht?i_comspalbpht?i1` FOREIGN KEY (`commentspisokalbumphoto?i_id` ) REFERENCES `commentspisokalbumphoto?i` (`id` ) ON DELETE CASCADE ON UPDATE CASCADE, CONSTRAINT `fk_lkcomspalbpht?i_prf1` FOREIGN KEY (`profile_id` ) REFERENCES `profile` (`id` ) ON DELETE CASCADE ON UPDATE CASCADE) ENGINE = InnoDB;";

    # 27. CREATE TABLE News
      /** * Count placeholder: 6 */
      static public $createTableNewsN = "CREATE  TABLE IF NOT EXISTS `news?i` (`id` INT UNSIGNED NOT NULL AUTO_INCREMENT , `type` INT NOT NULL , `text` VARCHAR(256) NOT NULL , `datetime` DATETIME NOT NULL , `extension` INT UNSIGNED NULL , PRIMARY KEY (`id`) , UNIQUE INDEX `id_UNIQUE` (`id` ASC) , INDEX `fk_nws?i_nws?i1_idx` (`extension` ASC) , CONSTRAINT `fk_nws?i_nws?i1` FOREIGN KEY (`extension` ) REFERENCES `news?i` (`id` ) ON DELETE CASCADE ON UPDATE CASCADE) ENGINE = InnoDB;";
      
    # 28. CREATE TABLE Spisoknewsphoto
      /** * Count placeholder: 17 */
      static public $createTableSpisoknewsphotoN = "CREATE  TABLE IF NOT EXISTS `spisoknewsphoto?i` (`news?i_id` INT UNSIGNED NOT NULL , `spisokalbumphoto?i_id` INT UNSIGNED NOT NULL , INDEX `fk_spnwspht?i_nws?i1_idx` (`news?i_id` ASC) , INDEX `fk_spnwspht?i_spalbpht?i1_idx` (`spisokalbumphoto?i_id` ASC) , CONSTRAINT `fk_spnwspht?i_nws?i1` FOREIGN KEY (`news?i_id` ) REFERENCES `news?i` (`id` ) ON DELETE CASCADE ON UPDATE CASCADE, CONSTRAINT `fk_spnwspht?i_spalbpht?i1` FOREIGN KEY (`spisokalbumphoto?i_id` ) REFERENCES `spisokalbumphoto?i` (`id` ) ON DELETE CASCADE ON UPDATE CASCADE) ENGINE = InnoDB;";
      
    # 29. CREATE TABLE Spisoknewsavatar
      /** * Count placeholder: 17 */
      static public $createTableSpisoknewsavatarN = "CREATE  TABLE IF NOT EXISTS `spisoknewsavatar?i` (`news?i_id` INT UNSIGNED NOT NULL , `avatar?i_id` INT UNSIGNED NOT NULL , INDEX `fk_spnwsava?i_nws?i1_idx` (`news?i_id` ASC) , INDEX `fk_spnwsava?i_ava?i1_idx` (`avatar?i_id` ASC) , CONSTRAINT `fk_spnwsava?i_nws?i1` FOREIGN KEY (`news?i_id` ) REFERENCES `news?i` (`id` ) ON DELETE CASCADE ON UPDATE CASCADE, CONSTRAINT `fk_spnwsava?i_ava?i1` FOREIGN KEY (`avatar?i_id` ) REFERENCES `avatar?i` (`id` ) ON DELETE CASCADE ON UPDATE CASCADE) ENGINE = InnoDB;";
      
    # 30. CREATE TABLE Spisoknewsblog
      /** * Count placeholder: 17 */
      static public $createTableSpisoknewsblogN = "CREATE  TABLE IF NOT EXISTS `spisoknewsblog?i` (`news?i_id` INT UNSIGNED NOT NULL , `blog?i_id` INT UNSIGNED NOT NULL , INDEX `fk_spnwsblg?i_nws?i1_idx` (`news?i_id` ASC) , INDEX `fk_spnwsblg?i_blg?i1_idx` (`blog?i_id` ASC) , CONSTRAINT `fk_spnwsblg?i_nws?i1` FOREIGN KEY (`news?i_id` ) REFERENCES `news?i` (`id` ) ON DELETE CASCADE ON UPDATE CASCADE, CONSTRAINT `fk_spnwsblg?i_blg?i1` FOREIGN KEY (`blog?i_id` ) REFERENCES `blog?i` (`id` ) ON DELETE CASCADE ON UPDATE CASCADE) ENGINE = InnoDB;";
      
    # 31. CREATE TABLE Personalnews
      /** * Count placeholder: 1 */
      static public $createTablePersonalnewsN = "CREATE  TABLE IF NOT EXISTS `personalnews?i` (`id` INT UNSIGNED NOT NULL AUTO_INCREMENT , `type` INT NOT NULL , `author` INT UNSIGNED NOT NULL , `text` VARCHAR(256) NOT NULL , `datetime` DATETIME NOT NULL , `idnewsobject` INT UNSIGNED NOT NULL , PRIMARY KEY (`id`) , UNIQUE INDEX `id_UNIQUE` (`id` ASC) ) ENGINE = InnoDB;";
      
    # 32. CREATE TABLE Organizer
      /** * Count placeholder: 1 */
      static public $createTableOrganizerN = "CREATE  TABLE IF NOT EXISTS `organizer?i` (`id` INT UNSIGNED NOT NULL AUTO_INCREMENT , `text` VARCHAR(256) NOT NULL , `detatimestart` DATETIME NOT NULL , `datetimefinish` DATETIME NULL , `type` INT NOT NULL , PRIMARY KEY (`id`) , UNIQUE INDEX `id_UNIQUE` (`id` ASC) ) ENGINE = InnoDB;";

}
?>