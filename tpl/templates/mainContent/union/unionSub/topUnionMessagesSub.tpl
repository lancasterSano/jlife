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
                                    <a id="inboxMsg" href="./messages.php?type=inbox" {if $numTab==21}class="active"{/if}>
                                        Входящие (<span id="countinboxMessagesSpan" class="messageTabSpan">{$countInboxMsg}</span>)
                                    </a>
                                </li>
                                <li><a id="outboxMsg" href="./messages.php?type=outbox"{if $numTab==22}class="active"{/if}>
                                        Исходящие (<span id="countoutboxMessagesSpan" class="messageTabSpan">{$countOutboxMsg}</span>)
                                    </a>
                                </li>
                            </ul>
                            <div class="messageAllButtons">
                                <div id="deleteMarked" title="Удалить отмеченные" onclick="Messages.deleteMarked();"></div>
                                <div id="markAll" title="Отметить все/снять выделение" onclick="Messages.markAll();"></div>
                                <div id="newMsgBtn" title="Написать новое сообщение"onclick="Messages.newMessage();"></div>
                            </div>
                            <div id="countMessagesInfo"></div>
                	    </div>
                    </div>
                </div>
            </div>