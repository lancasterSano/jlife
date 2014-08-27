<?php

/**
 * This is the model class for table "y_splearnerquestion".
 *
 * The followings are the available columns in table 'y_splearnerquestion':
 * @property string $id
 * @property string $idownerdo
 * @property string $idmaterialdo
 * @property string $idsectiondo
 * @property string $idparagraphdo
 * @property string $idquestiondo
 * @property string $idsubjectdo
 * @property string $splq_learner_id
 * @property string $splq_subgroup_id
 * @property integer $mark
 * @property string $countrightanswer
 * @property string $countallanswer
 * @property integer $isblocked
 * @property string $dateblock
 * @property string $splq_questionmeta_id
 * @property string $splq_setlearnerquestion_id
 * @property integer $relativecomplexity
 * @property integer $absolutecomplexity
 * @property integer $isborrowed
 * @property integer $level
 *
 * The followings are the available model relations:
 * @property YSetlearnerquestion $splqSetlearnerquestion
 * @property YLearner $splqLearner
 * @property YQuestionmeta $splqQuestionmeta
 * @property YSubgroup $splqSubgroup
 */
class YSPLearnerquestion extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'y_splearnerquestion';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('idownerdo, idmaterialdo, idsectiondo, idparagraphdo, idquestiondo, idsubjectdo, splq_learner_id, splq_subgroup_id, splq_questionmeta_id, isborrowed, level', 'required'),
			array('mark, isblocked, relativecomplexity, absolutecomplexity, isborrowed, level', 'numerical', 'integerOnly'=>true),
			array('idownerdo, idmaterialdo, idsectiondo, idparagraphdo, idquestiondo, idsubjectdo, splq_learner_id, splq_subgroup_id, countrightanswer, countallanswer, splq_questionmeta_id, splq_setlearnerquestion_id', 'length', 'max'=>10),
			array('dateblock', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, idownerdo, idmaterialdo, idsectiondo, idparagraphdo, idquestiondo, idsubjectdo, splq_learner_id, splq_subgroup_id, mark, countrightanswer, countallanswer, isblocked, dateblock, splq_questionmeta_id, splq_setlearnerquestion_id, relativecomplexity, absolutecomplexity, isborrowed, level', 'safe', 'on'=>'search'),
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
			'splqSetlearnerquestion' => array(self::BELONGS_TO, 'YSetlearnerquestion', 'splq_setlearnerquestion_id'),
			'splqLearner' => array(self::BELONGS_TO, 'YLearner', 'splq_learner_id'),
			'splqQuestionmeta' => array(self::BELONGS_TO, 'YQuestionmeta', 'splq_questionmeta_id'),
			'splqSubgroup' => array(self::BELONGS_TO, 'YSubgroup', 'splq_subgroup_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'idownerdo' => 'вопрос: владелец',
			'idmaterialdo' => 'вопрос: материал',
			'idsectiondo' => 'вопрос: секция',
			'idparagraphdo' => 'вопрос: параграф',
			'idquestiondo' => 'вопрос: сам вопрос',
			'idsubjectdo' => 'вопрос: предмет',
			'splq_learner_id' => 'ID ученика',
			'splq_subgroup_id' => 'от какой группы достался вопрос',
			'mark' => 'Оценка ученика за вопрос',
			'countrightanswer' => 'Количество правильных ответов',
			'countallanswer' => 'Количество ответов',
			'isblocked' => 'Заблокирован ли вопрос',
			'dateblock' => 'Дата блокировки',
			'splq_questionmeta_id' => 'Splq Questionmeta',
			'splq_setlearnerquestion_id' => 'Splq Setlearnerquestion',
			'relativecomplexity' => 'относительная сложность (легкий, средний, сложный)',
			'absolutecomplexity' => 'уровень сложности (от 1 до 12)',
			'isborrowed' => 'одолжен ли вопрос (наследуется от questionforgroup)',
			'level' => 'с какого уровня доступен данный вопрос',
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
		$criteria->compare('idownerdo',$this->idownerdo,true);
		$criteria->compare('idmaterialdo',$this->idmaterialdo,true);
		$criteria->compare('idsectiondo',$this->idsectiondo,true);
		$criteria->compare('idparagraphdo',$this->idparagraphdo,true);
		$criteria->compare('idquestiondo',$this->idquestiondo,true);
		$criteria->compare('idsubjectdo',$this->idsubjectdo,true);
		$criteria->compare('splq_learner_id',$this->splq_learner_id,true);
		$criteria->compare('splq_subgroup_id',$this->splq_subgroup_id,true);
		$criteria->compare('mark',$this->mark);
		$criteria->compare('countrightanswer',$this->countrightanswer,true);
		$criteria->compare('countallanswer',$this->countallanswer,true);
		$criteria->compare('isblocked',$this->isblocked);
		$criteria->compare('dateblock',$this->dateblock,true);
		$criteria->compare('splq_questionmeta_id',$this->splq_questionmeta_id,true);
		$criteria->compare('splq_setlearnerquestion_id',$this->splq_setlearnerquestion_id,true);
		$criteria->compare('relativecomplexity',$this->relativecomplexity);
		$criteria->compare('absolutecomplexity',$this->absolutecomplexity);
		$criteria->compare('isborrowed',$this->isborrowed);
		$criteria->compare('level',$this->level);

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
	 * @return YSPLearnerquestion the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
