<?php

/**
 * This is the model class for table "grouppermissions".
 *
 * The followings are the available columns in table 'grouppermissions':
 * @property string $grouprole
 * @property string $descrparticipants
 * @property integer $teacher
 * @property integer $responsible
 * @property integer $ko
 * @property integer $yoda
 * @property integer $director
 * @property integer $learner
 *
 * The followings are the available model relations:
 * @property Splabelanswer[] $splabelanswers
 */
class Grouppermissions extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'grouppermissions';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('teacher, responsible, ko, yoda, director, learner', 'numerical', 'integerOnly'=>true),
			array('descrparticipants', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('grouprole, descrparticipants, teacher, responsible, ko, yoda, director, learner', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'splabelanswers' => array(self::HAS_MANY, 'Splabelanswer', 'grouprole'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'grouprole' => 'уникальное число описсывающее группу',
			'descrparticipants' => 'описание через запятую, кто относится  к этой группе',
			'teacher' => 'Teacher',
			'responsible' => 'Responsible',
			'ko' => 'Ko',
			'yoda' => 'Yoda',
			'director' => 'Director',
			'learner' => 'Learner',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('grouprole',$this->grouprole,true);
		$criteria->compare('descrparticipants',$this->descrparticipants,true);
		$criteria->compare('teacher',$this->teacher);
		$criteria->compare('responsible',$this->responsible);
		$criteria->compare('ko',$this->ko);
		$criteria->compare('yoda',$this->yoda);
		$criteria->compare('director',$this->director);
		$criteria->compare('learner',$this->learner);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * @return CDbConnection the database connection used for this class
	 */
	public function getDbConnection()
	{
		return Yii::app()->dbFAQ;
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Grouppermissions the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
