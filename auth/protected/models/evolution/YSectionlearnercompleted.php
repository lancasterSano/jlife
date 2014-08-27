<?php

/**
 * This is the model class for table "y_sectionlearnercompleted".
 *
 * The followings are the available columns in table 'y_sectionlearnercompleted':
 * @property string $id
 * @property string $slc_learner_id
 * @property string $slc_subgroup_id
 * @property string $idsectiondo
 * @property string $date
 * @property integer $questionsmoved
 * @property string $idsubjectdo
 *
 * The followings are the available model relations:
 * @property YSubgroup $slcSubgroup
 * @property YLearner $slcLearner
 */
class YSectionlearnercompleted extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'y_sectionlearnercompleted';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('slc_learner_id, slc_subgroup_id, idsectiondo, date, idsubjectdo', 'required'),
			array('questionsmoved', 'numerical', 'integerOnly'=>true),
			array('slc_learner_id, slc_subgroup_id, idsectiondo', 'length', 'max'=>10),
			array('idsubjectdo', 'length', 'max'=>11),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, slc_learner_id, slc_subgroup_id, idsectiondo, date, questionsmoved, idsubjectdo', 'safe', 'on'=>'search'),
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
			'slcSubgroup' => array(self::BELONGS_TO, 'YSubgroup', 'slc_subgroup_id'),
			'slcLearner' => array(self::BELONGS_TO, 'YLearner', 'slc_learner_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'slc_learner_id' => 'Slc Learner',
			'slc_subgroup_id' => 'Slc Subgroup',
			'idsectiondo' => 'Idsectiondo',
			'date' => 'Дата прохождения',
			'questionsmoved' => 'Перекинули ли вопросы ученику по пройденному разделы',
			'idsubjectdo' => 'Idsubjectdo',
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
		$criteria->compare('slc_learner_id',$this->slc_learner_id,true);
		$criteria->compare('slc_subgroup_id',$this->slc_subgroup_id,true);
		$criteria->compare('idsectiondo',$this->idsectiondo,true);
		$criteria->compare('date',$this->date,true);
		$criteria->compare('questionsmoved',$this->questionsmoved);
		$criteria->compare('idsubjectdo',$this->idsubjectdo,true);

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
	 * @return YSectionlearnercompleted the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
