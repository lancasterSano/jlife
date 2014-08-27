	{* Шапка контента: ФИО, подложка, аватарка, менюшка и т.д. *}
{* Требования : 
    $ProfileLoad - пользователь, страницу пользователя которого мы грузим
    $PROJECT_PATH
*}      
    
<div class="subl">
    <ul id="lineTabs">
        <li><a href="{$PROJECT_PATH}/pages/register.php" {if $numTab==11}class="active"{/if}>Регистрация профиля</a></li>
        <li><a href="{$PROJECT_PATH}/pages/god/goduser.php" {if $numTab==12}class="active"{/if}>БОГ</a></li>
        <li><a href="{$PROJECT_PATH}/pages/contacts.php" {if $numTab==13}class="active"{/if}>ЗАВУЧ</a></li>
        <li><a href="{$PROJECT_PATH}/pages/contacts.php" {if $numTab==14}class="active"{/if}>ШКОЛА</a></li>
    </ul>
</div>
            
  