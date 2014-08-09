<?
define("NOT_CHECK_PERMISSIONS", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
$arData=json_decode($_REQUEST['payload'], true);

$rsUser=CUser::GetList(($by="ID"), ($order="DESC"), array("UF_BITBUCKET_ID"=>$arData["user"]));
$arUser=$rsUser->Fetch();
if(!$arUser)
return;

$GLOBALS["USER"]->Authorize($arUser["ID"]);

$TASK_ID=false;

/*
if(preg_match("/task([0-9]+)/i", $arData["repository"]["name"], $r))
	$TASK_ID=$r[1];
elseif(preg_match("/task([0-9]+)/i", $arData["repository"]["description"], $r))
	$TASK_ID=$r[1];
elseif(preg_match("/task([0-9]+)/i", $arData["ref"], $r))
	$TASK_ID=$r[1];
*/    
    
CModule::IncludeModule("tasks");
CModule::IncludeModule("forum");

//Commit URL
$commit_url = $arData["canon_url"] . $arData["repository"]["absolute_url"]."commits/";

foreach($arData["commits"] as $arCommit)
{
    $message=$arCommit["message"];
//    $message=utf8win1251($message);
    if(preg_match("/task([0-9]+)/i", $message, $r)) $TASK_ID=$r[1];
    if(!$TASK_ID) continue;
    $message=str_replace($r[0], "", $message);
    if(is_array($arCommit["files"]) && count($arCommit["files"])>0){
        $files_html = "";
        foreach($arCommit["files"] as $arFile){
            $files_html .= "File: <b>" . $arFile["file"] . "</b> Status: " . $arFile["type"] . "\n";
        }
    }
    $rsTask=CTasks::GetList(array(), array("ID"=>$TASK_ID));
    $arTask=$rsTask->Fetch();
    if(!$arTask) continue;

    CTaskComments::add(
        $arTask["ID"], 
        $arUser["ID"],
        "<b>Commit:</b> <a href=" . $commit_url . $arCommit["raw_node"] . ">" .substr($arCommit["raw_node"], 0, 7) . "</a>\n
        Branch: " . $arCommit["branch"] . "\n"
        .$message. "\n".
        "Affected files:\n".
        $files_html . "\n"
    );
}
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");
?>