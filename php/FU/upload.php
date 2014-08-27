<?php 
 var_dump($_FILES);
// var_dump($_POST['_file']['userpic']);
// var_dump($_SERVER);
// exit();
// if ($_POST) {
//     define('UPLOAD_DIR', 'pic/');
//     $img = $_POST['_file']['userpic'];
//     $img = str_replace('data:image/jpeg;base64,', '', $img);
//     $img = str_replace(' ', '+', $img);
//     $data = base64_decode($img);
//     $file = UPLOAD_DIR . uniqid() . '.jpg';
//     $success = file_put_contents($file, $data);
//     print $success ? $file : 'Unable to save the file.';
// }





// var_dump($_GET);
// exit();
if(isset($_FILES['file'])){
    //Список разрешенных файлов
    //$whitelist = array(".jpg", ".jpeg", ".png",".JPG", ".JPEG", ".PNG");         
	$data = array();
	$error = false;
	
	$countSuccessFile = 4;
	$indexes = array('original' => 'original', 'userpic' => 'userpic', 'avatar' => 'avatar','aspect' => 'aspect');

	foreach ($_FILES['file']['error'] as $key => $value) {
	 	if ($value) { unset($indexes[$key]); $countSuccessFile--; }
	}

	//если нет ошибок, грузим файл
	if($countSuccessFile==4) {
		foreach($indexes as $key => $index_file)
		{
			//var_dump($index_file);
			$folder =  'pic/';//директория в которую будет загружен файл
			switch($index_file){
				case 'original': break;
				case 'userpic': break;
				case 'avatar': break;
				case 'aspect': break;
				default: break;
			}




			$tmp_name = $_FILES['file']["tmp_name"]["$index_file"];
			$type = preg_split('/\//', $_FILES['file']["type"]["$index_file"]);
			// echo $type[1]." ";

	        $name_loaded = basename($_FILES['file']["tmp_name"]["$index_file"],".tmp");        
			if(is_uploaded_file($tmp_name)){		
				if(!move_uploaded_file($tmp_name, "$folder/".$name_loaded."_".$index_file.".".$type[1]))
					{$data['errors'] = "Во время загрузки файла произошла ошибка"; }
			}
			else {$data['errors'] = "Файл не  загружен"; }
		}
    }
    else{$data['errors'] = 'При передаче произошли ошибки загрузки!'; }
    if(isset($data['errors'])) var_dump($data['errors']);
}
else{die("ERROR"); }
?>