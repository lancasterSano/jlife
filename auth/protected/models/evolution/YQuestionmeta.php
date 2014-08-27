<?php

/**
 * This is the model class for table "y_questionmeta".
 *
 * The followings are the available columns in table 'y_questionmeta':
 * @property string $id
 * @property string $idownerdo
 * @property string $idmaterialdo
 * @property string $idsectiondo
 * @property string $idparagraphdo
 * @property string $idquestiondo
 * @property string $idsubjectdo
 * @property double $answeractivity
 * @property string $countrightanswer
 * @property string $countallanswer
 * @property string $countcomplexitytransfer
 * @property integer $relativecomplexity
 * @property string $dateblock
 * @property integer $absolutecomplexity
 * @property string $qm_questiontext_id
 * @property string $countactualborrows
 *
 * The followings are the available model relations:
 * @property YFailanswer[] $yFailanswers
 * @property YQuestionforgroup[] $yQuestionforgroups
 * @property YQuestiontext $qmQuestiontext
 * @property YSplearnerquestion[] $ySplearnerquestions
 * @property YSplearnerquestionhistory[] $ySplearnerquestionhistories
 */
class YQuestionmeta extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'y_questionmeta';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('idownerdo, idmaterialdo, idsectiondo, idparagraphdo, idquestiondo, idsubjectdo, relativecomplexity, absolutecomplexity, qm_questiontext_id', 'required'),
			array('relativecomplexity, absolutecomplexity', 'numerical', 'integerOnly'=>true),
			array('answeractivity', 'numerical'),
			array('idownerdo, idmaterialdo, idsectiondo, idparagraphdo, idquestiondo, idsubjectdo, countrightanswer, countallanswer, countcomplexitytransfer', 'length', 'max'=>10),
			array('qm_questiontext_id, countactualborrows', 'length', 'max'=>11),
			array('dateblock', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, idownerdo, idmaterialdo, idsectiondo, idparagraphdo, idquestiondo, idsubjectdo, answeractivity, countrightanswer, countallanswer, countcomplexitytransfer, relativecomplexity, dateblock, absolutecomplexity, qm_questiontext_id, countactualborrows', 'safe', 'on'=>'search'),
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
			'yFailanswers' => array(self::HAS_MANY, 'YFailanswer', 'fa_questionmeta_id'),
			'yQuestionforgroups' => array(self::HAS_MANY, 'YQuestionforgroup', 'qfg_questionmeta_id'),
			'qmQuestiontext' => array(self::BELONGS_TO, 'YQuestiontext', 'qm_questiontext_id'),
			'ySplearnerquestions' => array(self::HAS_MANY, 'YSplearnerquestion', 'splq_questionmeta_id'),
			'ySplearnerquestionhistories' => array(self::HAS_MANY, 'YSplearnerquestionhistory', 'splqh_questionmeta_id'),
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
			'answeractivity' => 'отвечаемость',
			'countrightanswer' => 'количество правильных ответов на вопрос',
			'countallanswer' => 'количество ответов на вопрос',
			'countcomplexitytransfer' => 'количество переходов из сложности в сложность',
			'relativecomplexity' => 'относительная сложность (легкий, средний, сложный)',
			'dateblock' => 'дата блокировки',
			'absolutecomplexity' => 'уровень сложности (от 1 до 12)',
			'qm_questiontext_id' => 'Qm Questiontext',
			'countactualborrows' => 'количество одолжений этого вопроса на данный момент',
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
		$criteria->compare('answeractivity',$this->answeractivity);
		$criteria->compare('countrightanswer',$this->countrightanswer,true);
		$criteria->compare('countallanswer',$this->countallanswer,true);
		$criteria->compare('countcomplexitytransfer',$this->countcomplexitytransfer,true);
		$criteria->compare('relativecomplexity',$this->relativecomplexity);
		$criteria->compare('dateblock',$this->dateblock,true);
		$criteria->compare('absolutecomplexity',$this->absolutecomplexity);
		$criteria->compare('qm_questiontext_id',$this->qm_questiontext_id,true);
		$criteria->compare('countactualborrows',$this->countactualborrows,true);

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
	 * @return YQuestionmeta the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
