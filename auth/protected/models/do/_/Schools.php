<?php

/**
 * This is the model class for table "schools".
 *
 * The followings are the available columns in table 'schools':
 * @property string $id
 * @property string $number
 * @property string $name
 * @property string $directoruser
 * @property integer $countclass
 * @property integer $countlearner
 * @property integer $countteacher
 * @property integer $countko
 * @property integer $countclassroom
 * @property integer $deleted
 * @property string $description
 * @property integer $countresponsible
 * @property string $idstudyduration
 * @property integer $countstudyday
 * @property string $datedeleted
 * @property integer $shift
 * @property integer $step
 *
 * The followings are the available model relations:
 * @property Classrooms[] $classrooms
 * @property Classs[] $classses
 * @property Hourload[] $hourloads
 * @property Kos[] $koses
 * @property Learners[] $learners
 * @property Responsibles[] $responsibles
 * @property Schedulerings[] $schedulerings
 * @property Spisoksubjectschools[] $spisoksubjectschools
 * @property Studyduration[] $studydurations
 * @property Teachers[] $teachers
 * @property Timetables[] $timetables
 * @property Yodas[] $yodases
 */
class Schools extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'schools';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, directoruser', 'required'),
			array('countclass, countlearner, countteacher, countko, countclassroom, deleted, countresponsible, countstudyday, shift, step', 'numerical', 'integerOnly'=>true),
			array('number, directoruser, idstudyduration', 'length', 'max'=>10),
			array('name', 'length', 'max'=>256),
			array('description, datedeleted', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, number, name, directoruser, countclass, countlearner, countteacher, countko, countclassroom, deleted, description, countresponsible, idstudyduration, countstudyday, datedeleted, shift, step', 'safe', 'on'=>'search'),
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
			'classrooms' => array(self::HAS_MANY, 'Classrooms', 'schoolS_id'),
			'classses' => array(self::HAS_MANY, 'Classs', 'schoolS_id'),
			'hourloads' => array(self::HAS_MANY, 'Hourload', 'schools_id'),
			'koses' => array(self::HAS_MANY, 'Kos', 'schoolS_id'),
			'learners' => array(self::HAS_MANY, 'Learners', 'schoolS_id'),
			'responsibles' => array(self::HAS_MANY, 'Responsibles', 'schoolS_id'),
			'schedulerings' => array(self::HAS_MANY, 'Schedulerings', 'schoolS_id'),
			'spisoksubjectschools' => array(self::HAS_MANY, 'Spisoksubjectschools', 'schools_id'),
			'studydurations' => array(self::HAS_MANY, 'Studyduration', 'schoolS_id'),
			'teachers' => array(self::HAS_MANY, 'Teachers', 'schoolS_id'),
			'timetables' => array(self::HAS_MANY, 'Timetables', 'schoolS_id'),
			'yodases' => array(self::HAS_MANY, 'Yodas', 'schoolS_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'number' => 'Number',
			'name' => 'Name',
			'directoruser' => 'id пользователя выполняющий роль директора',
			'countclass' => 'Countclass',
			'countlearner' => 'Countlearner',
			'countteacher' => 'Countteacher',
			'countko' => 'Countko',
			'countclassroom' => 'Countclassroom',
			'deleted' => 'Deleted',
			'description' => 'Description',
			'countresponsible' => 'Countresponsible',
			'idstudyduration' => 'Idstudyduration',
			'countstudyday' => 'Countstudyday',
			'datedeleted' => 'Datedeleted',
			'shift' => 'Shift',
			'step' => 'Step',
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
		$criteria->compare('number',$this->number,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('directoruser',$this->directoruser,true);
		$criteria->compare('countclass',$this->countclass);
		$criteria->compare('countlearner',$this->countlearner);
		$criteria->compare('countteacher',$this->countteacher);
		$criteria->compare('countko',$this->countko);
		$criteria->compare('countclassroom',$this->countclassroom);
		$criteria->compare('deleted',$this->deleted);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('countresponsible',$this->countresponsible);
		$criteria->compare('idstudyduration',$this->idstudyduration,true);
		$criteria->compare('countstudyday',$this->countstudyday);
		$criteria->compare('datedeleted',$this->datedeleted,true);
		$criteria->compare('shift',$this->shift);
		$criteria->compare('step',$this->step);

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
	 * @return Schools the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
