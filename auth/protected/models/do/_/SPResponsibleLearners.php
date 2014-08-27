<?php

/**
 * This is the model class for table "spisokresponsiblelearners".
 *
 * The followings are the available columns in table 'spisokresponsiblelearners':
 * @property string $id
 * @property string $relationS_id
 * @property string $learnerS_id1
 * @property string $responsibleS_id1
 *
 * The followings are the available model relations:
 * @property Learners $learnerSId1
 * @property Relations $relationS
 * @property Responsibles $responsibleSId1
 */
class SPResponsibleLearners extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'spisokresponsiblelearners';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('learnerS_id1, responsibleS_id1', 'required'),
			array('relationS_id, learnerS_id1, responsibleS_id1', 'length', 'max'=>10),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, relationS_id, learnerS_id1, responsibleS_id1', 'safe', 'on'=>'search'),
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
			'learnerSId1' => array(self::BELONGS_TO, 'Learners', 'learnerS_id1'),
			'relationS' => array(self::BELONGS_TO, 'Relations', 'relationS_id'),
			'responsibleSId1' => array(self::BELONGS_TO, 'Responsibles', 'responsibleS_id1'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'relationS_id' => 'Relation S',
			'learnerS_id1' => 'Learner S Id1',
			'responsibleS_id1' => 'Responsible S Id1',
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
		$criteria->compare('relationS_id',$this->relationS_id,true);
		$criteria->compare('learnerS_id1',$this->learnerS_id1,true);
		$criteria->compare('responsibleS_id1',$this->responsibleS_id1,true);

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
	 * @return SPResponsibleLearners the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
