<?php
return CMap::mergeArray(
    require(dirname(__FILE__).'/_social.php'),
    CMap::mergeArray(
        require(dirname(__FILE__).'/_evolution.php'),
        CMap::mergeArray(                
            require(dirname(__FILE__).'/_do.php'),
            array(
                'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
                'name'=>'JLife Console',

                'preload'=>array('log'),

                'import'=>array(
                        'application.models.*',
                        'application.models.do.*',
                        'application.models.social.*',
                        'application.models.faq.*',
                        'application.models.evolution.*',
                        // 'application.components.*',
                        // 'application.components.do.*',
                        // 'application.components.pageControllers.*',
                        // 'application.components.common.*',
                        // 'application.components.widgets.*',
                        'application.components.evolution.*',
                ),
                'components'=>array(
                        'cache'=>array(
                                'class'=>'CFileCache',
                                ),
                        'db'=>array(
                                'class' => 'CDbConnection',
                                'connectionString' => 'mysql:host=localhost;dbname=devsjlife_devssocial',
                                'emulatePrepare' => true,
                                'username' => 'devsjlife_www',
                                'password' => '1800',
                                'charset' => 'utf8',

                                // 'enableProfiling'=>true,
                                // 'enableParamLogging' => true,
                                ),
                        'dbdo'=>array(
                                'class' => 'CDbConnection',
                                'connectionString' => 'mysql:host=localhost;dbname=devsjlife_devsdo',
                                'emulatePrepare' => true,
                                'username' => 'devsjlife_www',
                                'password' => '1800',
                                'charset' => 'utf8',

                                // 'enableProfiling'=>true,
                                // 'enableParamLogging' => true,
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

                                // 'enableProfiling'=>true,
                                // 'enableParamLogging' => true,
                                ),
                        'log' => array(
                                'class' => 'CLogRouter',
                                'routes' => array(
                                    array(
                                        'class'=>'CFileLogRoute',
                                        'levels'=>'trace, info',
                                        'logFile'=>'dbY.consoleApplication.log',
                                        'categories'=>'application.*',
                                        'maxFileSize'=>1024,
                                    ),
                                ),
                            ),


                        ),
                'commandMap' => array(
                    'calculate' => array(
                        'class' => 'application.components.CalculateCommand',
                        'dbConnection' => 'db',
                    ),
                ),
            )
        )
    )
);