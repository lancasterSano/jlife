<?php

/**
 * This is the model class for table "roles".
 *
 * The followings are the available columns in table 'roles':
 * @property string $id
 * @property string $profile_id
 * @property integer $role
 * @property string $idadress
 * @property integer $idschool
 * @property string $info
 * @property string $datestart
 * @property string $datefinish
 */
class Roles extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'roles';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('profile_id, idschool, info, datestart', 'required'),
			array('role, idschool', 'numerical', 'integerOnly'=>true),
			array('profile_id, idadress', 'length', 'max'=>10),
			array('info', 'length', 'max'=>256),
			array('datefinish', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, profile_id, role, idadress, idschool, info, datestart, datefinish', 'safe', 'on'=>'search'),
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
			'profile_id' => 'Profile',
			'role' => 'Role',
			'idadress' => 'Idadress',
			'idschool' => 'Idschool',
			'info' => 'Info',
			'datestart' => 'Datestart',
			'datefinish' => 'Datefinish',
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
		$criteria->compare('profile_id',$this->profile_id,true);
		$criteria->compare('role',$this->role);
		$criteria->compare('idadress',$this->idadress,true);
		$criteria->compare('idschool',$this->idschool);
		$criteria->compare('info',$this->info,true);
		$criteria->compare('datestart',$this->datestart,true);
		$criteria->compare('datefinish',$this->datefinish,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Roles the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
