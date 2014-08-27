<?php

/**
 * This is the model class for table "y_sublevel".
 *
 * The followings are the available columns in table 'y_sublevel':
 * @property string $id
 * @property string $idquestion1
 * @property string $idquestion2
 * @property string $idquestion3
 * @property string $idquestion4
 * @property string $idquestion5
 * @property string $daterefresh
 * @property string $datefinish
 * @property integer $mark
 * @property string $sl_level_id
 * @property integer $relativecomplexity
 *
 * The followings are the available model relations:
 * @property YLevel $slLevel
 */
class YSublevel extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'y_sublevel';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('idquestion1, idquestion2, idquestion3, idquestion4, idquestion5, mark, sl_level_id, relativecomplexity', 'required'),
			array('mark, relativecomplexity', 'numerical', 'integerOnly'=>true),
			array('idquestion1, idquestion2, idquestion3, idquestion4, idquestion5, sl_level_id', 'length', 'max'=>10),
			array('daterefresh, datefinish', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, idquestion1, idquestion2, idquestion3, idquestion4, idquestion5, daterefresh, datefinish, mark, sl_level_id, relativecomplexity', 'safe', 'on'=>'search'),
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
			'slLevel' => array(self::BELONGS_TO, 'YLevel', 'sl_level_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'idquestion1' => 'Idquestion1',
			'idquestion2' => 'Idquestion2',
			'idquestion3' => 'Idquestion3',
			'idquestion4' => 'Idquestion4',
			'idquestion5' => 'Idquestion5',
			'daterefresh' => 'количество обновлений вопросов',
			'datefinish' => 'Datefinish',
			'mark' => 'Mark',
			'sl_level_id' => 'Sl Level',
			'relativecomplexity' => 'Relativecomplexity',
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
		$criteria->compare('idquestion1',$this->idquestion1,true);
		$criteria->compare('idquestion2',$this->idquestion2,true);
		$criteria->compare('idquestion3',$this->idquestion3,true);
		$criteria->compare('idquestion4',$this->idquestion4,true);
		$criteria->compare('idquestion5',$this->idquestion5,true);
		$criteria->compare('daterefresh',$this->daterefresh,true);
		$criteria->compare('datefinish',$this->datefinish,true);
		$criteria->compare('mark',$this->mark);
		$criteria->compare('sl_level_id',$this->sl_level_id,true);
		$criteria->compare('relativecomplexity',$this->relativecomplexity);

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
	 * @return YSublevel the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
