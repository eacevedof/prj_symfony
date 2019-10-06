/*
SQLyog Ultimate v9.02 
MySQL - 5.5.5-10.1.35-MariaDB : Database - db_mediarepo
*********************************************************************
*/


/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`db_mediarepo` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `db_mediarepo`;

/*Table structure for table `app_media` */

DROP TABLE IF EXISTS `app_media`;

CREATE TABLE `app_media` (
  `processflag` varchar(5) DEFAULT NULL,
  `insert_platform` varchar(3) DEFAULT '1',
  `insert_user` varchar(15) DEFAULT NULL,
  `insert_date` varchar(14) DEFAULT NULL,
  `update_platform` varchar(3) DEFAULT NULL,
  `update_user` varchar(15) DEFAULT NULL,
  `update_date` varchar(14) DEFAULT NULL,
  `delete_platform` varchar(3) DEFAULT NULL,
  `delete_user` varchar(15) DEFAULT NULL,
  `delete_date` varchar(14) DEFAULT NULL,
  `cru_csvnote` varchar(500) DEFAULT NULL,
  `is_erpsent` varchar(3) DEFAULT '0',
  `is_enabled` varchar(3) DEFAULT '1',
  `i` int(11) DEFAULT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code_erp` varchar(25) DEFAULT NULL,
  `description` varchar(200) DEFAULT NULL,
  `id_type` int(11) DEFAULT NULL COMMENT 'media_array.type=1 video,image,audio',
  `id_type_entity` int(11) DEFAULT NULL COMMENT 'media_array.type=2 module, default generic',
  `id_entity` int(11) DEFAULT NULL COMMENT 'default Null n/a',
  `id_owner_entity` int(11) DEFAULT NULL COMMENT 'media_array.type=3 site owner of the image',
  `id_owner` int(11) DEFAULT NULL COMMENT 'table_user.id',
  `id_group` int(11) DEFAULT NULL COMMENT 'media_array.type=4 for subcategories, uses is_bydefault',
  `uri_local` varchar(250) DEFAULT NULL COMMENT 'something like <cdn-folder>/media/yyyymmdd/<id_type>',
  `uri_public` varchar(250) DEFAULT NULL COMMENT '<cdn-domain>/yyyymmdd/type/filename',
  `filename` varchar(250) DEFAULT NULL COMMENT 'final name of the uploaded file with extension',
  `name` varchar(250) DEFAULT NULL COMMENT 'just the name',
  `shortname` varchar(125) DEFAULT NULL,
  `extension` varchar(5) DEFAULT NULL,
  `create_date` char(14) DEFAULT NULL COMMENT 'metadata',
  `modify_date` char(14) DEFAULT NULL COMMENT 'metadata',
  `size` int(11) DEFAULT NULL COMMENT 'metadata',
  `width` int(11) DEFAULT NULL COMMENT 'metadata',
  `height` int(11) DEFAULT NULL COMMENT 'metadata',
  `resolution` int(11) DEFAULT NULL COMMENT 'metadata',
  `source` varchar(250) DEFAULT NULL COMMENT 'where is it purchased',
  `source_filename` varchar(250) DEFAULT NULL COMMENT 'original name',
  `folder` varchar(50) DEFAULT NULL COMMENT '<yyyymmdd>/type/',
  `parent_path` varchar(250) DEFAULT NULL,
  `information` varchar(250) DEFAULT NULL COMMENT 'why is it stored',
  `information_extra` varchar(250) DEFAULT NULL COMMENT 'public user info',
  `rating` int(11) DEFAULT NULL,
  `media_title` varchar(100) DEFAULT NULL COMMENT 'html title',
  `anchor_text` varchar(100) DEFAULT NULL,
  `id_thumb_1` int(11) DEFAULT NULL COMMENT 'thumbs are created and modified and are added here dus app_media.id',
  `id_thumb_2` int(11) DEFAULT NULL COMMENT 'thumb size 2',
  `id_thumb_3` int(11) DEFAULT NULL COMMENT 'thumb size 3',
  `id_thumb_4` int(11) DEFAULT NULL COMMENT 'thumb size 4',
  `id_thumb_5` int(11) DEFAULT NULL COMMENT 'thumb size 5',
  `csv_tags` varchar(200) DEFAULT NULL COMMENT 'text tags',
  `is_show` tinyint(4) DEFAULT '1' COMMENT 'si se permite mostrar',
  `is_bydefault` tinyint(4) DEFAULT '0' COMMENT 'en un grupo si se la de por defecto',
  `is_public` tinyint(4) DEFAULT '1',
  `is_file` tinyint(4) DEFAULT '1',
  `is_error` tinyint(4) DEFAULT '0',
  `is_repeated` tinyint(4) DEFAULT '0',
  `is_csrf` tinyint(4) DEFAULT '1',
  `order_by` int(5) NOT NULL DEFAULT '100',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `app_media` */

/*Table structure for table `app_media_array` */

DROP TABLE IF EXISTS `app_media_array`;

CREATE TABLE `app_media_array` (
  `processflag` varchar(5) DEFAULT NULL,
  `insert_platform` varchar(3) DEFAULT '1',
  `insert_user` varchar(15) DEFAULT NULL,
  `insert_date` varchar(14) DEFAULT NULL,
  `update_platform` varchar(3) DEFAULT NULL,
  `update_user` varchar(15) DEFAULT NULL,
  `update_date` varchar(14) DEFAULT NULL,
  `delete_platform` varchar(3) DEFAULT NULL,
  `delete_user` varchar(15) DEFAULT NULL,
  `delete_date` varchar(14) DEFAULT NULL,
  `cru_csvnote` varchar(500) DEFAULT NULL,
  `is_erpsent` varchar(3) DEFAULT '0',
  `is_enabled` varchar(3) DEFAULT '1',
  `i` int(11) DEFAULT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code_erp` varchar(25) DEFAULT NULL,
  `type` varchar(15) DEFAULT NULL,
  `id_tosave` varchar(25) DEFAULT NULL,
  `description` varchar(250) DEFAULT NULL,
  `order_by` int(5) NOT NULL DEFAULT '100',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

/*Data for the table `app_media_array` */

insert  into `app_media_array`(`processflag`,`insert_platform`,`insert_user`,`insert_date`,`update_platform`,`update_user`,`update_date`,`delete_platform`,`delete_user`,`delete_date`,`cru_csvnote`,`is_erpsent`,`is_enabled`,`i`,`id`,`code_erp`,`type`,`id_tosave`,`description`,`order_by`) values (NULL,'1',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'0','1',NULL,1,NULL,'1',NULL,'picture',100),(NULL,'1',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'0','1',NULL,2,NULL,'1',NULL,'video',100),(NULL,'1',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'0','1',NULL,3,NULL,'1',NULL,'soundtrack',100),(NULL,'1',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'0','1',NULL,4,NULL,'2',NULL,'mod-generic',100),(NULL,'1',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'0','1',NULL,5,NULL,'3',NULL,'site-generic',100),(NULL,'1',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'0','1',NULL,6,NULL,'4',NULL,'default by subgroup',100);

/*Table structure for table `app_media_tags` */

DROP TABLE IF EXISTS `app_media_tags`;

CREATE TABLE `app_media_tags` (
  `processflag` varchar(5) DEFAULT NULL,
  `insert_platform` varchar(3) DEFAULT NULL,
  `insert_user` varchar(15) DEFAULT NULL,
  `insert_date` varchar(14) DEFAULT NULL,
  `update_platform` varchar(3) DEFAULT NULL,
  `update_user` varchar(15) DEFAULT NULL,
  `update_date` varchar(14) DEFAULT NULL,
  `delete_platform` varchar(3) DEFAULT NULL,
  `delete_user` varchar(15) DEFAULT NULL,
  `delete_date` varchar(14) DEFAULT NULL,
  `cru_csvnote` varchar(500) DEFAULT NULL,
  `is_erpsent` varchar(3) DEFAULT '0',
  `is_enabled` varchar(3) DEFAULT '1',
  `i` int(11) DEFAULT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_type` int(11) DEFAULT NULL,
  `description` varchar(50) DEFAULT NULL,
  `slug` varchar(100) DEFAULT NULL COMMENT 'la descripcion en slug',
  `order_by` int(5) NOT NULL DEFAULT '100',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `app_media_tags` */

/*Table structure for table `app_search` */

DROP TABLE IF EXISTS `app_search`;

CREATE TABLE `app_search` (
  `processflag` varchar(5) DEFAULT NULL COMMENT 'por si hay que exportar',
  `insert_platform` varchar(3) DEFAULT '1',
  `insert_user` varchar(15) DEFAULT NULL,
  `insert_date` varchar(14) DEFAULT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `remote_ip` varchar(50) DEFAULT NULL,
  `url` varchar(250) DEFAULT NULL,
  `value` varchar(250) DEFAULT NULL,
  `iresults` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `app_search` */

/*Table structure for table `app_tag` */

DROP TABLE IF EXISTS `app_tag`;

CREATE TABLE `app_tag` (
  `processflag` varchar(5) DEFAULT NULL,
  `insert_platform` varchar(3) DEFAULT NULL,
  `insert_user` varchar(15) DEFAULT NULL,
  `insert_date` varchar(14) DEFAULT NULL,
  `update_platform` varchar(3) DEFAULT NULL,
  `update_user` varchar(15) DEFAULT NULL,
  `update_date` varchar(14) DEFAULT NULL,
  `delete_platform` varchar(3) DEFAULT NULL,
  `delete_user` varchar(15) DEFAULT NULL,
  `delete_date` varchar(14) DEFAULT NULL,
  `cru_csvnote` varchar(500) DEFAULT NULL,
  `is_erpsent` varchar(3) DEFAULT '0',
  `is_enabled` varchar(3) DEFAULT '1',
  `i` int(11) DEFAULT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_type` int(11) DEFAULT NULL,
  `description` varchar(50) DEFAULT NULL,
  `slug` varchar(100) DEFAULT NULL COMMENT 'la descripcion en slug',
  `order_by` int(5) NOT NULL DEFAULT '100',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `app_tag` */

/*Table structure for table `app_tag_array` */

DROP TABLE IF EXISTS `app_tag_array`;

CREATE TABLE `app_tag_array` (
  `processflag` varchar(5) DEFAULT NULL,
  `insert_platform` varchar(3) DEFAULT NULL,
  `insert_user` varchar(15) DEFAULT NULL,
  `insert_date` varchar(14) DEFAULT NULL,
  `update_platform` varchar(3) DEFAULT NULL,
  `update_user` varchar(15) DEFAULT NULL,
  `update_date` varchar(14) DEFAULT NULL,
  `delete_platform` varchar(3) DEFAULT NULL,
  `delete_user` varchar(15) DEFAULT NULL,
  `delete_date` varchar(14) DEFAULT NULL,
  `cru_csvnote` varchar(500) DEFAULT NULL,
  `is_erpsent` varchar(3) DEFAULT '0',
  `is_enabled` varchar(3) DEFAULT '1',
  `i` int(11) DEFAULT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code_erp` varchar(25) DEFAULT NULL,
  `type` varchar(15) DEFAULT NULL,
  `id_tosave` varchar(25) DEFAULT NULL,
  `description` varchar(250) DEFAULT NULL,
  `order_by` int(5) NOT NULL DEFAULT '100',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `app_tag_array` */

/*Table structure for table `app_tag_array_lang` */

DROP TABLE IF EXISTS `app_tag_array_lang`;

CREATE TABLE `app_tag_array_lang` (
  `processflag` varchar(5) DEFAULT NULL,
  `insert_platform` varchar(3) DEFAULT NULL,
  `insert_user` varchar(15) DEFAULT NULL,
  `insert_date` varchar(14) DEFAULT NULL,
  `update_platform` varchar(3) DEFAULT NULL,
  `update_user` varchar(15) DEFAULT NULL,
  `update_date` varchar(14) DEFAULT NULL,
  `delete_platform` varchar(3) DEFAULT NULL,
  `delete_user` varchar(15) DEFAULT NULL,
  `delete_date` varchar(14) DEFAULT NULL,
  `cru_csvnote` varchar(500) DEFAULT NULL,
  `is_erpsent` varchar(3) DEFAULT NULL,
  `is_enabled` varchar(3) DEFAULT NULL,
  `i` int(11) DEFAULT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_source` int(11) DEFAULT NULL,
  `id_language` int(11) DEFAULT NULL,
  `description` varchar(250) DEFAULT NULL,
  `order_by` int(5) NOT NULL DEFAULT '100',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `app_tag_array_lang` */

/*Table structure for table `app_tag_lang` */

DROP TABLE IF EXISTS `app_tag_lang`;

CREATE TABLE `app_tag_lang` (
  `processflag` varchar(5) DEFAULT NULL,
  `insert_platform` varchar(3) DEFAULT NULL,
  `insert_user` varchar(15) DEFAULT NULL,
  `insert_date` varchar(14) DEFAULT NULL,
  `update_platform` varchar(3) DEFAULT NULL,
  `update_user` varchar(15) DEFAULT NULL,
  `update_date` varchar(14) DEFAULT NULL,
  `delete_platform` varchar(3) DEFAULT NULL,
  `delete_user` varchar(15) DEFAULT NULL,
  `delete_date` varchar(14) DEFAULT NULL,
  `cru_csvnote` varchar(500) DEFAULT NULL,
  `is_erpsent` varchar(3) DEFAULT NULL,
  `is_enabled` varchar(3) DEFAULT NULL,
  `i` int(11) DEFAULT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_source` int(11) DEFAULT NULL,
  `id_language` int(11) DEFAULT NULL,
  `description` varchar(50) DEFAULT NULL,
  `slug` varchar(100) DEFAULT NULL,
  `order_by` int(5) NOT NULL DEFAULT '100',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `app_tag_lang` */

/*Table structure for table `base_user` */

DROP TABLE IF EXISTS `base_user`;

CREATE TABLE `base_user` (
  `processflag` varchar(5) DEFAULT NULL,
  `insert_platform` varchar(3) DEFAULT '1',
  `insert_user` varchar(15) DEFAULT NULL,
  `insert_date` varchar(14) DEFAULT NULL,
  `update_platform` varchar(3) DEFAULT NULL,
  `update_user` varchar(15) DEFAULT NULL,
  `update_date` varchar(14) DEFAULT NULL,
  `delete_platform` varchar(3) DEFAULT NULL,
  `delete_user` varchar(15) DEFAULT NULL,
  `delete_date` varchar(14) DEFAULT NULL,
  `cru_csvnote` varchar(500) DEFAULT NULL,
  `is_erpsent` varchar(3) DEFAULT '0',
  `is_enabled` varchar(3) DEFAULT '1',
  `i` int(11) DEFAULT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code_erp` varchar(25) DEFAULT NULL,
  `description` varchar(250) DEFAULT NULL,
  `id_country` int(11) DEFAULT NULL COMMENT 'app_array.type=country',
  `id_language` int(11) DEFAULT NULL COMMENT 'su idioma de preferencia',
  `path_picture` varchar(100) DEFAULT NULL,
  `id_profile` int(11) DEFAULT NULL COMMENT 'app_array.type=profile: user,maintenaince,system',
  `tokenreset` varchar(250) DEFAULT NULL,
  `log_attempts` int(5) DEFAULT '0',
  `rating` int(11) DEFAULT NULL COMMENT 'la puntuacion',
  `date_validated` varchar(14) DEFAULT NULL COMMENT 'cuando valido su cuenta por email',
  `code_cache` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `base_user` */

/*Table structure for table `base_user_array` */

DROP TABLE IF EXISTS `base_user_array`;

CREATE TABLE `base_user_array` (
  `processflag` varchar(5) DEFAULT NULL,
  `insert_platform` varchar(3) DEFAULT '1',
  `insert_user` varchar(15) DEFAULT NULL,
  `insert_date` varchar(14) DEFAULT NULL,
  `update_platform` varchar(3) DEFAULT NULL,
  `update_user` varchar(15) DEFAULT NULL,
  `update_date` varchar(14) DEFAULT NULL,
  `delete_platform` varchar(3) DEFAULT NULL,
  `delete_user` varchar(15) DEFAULT NULL,
  `delete_date` varchar(14) DEFAULT NULL,
  `cru_csvnote` varchar(500) DEFAULT NULL,
  `is_erpsent` varchar(3) DEFAULT '0',
  `is_enabled` varchar(3) DEFAULT '1',
  `i` int(11) DEFAULT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code_erp` varchar(25) DEFAULT NULL,
  `type` varchar(15) DEFAULT NULL,
  `id_tosave` varchar(25) DEFAULT NULL,
  `description` varchar(250) DEFAULT NULL,
  `order_by` int(5) NOT NULL DEFAULT '100',
  `code_cache` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `base_user_array` */

/* Procedure structure for procedure `prc_get_version` */

/*!50003 DROP PROCEDURE IF EXISTS  `prc_get_version` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `prc_get_version`()
BEGIN
    SET @sDB := (SELECT DATABASE());
    SET @iTables :=(
        SELECT COUNT(*)
        FROM information_schema.TABLES
        WHERE (TABLE_SCHEMA = @sDB) 
        AND (TABLE_NAME = 'version_db')
    );
    IF (@iTables=1) THEN
        SELECT * FROM version_db ORDER BY id DESC LIMIT 1;
    ELSEIF (@iTables=0) THEN
        SELECT 'no version table' AS ver_schema;
    END IF;
END */$$
DELIMITER ;

/* Procedure structure for procedure `prc_clone_row` */

/*!50003 DROP PROCEDURE IF EXISTS  `prc_clone_row` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `prc_clone_row`(
    sTableName VARCHAR(25)
    ,sId VARCHAR(5)
    )
BEGIN
    SET @sSQL := CONCAT('SELECT (MAX(id)+1) AS idnew FROM ',sTableName,' INTO @sIdNew');
    PREPARE sExecute FROM @sSQL;
    EXECUTE sExecute;
    IF (@sIdNew IS NOT NULL) THEN
        SET @sSQL := CONCAT('CREATE TEMPORARY TABLE tempo_table SELECT * FROM ',sTableName,' WHERE id = ',sId,'; ');
        PREPARE sExecute FROM @sSQL;
        EXECUTE sExecute; 
           
        SET @sSQL := CONCAT('UPDATE tempo_table SET id=',@sIdNew,' WHERE id=',sId,'; ');
        PREPARE sExecute FROM @sSQL;
        EXECUTE sExecute;        
        
        SET @sSQL := CONCAT('INSERT INTO ',sTableName,' SELECT * FROM tempo_table WHERE id=',@sIdNew,'; ');
        PREPARE sExecute FROM @sSQL;
        EXECUTE sExecute; 
        SET @sSQL := CONCAT('SELECT * FROM ',sTableName,' ORDER BY id DESC;');
        PREPARE sExecute FROM @sSQL;
        EXECUTE sExecute;   
    ELSE
        SELECT CONCAT('TABLE ',sTableName,' IS EMPTY!!!') AS msg;
    END IF;
   
END */$$
DELIMITER ;

/* Procedure structure for procedure `prc_table` */

/*!50003 DROP PROCEDURE IF EXISTS  `prc_table` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`root`@`localhost` PROCEDURE `prc_table`(
    sTableName VARCHAR(25)
    ,sFieldName VARCHAR(50)
    )
BEGIN
    SET @sDB := (SELECT DATABASE());
    SET @sSQL = '
    SELECT table_name AS tablename 
    ,LOWER(column_name) AS fieldname 
    ,CASE COALESCE(pks.cn,\'\')
        WHEN \'\' THEN \'\'
        ELSE \'Y\'
    END AS ispk
    ,LOWER(DATA_TYPE) AS fieldtype
    ,CASE LOWER(DATA_TYPE) 
        WHEN \'datetime\' THEN 19 
        ELSE character_maximum_length 
    END AS fieldlen
    -- ,\'\' AS selectall
    FROM information_schema.columns
    LEFT JOIN
    (
        SELECT DISTINCT table_name AS tn,column_name AS cn
        FROM information_schema.key_column_usage
        WHERE table_schema = schema()   -- only look in the current db
        AND constraint_name = \'PRIMARY\' -- always PRIMARY for PRIMARY KEY constraints
    ) AS pks
    ON pks.tn = table_name AND pks.cn=column_name 
    WHERE 1=1 ';
    -- incluyo la bd
    SET @sSQL := CONCAT(@sSQL,'AND table_schema=\'',@sDB,'\''); 
    -- tabla
    IF(sTableName IS NOT NULL AND sTableName!='')THEN
        SET @sSQL := CONCAT(@sSQL,'AND table_name LIKE \'%',sTableName,'%\' ');    
    END IF;
    IF(sFieldName IS NOT NULL AND sFieldName!='')THEN
        SET @sSQL := CONCAT(@sSQL,'AND LOWER(column_name) LIKE \'%',sFieldName,'%\' ');    
    END IF;
    SET @sSQL := CONCAT(@sSQL,'ORDER BY tablename,ORDINAL_POSITION, fieldname ASC ');
    PREPARE sExecute FROM @sSQL;
    EXECUTE sExecute;
END */$$
DELIMITER ;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
