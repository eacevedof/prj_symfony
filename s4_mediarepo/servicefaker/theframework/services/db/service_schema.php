<?php
/**
 * @author Eduardo Acevedo Farje.
 * @link www.eduardoaf.com
 * @name TheFramework\Services\Db\ServiceSchema
 * @file service_schema.php 1.1.0
 * @date 23-06-2019 15:39 SPAIN
 * @observations
 */
namespace TheFramework\Services\Db;

use TheFramework\Behaviours\BehaviourSchema;

class ServiceSchema 
{
    private $oBehav;
    
    public function __construct(BehaviourSchema $oBehav) 
    {
        $this->oBehav = new BehaviourSchema();
        if($oBehav) $this->oBehav = $oBehav;
    }
    
    public function get_tables()
    {
        //$arReturn = [];
        $arReturn = $this->oBehav->get_tables();
        return $arReturn;
    }

    public function get_fields_info($sTable)
    {
        $arReturn = $this->oBehav->get_fields_info($sTable);
        return $arReturn;
    }
        
    public function get_tables_info($sTables="")
    {
        $arTables = [];
        if(!$sTables)
            $arTables = $this->get_tables();
        else 
            $arTables = explode(",",$sTables);
        
        $arReturn = [];
        foreach ($arTables as $sTable)
            $arReturn[$sTable] = $this->get_fields_info($sTable);
        
        return $arReturn;
    }
    
    
}//ServiceSchema
