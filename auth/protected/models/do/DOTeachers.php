<?php

/**
 * This is the model class for table "teachers".
 *
 * The followings are the available columns in table 'teachers':
 * @property string $id
 * @property string $iduser
 * @property string $firstname
 * @property string $lastname
 * @property string $middlename
 * @property string $absentstart
 * @property string $absentfinish
 * @property integer $deleted
 * @property string $schoolS_id
 * @property integer $category
 * @property string $datedeleted
 *
 * The followings are the available model relations:
 * @property Lessons[] $lessons
 * @property Materials[] $materials
 * @property Subgroups[] $subgroups
 * @property Schools $schoolS
 * @property Teachersubjects[] $teachersubjects
 */
class DOTeachers extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'teachers';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('iduser, firstname, lastname, middlename, schoolS_id', 'required'),
			array('deleted, category', 'numerical', 'integerOnly'=>true),
			array('iduser, schoolS_id', 'length', 'max'=>10),
			array('firstname, lastname, middlename', 'length', 'max'=>256),
			array('absentstart, absentfinish, datedeleted', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, iduser, firstname, lastname, middlename, absentstart, absentfinish, deleted, schoolS_id, category, datedeleted', 'safe', 'on'=>'search'),
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
			'lessons' => array(self::HAS_MANY, 'Lessons', 'teacherS_id1'),
			'materials' => array(self::HAS_MANY, 'Materials', 'teacherS_id1'),
			'subgroups' => array(self::HAS_MANY, 'Subgroups', 'teacherS_id'),
			'schoolS' => array(self::BELONGS_TO, 'Schools', 'schoolS_id'),
			'teachersubjects' => array(self::HAS_MANY, 'Teachersubjects', 'teacherS_id1'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'iduser' => 'Iduser',
			'firstname' => 'Firstname',
			'lastname' => 'Lastname',
			'middlename' => 'Middlename',
			'absentstart' => 'Absentstart',
			'absentfinish' => 'Absentfinish',
			'deleted' => 'Deleted',
			'schoolS_id' => 'School S',
			'category' => 'Category',
			'datedeleted' => 'Datedeleted',
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

		$criteria->compare('id',$this->id,true);
		$criteria->compare('iduser',$this->iduser,true);
		$criteria->compare('firstname',$this->firstname,true);
		$criteria->compare('lastname',$this->lastname,true);
		$criteria->compare('middlename',$this->middlename,true);
		$criteria->compare('absentstart',$this->absentstart,true);
		$criteria->compare('absentfinish',$this->absentfinish,true);
		$criteria->compare('deleted',$this->deleted);
		$criteria->compare('schoolS_id',$this->schoolS_id,true);
		$criteria->compare('category',$this->category);
		$criteria->compare('datedeleted',$this->datedeleted,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * @return CDbConnection the database connection used for this class
	 */
	public function getDbConnection()
	{
		return Yii::app()->dbdo;
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return DOTeachers the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
