<?php
include_once("theframework/behaviours/autoload.php");
include_once("theframework/components/autoload.php");
include_once("theframework/services/autoload.php");
        
use \TheFramework\Services\Data\ServiceFaker;

class Index
{
    public function run()
    {
        $arConfig["db"] = "db_mediarepo";
        $arConfig["server"] = "127.0.0.1";
        $arConfig["user"] = "root";
        $arConfig["password"] = "";
        $oFaker = new ServiceFaker($arConfig);
        $oFaker->add_config([
            "table"     => "app_media",         //tabla
            "truncate"  => 1,                   //truncar antes de crear
            "items"     => 10,                  //los que se crearán
            "exclude"   => ["id"],              //campos a ignorar
            "constant"  => [["field"=>"","value"=>""]],
            "as"        => [                    //change type
                ["field"=>"insert_date","value"=>"date"],
                ["field"=>"insert_user","value"=>"int"],
                ["field"=>"insert_platform","value"=>"int"],
                ["field"=>"update_date","value"=>"date"],
                ["field"=>"update_user","value"=>"int"],
                ["field"=>"update_platform","value"=>"int"],
                ["field"=>"delete_date","value"=>"date"],
                ["field"=>"delete_user","value"=>"int"],
                ["field"=>"delete_platform","value"=>"int"],
            ]
        ]);
        $oFaker->run();
    }//run
    
}//Index

(new Index)->run();