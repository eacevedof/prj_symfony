DELIMITER $$

USE `db_mediarepo`$$

DROP PROCEDURE IF EXISTS `prc_table`$$

CREATE PROCEDURE `prc_table`(
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
END$$

DELIMITER ;