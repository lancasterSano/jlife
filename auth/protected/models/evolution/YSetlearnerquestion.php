<?php

/**
 * This is the model class for table "y_setlearnerquestion".
 *
 * The followings are the available columns in table 'y_setlearnerquestion':
 * @property string $id
 * @property integer $relativecomplexity
 * @property integer $absolutecomplexity
 * @property integer $isteacherownerofset
 * @property integer $ispopular
 * @property integer $actualitystate
 * @property string $slq_learner_id
 * @property string $slq_subgroup_id
 * @property string $idsubjectdo
 * @property string $countavailable4
 * @property string $countavailable5
 * @property string $countavailable6
 * @property string $countavailable7
 * @property string $countavailable8
 * @property string $countavailable9
 * @property string $countavailable10
 * @property string $lagcountavailableall
 * @property string $actualcountavailableall
 * @property string $advancecountavailableall
 * @property string $countall4
 * @property string $countall5
 * @property string $countall6
 * @property string $countall7
 * @property string $countall8
 * @property string $countall9
 * @property string $countall10
 *
 * The followings are the available model relations:
 * @property YSubgroup $slqSubgroup
 * @property YLearner $slqLearner
 * @property YSplearnerquestion[] $ySplearnerquestions
 */
class YSetlearnerquestion extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'y_setlearnerquestion';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('relativecomplexity, absolutecomplexity, isteacherownerofset, ispopular, actualitystate, slq_learner_id, slq_subgroup_id, idsubjectdo', 'required'),
			array('relativecomplexity, absolutecomplexity, isteacherownerofset, ispopular, actualitystate', 'numerical', 'integerOnly'=>true),
			array('slq_learner_id, slq_subgroup_id, idsubjectdo, countavailable4, countavailable5, countavailable6, countavailable7, countavailable8, countavailable9, countavailable10, lagcountavailableall, actualcountavailableall, advancecountavailableall, countall4, countall5, countall6, countall7, countall8, countall9, countall10', 'length', 'max'=>10),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, relativecomplexity, absolutecomplexity, isteacherownerofset, ispopular, actualitystate, slq_learner_id, slq_subgroup_id, idsubjectdo, countavailable4, countavailable5, countavailable6, countavailable7, countavailable8, countavailable9, countavailable10, lagcountavailableall, actualcountavailableall, advancecountavailableall, countall4, countall5, countall6, countall7, countall8, countall9, countall10', 'safe', 'on'=>'search'),
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
			'slqSubgroup' => array(self::BELONGS_TO, 'YSubgroup', 'slq_subgroup_id'),
			'slqLearner' => array(self::BELONGS_TO, 'YLearner', 'slq_learner_id'),
			'ySplearnerquestions' => array(self::HAS_MANY, 'YSplearnerquestion', 'splq_setlearnerquestion_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'relativecomplexity' => 'относительная сложность (легкий-средний-сложный)',
			'absolutecomplexity' => 'уровень от 1 до 12',
			'isteacherownerofset' => 'принадлежит ли множество вопросов учителю',
			'ispopular' => 'является ли популярным',
			'actualitystate' => 'какой вопрос (повторение/тек.раздел/тек.сем./экстерн)',
			'slq_learner_id' => 'Slq Learner',
			'slq_subgroup_id' => 'Slq Subgroup',
			'idsubjectdo' => 'Idsubjectdo',
			'countavailable4' => 'количество доступных вопросов ценой в 4 балла',
			'countavailable5' => 'количество доступных вопросов ценой в 5 баллов',
			'countavailable6' => 'количество доступных вопросов ценой в 6 баллов',
			'countavailable7' => 'количество доступных вопросов ценой в 7 баллов',
			'countavailable8' => 'количество доступных вопросов ценой в 8 баллов',
			'countavailable9' => 'количество доступных вопросов ценой в 9 баллов',
			'countavailable10' => 'количество доступных вопросов ценой в 10 баллов',
			'lagcountavailableall' => '[state:lag] количество доступных вопросов (общее)',
			'actualcountavailableall' => '[state:actual] количество доступных вопросов (общее)',
			'advancecountavailableall' => '[state:advance] количество доступных вопросов (общее)',
			'countall4' => 'количество всех вопросов ценой в 4 балла',
			'countall5' => 'количество всех вопросов ценой в 5 баллов',
			'countall6' => 'количество всех вопросов ценой в 6 баллов',
			'countall7' => 'количество всех вопросов ценой в 7 баллов',
			'countall8' => 'количество всех вопросов ценой в 8 баллов',
			'countall9' => 'количество всех вопросов ценой в 9 баллов',
			'countall10' => 'количество всех вопросов ценой в 10 баллов',
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
		$criteria->compare('absolutecomplexity',$this->absolutecomplexity);
		$criteria->compare('isteacherownerofset',$this->isteacherownerofset);
		$criteria->compare('ispopular',$this->ispopular);
		$criteria->compare('actualitystate',$this->actualitystate);
		$criteria->compare('slq_learner_id',$this->slq_learner_id,true);
		$criteria->compare('slq_subgroup_id',$this->slq_subgroup_id,true);
		$criteria->compare('idsubjectdo',$this->idsubjectdo,true);
		$criteria->compare('countavailable4',$this->countavailable4,true);
		$criteria->compare('countavailable5',$this->countavailable5,true);
		$criteria->compare('countavailable6',$this->countavailable6,true);
		$criteria->compare('countavailable7',$this->countavailable7,true);
		$criteria->compare('countavailable8',$this->countavailable8,true);
		$criteria->compare('countavailable9',$this->countavailable9,true);
		$criteria->compare('countavailable10',$this->countavailable10,true);
		$criteria->compare('lagcountavailableall',$this->lagcountavailableall,true);
		$criteria->compare('actualcountavailableall',$this->actualcountavailableall,true);
		$criteria->compare('advancecountavailableall',$this->advancecountavailableall,true);
		$criteria->compare('countall4',$this->countall4,true);
		$criteria->compare('countall5',$this->countall5,true);
		$criteria->compare('countall6',$this->countall6,true);
		$criteria->compare('countall7',$this->countall7,true);
		$criteria->compare('countall8',$this->countall8,true);
		$criteria->compare('countall9',$this->countall9,true);
		$criteria->compare('countall10',$this->countall10,true);

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
	 * @return YSetlearnerquestion the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
