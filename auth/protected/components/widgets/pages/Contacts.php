<?php

// Yii::import('zii.widgets.CWidget');

class Contacts extends CWidget
{
    public $countViewFriends = 6;

    public function init()
    {
        // этот метод будет вызван внутри CBaseController::beginWidget()

    }
 
    public function run()
    {
        // этот метод будет вызван внутри CBaseController::endWidget()

        # Поиск количества друзей пользователя
            $countContact = Profile::model()->find( 
                array(
                    'select'=>array('countcontact'),
                    'condition'=>'id=:id',
                    'params' => array(':id'=>Yii::app()->user->id),
                )
            );
        
        # Выбираем id друзей 
            $from = 'Spisokcontactgroupuser'.Yii::app()->user->id;
            $idFriends = Yii::app()->db->createCommand()
                ->selectDistinct('iduser')
                ->from($from)
                ->where('deleted = 0 AND state = 0')
                ->limit($this->countViewFriends)
                ->order('RAND()')
                ->queryAll();

        # Пересоздаем массив друзей (структурируем)
            foreach($idFriends as $key => $valueIdFriends)
                $mass[] = $valueIdFriends['iduser'];

        # Получаем информацию по id друзей, которые мы нашли в прошлом запросе
            $profileFriend = Yii::app()->db->createCommand()
                ->select('id, firstname , lastname , middlename ,  telephone,
                        mobile, city, country, birthday, countcontact, countalbum,
                        countblog, countblogcomment, countwall, countwallmy,
                        isdefaultava, isdefaulthb, role, countinbox, countoutbox,
                        sex, deleted, lock, valid, acceptlicense, private,
                        email')
                ->from('profile')
                ->where(array('in', 'id', $mass))
                ->queryAll();
                
        # Формируем конечный массив друзей с аватаркой и id     
            foreach($profileFriend as $oneProfile)
            {
                $friend = array(
                    "id" => $oneProfile["id"],
                    "pathAvatar" => ProfileManager::getProjectPathAvatar($profileFriend->id, $profileFriend->isdefaultava, 0, $profileFriend->sex),
                );
            
            $friends[] = $friend;
            }

        $this->render('contacts',array('countcontact'=>$countContact->countcontact, 'friends'=>$friends));

    }
}