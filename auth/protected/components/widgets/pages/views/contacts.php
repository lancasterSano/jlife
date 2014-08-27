<div class="Contacts">
   	<div class="contactNum">
        <span>
        	<a href="../pages/contacts.php">Контакты(<?php echo $countcontact?>)</a>
        </span>
    </div>
    <div class="photoTable">
        <?php
        $oldProjectProfile_url = '../pages/index.php?id=';

        foreach($friends as $friend)
        	echo CHtml::link(CHtml::image($friend['pathAvatar'],'Img-'.$friend['iduser']),
                array($oldProjectProfile_url.$friend['id']));
        ?>
    </div>
 </div>