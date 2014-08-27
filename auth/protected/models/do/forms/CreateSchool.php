<?php
class CreateSchool extends CFormModel
{
    public $name;
    public $number;
    public $description;

    public function rules()
    {
        return array(
            array('name', 'required'),
            array('name', 'dublicate'),
        );
    }
    public function attributeLabels()
    {
    	return array(
    		'name'=>'Название школы',
    		'number'=>'Номер',
    		'description'=>'Описание',
    	);
    }

    public function dublicate($attribute,$params)
    {
    	$rez = Schools::model()->count('name LIKE :nameS', array('nameS'=>$this->name));
    	if($rez) $this->addError('name','Такая школа уже есть в системе (LIKE NAME).');
    }
}
?>