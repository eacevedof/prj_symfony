<?php
/**
 * @author Eduardo Acevedo Farje.
 * @link www.eduardoaf.com
 * @name TheFramework\Services\Data\ServiceFaker 
 * @file service_faker.php 1.0.0
 * @date 23-06-2019 14:46 SPAIN
 * @observations
 */
namespace TheFramework\Services\Data;

use TheFramework\Components\ComponentLog;
use TheFramework\Behaviours\BehaviourSchema;
use TheFramework\Services\Db\ServiceSchema;
use TheFramework\Services\Data\ServiceRandomizer;

class ServiceFaker
{
    private $oLog;
    private $oCrud;
    private $oSrvSchema;
    
    private $arReserved;
    private $arTypes;
    
    private $arConfig;
    
    public function __construct($arDbConfig=[]) 
    {
        $this->oLog = new ComponentLog();
        //$this->oCrud = new ComponentCrud();
        //$oBehavSchema = new BehaviourSchema($arDbConfig);
        $this->oSrvSchema = new ServiceSchema(new BehaviourSchema($arDbConfig));
        $this->load_types();
        $this->load_reserved();
    }//__construct
    
    private function load_types()
    {
        $this->arTypes = [
            "string" => ["varchar","char","text"],
            "int" => ["int","smallint","bigint","tinyint"],
            "float" => ["float","decimal"],
            "date" => ["timestamp","date","datetime"],
            "time" => ["hour","time","minute"]
        ];
    }

    private function load_reserved()
    {
        $this->arReserved = [
            "show","name","order","by","limit","group","count","where","select",
            "from","min","max","avg","inner","join","left","right","outer",
        ];
    }    
    
    private function pr($text,$pre=1)
    {
        if(!is_string($text)) 
            $text = var_export($text,1);        
        if($pre) echo "<pre>";
        echo $text;
        if($pre) echo "\n"; 
        else echo "<br>";
        echo "</pre>";
    }
    
    private function log($mxVar,$sTitle="",$isTime=0){$this->oLog->is_timetitle($isTime);$this->oLog->save($mxVar,$sTitle);}    
    
    public function add_config($arConf=[]){$this->arConfig[] = $arConf;}
    public function set_config($arConf=[]){ $this->arConfig = $arConf;}
    
    private function get_value_by_type($iMaxLen,$sDbType="varchar")
    {
        $oRndm = new ServiceRandomizer;
        
        if(in_array($sDbType,$this->arTypes["string"]))
        {
            return $oRndm->get_substring_len($iMaxLen);
        }
        elseif(in_array($sDbType,$this->arTypes["int"]))
        {
            return $oRndm->get_int();
        }
        elseif(in_array($sDbType,$this->arTypes["date"]))
        {
            return $oRndm->get_date_ymd("");
        }
        elseif(in_array($sDbType,$this->arTypes["float"]))
        {
            return $oRndm->get_float();
        }
        elseif(in_array($sDbType,$this->arTypes["time"]))
        {
            return $oRndm->get_hour("");
        }
        return "";
    }
    
    private function get_inserts($arConf)
    {
        $sTable = isset($arConf["table"])?$arConf["table"]:NULL;
        if(!$sTable) return;
        
        $arExclude = isset($arConf["exclude"])?$arConf["exclude"]:[];        
        $arFields = $this->oSrvSchema->get_fields_info($sTable);

        $oCrud = new \TheFramework\Components\Db\ComponentCrud();
        $oCrud->set_table($sTable);
        $oCrud->set_comment("faker of $sTable");

        foreach($arFields as $i=>$arFieldInfo)
        {
            $sFieldName = $arFieldInfo["field_name"];
            if(in_array($sFieldName,$arExclude)) continue;
            if(in_array($sFieldName,$this->arReserved)) $sFieldName = "`$sFieldName`";

            $sDbType = $arFieldInfo["field_type"];
            $iLen = $arFieldInfo["field_length"];
            $mxValue = $this->get_value_by_type($iLen,$sDbType);
            //$this->log($mxValue,"dbtype:$sDbType,fieldname:$sFieldName,len:$iLen");                
            $oCrud->add_insert_fv($sFieldName, $mxValue);

        }//foreach
        
        $oCrud->autoinsert();
        //$this->log($oCrud->get_sql());
        $sSQL = $oCrud->get_sql();
        return $sSQL;
        
    }//get_insert_table
    
    public function run()
    {
        $this->pr("Service.run");
        
        $arInserts = [];
        foreach($this->arConfig as $arConf)
        {
            $isTrunc = isset($arConf["truncate"])?$arConf["truncate"]:0;
            $iItems = isset($arConf["items"])?$arConf["items"]:1;
            if($isTrunc) $arInserts[] = "TRUNCATE TABLE {$arConf["table"]};";
            
            for($i=0; $i<$iItems; $i++)
            {            
                $sSQL = $this->get_inserts($arConf).";";
                $this->log($sSQL,"",1);
                $arInserts[] = $sSQL;
            }
        }//foreach this->arConfig

    }//run()
    
}//ServiceFaker