<?php

class CHERRYController extends Controller
{
	public $defaultAction = 'pview';
	protected $pageName = 'CHERRY';
	// public function actionIndex()
	// {
	// 	// $this->render('pview');
	// }
	public function actionPview()
	{
		$placeholder = 1100;
		$models = Profile::model()->FindAll('id>:placeholder', array(':placeholder'=>$placeholder));
		$this->render('pview', array('models' => $models));
	}

	public function actionView($id = false)
	{
		// $user = Yii::app()->db->createCommand()
		//     ->select('*')
		//     ->from('profile u')
		//     ->where('id=:id', array(':id'=>1100))
		//     ->queryRow();
		// 	var_dump($user);

		$model = new Profile;
		// // Insert into DB
		// $model = new Profile;
		// $model->firstname =  'UPDATE firstname';
		// $model->save(false);
		
		// SELECT & FIND
			// // Find by PK
			// $a = Profile::model()->findByPk(1100);
			// echo $a->firstname." ".$a->lastname;
			
			// // Find All by PK's
			// $rez = Profile::model()->findAllByPk( array(1100, 1102));
			// foreach ($rez as $key => $a) {
			// 	echo $key.") ".$a->firstname." ".$a->lastname."<br/>";			
			// }

			// // Find first finded
			// $placeholder = 1100;
			// $rez = Profile::model()->find('id>:placeholder', array(':placeholder'=>$placeholder));


			// // Find all by condition with placeholder
			// $placeholder = 1100;
			// $rez = Profile::model()->findAll('id>:placeholder', array(':placeholder'=>$placeholder));


			// // Find findByAttributes
			// // Find findAllByAttributes		
			// $placeholder = 1100;
			// $rez = Profile::model()->findAllByAttributes(array('id' => array(1103, 1102)));
			
			// // findBySql
			// $rez = Profile::model()->findBySql('SELECT id, email FROM profile WHERE id = :id', array(':id' => 1100));
			
			// // findAllBySql
			// $rez = Profile::model()->findAllBySql('SELECT * FROM profile WHERE email LIKE :email', array(':email' => '%user%'));

			// count
			// $rez = Profile::model()->count('email LIKE :email', array(':email' => '%user%'));

			// // countBySql
			// $rez = Profile::model()->countBySql('SELECT count(*) FROM profile WHERE email LIKE :email', array(':email' => '%user%'));

			// exist (1 - true)
			// $rez = Profile::model()->exists('email LIKE :email', array(':email' => '%user%'));
			

		// UPDATE
			// updateByPk (1 - success update)
			// $rez = Profile::model()->updateByPk(array(1100), array('email' => 'new mail 1100'));
			
			// updateAll (1 - success update) not use as this. Use placeholder!
			// $rez = Profile::model()->updateAll(array('email' => 'new mail 1100'), 'email = "new mail 1100 updated"');

		// DELETE (1 - success delete)
			// $rez = Profile::model()->deleteByPk(2);
			// $rez = Profile::model()->deleteAll('email LIKE :email', array(':email' => '%EMAIL_FIND_TMPL%'));
		
		// CDbCriteria ( http://www.yiiframework.com/doc/api/1.1/CDbCriteria )
		// $criteria = new CDbCriteria;
		// $criteria->condition = 'id = :id';
		// $criteria->params = array(':id'=>1100);
		// $criteria->limit = '10';
		// $rez = Profile::model()->findAll($criteria);

		// var_dump($rez);
		// $placeholder = 1100; 
		// $rez = Profile::model()->findAll('id>:placeholder', array(':placeholder'=>$placeholder));



		// echo $rez;
		// echo $rez->id." ".$rez->email;
		// foreach ($rez as $key => $a) {
		// 	echo $key.") ".$a->firstname." ".$a->lastname."<br/>";
		// }
		// echo $rez->firstname." ".$rez->lastname;
		// echo '<br/>done....!';
		
		// $id = $_GET['id'];

		$rez = Profile::model()->findByPk ($id);

		// render('"name" view of this controller', '"data" - params', "if true - view returned and not show")
		$this->render('view', array('rez'=>$rez));
	}

	// Uncomment the following methods and override them if needed
	/*
	public function filters()
	{
		// return the filter configuration for this controller, e.g.:
		return array(
			'inlineFilterName',
			array(
				'class'=>'path.to.FilterClass',
				'propertyName'=>'propertyValue',
			),
		);
	}

	public function actions()
	{
		// return external action classes, e.g.:
		return array(
			'action1'=>'path.to.ActionClass',
			'action2'=>array(
				'class'=>'path.to.AnotherActionClass',
				'propertyName'=>'propertyValue',
			),
		);
	}
	*/
}