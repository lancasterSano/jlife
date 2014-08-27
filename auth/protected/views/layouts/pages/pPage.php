<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="shortcut icon" href="/img/<?php echo JLIFE_ICON; ?>.ico" type="image/x-icon" />
    <title><?php echo $this->pageTitle ?></title>
    <noscript>&lt;meta http-equiv="refresh" content="0; URL=./../badbrowser.php"&gt;</noscript>

    <script type="text/javascript">
    var PM = {idAuth:1100, navigate:"..", pv:0, pua:'2014-01-09'}; </script>
</head>

<body class="twoColLiqLt" onresize="onBodyResize()" style="margin:-1000px 0 0 0;">
    <div id="scrollFix">
        <div id="site_block" class="site_block">
            
            <div class="menu">
               <A href=''> <img src="/img/logo.png"/></a>
                <div class="menuitems">
                </div>
                <div class="search">
                    <div class="login-logout">        
                        <span>
                            <?php 
                                echo Yii::app()->user->name;
                                $url=$this->createUrl('/auth/logout');
                                echo CHtml::link('<img src="/img/shut_down.png"/>', $url);

                            ?>
                                <!-- <a href="./index.php?r=auth/logout"> <img src="/img/shut_down.png"/> </a> -->
                            <!-- <a href="auth/logout">  </a> -->
                        </span>
                    </div>
                    <?php $this->widget('application.components.widgets.pages.faqBtn', array('htmlOptions'=>array('id'=>'faqp', 'class'=>(false)? 'faqp-small':'n',))); ?>
                </div>
            </div>

            <div id="container">

                <div id="sidebar1">
                    <?php $this->widget('application.components.widgets.JLIFE.jLSublMenu',array(
                        'encodeLabel' => false,
                        'htmlOptions' => array( 'id' => 'sidebar'),
                        'items'=>array(
                            array(
                                'url'=>Yii::app()->getBaseUrl(true).'/../pages/index.php', 
                                'label'=>'<img src="/img/menu/home.png" name="image1"/><span>Моя страница</span></a>', 
                                'linkOptions'=>array(
                                    'OnMouseOver'=>"document.image1.src='/img/menu/home_h.png'",
                                    'OnMouseOut'=>"document.image1.src='/img/menu/home.png'",
                                ),
                            ),
                            array(
                                'url'=>Yii::app()->getBaseUrl(true).'/../pages/contacts.php', 
                                'label'=>'<img src="/img/menu/cont.png" name="image8"/><span>Контакты</span></a>', 
                                'linkOptions'=>array(
                                    'OnMouseOver'=>"document.image8.src=\"/img/menu/cont_h.png\"",
                                    'OnMouseOut'=>"document.image8.src=\"/img/menu/cont.png\"",
                                ),
                            ),
                            array(
                                'url'=>Yii::app()->getBaseUrl(true).'/../pages/do/cabinet.php', 
                                'label'=>'<img src="/img/menu/doc.png" name="image2"/><span>Кабинет</span></a>', 
                                'linkOptions'=>array(
                                    'OnMouseOver'=>"document.image2.src=\"/img/menu/doc_h.png\"",
                                    'OnMouseOut'=>"document.image2.src=\"/img/menu/doc.png\"",
                                ),
                            ),
                            array(
                                'url'=>Yii::app()->getBaseUrl(true).'/../pages/do/study.php', 
                                'label'=>'<img src="/img/menu/stud.png" name="image5"/><span>Обучение</span></a>', 
                                'linkOptions'=>array(
                                    'OnMouseOver'=>"document.image5.src=\"/img/menu/stud_h.png\"",
                                    'OnMouseOut'=>"document.image5.src=\"/img/menu/stud.png\"",
                                ),
                            ),
                            array(
                                'url'=>Yii::app()->getBaseUrl(true).'/../pages/messages.php', 
                                'label'=>'<img src="/img/menu/mail.png" name="image4"/><span>Сообщения</span></a>', 
                                'linkOptions'=>array(
                                    'OnMouseOver'=>"document.image4.src=\"/img/menu/mail_h.png\"",
                                    'OnMouseOut'=>"document.image4.src=\"/img/menu/mail.png\"",
                                ),
                            ),
                            array(
                                'url'=>Yii::app()->getBaseUrl(true).'/../pages/my_albums.php', 
                                'label'=>'<img src="/img/menu/phot.png" name="image7"/><span>Фотографии</span></a>', 
                                'linkOptions'=>array(
                                    'OnMouseOver'=>"document.image7.src=\"/img/menu/phot_h.png\"",
                                    'OnMouseOut'=>"document.image7.src=\"/img/menu/phot.png\"",
                                ),
                            ),
                            array(
                                'url'=>Yii::app()->getBaseUrl(true).'/../pages/spersonalization.php', 
                                'label'=>'<img src="/img/menu/set.png" name="image9"/><span>Настройки</span></a>', 
                                'linkOptions'=>array(
                                    'OnMouseOver'=>"document.image9.src=\"/img/menu/set_h.png\"",
                                    'OnMouseOut'=>"document.image9.src=\"/img/menu/set.png\"",
                                ),
                            ),
                            array(
                                'url'=>Yii::app()->getBaseUrl(true).'/../auth/', 
                                'label'=>'<img src="/img/menu/set.png" name="image91"/><span>Yii public</span></a>', 
                                'linkOptions'=>array(
                                    'OnMouseOver'=>"document.image91.src=\"/img/menu/set_h.png\"",
                                    'OnMouseOut'=>"document.image91.src=\"/img/menu/set.png\"",
                                ),
                            ),
                        ),
                    )); ?>
                    <?php
                        if(YII_DEBUG) {
                            echo '<div class="" style="font: 12px \'Trebuchet MS\', Arial, Helvetica, sans-serif; color: rgba(99,147,193,1);">';
                            echo '<br/>controller: <b>'.$this->id.'</b>';
                            echo '<br/>action: <b>'.$this->action->id.'</b>';
                            echo '<br/>pageNumber: <b>'.$this->pageNumber.'</b>';
                            echo '<br/>fpage id _GET: <b>'.$_GET['fpage'].'</b>';
                            if(property_exists(get_class($this), 'findPageNumber')) echo '<br/>this->findPageNumber: <b>'.$this->findPageNumber.'</b>';
                            if(property_exists(get_class($this), 'findPageRoute')) echo '<br/>this->findPageRoute: <b>'.$this->findPageRoute.'</b>';
                            
                            if(property_exists(get_class($this), 'selectSchool')) {
                                echo '<br/><br/> SELECTE School: <b>'.$this->selectSchool.'</b>';
                                echo '<br/> SELECTE RoleRouteType: <b>'.$this->selectRoleRouteType.'</b>';
                                echo '<br/> SELECTE Role: <b>'.$this->selectRole.'</b>';
                                echo '<br/> SELECTE Adress: <b>'.$this->selectAdress.'</b>';
                                echo '<br/> SELECTE LearnerOrder: <b>'.$this->selectLearnerOrder.'</b>';
                                echo '<br/> SELECTE Learner: <b>'.$this->selectLearner.'</b>';
                            }
                            echo '</div>';
                        }
                    ?>
                </div>

                <div id="mainContent">
                    <?php echo $content; ?>
                </div>
            </div>
        </div>    
    </div>    
</body>
</html>