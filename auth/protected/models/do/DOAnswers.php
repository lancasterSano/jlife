<?php

/**
 * This is the model class for table "answers".
 *
 * The followings are the available columns in table 'answers':
 * @property string $id
 * @property string $text
 * @property integer $right
 * @property integer $deleted
 * @property string $questionS_id1
 * @property string $datedeleted
 *
 * The followings are the available model relations:
 * @property Questions $questionSId1
 */
class DOAnswers extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'answers';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('text, questionS_id1', 'required'),
			array('right, deleted', 'numerical', 'integerOnly'=>true),
			array('text', 'length', 'max'=>256),
			array('questionS_id1', 'length', 'max'=>10),
			array('datedeleted', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, text, right, deleted, questionS_id1, datedeleted', 'safe', 'on'=>'search'),
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
			'questionSId1' => array(self::BELONGS_TO, 'Questions', 'questionS_id1'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'text' => 'Text',
			'right' => 'Right',
			'deleted' => 'Deleted',
			'questionS_id1' => 'Question S Id1',
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
		$criteria->compare('text',$this->text,true);
		$criteria->compare('right',$this->right);
		$criteria->compare('deleted',$this->deleted);
		$criteria->compare('questionS_id1',$this->questionS_id1,true);
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
	 * @return DOAnswers the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
