DELIMITER $$

USE `db_mediarepo`$$

DROP PROCEDURE IF EXISTS `prc_clone_row`$$

CREATE PROCEDURE `prc_clone_row`(
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
   
END$$

DELIMITER ;