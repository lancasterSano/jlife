<div class="mainfield">    
    <div class="content">
        <div id="messages_{$idOwner}">
        {if isset($isMsgInActiveTab)}
        {foreach $messages as $message}
        <div id="message_{$idOwner}_{$message.id}" class="{if $message.state > 0}messageUnR{else}message{/if}" onclick="location.href='{$PROJECT_PATH}{$message.expandLink}';"><!--class "message" if read, "messageUnR" if unread-->     
            <div class='avatar'>
                <a href='{$PROJECT_PATH}{$message.partnerProfileLink}' onclick="event.stopPropagation();"><img src='{$PROJECT_PATH}{$message.partnerAvatarPath}'/></a>
            </div>   
            <div class='messageContent'>    
                <div class='senderName'>
                    <a href='{$PROJECT_PATH}{$message.partnerProfileLink}' onclick="event.stopPropagation();"><span>{$message.partnerFI}</span></a>
                    <span class='deleteMes' onclick="Messages.deleteMessage('{$message.id}');event.stopPropagation();">Удалить</span>
                </div>
                <div class='messageText'>
                    <p><a href="{$PROJECT_PATH}{$message.expandLink}" onclick="event.stopPropagation();">{$message.text}</a></p>
                </div>
                <div class='forAct'>
                    <input type='checkBox' name='{$message.id}' onclick="event.stopPropagation();"/>
                </div>
                <div class='dateSent'>
                    <span>{$message.date}</span>
                </div>        
            </div>      
        </div><!--end messageclass -->
        {/foreach}
        {else}
            {$notices['MI_NO_MESSAGES']['type'] = 1}
            {$notices['MI_NO_MESSAGES']['messages'][]['title'] = $smarty.const.MI_NO_MESSAGES_TEXT}
            <div class='conformation'>
                {include file="../general/notice.tpl" IDNOTICE="MI_NO_MESSAGES"}
            </div>
        {/if}
        </div>
         <!-- Блок ЕЩЕ... -->
        <div class="warningMessage" id="loadMessages_{$idOwner}"
             {if $countActiveTabMsg <= $SETTING_COUNT_FIRST_LOAD_MESSAGES}style="display: none;"
             {/if}
             onmouseover="$(this).css('background','#ececec');" 
             onmouseout="$(this).css('background','#fff');" onclick="Messages.loadMessagesUI('{$SETTING_COUNT_CONTINUATION_LOAD_MESSAGES}');">
            <div class="centeredm">
                <span>Выгрузить еще...</span>
            </div>
        </div>
    </div><!--end content-->
</div> <!--end mainfield-->
