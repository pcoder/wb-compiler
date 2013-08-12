<?php
$request_body = file_get_contents('php://input');
$my_data = json_decode($request_body,true);

$PROJECTS_BASE_PATH="/wisebender/sketches/";
$basepath="/compiler/app/";

$username=$my_data["username"];
$wiselibUUID=$my_data["wiselibUUID"];
$makeTarget=trim(strtolower($my_data["build"]));

switch($makeTarget){
	case "isense":
		$makeTarget = "isense";
		break;
	case "isense5148":
		$makeTarget = "isense.5148";
		break;
	case "shawn":
		$makeTarget = "shawn";
		break;
}

foreach ($my_data['files'] as $key => $value){
        file_put_contents($basepath."app.cpp", html_entity_decode($value['content']), LOCK_EX);
}

chdir( $basepath );

if(trim(strtolower($wiselibUUID)) == "default"){
    // compile the app. against the latest Wiselib source
	$PROJECTS_BASE_PATH="/var/www/wisebender/Symfony/wiselib/";
    	file_put_contents($basepath."Makefile.path" ,
	"export WISELIB_BASE=".$PROJECTS_BASE_PATH ,LOCK_EX);
}else{
	file_put_contents($basepath."Makefile.path" ,
	"export WISELIB_BASE=".$PROJECTS_BASE_PATH.$username."/".$wiselibUUID."/",LOCK_EX);
}
exec ( "make ".$makeTarget." 2>&1",$retstr,$retval);

$tmpfname = "";
if($makeTarget == 'shawn'){
	$isense_sub_path="./app";
	$tmpfname = tempnam($basepath . "out/shawn/tmp/", "app_");
}else{
	$isense_sub_path="out/isense/app.bin";
	$tmpfname = tempnam($basepath . "out/isense/tmp/", "app_");
}
	$size = filesize ( $basepath.$isense_sub_path );


copy( $basepath.$isense_sub_path , $tmpfname );

$response["success"]=$retval===0;
$response["size"]=$size;
$response["message"]=implode("<br/>",$retstr);

#if($makeTarget == 'shawn'){
#	$response['message'] = "Not implemented yet. Please try again in some days.";
#	$response["success"] = false;
#}

$response["output"]= basename($tmpfname);
print_r(json_encode($response));
?>
