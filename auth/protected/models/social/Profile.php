<?php

/**
 * This is the model class for table "profile".
 *
 * The followings are the available columns in table 'profile':
 * @property string $id
 * @property string $login
 * @property string $password
 * @property integer $privatepswd
 * @property string $email
 * @property integer $private
 * @property string $firstname
 * @property string $lastname
 * @property string $middlename
 * @property string $telephone
 * @property string $mobile
 * @property string $email2
 * @property string $city
 * @property string $country
 * @property string $birthday
 * @property string $countsubscribes
 * @property string $countalbum
 * @property string $countcontact
 * @property string $countblog
 * @property string $countblogcomment
 * @property string $countwall
 * @property string $countwallmy
 * @property integer $isdefaultava
 * @property integer $isdefaulthb
 * @property integer $role
 * @property integer $deleted
 * @property integer $lock
 * @property integer $valid
 * @property string $countinbox
 * @property string $countoutbox
 * @property integer $sex
 * @property string $acceptlicense
 */
class Profile extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'profile';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('login, password, email, firstname, lastname', 'required'),
			array('privatepswd, private, isdefaultava, isdefaulthb, role, deleted, lock, valid, sex', 'numerical', 'integerOnly'=>true),
			array('login, email, email2', 'length', 'max'=>254),
			array('password', 'length', 'max'=>32),
			array('firstname, lastname, middlename', 'length', 'max'=>25),
			array('telephone, mobile', 'length', 'max'=>15),
			array('city', 'length', 'max'=>20),
			array('country', 'length', 'max'=>45),
			array('countsubscribes, countalbum, countcontact, countblog, countblogcomment, countwall, countwallmy, countinbox, countoutbox', 'length', 'max'=>10),
			array('birthday, acceptlicense', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, login, password, privatepswd, email, private, firstname, lastname, middlename, telephone, mobile, email2, city, country, birthday, countsubscribes, countalbum, countcontact, countblog, countblogcomment, countwall, countwallmy, isdefaultava, isdefaulthb, role, deleted, lock, valid, countinbox, countoutbox, sex, acceptlicense', 'safe', 'on'=>'search'),
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
			'login' => 'md5',
			'password' => 'md5',
			'privatepswd' => 'сменил ли пользователь пароль',
			'email' => 'private email',
			'private' => 'сменил ли пользователь способ аутентификации (логин на email)',
			'firstname' => 'Firstname',
			'lastname' => 'Lastname',
			'middlename' => 'Middlename',
			'telephone' => 'Telephone',
			'mobile' => 'Mobile',
			'email2' => 'public email',
			'city' => 'City',
			'country' => 'Country',
			'birthday' => 'Birthday',
			'countsubscribes' => 'Countsubscribes',
			'countalbum' => 'Countalbum',
			'countcontact' => 'Countcontact',
			'countblog' => 'Countblog',
			'countblogcomment' => 'Countblogcomment',
			'countwall' => 'Countwall',
			'countwallmy' => 'Countwallmy',
			'isdefaultava' => 'Isdefaultava',
			'isdefaulthb' => 'Isdefaulthb',
			'role' => 'may be deleted',
			'deleted' => 'Deleted',
			'lock' => 'Lock',
			'valid' => 'Valid',
			'countinbox' => 'Countinbox',
			'countoutbox' => 'Countoutbox',
			'sex' => 'Sex',
			'acceptlicense' => 'прочели согласился ли с лицензией',
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
		$criteria->compare('login',$this->login,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('privatepswd',$this->privatepswd);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('private',$this->private);
		$criteria->compare('firstname',$this->firstname,true);
		$criteria->compare('lastname',$this->lastname,true);
		$criteria->compare('middlename',$this->middlename,true);
		$criteria->compare('telephone',$this->telephone,true);
		$criteria->compare('mobile',$this->mobile,true);
		$criteria->compare('email2',$this->email2,true);
		$criteria->compare('city',$this->city,true);
		$criteria->compare('country',$this->country,true);
		$criteria->compare('birthday',$this->birthday,true);
		$criteria->compare('countsubscribes',$this->countsubscribes,true);
		$criteria->compare('countalbum',$this->countalbum,true);
		$criteria->compare('countcontact',$this->countcontact,true);
		$criteria->compare('countblog',$this->countblog,true);
		$criteria->compare('countblogcomment',$this->countblogcomment,true);
		$criteria->compare('countwall',$this->countwall,true);
		$criteria->compare('countwallmy',$this->countwallmy,true);
		$criteria->compare('isdefaultava',$this->isdefaultava);
		$criteria->compare('isdefaulthb',$this->isdefaulthb);
		$criteria->compare('role',$this->role);
		$criteria->compare('deleted',$this->deleted);
		$criteria->compare('lock',$this->lock);
		$criteria->compare('valid',$this->valid);
		$criteria->compare('countinbox',$this->countinbox,true);
		$criteria->compare('countoutbox',$this->countoutbox,true);
		$criteria->compare('sex',$this->sex);
		$criteria->compare('acceptlicense',$this->acceptlicense,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Profile the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
