<?php

/**
 * This is the model class for table "y_subgroup".
 *
 * The followings are the available columns in table 'y_subgroup':
 * @property string $id
 * @property string $idsubgroupdo
 * @property integer $absolutecomplexity
 * @property string $idschooldo
 * @property integer $_issync
 * @property string $idmaterialdo
 * @property string $idteacherdo
 * @property integer $level
 * @property string $idsubjectdo
 * @property integer $evolution_access
 *
 * The followings are the available model relations:
 * @property YQuestionforgroup[] $yQuestionforgroups
 * @property YSectiontiming[] $ySectiontimings
 * @property YSetlearnerquestion[] $ySetlearnerquestions
 * @property YSplearnergroup[] $ySplearnergroups
 * @property YSplearnerquestion[] $ySplearnerquestions
 * @property YSubgroupratio[] $ySubgroupratios
 */
class YSubgroup extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'y_subgroup';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('idsubgroupdo, absolutecomplexity, idschooldo, idsubjectdo', 'required'),
			array('absolutecomplexity, _issync, level, evolution_access', 'numerical', 'integerOnly'=>true),
			array('idsubgroupdo, idschooldo, idsubjectdo', 'length', 'max'=>10),
			array('idmaterialdo, idteacherdo', 'length', 'max'=>11),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, idsubgroupdo, absolutecomplexity, idschooldo, _issync, idmaterialdo, idteacherdo, level, idsubjectdo, evolution_access', 'safe', 'on'=>'search'),
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
			'yQuestionforgroups' => array(self::HAS_MANY, 'YQuestionforgroup', 'qfg_subgroup_id'),
			'ySectiontimings' => array(self::HAS_MANY, 'YSectiontiming', 'st_subgroup_id'),
			'ySetlearnerquestions' => array(self::HAS_MANY, 'YSetlearnerquestion', 'slq_subgroup_id'),
			'ySplearnergroups' => array(self::HAS_MANY, 'YSplearnergroup', 'splg_subgroup_id'),
			'ySplearnerquestions' => array(self::HAS_MANY, 'YSplearnerquestion', 'splq_subgroup_id'),
			'ySubgroupratios' => array(self::HAS_MANY, 'YSubgroupratio', 'sgr_subgroup_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'idsubgroupdo' => 'ID группы в ДО',
			'absolutecomplexity' => 'Уровень сложности от 1 до 12',
			'idschooldo' => 'ID школы в ДО',
			'_issync' => 'Issync',
			'idmaterialdo' => 'Idmaterialdo',
			'idteacherdo' => 'Idteacherdo',
			'level' => 'на каком уровне находятся ученики группы',
			'idsubjectdo' => 'Idsubjectdo',
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
		$criteria->compare('idsubgroupdo',$this->idsubgroupdo,true);
		$criteria->compare('absolutecomplexity',$this->absolutecomplexity);
		$criteria->compare('idschooldo',$this->idschooldo,true);
		$criteria->compare('_issync',$this->_issync);
		$criteria->compare('idmaterialdo',$this->idmaterialdo,true);
		$criteria->compare('idteacherdo',$this->idteacherdo,true);
		$criteria->compare('level',$this->level);
		$criteria->compare('idsubjectdo',$this->idsubjectdo,true);
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
	 * @return YSubgroup the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
