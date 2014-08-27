<?php
return CMap::mergeArray(
    require(dirname(__FILE__).'/_social.php'),
    CMap::mergeArray(
        require(dirname(__FILE__).'/_evolution.php'),
        CMap::mergeArray(                
            require(dirname(__FILE__).'/_do.php'),
            array(
                'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
                'name'=>'JLife',

                // preloading 'log' component
                'preload'=>array('log'),

                // autoloading model and component classes
                'import'=>array(
                        'application.models.*',
                        'application.models.do.*',
                        'application.models.social.*',
                        'application.models.faq.*',
                        'application.models.evolution.*',
                        'application.components.*',
                        'application.components.do.*',
                        'application.components.pageControllers.*',
                        'application.components.common.*',
                        'application.components.widgets.*',
                        'application.components.evolution.*',
                ),

                // 'modules'=>array(
                // 	// uncomment the following to enable the Gii tool

                // 	'gii'=>array(
                // 		'class'=>'system.gii.GiiModule',
                // 		'password'=>'1800',
                // 		// If removed, Gii defaults to localhost only. Edit carefully to taste.
                // 		'ipFilters'=>array('127.0.0.1','::1'),
                // 	),

                // ),

                // application components
                'components'=>array(
                        'user'=>array(
                                // enable cookie-based authentication
                                'loginUrl'=>array('auth/login'),
                                'allowAutoLogin'=>true,
                        ),
                        // uncomment the following to enable URLs in path-format		
                        'urlManager'=>array(
                                'urlFormat'=>'path',
                                'showScriptName'=>false,
                                'rules'=>array(
                                        'gii' => 'gii',
                                        'gii/<controller:\w+>' => 'gii/<controller>',
                                        'gii/<controller:\w+>/<action:\w+>' => 'gii/<controller>/<action>',
                                // social controller
                                        'notes'=>'social/note/list',
                                        'notes/<idprofile:\d+>'=>'social/note/list',
                                        'notes/<idprofile:\d+>/<master:[0-1]+>'=>'social/note/list',

                                        // '<_c:(profile)>'=>'social/<_c>', 
                                        'profile'=>'social/profile/general',
                                        'profile/<idprofile:\d+>'=>'social/profile/general',
                                        'profile/general'=>'social/profile/general',
                                        'profile/general/<idprofile:\d+>'=>'social/profile/general',
                                        'profile/general/<idprofile:\d+>/*'=>'social/profile/general',
                                        // 'social/profile'=>'error',

                                        'note/*'=>'error',


                                // do controller
                                        // '<school:\d+>/<controller:\w+>/*'=>'do/<controller>',
                                        // '<school:\d+>/<_c:(evolution|site|journal|schedule|subjects|testreasult)>/<role:[l]{1}+>/<learnerOrder:\d+>/faq/<fPage:\d+>/*'=>'faq/search',
                                        // '<school:\d+>/<_c:(evolution|site|journal|schedule|subjects|testreasult)>/<role:[slkc]{1}+>/faq/<fPage:\d+>/*'=>'faq/search',
                                        // '<school:\d+>/<_c:(evolution|site|journal|schedule|subjects|testreasult)>/faq/<fPage:\d+>/*'=>'faq/search',
                                        // 'do/<controller:(evolution|site|journal|schedule|subjects|testreasult)>/<action:\w+>/<school>/<role>/*'=>'<school>/<action>/<role>/faq',

                                        '<school:\d+>/<_fc:(evolution|site|journal|schedule|subjects|testreasult)>/<role:[l]{1}+>/<learnerOrder:\d+>/faq/*'=>'faq/search',
                                        '<school:\d+>/<_fc:(evolution|site|journal|schedule|subjects|testreasult)>/<role:[slkc]{1}+>/faq/*'=>'faq/search',
                                        '<school:\d+>/<_fc:(evolution|site|journal|schedule|subjects|testreasult)>/faq/*'=>'faq/search',


                                        // '<school:\d+>/<_c:(evolution|site|journal|schedule|subjects|testreasult)>/<role:[l]{1}+>/<learnerOrder:\d+>/<fpage:\d+>/faq/*'=>'faq',
                                        // '<school:\d+>/<_c:(evolution|site|journal|schedule|subjects|testreasult)>/<role:[slkc]{1}+>/<fpage:\d+>/faq/*'=>'faq',
                                        // '<school:\d+>/<_c:(evolution|site|journal|schedule|subjects|testreasult)>/<fpage:\d+>/faq/*'=>'faq',

                                        '<school:\d+>/<_c:(evolution|site|journal|schedule|subjects|testreasult)>/<role:[l]{1}+>/<learnerOrder:\d+>/*'=>'do/<_c>',	
                                        '<school:\d+>/<_c:(evolution|site|journal|schedule|subjects|testreasult)>/<role:[slkc]{1}+>/*'=>'do/<_c>',
                                        '<school:\d+>/<_c:(evolution|site|journal|schedule|subjects|testreasult)>/*'=>'do/<_c>',

                                        'faq'=>'faq/sea2rch',
                                        'faq/<fPage:\d+>'=>'faq/search',
                                        'faq/<fPage:\d+>/*'=>'faq/sear234ch',
                                        'faq/*'=>'faq/sea211_rch',

                                        // '<controller:\w+>/<id:\d+>'=>'<controller>/view',
                                        // '<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
                                        // '<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
                                        // '*'=>'error',



                                        'loglp/<action>/*'=>'core/loglp/<action>',
                                        // 'godpanel/quotes/*'=>'core/god/quotes',
                                        'godpanel/1/<action:\w+>/*'=>'core/god/<action>',
                                        // 'loglp/list/*'=>'core/loglp/list',

                                ),
                        ),

                        'errorHandler'=>array(
                                // use 'site/error' action to display errors
                                'errorAction'=>'site/error',
                        ),
                ),

                // application-level parameters that can be accessed
                // using Yii::app()->params['paramName']
                'params'=>array(
                    // this is used in contact page
                    'adminEmail'=>'smuchka.cherry@gmail.com',
                    // 'SESSION_IDPROFILE'=>$SESSION_IDPROFILE,
                    // 'SESSION_JLIN'=>$SESSION_JLIN,
                )
            )
        )
    )
);