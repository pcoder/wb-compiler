<?php
$request_body = file_get_contents('php://input');
$my_data = json_decode($request_body,true);

$PROJECTS_BASE_PATH="/wisebender/sketches/";
$basepath="/compiler/app/";

$username=$my_data["username"];
$wiselibUUID=$my_data["wiselibUUID"];
$makeTarget=$my_data["build"];

foreach ($my_data['files'] as $key => $value){
        file_put_contents($basepath."app.cpp", html_entity_decode($value['content']), LOCK_EX);
}

chdir( $basepath );

file_put_contents($basepath."Makefile.path" ,
	"export WISELIB_BASE=".$PROJECTS_BASE_PATH.$username."/".$wiselibUUID."/",LOCK_EX);

exec ( "make ".$makeTarget,$retstr,$retval);

$isense_sub_path="out/isense/app.bin";
$size = filesize ( $basepath.$isense_sub_path );

$handle = fopen($basepath.$isense_sub_path, "r");
$contents = fread($handle, filesize($basepath.$isense_sub_path));
fclose($handle);


$response["success"]=$retval===0;
$response["size"]=$size;
$response["message"]=implode("<br/>",$retstr);
//file_put_contents($basepath."app.before",$contents), LOCK_EX);

$tmpfname = tempnam($basepath . "out/isense/tmp/", "app_");

$handle = fopen($basepath. "out/isense/tmp/" . basename($tmpfname), "w");
fwrite($handle, $contents);
fclose($handle);

$response["output"]= basename($tmpfname);

print_r(json_encode($response));

//unlink($basepath."app.cpp");
//unlink($basepath.$isense_sub_path);
//print_r($my_data['files']);

?>
