<?php

/**
 * This is the model class for table "y_sectiontiming".
 *
 * The followings are the available columns in table 'y_sectiontiming':
 * @property string $id
 * @property string $idsectiondo
 * @property integer $numbersectiondo
 * @property string $st_subgroup_id
 * @property integer $idsubjectdo
 * @property string $levelstart
 * @property string $levelcount
 * @property string $date
 *
 * The followings are the available model relations:
 * @property YSubgroup $stSubgroup
 */
class YSectiontiming extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'y_sectiontiming';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('idsectiondo, st_subgroup_id, idsubjectdo, levelstart, levelcount, date', 'required'),
			array('numbersectiondo, idsubjectdo', 'numerical', 'integerOnly'=>true),
			array('idsectiondo, st_subgroup_id, levelstart, levelcount', 'length', 'max'=>10),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, idsectiondo, numbersectiondo, st_subgroup_id, idsubjectdo, levelstart, levelcount, date', 'safe', 'on'=>'search'),
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
			'stSubgroup' => array(self::BELONGS_TO, 'YSubgroup', 'st_subgroup_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'idsectiondo' => 'Секция: ID секции из ДО',
			'numbersectiondo' => 'Секция: номер секции',
			'st_subgroup_id' => 'St Subgroup',
			'idsubjectdo' => 'Группа: ID группы из ДО',
			'levelstart' => 'Уровень в Y, где начинается секци',
			'levelcount' => 'Продолжительность по уровням',
			'date' => 'дата открытия секции для группы',
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
		$criteria->compare('idsectiondo',$this->idsectiondo,true);
		$criteria->compare('numbersectiondo',$this->numbersectiondo);
		$criteria->compare('st_subgroup_id',$this->st_subgroup_id,true);
		$criteria->compare('idsubjectdo',$this->idsubjectdo);
		$criteria->compare('levelstart',$this->levelstart,true);
		$criteria->compare('levelcount',$this->levelcount,true);
		$criteria->compare('date',$this->date,true);

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
	 * @return YSectiontiming the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
