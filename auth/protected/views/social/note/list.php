<?php
	// echo '<br/>find page number: '.$this->findPage;
	echo '<br/>controller: '.$this->id;
	echo '<br/>action: '.$this->action->id;

	echo '<br/><br/> _GET: ';
	var_dump($_GET);

	echo '<br/><br/>school: '.$_GET['school'];
	echo '<br/>role: '.$_GET['role'];
	echo '<br/>idadress: '.$_GET['idadress'];
	echo '<br/>learner: '.$_GET['learner'];
	echo '<br/>fPage: '.$_GET['fPage'];
	echo '<br/> '. $this->createUrl('evolution/index',array('id'=>100,'#'=>'title'));
?>
<?php
/* @var $this NoteController */

$this->breadcrumbs=array(
	'Note'=>array('/note'),
	'List',
);
?>
<h1><?php echo $this->id . '/' . $this->action->id; ?></h1>

<p>
	You may change the content of this page by modifying
	the file <tt><?php echo __FILE__; ?></tt>.
</p>
