<?php

/**
 * This is the model class for table "y_questiontext".
 *
 * The followings are the available columns in table 'y_questiontext':
 * @property string $id
 * @property string $idownerdo
 * @property string $idmaterialdo
 * @property string $idsectiondo
 * @property string $idparagraphdo
 * @property string $idquestiondo
 * @property string $idsubjectdo
 * @property integer $isusedbyowner
 * @property string $countfavorite
 *
 * The followings are the available model relations:
 * @property YQuestionmeta[] $yQuestionmetas
 * @property YTeacherownquestion[] $yTeacherownquestions
 */
class YQuestiontext extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'y_questiontext';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('idownerdo, idmaterialdo, idsectiondo, idparagraphdo, idquestiondo, idsubjectdo', 'required'),
			array('isusedbyowner', 'numerical', 'integerOnly'=>true),
			array('idownerdo, idmaterialdo, idsectiondo, idparagraphdo, idquestiondo, idsubjectdo', 'length', 'max'=>10),
			array('countfavorite', 'length', 'max'=>11),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, idownerdo, idmaterialdo, idsectiondo, idparagraphdo, idquestiondo, idsubjectdo, isusedbyowner, countfavorite', 'safe', 'on'=>'search'),
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
			'yQuestionmetas' => array(self::HAS_MANY, 'YQuestionmeta', 'qm_questiontext_id'),
			'yTeacherownquestions' => array(self::HAS_MANY, 'YTeacherownquestion', 'toq_questiontext_id'),
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
			'isusedbyowner' => 'Использует ли вопрос владелец',
			'countfavorite' => 'сколько пользователей добавили вопрос в избранное',
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
		$criteria->compare('isusedbyowner',$this->isusedbyowner);
		$criteria->compare('countfavorite',$this->countfavorite,true);

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
	 * @return YQuestiontext the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
