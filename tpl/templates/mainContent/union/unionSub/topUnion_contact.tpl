	{* Шапка контента: ФИО, подложка, аватарка, менюшка и т.д. *}
{* Требования : 
    $ProfileLoad - пользователь, страницу пользователя которого мы грузим
    $PROJECT_PATH
*}      
    
<div class="subl">
    <ul id="lineTabs">
        <li><a href="./contacts.php" {if $numTab==21}class="active"{/if}>Контакты</a></li>
        <li><a href="./contactsadd.php" {if $numTab==22}class="active"{/if}>Заявки</a></li>
        <li><a href="./contactssub.php" {if $numTab==23}class="active"{/if}>Подписчики</a></li>
        <li><a href="./contactssearch.php" {if $numTab==24}class="active"{/if}>Поиск</a></li>
    </ul>
</div>
            
  