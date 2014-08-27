<?php $this->beginContent('//layouts/pages/pPage'); ?>
    <?php $this->beginContent('//layouts/unions/unionSMF'); ?>
        <!-- Меню для ДО -->
        <?php 
            $this->widget('application.components.widgets.JLIFE.jLSublMenu',array(
                'htmlOptions' => array( 'id' => 'lineTabs'),
                'items' => Yii::app()->controller->sublmenu,
                'activateParents'=>true,
                'activateItems'=>true,
            ));
        ?>
    <?php $this->endContent(); ?>
    <div class="mainfield">
<!--         <div class="contentHead">
          <span class="activePart">Расписание класса</span>
        </div>  -->
        <div class="seachforhelp">
            <div class="searchinput">
                <input type="text" value="Укажите запрос..." onfocus="if(this.value=='Укажите запрос...'){this.value=''}" onblur="if(this.value==''){this.value='Укажите запрос...'}"/>
                <img src="/img/search.png"/>
            </div> 
            <?
                $this->widget('application.components.widgets.pages.Hashtags',array(
                    'htmlOptions' => array( 'class' => 'hashtags'),
                    'items' => array(),
                ));
            ?>
        </div>
        <div class="content">
            <?php echo $content; ?>
        </div>
    </div>
<?php $this->endContent(); ?>