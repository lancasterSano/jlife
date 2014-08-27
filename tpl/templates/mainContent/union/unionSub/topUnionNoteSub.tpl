{* Шапка контента: ФИО, подложка, аватарка, менюшка и т.д. *}
{* Требования : 
    $ProfileLoad - пользователь, страницу пользователя которого мы грузим
    $PROJECT_PATH
*}
<div class="subl">
    <ul id="lineTabs" >
        <li ><a id="p_notes_{$ProfileLoad->ID}" href="{$YII_APP->createUrl('../pages/index.php')}?id={$ProfileLoad->ID}"
                {if $numTab == 11}class="active"{/if}>Все заметки</a>
        </li>
        <li ><a id="p_my_notes_{$ProfileLoad->ID}" href="{$YII_APP->createUrl('../pages/index.php')}?m=1&id={$ProfileLoad->ID}"
                {if $numTab == 12 }class="active"{/if}>Заметки {$ProfileLoad->F()} </a>
        </li>
    </ul>
</div>