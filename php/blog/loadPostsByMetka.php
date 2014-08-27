<?php
require_once '.htpaths';
require_once(PROJECT_PATH."/php/class/ProfileAuth.php");
require_once(PROJECT_PATH."/php/common.php");
require_once(PROJECT_PATH."/settings/settings.php");
/**** AuthentificateProfile ***/ require_once PROJECT_PATH."/php/authorization.php";
if(isset($_POST['idMetka']))
{
	$idMetka = $_POST['idMetka'];
}
if(isset($_POST['idOwner']))
{
	$idOwner = $_POST['idOwner'];
}
//echo $idOwner;
//�������� ��� ������
$p = $DB->getRow(QS::$q3, $idOwner);
$ProfileLoad = new Profile($idOwner, $p);

$author = $ProfileLoad->FI();


// ���������, ���� ������ ����� "��� �����", �� ������ ������ ������
if($idMetka == 0)
{	
	/*
	$p = $DB->getAll(QS::$getAllPosts, $idOwner, $idOwner, $idOwner, SETTING_COUNT_FIRST_LOAD_ENTITY, $idOwner, SETTING_COUNT_FIRST_LOAD_ENTITY);
	*/
	$p = $DB->getAll(QS::$getAllPosts, $idOwner, SETTING_COUNT_FIRST_LOAD_ENTITY);
	
	$countblog = $ProfileLoad->countblog;
}
else 
	// ���������, ���� ������ ����� "��� �����", �� ������ ������ ������
	if($idMetka == -1)
	{
		$p = $DB->getAll(QS::$getPostsWithoutMetka, $idOwner, $idOwner, $idOwner, SETTING_COUNT_FIRST_LOAD_ENTITY);
		//print_r($p);
		$i = 0; // ���������� �������� ����������, � ����� ����� ������� ��������� �������� ������
		$count = 0; // ���������� �������� ��� 1 ��������� ��������� �������� "tempExtension" ��������
		$countblog = $DB->getAll(QS::$getCountWithoutMetkaPosts, $idOwner, $idOwner, $idOwner);
		/*
		// ��������� ���� �� �������� ������� �� �����, � ������ ������ �� ����� �������� ����� ������
		foreach($p as $key => $value)
		{
			// ������ ������ �� �������� ������ ������� ������
			$p3 = $DB->getAll(QS::$getDoughterPosts, $idOwner, $value["id"]);

			// ��������� ���� �� �������� �������� ������� ������� ������
			foreach($p3 as $key => $value1)
			{
				// ������� ��� ������������ ������ ��� �������� ��� ���������� "tempExtension"
				if($count == 0)
				{
					$tempExtension = $value["id"];
					$count = 1;
				}
				// ������� ��� ����������� ������ ������ ������ ��� ������� � ������ ��������
				if($tempExtension == $value1["extension"])
					$i++;
				else
				{
					$i+=2;
					$tempExtension = $value1["extension"];
				}

			$temp[] = array("id" => $value1["id"],
							"datetime" => $value1["datetime"],
							"text" => $value1["text"],
							"countlike" => $value1["countlike"],
							"countcomment" => $value1["countcomment"],
							"extension" => $value1["extension"],
							"name" => $value1["name"],
							"source" => $value1["source"],
							"idauthor" => $value1["idauthor"]
							);
			array_splice($p,$i,0,$temp);
			unset($temp);
			}
		}
		*/
	}
