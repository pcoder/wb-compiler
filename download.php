<?php
$request_body = file_get_contents('php://input');
$my_data = json_decode($request_body,true);


$PROJECTS_BASE_PATH="/wisebender/sketches/";
$basepath="/compiler/app/";
$isense_sub_path="out/isense/";

$fname=$my_data["fname"];
$pname=$my_data["pname"];

if(trim($pname) == "")
{
	$pname="app";
}

$ret = array();
$file = $basepath . $isense_sub_path . $fname; 
if(file_exists($file )){


    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename='. $pname . '.bin');
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file));
    ob_clean();
    flush();
    echo utf8_encode(file_get_contents($file));
    //readfile($file);
	//unlink($file);
    exit;	
}else{
	$ret['success'] = false;
	$ret['message'] = "File not found";
	print_r(json_encode($ret));
}
?>
