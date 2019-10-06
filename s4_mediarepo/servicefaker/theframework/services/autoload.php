<?php
//services autoload
//autoload.php 1.0.0
$sPathRoot = dirname(__FILE__).DIRECTORY_SEPARATOR;
//die("sPathRoot: $sPathRoot");//...tests\vendor\theframework\services
$arSubFolders[] = get_include_path();
$arSubFolders[] = $sPathRoot;//ruta de services
//subcarpetas dentro de services
$arSubFolders[] = $sPathRoot."data";
$arSubFolders[] = $sPathRoot."db";
$arSubFolders = array_unique($arSubFolders);

$sPathInclude = implode(PATH_SEPARATOR,$arSubFolders);
set_include_path($sPathInclude);

spl_autoload_register(function($sNSClassName)
{
    //si existe la palabra TheFramework
    if(strstr($sNSClassName,"TheFramework"))
    {
        $arClass = explode("\\",$sNSClassName);
        $sClassName = end($arClass);
        //https://autohotkey.com/docs/misc/RegEx-QuickRef.htm
        // (?<=...) and (?<!...) are positive and negative look-behinds (respectively) 
        // because they look to the left of the current position rather than the right 
        $sClassName = preg_replace("/(?<!^)([A-Z])/","_\\1",$sClassName);
        //print_r("classname:".$sClassName);die;
        if(strstr($sClassName,"Service"))
        {
            $sClassName = str_replace("Service","",$sClassName);
            $sClassName = strtolower($sClassName);
            //if(strstr($sClassName,"xp"))die($sClassName);
            $sClassName = "service$sClassName.php";
        }    
        else 
            return;
        
        //print_r("\n classname include: $sClassName");
        if(stream_resolve_include_path($sClassName))
            include_once $sClassName;
        
        elseif(function_exists("lg"))
        {
            lg("Class not found: $sClassName");
        }
        else 
        {
            echo "Service Class not found: $sClassName";
        }
    }
});//spl_autoload_register

