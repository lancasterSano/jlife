<?php

/**
 * This is the model class for table "y_nightscenario".
 *
 * The followings are the available columns in table 'y_nightscenario':
 * @property integer $id
 * @property string $ns_groupunic_id
 * @property integer $ns_task
 * @property integer $number
 * @property string $nameopp
 * @property string $datetime
 * @property string $desc
 * @property string $debug_backtrace
 * @property integer $success
 * @property string $date
 */
class YNightscenario extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'y_nightscenario';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('ns_groupunic_id, ns_task, number, nameopp, datetime, date', 'required'),
			array('ns_task, number, success', 'numerical', 'integerOnly'=>true),
			array('ns_groupunic_id, nameopp', 'length', 'max'=>255),
			array('desc, debug_backtrace', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, ns_groupunic_id, ns_task, number, nameopp, datetime, desc, debug_backtrace, success, date', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'ns_groupunic_id' => 'имя/дата сценария',
			'ns_task' => 'Ns Task',
			'number' => 'Number',
			'nameopp' => 'Nameopp',
			'datetime' => 'Datetime',
			'desc' => 'Desc',
			'debug_backtrace' => 'Debug Backtrace',
			'success' => 'Success',
			'date' => 'Date',
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

		$criteria->compare('id',$this->id);
		$criteria->compare('ns_groupunic_id',$this->ns_groupunic_id,true);
		$criteria->compare('ns_task',$this->ns_task);
		$criteria->compare('number',$this->number);
		$criteria->compare('nameopp',$this->nameopp,true);
		$criteria->compare('datetime',$this->datetime,true);
		$criteria->compare('desc',$this->desc,true);
		$criteria->compare('debug_backtrace',$this->debug_backtrace,true);
		$criteria->compare('success',$this->success);
		$criteria->compare('date',$this->date,true);

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
	 * @return YNightscenario the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
