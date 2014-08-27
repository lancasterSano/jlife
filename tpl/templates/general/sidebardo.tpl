{* Меню. Подключается в шаблонах: page* *}
{* Требования : 
    $ProfileAuth - Авторизированный пользователь.
*}
<div id="sidebar1">
    <ul>
        <li title="Моя страница">
            <a href="{$PROJECT_PATH}/pages/index.php?id={$ProfileAuth->ID}" 
            onmouseover="document.image1.src='{$PROJECT_PATH}/img/gr/home1.png'" 
            onmouseout="document.image1.src='{$PROJECT_PATH}/img/gr/home.png'">
            <img src="{$PROJECT_PATH}/img/gr/home.png" name="image1"></a>
        </li>
        <li title="Контакты">
            <a href="{$PROJECT_PATH}/pages/contacts.php?id={$ProfileAuth->ID}" 
            onmouseover="document.image2.src='{$PROJECT_PATH}/img/gr/comu.png'" 
            onmouseout="document.image2.src='{$PROJECT_PATH}/img/gr/com.png'">
            <img src="{$PROJECT_PATH}/img/gr/com.png" name="image2"></a>
        </li>
        <li title="Кабинет">
            <a href="{$PROJECT_PATH}/pages/do/cabinet.php" 
            onmouseover="document.image3.src='{$PROJECT_PATH}/img/gr/doc2.png'" 
            onmouseout="document.image3.src='{$PROJECT_PATH}/img/gr/doc.png'">
            <img src="{$PROJECT_PATH}/img/gr/doc.png" name="image3"></a>
        </li>
        <li title="Обучение">
            <a href="{$PROJECT_PATH}/pages/do/study.php" 
            onmouseover="document.image5.src='{$PROJECT_PATH}/img/gr/std2.png'" 
            onmouseout="document.image5.src='{$PROJECT_PATH}/img/gr/std.png'">
            <img src="{$PROJECT_PATH}/img/gr/std.png" name="image5"></a>
        </li>
        <li title="Сообщения">
            <a href="{$PROJECT_PATH}/pages/messages.php?type=inbox"
            onmouseover="document.image4.src='{$PROJECT_PATH}/img/gr/mail2.png'" 
            onmouseout="document.image4.src='{$PROJECT_PATH}/img/gr/mail.png'">
            <img src="{$PROJECT_PATH}/img/gr/mail.png" name="image4"></a>
        </li>
<!--         <li title="">
            <a href="{$PROJECT_PATH}/pages/my_albums.php?id={$ProfileAuth->ID}" 
            onmouseover="document.image6.src='{$PROJECT_PATH}/img/gr/pho2.png'" 
            onmouseout="document.image6.src='{$PROJECT_PATH}/img/gr/phot.png'">
            <img src="{$PROJECT_PATH}/img/gr/phot.png" name="image6"></a>
        </li> -->
        <li title="Настройки">
            <a href="{$PROJECT_PATH}/pages/my_settingsava.php?id={$ProfileAuth->ID}" 
            onmouseover="document.image7.src='{$PROJECT_PATH}/img/gr/sec2.png'" 
            onmouseout="document.image7.src='{$PROJECT_PATH}/img/gr/sec.png'">
            <img src="{$PROJECT_PATH}/img/gr/sec.png" name="image7"></a>
        </li>
    </ul>
  <!-- end #sidebar1 --></div>