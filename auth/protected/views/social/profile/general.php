<div class="uInfoContacts">
    <div class="mainInfo">  
        <?php
            // echo '<br/>test: '.$test;
            // echo '<br/>find page number: '.$this->findPage;
            // echo '<br/>controller: '.$this->id;
            // echo '<br/>action: '.$this->action->id;

            // echo '<br/><br/> _GET: ';
            // var_dump($_GET);

            // echo '<br/><br/>school: '.$_GET['school'];
            // echo '<br/>role: '.$_GET['role'];
            // echo '<br/>idadress: '.$_GET['idadress'];
            // echo '<br/>learner: '.$_GET['learner'];
            // echo '<br/>fPage: '.$_GET['fPage'];
            // echo '<br/> '. $this->createUrl('evolution/index',array('id'=>100,'#'=>'title'));
        ?>
        <div class="uInfo">
            <table>
                <?php if($birthday) { ?>
                <tr>
                	<td id="field">
                    	Дата рождения
                    </td>
                    <td>
                    	<?php echo $birthday ?>
                    </td>
                </tr>
                <?php } ?>
                <!-- ************** -->
                <?php if($country) { ?>
                <tr>
                	<td id="field">
                    	Страна
                    </td>
                    <td>
                    	<?php echo $country ?>
                    </td>
                </tr>
                <?php } ?>
                <!-- ************** -->
                <?php if($city) { ?>
                <tr>
                	<td id="field">
                    	Город
                    </td>
                    <td>
                    	<?php echo $city ?>
                    </td>
                </tr>
                <?php } ?>
                <!-- ************** -->
                <?php if($telephone) { ?>
                <tr>
                	<td id="field">
                    	Телефон
                    </td>
                    <td>
                    	<?php echo $telephone ?>
                    </td>
                </tr>
                <?php } ?>
                <!-- ************** -->
                <?php if($mobile) { ?>
                <tr>
                	<td id="field">
                    	Мобильный
                    </td>
                    <td>
                    	<?php echo $mobile ?>
                    </td>
                </tr>
                <?php } ?>
            </table>
        </div>

        <div class="underInfo">
         	<!--<table>
            	<tr>
                	<td id="field">
                    	Деятельность
                    </td>
                    <td>
                    	Ученик Школа №62
                    </td>
                </tr>
            </table> -->
        </div>
    </div>
    <?php
        $this->widget('application.components.widgets.pages.Contacts',
            array(
                'countViewFriends'=>3,
                )
            );
    ?> 
    <!-- <div class="Contacts">
      	<div class="contactNum">
            <span><a href="contacts.php?id={$idLoad}">Контакты(1)</a></span>
        </div>
        <div class="photoTable">
            <a href="index.php?id=1100"> <img src="" />
            </a>
        </div>
    </div> --> 
</div>
