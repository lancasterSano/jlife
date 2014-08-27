{* Шапка контента: ФИО, подложка, аватарка, менюшка и т.д. *}
{* Требования : 
    $ProfileLoad - пользователь, страницу пользователя которого мы грузим
    $ProfileAuth
    $PROJECT_PATH
    $freind -друзья ли ProfileAuth vs ProfileLoad
*}
<div class="union">
    <div class="left_side">
        <div class="switch">
            <div class="subl">
                <ul id="lineTabs">
                    {foreach key=outer item=School from=$Schools name=Schools}
                        {if ($numTab == $School.id)}
                            {assign var="CURSCHOOLID_BOOL" value=true}
                        {else} {assign var="CURSCHOOLID_BOOL" value=false} {/if}
                        <li id="school_{$School.id}_{$ProfileLearner->idLearner}">
                            <a href="./study.php?school={$School.id}" {if $CURSCHOOLID_BOOL == true} class="active" {/if}>{$School.name}</a>
                        </li>
                    {/foreach}
                </ul>
            </div>
            {$topUnionSub_subl}
        </div>
    </div> 
</div><!-- end union-->