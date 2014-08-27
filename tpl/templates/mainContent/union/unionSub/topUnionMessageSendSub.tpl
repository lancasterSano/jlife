{* Шапка контента: ФИО, подложка, аватарка, менюшка и т.д. *}
{* Требования : 
    $ProfileLoad - пользователь, страницу пользователя которого мы грузим
    $PROJECT_PATH
*}
<div class='union'>
    <div class='left_side'>
        <div class="switch">
            <div class="subl">
                <ul id="lineTabs" >
                    <li>
                        <a href="./messages.php?id={$ProfileAuth->ID}&type=inbox" {if $numTab==21}class="active"{/if}>
                            <span class="messageSendTabSpan">Входящие(</span>
                            <span class="messageSendTabSpan">{$countInboxMsg}</span>
                            <span class="messageSendTabSpan">)</span>
                        </a>
                    </li>
                    <li>
                        <a href="./messages.php?id={$ProfileAuth->ID}&type=outbox"{if $numTab==22}class="active"{/if}>
                            <span class="messageSendTabSpan">Исходящие(</span>
                            <span class="messageSendTabSpan">{$countOutboxMsg}</span>
                            <span class="messageSendTabSpan">)</span>
                        </a>
                    </li>
                    <li>
                        <a href="" {if $numTab==23}class="active"{/if}>
                            Сообщение
                        </a>
                    </li>
                </ul>
                </div>
        </div>
    </div>
</div>
