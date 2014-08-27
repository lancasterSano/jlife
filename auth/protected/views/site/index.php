<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;
// echo '<table border="1" width="100%"><tr><td>';
// 	echo "<br>YII_DEBUG:".YII_DEBUG."<br/>"; echo '</td><td>'; echo Yii::powered(); echo '</td><td>';
// 	//показываем суммарные данные по использованию ресурсов
// 	$memory = round(Yii::getLogger()->memoryUsage/1024/1024, 3); $time = round(Yii::getLogger()->executionTime, 3);
// 	echo '<br />Использовано памяти: '.$memory.' МБ<br />'; echo '</td><td>'; echo 'Время выполнения: '.$time.' с';
// echo '</td></tr><tr><td colspan="4">';

// echo 'PARAM Yii::SESSION_JLIN: '.Yii::app()->params['SESSION_JLIN'];
// echo '<br/> PARAM Yii::SESSION_IDPROFILE: '.Yii::app()->params['SESSION_IDPROFILE'];

// echo "<br/>Guest: ".(Yii::app()->user->isGuest?'true':'false');
// echo "<br/>Yii User: ".(Yii::app()->user->getName());
// echo "<br/>Jlife User: ".('');
// echo "<br/>Jlife SESSION['jlin']: ".(yii::app()->user->getState('jlin'));
// echo "<br/>Jlife COOKIES['jlin']: ".((string)Yii::app()->request->cookies['jlin']);
// echo "<br/>Yii returnUrl: ".(Yii::app()->user->returnUrl);
// echo "<br/>Jlife HTTP_REFERER: ".($_SERVER['HTTP_REFERER']);
// echo "<br/>UserIdentity::canAuthInYii: ".(UserIdentity::canAuthInYii() ? "true": "false");

// echo '</td></tr><tr><td colspan="4">';
// var_dump($_SESSION);
// echo '</td></tr></table>';



// // $hashon = Hashon::model()->findByAttributes(array('hash'=>Yii::app()->request->cookies['jlin']));

// echo "<br/> jlin cookies: <b>".Yii::app()->request->cookies['jlin']."</b><br/>hashon: <b>".$hashon->idprofile."</b>";

// // // // Insert into DB
// // $model = new Profile;
// // $model->firstname =  't1234567';
// // $model->email =  't1234567';
// // $model->login =  't1234567';
// // // $model->save(false);
// // print_r(Profile::model()->findAll());
  // echo '<br/>find page number: '.$this->findPage;
  echo '<br/>controller: '.$this->id;
  echo '<br/>action: '.$this->action->id;
  echo '<br/>pageNumber: '.$this->pageNumber;
  echo '<br/>findPage id _GET:'.$_GET['fPage'];

  echo '<br/><br/> _GET: '; var_dump($_GET);

  echo '<br/><br/><b>school:</b> '.$_GET['school'];
  echo '<br/><b>role:</b> '.$_GET['role'];
  echo '<br/><b>idadress:</b> '.$_GET['adress'];
  echo '<br/><b>learner:</b> '.$_GET['learner'];
  echo '<br/><b>fPage:</b> '.$_GET['fPage'];

  // echo '<br/> '. $this->createUrl('evolution/index',array('id'=>100,'#'=>'title'));


  echo '<br/><br/><b>getUrl:</b> '.Yii::app()->getRequest()->getUrl();
  echo '<br/><b>getHostInfo:</b> '.Yii::app()->getRequest()->getHostInfo();
  echo '<br/><b>getPathInfo:</b> '.Yii::app()->getRequest()->getPathInfo();
  echo '<br/><b>getRequestUri:</b> '.Yii::app()->getRequest()->getRequestUri();
  echo '<br/><b>getQueryString:</b> '.Yii::app()->getRequest()->getQueryString();


  // var_dump($this->action);
?>
<!-- 
<h1>Welcome to <i><?php echo CHtml::encode(Yii::app()->name); ?></i></h1>

<p>Congratulations! You have successfully created your Yii application.</p>

<p>You may change the content of this page by modifying the following two files:</p>
<ul>
	<li>View file: <code><?php echo __FILE__; ?></code></li>
	<li>Layout file: <code><?php echo $this->getLayoutFile('main'); ?></code></li>
</ul>

<p>For more details on how to further develop this application, please read
the <a href="http://www.yiiframework.com/doc/">documentation</a>.
Feel free to ask in the <a href="http://www.yiiframework.com/forum/">forum</a>,
should you have any questions.</p> -->
