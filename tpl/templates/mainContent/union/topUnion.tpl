{* Шапка контента: ФИО, подложка, аватарка, менюшка и т.д. *}
{* Требования : 
    $ProfileLoad - пользователь, страницу пользователя которого мы грузим
    $PROJECT_PATH
*}
            <div class="subl">
                <ul id="lineTabs" >
                    <li ><a id="p_notes_{$ProfileLoad->ID}" href="./index.php?id={$ProfileLoad->ID}"
                            {if $numTab == 11}class="active"{/if}>Все заметки</a>
                    </li>
                    <li ><a id="p_my_notes_{$ProfileLoad->ID}" href="./index.php?id={$ProfileLoad->ID}&m=1"
                            {if $numTab == 12 }class="activeHREF"{/if}>Заметки {$ProfileLoad->F()} </a>
                    </li>
                </ul>
            </div>
        </div>
    </div> 
    <div class="right_side">
        <img id="ava" src="{$PROJECT_PATH}{$ProfileLoad->ProfilePathAvatar()}" />
    </div>
</div><!-- end union-->