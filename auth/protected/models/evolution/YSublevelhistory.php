<?php

/**
 * This is the model class for table "y_sublevelhistory".
 *
 * The followings are the available columns in table 'y_sublevelhistory':
 * @property string $id
 * @property integer $relativecomplexity
 * @property string $slh_level_id
 * @property string $daterefresh
 * @property string $datefinish
 * @property integer $mark
 *
 * The followings are the available model relations:
 * @property YLevel $slhLevel
 */
class YSublevelhistory extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'y_sublevelhistory';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('relativecomplexity, slh_level_id, datefinish', 'required'),
			array('relativecomplexity, mark', 'numerical', 'integerOnly'=>true),
			array('slh_level_id', 'length', 'max'=>10),
			array('daterefresh', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, relativecomplexity, slh_level_id, daterefresh, datefinish, mark', 'safe', 'on'=>'search'),
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
			'slhLevel' => array(self::BELONGS_TO, 'YLevel', 'slh_level_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'relativecomplexity' => 'Relativecomplexity',
			'slh_level_id' => 'Slh Level',
			'daterefresh' => 'Daterefresh',
			'datefinish' => 'Datefinish',
			'mark' => 'Mark',
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
		$criteria->compare('relativecomplexity',$this->relativecomplexity);
		$criteria->compare('slh_level_id',$this->slh_level_id,true);
		$criteria->compare('daterefresh',$this->daterefresh,true);
		$criteria->compare('datefinish',$this->datefinish,true);
		$criteria->compare('mark',$this->mark);

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
	 * @return YSublevelhistory the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
