{* Шапка контента: ФИО, подложка, аватарка, менюшка и т.д. *}
{* Требования : 
    $ProfileLoad - пользователь, страницу пользователя которого мы грузим
    $PROJECT_PATH
*}
<div class="subl">
    <ul id="lineTabs" >
        <li ><a href="./{if $pageFormat == 2}my_{/if}personal.php?id={$ProfileLoad->ID}" {if $numTab==41}class="active"{/if}>Общее</a></li>
        <!-- <li ><a href="#"{if $numTab==42}class="active"{/if}>Достижения</a></li> -->
    </ul>
</div>