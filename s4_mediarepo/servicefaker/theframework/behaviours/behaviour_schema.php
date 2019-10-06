<?php
/**
 * @author Eduardo Acevedo Farje.
 * @link www.eduardoaf.com
 * @name TheFramework\Behaviours\BehaviourSchema 
 * @file behaviour_schema.php v2.0.0
 * @date 23-06-2019 15:29 SPAIN
 * @observations
 */
namespace TheFramework\Behaviours;

use TheFramework\Services\Db\ServiceCoreQueries;
use TheFramework\Components\Db\ComponentMysql;

class BehaviourSchema 
{
    private $oDb;
    private $oQServ;
    private $sDbname;
    
    public function __construct($arConfig=[]) 
    {
        $this->sDbname = isset($arConfig["db"])?$arConfig["db"]:"";
        $this->oDb = new ComponentMysql($arConfig);
        $this->oQServ = new ServiceCoreQueries();
    }
    
    public function query($sSQL,$iCol=NULL,$iRow=NULL)
    {
        return $this->oDb->query($sSQL,$iCol,$iRow);
    }
    
    public function get_tables()
    {
        $sSQL = $this->oQServ->get_tables($this->sDbname);
        //bug($sSQL);
        $arRows = $this->query($sSQL,0);
        //bug($arRows);
        return $arRows;
    }
    
    public function get_table($sTable)
    {
        $sSQL = $this->oQServ->get_tables($this->Db,$sTable);
        $arRows = $this->query($sSQL,0);
        return $arRows;        
    }
   
    public function get_fields_info($sTable)
    {
        $sSQL = $this->oQServ->get_fields($this->sDbname,$sTable);
        $arRows = $this->query($sSQL);
        return $arRows;
    }

}//BehaviourSchema
