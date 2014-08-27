<?php

/**
 * This is the model class for table "classs".
 *
 * The followings are the available columns in table 'classs':
 * @property string $id
 * @property string $name
 * @property integer $level
 * @property integer $entryyear
 * @property integer $endyear
 * @property integer $countlearner
 * @property integer $deleted
 * @property string $schoolS_id
 * @property string $yodaS_id
 * @property string $letter
 * @property string $datedeleted
 * @property integer $shift
 *
 * The followings are the available model relations:
 * @property Schools $schoolS
 * @property Yodas $yodaS
 * @property Spisokclassslearners[] $spisokclassslearners
 * @property Spisokclassyodas[] $spisokclassyodases
 * @property Subgroups[] $subgroups
 * @property Timetables[] $timetables
 * @property Yodas[] $yodases
 */
class Classs extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'classs';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, level, entryyear, schoolS_id', 'required'),
			array('level, entryyear, endyear, countlearner, deleted, shift', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>256),
			array('schoolS_id, yodaS_id', 'length', 'max'=>10),
			array('letter', 'length', 'max'=>2),
			array('datedeleted', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, level, entryyear, endyear, countlearner, deleted, schoolS_id, yodaS_id, letter, datedeleted, shift', 'safe', 'on'=>'search'),
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
			'schoolS' => array(self::BELONGS_TO, 'Schools', 'schoolS_id'),
			'yodaS' => array(self::BELONGS_TO, 'Yodas', 'yodaS_id'),
			'spisokclassslearners' => array(self::HAS_MANY, 'Spisokclassslearners', 'classS_id'),
			'spisokclassyodases' => array(self::HAS_MANY, 'Spisokclassyodas', 'classS_id'),
			'subgroups' => array(self::HAS_MANY, 'Subgroups', 'classS_id1'),
			'timetables' => array(self::HAS_MANY, 'Timetables', 'classs_id'),
			'yodases' => array(self::HAS_MANY, 'Yodas', 'classS_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => 'Name',
			'level' => 'Level',
			'entryyear' => 'Entryyear',
			'endyear' => 'Endyear',
			'countlearner' => 'Countlearner',
			'deleted' => 'Deleted',
			'schoolS_id' => 'School S',
			'yodaS_id' => 'Yoda S',
			'letter' => 'Letter',
			'datedeleted' => 'Datedeleted',
			'shift' => 'Shift',
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('level',$this->level);
		$criteria->compare('entryyear',$this->entryyear);
		$criteria->compare('endyear',$this->endyear);
		$criteria->compare('countlearner',$this->countlearner);
		$criteria->compare('deleted',$this->deleted);
		$criteria->compare('schoolS_id',$this->schoolS_id,true);
		$criteria->compare('yodaS_id',$this->yodaS_id,true);
		$criteria->compare('letter',$this->letter,true);
		$criteria->compare('datedeleted',$this->datedeleted,true);
		$criteria->compare('shift',$this->shift);

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
	 * @return Classs the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