else
{	//������ ������ �� ������ ����� �� ��������� ����� (�����, ����, ���������� ������, ���������� ������������, �.�.�.) 
	$p = $DB->getAll(QS::$getPostsByMetka, $idOwner, $idOwner,$idOwner, $idMetka, SETTING_COUNT_FIRST_LOAD_ENTITY);
	//print_r($p);
	$i = 0; // ���������� �������� ����������, � ����� ����� ������� ��������� �������� ������
	$count = 0; // ���������� �������� ��� 1 ��������� ��������� �������� "tempExtension" ��������

	// ���������� ������� � ��������� ������
	$countblog = $DB->getAll(QS::$getCountMetkaPosts, $idOwner, $idOwner, $idOwner, $idMetka);
	//echo $p[0]["id"];
	//print_r($countblog);
	// ��������� ���� �� �������� ������� �� �����, � ������ ������ �� ����� �������� ����� ������
	foreach($p as $key => $value)
	{
		// ������ ������ �� �������� ������ ������� ������
		$p3 = $DB->getAll(QS::$getDoughterPosts, $idOwner, $value["id"]);
		//print_r($p3[0]["id"]);
		/*
		// ��������� ���� �� �������� �������� ������� ������� ������
		foreach($p3 as $key => $value1)
		{
			// ������� ��� ������������ ������ ��� �������� ��� ���������� "tempExtension"
			if($count == 0)
			{
				$tempExtension = $value["id"];
				$count = 1;
			}
			// ������� ��� ����������� ������ ������ ������ ��� ������� � ������ ��������
			if($tempExtension == $value1["extension"])
				$i++;
			else
			{
				$i+=2;
				$tempExtension = $value1["extension"];
			}

		$temp[] = array("id" => $value1["id"],
						"datetime" => $value1["datetime"],
						"text" => $value1["text"],
						"countlike" => $value1["countlike"],
						"countcomment" => $value1["countcomment"],
						"extension" => $value1["extension"],
						"name" => $value1["name"],
						"source" => $value1["source"],
						"idauthor" => $value1["idauthor"]
						);
		array_splice($p,$i,0,$temp);
		unset($temp);

		}
		*/
	}
}
$i = -1; // ���������� ��� ���������� ��������� ������� "$contents"
//��������� ���� �� ���� �������� ������� �����
foreach($p as $key => $value)
{  
   // �������� �� ���������������� ������������ ���� �� �������� ������
   $like = $DB->getOne(QS::$getLikeBlog, $ProfileLoad->ID, $ProfileLoad->ID, $ProfileAuth->ID, $ProfileLoad->ID, $value["id"]);

   // ������ ������ �� ����� � ������ (�������� �����, ���� � �������������)
   $p2 = $DB->getAll(QS::$getMetkas, $idOwner, $idOwner, $value["id"]);
   // ��������� ���� �� �������� ������
	foreach($p2 as $key => $value1)
   {
		// ��������� ������ ��������� ����� � ������ $oneMetka
		$oneMetka = array(
			"idblogmetka" => $value1["idblogmetka"],
			"name" => $value1["name"],
			"color" => $value1["color"]
		);
	// �������� ������ $oneMetka � ������ $spisokMetok
	$spisokMetok[$oneMetka["idblogmetka"]] = $oneMetka;
	}
	// ��������� � ���������� $link �������� "source" (�������� ���������� � ������)
	$link = $value["source"];
	// ���������, ���� ����� ������ ���������� $link ������, ��� 23 ��������...
	if(mb_strlen($link) > 23)
		// ����� ��������������� �������� ���������� $link, ���������� ������ �� 23 �������� �� ������ � ���������� "..."
		$link = mb_substr($link, 0, 23)."...";
	// ����������� ������� $content ��������, ������� �� �������� �� ������� � ������� ����� (�����, ����, ���������� ������, �.�.�.) 
	$content = array(
				   "id" => $value["id"],
				   "text" => $value["text"],
				   "datetime" => $value["datetime"],
				   "idauthor" => $value["idauthor"],
				   "countlike" => $value["countlike"],
				   "countcomment" => $value["countcomment"],
				   "isProfileAuthSetLike" => $like != null ? ($like["count"]>0 ? true:false) : null,
				   "extension" => $value["extension"],
				   "name" => $value["name"],
				   "source" => $value["source"],
				   "nameLink" => $link,
					// ����������� ������� $content ������ $spisokMetok
				   "metkas" => $spisokMetok
   );

	if($content["extension"] == null)
			{                                              
				//$contents[$value["id"]] = $content;
				$i++;
				$contents[$i] = $content;
				$root_content = $content;
				//$last_add_content = $content;           
			}
	else if($content["extension"] != null)
			{
				
				//if($content["extention"] == $contents[$root_content["id"]]["id"])
				$contents[$i]["text"] .= $content["text"];
			}
   // �������� ������ $spisokMetok
   unset($spisokMetok);
   // ����������� ������� $contents ������ $content
   			//$contents[$value["id"]] = $content;
   // �������� ������ $content
   unset($content);
}
	
	//if($contents != null)
		//krsort($contents);
	//print_r($contents);
	//$countblog = $ProfileLoad->countblog;
	$response = array("contents" => $contents, "author" => $author, "countblog" => $countblog);

	//print_r($contents);
	//print_r($response);
	print json_encode($response);
?>