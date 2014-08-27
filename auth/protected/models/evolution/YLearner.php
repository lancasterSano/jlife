<?php

/**
 * This is the model class for table "y_learner".
 *
 * The followings are the available columns in table 'y_learner':
 * @property string $id
 * @property string $idlearnerdo
 * @property string $l_level_id
 * @property integer $_issync
 * @property integer $removed
 * @property integer $state
 *
 * The followings are the available model relations:
 * @property YLevel $lLevel
 * @property YSectionlearnercompleted[] $ySectionlearnercompleteds
 * @property YSetlearnerquestion[] $ySetlearnerquestions
 * @property YSplearnergroup[] $ySplearnergroups
 * @property YSplearnerquestion[] $ySplearnerquestions
 * @property YSplearnerquestionhistory[] $ySplearnerquestionhistories
 */
class YLearner extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'y_learner';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('idlearnerdo, state', 'required'),
			array('_issync, removed, state', 'numerical', 'integerOnly'=>true),
			array('idlearnerdo, l_level_id', 'length', 'max'=>10),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, idlearnerdo, l_level_id, _issync, removed, state', 'safe', 'on'=>'search'),
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
			'lLevel' => array(self::BELONGS_TO, 'YLevel', 'l_level_id'),
			'ySectionlearnercompleteds' => array(self::HAS_MANY, 'YSectionlearnercompleted', 'slc_learner_id'),
			'ySetlearnerquestions' => array(self::HAS_MANY, 'YSetlearnerquestion', 'slq_learner_id'),
			'ySplearnergroups' => array(self::HAS_MANY, 'YSplearnergroup', 'splg_learner_id'),
			'ySplearnerquestions' => array(self::HAS_MANY, 'YSplearnerquestion', 'splq_learner_id'),
			'ySplearnerquestionhistories' => array(self::HAS_MANY, 'YSplearnerquestionhistory', 'splqh_learner_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'idlearnerdo' => 'ID ученика из ДО',
			'l_level_id' => 'Когда начинает idLevel( number=1)',
			'_issync' => 'Он появился в системе в ближайшие сутки',
			'removed' => 'ученик удален из системы роста',
			'state' => 'состояние ученика (отстает/идет по графику/опережает)',
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
		$criteria->compare('idlearnerdo',$this->idlearnerdo,true);
		$criteria->compare('l_level_id',$this->l_level_id,true);
		$criteria->compare('_issync',$this->_issync);
		$criteria->compare('removed',$this->removed);
		$criteria->compare('state',$this->state);

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
	 * @return YLearner the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
