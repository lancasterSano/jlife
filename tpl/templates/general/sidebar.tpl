{* Меню. Подклюяается в шаблонах: page* *}
{* Требования : 
    $ProfileAuth - Авторизированный пользователь.
*}
<div id="sidebar1">
    <ul> 
        <li>
            <a href="{$PROJECT_PATH}/pages/index.php" 
                onMouseOver="document.image1.src='{$PROJECT_PATH}/img/menu/home_h.png'" 
                onMouseOut="document.image1.src='{$PROJECT_PATH}/img/menu/home.png'">
            <img src="{$PROJECT_PATH}/img/menu/home.png" name="image1"/><span>Моя страница</span></a>
        </li>
        <li>
            <a href="{$PROJECT_PATH}/pages/contacts.php" 
                onMouseOver="document.image8.src='{$PROJECT_PATH}/img/menu/cont_h.png'" 
                onMouseOut="document.image8.src='{$PROJECT_PATH}/img/menu/cont.png'">
            <img src="{$PROJECT_PATH}/img/menu/cont.png" name="image8"/><span>Контакты</span>
            </a>
            <span id="countNewRequestsSpan" class="alertS"></span>
        </li>
        <li>
            <a href="{$PROJECT_PATH}/pages/do/cabinet.php" 
                onMouseOver="document.image2.src='{$PROJECT_PATH}/img/menu/doc_h.png'"  
                onMouseOut="document.image2.src='{$PROJECT_PATH}/img/menu/doc.png'">
            <img src="{$PROJECT_PATH}/img/menu/doc.png" name="image2"/><span>Кабинет</span></a>
        </li>
        <li>
            <a href="{$PROJECT_PATH}/pages/do/study.php" 
                onMouseOver="document.image5.src='{$PROJECT_PATH}/img/menu/stud_h.png';" 
                onMouseOut="document.image5.src='{$PROJECT_PATH}/img/menu/stud.png';">
            <img src="{$PROJECT_PATH}/img/menu/stud.png" name="image5"/><span>Обучение</span></a>
        </li>       
        <li>
            <a href="{$PROJECT_PATH}/pages/messages.php" 
            onMouseOver="document.image4.src='{$PROJECT_PATH}/img/menu/mail_h.png';" 
            onMouseOut="document.image4.src='{$PROJECT_PATH}/img/menu/mail.png';">
                <img src="{$PROJECT_PATH}/img/menu/mail.png" name="image4"/><span>Сообщения</span>
            </a>
            <span id="comm2" class="alertS"></span>
        </li>
        <!-- <li>
            <a href="{$PROJECT_PATH}/pages/my_albums.php" 
                onMouseOver="document.image7.src='{$PROJECT_PATH}/img/menu/phot_h.png'" 
                onMouseOut="document.image7.src='{$PROJECT_PATH}/img/menu/phot.png'">
            <img src="{$PROJECT_PATH}/img/menu/phot.png" name="image7"/><span>Фотографии</span></a>
        </li> -->
        <li>
            <a href="{$PROJECT_PATH}/pages/spersonalization.php" 
                onMouseOver="document.image9.src='{$PROJECT_PATH}/img/menu/set_h.png'" 
                onMouseOut="document.image9.src='{$PROJECT_PATH}/img/menu/set.png'">
            <img src="{$PROJECT_PATH}/img/menu/set.png"name="image9"/><span>Настройки</span></a>    
        </li>
        {if isset($ProfileAuth) && $ProfileAuth->getRolesByRole(ROLES::$GOD)}
        <li>
            <a href="http://{$smarty.const.SYS_BASE_EVALUATION}" 
                onMouseOver="document.image9.src='{$PROJECT_PATH}/img/menu/set_h.png'" 
                onMouseOut="document.image9.src='{$PROJECT_PATH}/img/menu/set.png'">
            <img src="{$PROJECT_PATH}/img/menu/set.png"name="image9"/><span>EVALUATION</span></a>    
        </li>
<!--         <li>
            <a href="http://{$smarty.const.SYS_BASE_EVALUATION}/auth/index.php?r=site" 
                onMouseOver="document.image9.src='{$PROJECT_PATH}/img/menu/set_h.png'" 
                onMouseOut="document.image9.src='{$PROJECT_PATH}/img/menu/set.png'">
            <img src="{$PROJECT_PATH}/img/menu/set.png"name="image9"/><span>SITE/INDEX</span></a>    
        </li> -->
        <li>
            <a href="http://{$smarty.const.SYS_BASE_SOCIAL}/pages/god/goduser.php" 
                onMouseOver="document.image9.src='{$PROJECT_PATH}/img/menu/set_h.png'" 
                onMouseOut="document.image9.src='{$PROJECT_PATH}/img/menu/set.png'">
            <img src="{$PROJECT_PATH}/img/menu/set.png"name="image9"/><span>БОГ</span></a>    
        </li>
        {/if}
	</ul>
<!-- end #sidebar1 --></div>