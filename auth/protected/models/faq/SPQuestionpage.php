<?php

/**
 * This is the model class for table "spquestionpage".
 *
 * The followings are the available columns in table 'spquestionpage':
 * @property string $id
 * @property string $idpage
 * @property string $idquestion
 *
 * The followings are the available model relations:
 * @property Page $idpage0
 * @property Question $idquestion0
 */
class SPQuestionpage extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'spquestionpage';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('idpage, idquestion', 'required'),
			array('idpage, idquestion', 'length', 'max'=>10),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, idpage, idquestion', 'safe', 'on'=>'search'),
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
			'idpage0' => array(self::BELONGS_TO, 'Page', 'idpage'),
			'idquestion0' => array(self::BELONGS_TO, 'Question', 'idquestion'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'idpage' => 'Idpage',
			'idquestion' => 'Idquestion',
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
		$criteria->compare('idpage',$this->idpage,true);
		$criteria->compare('idquestion',$this->idquestion,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * @return CDbConnection the database connection used for this class
	 */
	public function getDbConnection()
	{
		return Yii::app()->dbFAQ;
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return SPQuestionpage the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
