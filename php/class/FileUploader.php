<?php
/**
 * Класс загрузки фото по местам из временной дериктории
 */
class FileUploader{
	/**
	 * Должен закинуть все файлы по типу загруженых файлов в соответствующие директории
	 * Все destination[category_image] долджны покрывать все tmpImage[category_image]
	 * tmpImage - массив мета о загруженых фалах
	 * 	array(
	 * 		[category_image]=>array(
	 * 			[name]=>"",
	 * 			[type]=>"",
	 * 			[tmp_name]=>"",
	 * 			[error]=>"",
	 * 			[sise]=>"",
	 *  	),
	 * 	)
	 * destination - пути по типам загруженых фалов
	 * 	array(
	 * 		[category_image]=>array(
	 * 			[path]=>""
	 *			[name]=>"new_name"
	 * 		),
	 * 	)
	 */
	static public function FetchImages($tmpImage, $destination)
	{
		$errors = array(); $data = array();
		try {
			foreach($tmpImage as $category_image => $file)
			{
				$cur_category_image = $destination[$category_image];
				if($cur_category_image) {
					// var_dump($destination[$category_image]); echo "<br/><br/>";
					$tmp_name = $file["tmp_name"];

					$type_save = preg_split('/\//', $file["type"]);
					$type_save = $type_save[1];

					$name_save = $cur_category_image['name'];

					// var_dump($tmp_name, $type_save, $name_save); echo "<br/><br/>";

					$pathWithName = $cur_category_image['path'].$name_save.".".$type_save;

					if(is_uploaded_file($tmp_name)){
						if(move_uploaded_file($tmp_name, $pathWithName)){
							// echo "*";
							$data[$category_image] = $cur_category_image;
							$data[$category_image]['pathWithName'] = $pathWithName;
						} else {
							throw new Exception("Файл не удалось переместить из tmp в ".$pathWithName);
						}
					}
					else throw new Exception("Файл не загружен в tmp");
				} else throw new Exception("Мета категории($category_image) в destination отсутствует ");
			}
		} catch (Exception $e) {
			// откатится - удалить все что перенесли и вернуть ошибку
		    foreach($data as $category_image => $file)
			{
				// 1. удалить перенесенный файл
				// rrdir
				ProfDir::rrmdir($file['pathWithName']);
				// 2. удалить из массива перенесенных
				unset($data[$category_image]);
			}
			// echo $e;
			return $data;
		}
		return $data;
	}
	function fetchImagesAvatar($ProfileAuth, $files){
		if(count($files) != 4) return false;

			$name_path = $ProfileAuth->changeAvatar();
			if(count($name_path)!=4) return false;
			// var_dump($name_path);
			// var_dump($files);
			foreach($files as $key => $index_file)
			{
				//var_dump($index_file);
				// $folder =  'pic/';//директория в которую будет загружен файл
				$path = null;
				$name = null;
				switch($key){
					case 'original': 
						$path = $name_path[$key]['path'];
						$name = $name_path[$key]['name'];
						break;
					case 'userpic':
						$path = $name_path[$key]['path'];
						$name = $name_path[$key]['name'];
						break;
					case 'avatar':
						$path = $name_path[$key]['path'];
						$name = $name_path[$key]['name'];
						break;
					case 'aspect':
						$path = $name_path[$key]['path'];
						$name = $name_path[$key]['name'];
						break;
					default:  break;
				}

			}
		return true;
	}
}
?>