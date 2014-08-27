<div class="mainfield"> 
    <div class="content">
        <div class="messagePosted">       
            <div class="sender">
                <div class="avatar">
                    <a href="{if isset($currentMessage.partnerProfileLink)}{$PROJECT_PATH}{$currentMessage.partnerProfileLink}{/if}">
                        <img id="partnerAva" src="{$PROJECT_PATH}{$currentMessage.partnerAvatarPath}"/>
                    </a>
                </div>
                <div class="choose">
                    <select id="friendSelectBox" onchange="Messages.changeRecepient();" onfocus="Messages.loadFriends();">
                        <!-- onchange="Messages.prepareSendMessage('{$row.idsender}');" onfocus="Messages.loadFriends('{$row.idsender}', '{$row.idrec}');" -->
                            {if isset($currentMessage.partnerFI)}
                            <option value="{$currentMessage.partnerID}">{$currentMessage.partnerFI}</option>
                            {else}
                            <option value="0" selected="selected">Выберите друга...</option>                            
                            {/if}
                    </select>
                </div>
                <div class="sendDate">
                     {if isset($currentMessage.date)}
                         <span>{$currentMessage.date}</span>
                    {/if}
                </div>   
            </div>
            
            <div class="messageText">
            {if isset($currentMessage.text)}
            <p>
                {$currentMessage.text}
            </p>
            {/if}
            </div>
            
        </div>
        <div class="typing">
            <div id="txtArea" contenteditable="true" autofocus></div><!--class="expanding"-->
        </div>
        <div id="informationMsg" style="float: left;width: 725px;padding-top: 5px;"></div>
        <div id="btnNewMsg" class="presstosend" onclick="Messages.addMessage({if isset($currentMessage.partnerID)}'{$currentMessage.partnerID}'{/if});">
                <span  onmouseover="document.img1.src='../img/12.png'" onmouseout="document.img1.src='../img/sendMessage.png'"><img  src="../img/sendMessage.png" name='img1'>Отправить</span>
        </div>    
    </div><!--end content-->   
</div> <!--end mainfield-->