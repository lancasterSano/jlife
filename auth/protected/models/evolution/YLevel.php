<?php

/**
 * This is the model class for table "y_level".
 *
 * The followings are the available columns in table 'y_level':
 * @property string $id
 * @property integer $number
 * @property integer $fullmark
 * @property integer $tries
 * @property string $datefirstcreate
 * @property string $datelastcreate
 * @property string $datefinish
 * @property integer $sublevelstatus
 * @property integer $tryleft
 * @property integer $levelavailable
 *
 * The followings are the available model relations:
 * @property YFailanswer[] $yFailanswers
 * @property YLearner[] $yLearners
 * @property YSublevel[] $ySublevels
 * @property YSublevelhistory[] $ySublevelhistories
 */
class YLevel extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'y_level';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('number, fullmark, tries, datefirstcreate, datelastcreate, levelavailable', 'required'),
			array('number, fullmark, tries, sublevelstatus, tryleft, levelavailable', 'numerical', 'integerOnly'=>true),
			array('datefinish', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, number, fullmark, tries, datefirstcreate, datelastcreate, datefinish, sublevelstatus, tryleft, levelavailable', 'safe', 'on'=>'search'),
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
			'yFailanswers' => array(self::HAS_MANY, 'YFailanswer', 'fa_level_id'),
			'yLearners' => array(self::HAS_MANY, 'YLearner', 'l_level_id'),
			'ySublevels' => array(self::HAS_MANY, 'YSublevel', 'sl_level_id'),
			'ySublevelhistories' => array(self::HAS_MANY, 'YSublevelhistory', 'slh_level_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'number' => 'номер уровня [0-50]',
			'fullmark' => 'сумма балов за 6 подуровней из max 26',
			'tries' => 'количество формирования уровня',
			'datefirstcreate' => 'дата первого формирования',
			'datelastcreate' => 'дата последнего формирования',
			'datefinish' => 'дата завершения уровня',
			'sublevelstatus' => '[0-6] какой подуровень мне предстоит пройти и (this-1) - какой подуровень я взял
0 значит что уровень не сформирован',
			'tryleft' => 'количество возможностей переформировать подуровень',
			'levelavailable' => 'может ли ученик открыть уровень днем или нет (1/0)',
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
		$criteria->compare('number',$this->number);
		$criteria->compare('fullmark',$this->fullmark);
		$criteria->compare('tries',$this->tries);
		$criteria->compare('datefirstcreate',$this->datefirstcreate,true);
		$criteria->compare('datelastcreate',$this->datelastcreate,true);
		$criteria->compare('datefinish',$this->datefinish,true);
		$criteria->compare('sublevelstatus',$this->sublevelstatus);
		$criteria->compare('tryleft',$this->tryleft);
		$criteria->compare('levelavailable',$this->levelavailable);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * @return CDbConnection the database connection used for this class
	 */
	public function getDbConnection()
	{
		return Yii::app()->dbY;
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return YLevel the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
