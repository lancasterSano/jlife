<?php
require_once '.htpaths';
require_once(PROJECT_PATH."/php/class/ProfileAuth.php");
require_once(PROJECT_PATH."/settings/settings.php");
require_once(PROJECT_PATH."/php/common.php");
require_once(PROJECT_PATH."/php/class/FileUploader.php");
/**** AuthentificateProfile ***/ require_once PROJECT_PATH."/php/authorization.php";
include    './FileAPI.class.php';

if( !empty($_SERVER['HTTP_ORIGIN']) ){
	// Enable CORS
	header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
	header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
	header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Range, Content-Disposition, Content-Type');
}
if( $_SERVER['REQUEST_METHOD'] == 'OPTIONS' ){ exit; }
if( strtoupper($_SERVER['REQUEST_METHOD']) == 'POST' ){
	// $errors = array(); $data = array();
	$files	= FileAPI::getFiles(); // Retrieve File List
		// // var_dump($files['file']);
		// // var_dump($ProfileAuth->changeAvatar_checkFrontendData($files['file']));
		try {
			$rez = $ProfileAuth->changeAvatar($files['file']);
			print json_encode($rez);
		} catch(Exception $e){

		}

	// $images	= array();
	// // Fetch all image-info from files list
	// // fetchImages($files, $images);
	// // JSONP callback name
	// $jsonp	= isset($_REQUEST['callback']) ? trim($_REQUEST['callback']) : null;
	// // JSON-data for server response
	// $json	= array(
	// 	  'images'	=> $images
	// 	, 'data'	=> array('_REQUEST' => $_REQUEST, '_FILES' => $files)
	// );
	// // Server response: "HTTP/1.1 200 OK"
	// FileAPI::makeResponse(array(
	// 	  'status' => FileAPI::OK
	// 	, 'statusText' => 'OK'
	// 	, 'body' => $json
	// ), $jsonp);
	exit;
}
function fetchImages($files, &$images, $name = 'file'){
	if( isset($files['tmp_name']) ){
		$filename = $files['tmp_name'];
		list($mime)	= explode(';', @mime_content_type($filename));

		// echo "#########################################################";
		// var_dump(explode(';', @mime_content_type($filename))); exit();
		// echo "#########################################################";

		if( strpos($mime, 'image') !== false ){
			$size = getimagesize($filename);
			$base64 = base64_encode(file_get_contents($filename));

			$images[$name] = array(
				  'width'	=> $size[0]
				, 'height'	=> $size[1]
				, 'mime'	=> $mime
				, 'size'	=> filesize($filename)
				, 'dataURL'	=> 'data:'. $mime .';base64,'. $base64
			);
		}
	}
	else {
		foreach( $files as $name => $file ){
			fetchImages($file, $images, $name);
		}
	}
}
?>
