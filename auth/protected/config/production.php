<?php
return CMap::mergeArray(
    require(dirname(__FILE__).'/main.php'),
    array(
        'components'=>array(
            'db'=>array(
                'connectionString' => 'mysql:host=localhost;dbname=admin_primesocial',
                'emulatePrepare' => true,
                'username' => 'admin_www',
                'password' => '1800',
                'charset' => 'utf8',
                // 'class' => 'CDbConnection'
            ),
            'dbY'=>array(
                'connectionString' => 'mysql:host=localhost;dbname=admin_primesocial',
                'emulatePrepare' => true,
                'username' => 'admin_www',
                'password' => '1800',
                'charset' => 'utf8',
                'class' => 'CDbConnection'
            ),
            /*
            'urlManager'=>array(
                'urlFormat'=>'path',
                'rules'=>array(
                    '<controller:\w+>/<id:\d+>'=>'<controller>/view',
                    '<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
                    '<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
                ),
            ),
            */
        ),
    )
);