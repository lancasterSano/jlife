<?php
return CMap::mergeArray(
    require(dirname(__FILE__).'/main.php'),
    array(
        'modules'=>array(
            // uncomment the following to enable the Gii tool
            
            'gii'=>array(
                'class'=>'system.gii.GiiModule',
                'password'=>'1800',
                // If removed, Gii defaults to localhost only. Edit carefully to taste.
                'ipFilters'=>array('127.0.0.1','::1'),
            ),
            
        ),
    	'components'=>array(
    		'db'=>array(
    			'class' => 'CDbConnection',
                'connectionString' => 'mysql:host=localhost;dbname=devsjlife_devssocial',
                'emulatePrepare' => true,
                'username' => 'devsjlife_www',
                'password' => '1800',
                'charset' => 'utf8',

                'enableProfiling'=>true,
                'enableParamLogging' => true,
            ),
            'dbdo'=>array(
                'class' => 'CDbConnection',
                'connectionString' => 'mysql:host=localhost;dbname=devsjlife_devssocial',
                'emulatePrepare' => true,
                'username' => 'devsjlife_www',
                'password' => '1800',
                'charset' => 'utf8',

                'enableProfiling'=>true,
                'enableParamLogging' => true,
             ),
            'dbdo'=>array(
                'class' => 'CDbConnection',
                'connectionString' => 'mysql:host=localhost;dbname=devsjlife_devsdo',
                'emulatePrepare' => true,
                'username' => 'devsjlife_www',
                'password' => '1800',
                'charset' => 'utf8',

                'enableProfiling'=>true,
                'enableParamLogging' => true,
            ),
            'dbY'=>array(
                'class' => 'CDbConnection',
                'connectionString' => 'mysql:host=localhost;dbname=devsjlife_devsy',
                'emulatePrepare' => true,
                'username' => 'devsjlife_www',
                'password' => '1800',
                'charset' => 'utf8',
                
                'enableProfiling'=>true,
                'enableParamLogging' => true,
            ),
            'dbFAQ'=>array(
                'class' => 'CDbConnection',
                'connectionString' => 'mysql:host=localhost;dbname=devsjlife_faq',
                'emulatePrepare' => true,
                'username' => 'devsjlife_www',
                'password' => '1800',
                'charset' => 'utf8',
                
                'enableProfiling'=>true,
                'enableParamLogging' => true,
    		),
            // 'log' => array(
            //     'class' => 'CLogRouter',
            //     'routes' => array(
            //         array(
            //             'class'=>'CProfileLogRoute',
            //             // 'levels'=>'profile',
            //             'enabled'=>true,
            //         ),
            //         array(
            //             'class' => 'CWebLogRoute',
            //             // 'categories' => 'application',
            //             'levels'=>'error, warning, trace, profile, info',
            //         ),
            //     ),
            // ),
            'log'=>array(
                'class'=>'CLogRouter',
                'enabled'=>YII_DEBUG,
                'routes'=>array(
                    #...
                    array(
                        'class'=>'CFileLogRoute',
                        'levels'=>'error, warning',
                    ),
                    array(
                        'class'=>'application.extensions.yii-debug-toolbar.YiiDebugToolbarRoute',
                        'ipFilters'=>array('127.0.0.1','192.168.1.215'),
                    ),
                ),
            ),

    	),
    )
);