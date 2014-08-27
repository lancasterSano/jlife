<?php

/**
 * This is the model class for table "askquestion".
 *
 * The followings are the available columns in table 'askquestion':
 * @property string $id
 * @property string $questiontext
 * @property string $askdate
 * @property string $askidprofile
 * @property string $askpage
 * @property string $dateanswer
 */
class Askquestion extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'askquestion';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('questiontext, askdate, askidprofile', 'required'),
			array('askidprofile', 'length', 'max'=>10),
			array('askpage', 'length', 'max'=>255),
			array('dateanswer', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, questiontext, askdate, askidprofile, askpage, dateanswer', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'questiontext' => 'Questiontext',
			'askdate' => 'Askdate',
			'askidprofile' => 'Askidprofile',
			'askpage' => 'Askpage',
			'dateanswer' => 'Dateanswer',
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
		$criteria->compare('questiontext',$this->questiontext,true);
		$criteria->compare('askdate',$this->askdate,true);
		$criteria->compare('askidprofile',$this->askidprofile,true);
		$criteria->compare('askpage',$this->askpage,true);
		$criteria->compare('dateanswer',$this->dateanswer,true);

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
	 * @return Askquestion the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
