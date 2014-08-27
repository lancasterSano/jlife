<?php

/**
 * This is the model class for table "y_subgroupratio".
 *
 * The followings are the available columns in table 'y_subgroupratio':
 * @property string $id
 * @property double $kpriority
 * @property double $lagkreview
 * @property double $lagkactivestudyduration
 * @property double $actualkreview
 * @property double $actualkactivestudyduration
 * @property double $actualkactivesection
 * @property double $advancekreview
 * @property double $advancekactivestudyduration
 * @property double $advancekactivesection
 * @property double $advancekextern
 * @property double $popularkteacher
 * @property double $popularkborrowed
 * @property double $unpopularkteacher
 * @property double $unpopularkborrowed
 * @property string $idschooldo
 * @property string $idsubjectdo
 * @property string $sgr_subgroup_id
 * @property integer $issync
 *
 * The followings are the available model relations:
 * @property YSubgroup $sgrSubgroup
 */
class YSubgroupratio extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'y_subgroupratio';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('kpriority, lagkreview, lagkactivestudyduration, actualkreview, actualkactivestudyduration, actualkactivesection, advancekreview, advancekactivestudyduration, advancekactivesection, advancekextern, popularkteacher, popularkborrowed, unpopularkteacher, unpopularkborrowed, idschooldo, idsubjectdo, sgr_subgroup_id', 'required'),
			array('issync', 'numerical', 'integerOnly'=>true),
			array('kpriority, lagkreview, lagkactivestudyduration, actualkreview, actualkactivestudyduration, actualkactivesection, advancekreview, advancekactivestudyduration, advancekactivesection, advancekextern, popularkteacher, popularkborrowed, unpopularkteacher, unpopularkborrowed', 'numerical'),
			array('idschooldo, idsubjectdo, sgr_subgroup_id', 'length', 'max'=>10),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, kpriority, lagkreview, lagkactivestudyduration, actualkreview, actualkactivestudyduration, actualkactivesection, advancekreview, advancekactivestudyduration, advancekactivesection, advancekextern, popularkteacher, popularkborrowed, unpopularkteacher, unpopularkborrowed, idschooldo, idsubjectdo, sgr_subgroup_id, issync', 'safe', 'on'=>'search'),
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
			'sgrSubgroup' => array(self::BELONGS_TO, 'YSubgroup', 'sgr_subgroup_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'kpriority' => 'коэффициент приоритета(актуальность в школе). значение от 1 до 12',
			'lagkreview' => '[state:lag] процент вопросов, которые можно взять за повторение прошлого материала (для учеников, которые отстают)',
			'lagkactivestudyduration' => '[state:lag] процент вопросов, которые можно взять из текущего семестра (для учеников, которые отстают)',
			'actualkreview' => '[state:actual] процент вопросов, которые можно взять за повторение прошлого материала (для учеников, которые идут по графику)',
			'actualkactivestudyduration' => '[state:actual] процент вопросов, которые можно взять из текущего семестра (для учеников, которые идут по графику)',
			'actualkactivesection' => '[state:actual] процент вопросов, которые можно взять из текущего раздела (для учеников, которые идут по графику)',
			'advancekreview' => '[state:advance] процент вопросов, которые можно взять за повторение прошлого материала (для учеников, опережающих программу)',
			'advancekactivestudyduration' => '[state:advance] процент вопросов, которые можно взять из текущего семестра (для учеников, опережающих программу)',
			'advancekactivesection' => '[state:advance] процент вопросов, которые можно взять из текущего раздела (для учеников, опережающих программу)',
			'advancekextern' => '[state:advance] процент вопросов, которые можно взять из темы экстерном (для учеников, опережающих программу)',
			'popularkteacher' => 'процент преподавательских прокачанных вопросов, которые можно взять',
			'popularkborrowed' => 'процент одолженных прокачанных вопросов, которые можно взять',
			'unpopularkteacher' => 'процент преподавательских непрокачанных вопросов, которые можно взять',
			'unpopularkborrowed' => 'процент одолженных непрокачанных вопросов, которые можно взять',
			'idschooldo' => 'Idschooldo',
			'idsubjectdo' => 'Idsubjectdo',
			'sgr_subgroup_id' => 'Sgr Subgroup',
			'issync' => 'пересчитаны ли коэффициенты',
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
		$criteria->compare('kpriority',$this->kpriority);
		$criteria->compare('lagkreview',$this->lagkreview);
		$criteria->compare('lagkactivestudyduration',$this->lagkactivestudyduration);
		$criteria->compare('actualkreview',$this->actualkreview);
		$criteria->compare('actualkactivestudyduration',$this->actualkactivestudyduration);
		$criteria->compare('actualkactivesection',$this->actualkactivesection);
		$criteria->compare('advancekreview',$this->advancekreview);
		$criteria->compare('advancekactivestudyduration',$this->advancekactivestudyduration);
		$criteria->compare('advancekactivesection',$this->advancekactivesection);
		$criteria->compare('advancekextern',$this->advancekextern);
		$criteria->compare('popularkteacher',$this->popularkteacher);
		$criteria->compare('popularkborrowed',$this->popularkborrowed);
		$criteria->compare('unpopularkteacher',$this->unpopularkteacher);
		$criteria->compare('unpopularkborrowed',$this->unpopularkborrowed);
		$criteria->compare('idschooldo',$this->idschooldo,true);
		$criteria->compare('idsubjectdo',$this->idsubjectdo,true);
		$criteria->compare('sgr_subgroup_id',$this->sgr_subgroup_id,true);
		$criteria->compare('issync',$this->issync);

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
	 * @return YSubgroupratio the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
