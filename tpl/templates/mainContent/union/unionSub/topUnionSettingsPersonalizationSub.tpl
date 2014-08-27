{* Шапка контента: ФИО, подложка, аватарка, менюшка и т.д. *}
{* Требования : 
    $ProfileLoad - пользователь, страницу пользователя которого мы грузим
    $PROJECT_PATH
*}
<div class="subl">
    <ul id="lineTabs">
        <li>
            <a href="./spersonalization.php" {if $numTab == 1} class="active" {/if}>Персонализация</a>
        </li>
        <li>
            <a href="./ssecurity.php" {if $numTab == 2} class="active" {/if}>Безопасность</a>
        </li>
        {if isset($hexverify)}
		<li>
            {if !$ProfileAuth->private}
                <a href="./ssecurity.php?asec={$hexverify}" {if $numTab == 3} class="active" {/if}>Подтверждение создания почтового ящика</a>
            {else}
                <a href="./ssecurity.php?asec={$hexverify}" {if $numTab == 3} class="active" {/if}>Подтверждение смены почтового ящика</a>
            {/if}
        </li>
        {/if}
        {if count($ProfileLoad->getRolesByRole(1))}
            <li>
                <a href="./regresponsible.php" {if $numTab == 4} class="active" {/if}> Школа {if $Schools!==null}№ {$Schools[$LearnerClass->idSchool]['number']}{/if}</a>
            </li>
        {/if}
    </ul>
</div>