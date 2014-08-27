<?php

/**
 * This is the model class for table "y_splearnergroup".
 *
 * The followings are the available columns in table 'y_splearnergroup':
 * @property string $id
 * @property string $splg_learner_id
 * @property string $splg_subgroup_id
 * @property integer $absolutecomplexity
 * @property integer $setcreated
 * @property integer $deleted
 * @property integer $evolution_access
 *
 * The followings are the available model relations:
 * @property YLearner $splgLearner
 * @property YSubgroup $splgSubgroup
 */
class YSPLearnergroup extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'y_splearnergroup';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('splg_learner_id, splg_subgroup_id, absolutecomplexity', 'required'),
			array('absolutecomplexity, setcreated, deleted, evolution_access', 'numerical', 'integerOnly'=>true),
			array('splg_learner_id, splg_subgroup_id', 'length', 'max'=>10),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, splg_learner_id, splg_subgroup_id, absolutecomplexity, setcreated, deleted, evolution_access', 'safe', 'on'=>'search'),
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
			'splgLearner' => array(self::BELONGS_TO, 'YLearner', 'splg_learner_id'),
			'splgSubgroup' => array(self::BELONGS_TO, 'YSubgroup', 'splg_subgroup_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'splg_learner_id' => 'ID ученика',
			'splg_subgroup_id' => 'ID группы',
			'absolutecomplexity' => 'Относительная сложность',
			'setcreated' => 'Созданы ли ученику множества',
			'deleted' => 'Пометка об удалении ученика из группы (нужно для ночи)',
			'evolution_access' => 'Доступна ли группе система роста',
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
		$criteria->compare('splg_learner_id',$this->splg_learner_id,true);
		$criteria->compare('splg_subgroup_id',$this->splg_subgroup_id,true);
		$criteria->compare('absolutecomplexity',$this->absolutecomplexity);
		$criteria->compare('setcreated',$this->setcreated);
		$criteria->compare('deleted',$this->deleted);
		$criteria->compare('evolution_access',$this->evolution_access);

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
	 * @return YSPLearnergroup the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
