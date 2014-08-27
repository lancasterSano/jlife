<?php

/**
 * This is the model class for table "y_questionforgroup".
 *
 * The followings are the available columns in table 'y_questionforgroup':
 * @property string $id
 * @property string $idownerdo
 * @property string $idmaterialdo
 * @property string $idsectiondo
 * @property string $idparagraphdo
 * @property string $idquestiondo
 * @property string $idsubjectdo
 * @property integer $_numbersectiondo
 * @property integer $isborrowed
 * @property string $qfg_subgroup_id
 * @property string $qfg_questionmeta_id
 * @property string $datemoved
 * @property integer $deleted
 *
 * The followings are the available model relations:
 * @property YQuestionmeta $qfgQuestionmeta
 * @property YSubgroup $qfgSubgroup
 */
class YQuestionforgroup extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'y_questionforgroup';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('idownerdo, idmaterialdo, idsectiondo, idparagraphdo, idquestiondo, idsubjectdo, qfg_subgroup_id, qfg_questionmeta_id', 'required'),
			array('_numbersectiondo, isborrowed, deleted', 'numerical', 'integerOnly'=>true),
			array('idownerdo, idmaterialdo, idsectiondo, idparagraphdo, idquestiondo, idsubjectdo', 'length', 'max'=>10),
			array('qfg_subgroup_id, qfg_questionmeta_id', 'length', 'max'=>11),
			array('datemoved', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, idownerdo, idmaterialdo, idsectiondo, idparagraphdo, idquestiondo, idsubjectdo, _numbersectiondo, isborrowed, qfg_subgroup_id, qfg_questionmeta_id, datemoved, deleted', 'safe', 'on'=>'search'),
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
			'qfgQuestionmeta' => array(self::BELONGS_TO, 'YQuestionmeta', 'qfg_questionmeta_id'),
			'qfgSubgroup' => array(self::BELONGS_TO, 'YSubgroup', 'qfg_subgroup_id'),
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
			'_numbersectiondo' => 'секция: номер секции',
			'isborrowed' => 'Одолжен ли вопрос',
			'qfg_subgroup_id' => 'ID группы',
			'qfg_questionmeta_id' => 'ID questionmeta',
			'datemoved' => 'дата перемещения из этой таблицы в таблицу учеников',
			'deleted' => 'Пометка об удалении вопроса из группы для ночи',
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
		$criteria->compare('_numbersectiondo',$this->_numbersectiondo);
		$criteria->compare('isborrowed',$this->isborrowed);
		$criteria->compare('qfg_subgroup_id',$this->qfg_subgroup_id,true);
		$criteria->compare('qfg_questionmeta_id',$this->qfg_questionmeta_id,true);
		$criteria->compare('datemoved',$this->datemoved,true);
		$criteria->compare('deleted',$this->deleted);

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
	 * @return YQuestionforgroup the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
