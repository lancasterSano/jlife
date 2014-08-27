{* Шапка контента: ФИО, подложка, аватарка, менюшка и т.д. *}
{* Требования : 
    $ProfileLoad - пользователь, страницу пользователя которого мы грузим
    $ProfileAuth
    $PROJECT_PATH
    $freind -друзья ли ProfileAuth vs ProfileLoad
*}
<div class="union">
    <div class="left_side">
        <div class="name">
            <div class='fio'>
                <span {if mb_strlen($ProfileLoad->FI()) > 28} class="fiosmall" {/if} title="{$ProfileLoad->FI()|default:"ФИ"}" alt="{$ProfileLoad->FI()|default:"ФИ"}">
                    {if mb_strlen($ProfileLoad->FI()) > 28} {$ProfileLoad->FI(28)|default:"ФИ"} {else} {$ProfileLoad->FI()|default:"ФИ"} {/if}
                </span>
            </div>
            {if $ProfileLoad->ID != $ProfileAuth->ID}
                <div class="pageBut">
                    <div id="subscriber" onclick="Subscriber.addSubscriberFromIndexUI({$ProfileLoad->ID});" class="{if $subscribe==0}subscribe{else if $subscribe>0}unsubscribe{/if}" ></div>
                    <div id="friends_{$ProfileLoad->ID}" 
                        onclick="Friend.requestAddFriend($(this), {$ProfileLoad->ID});"
                        class="{if $friend==null}sendAddRequest{else if $friend==0}editFriendRequest{else if $friend==1}editAddRequest{else if $friend==2}acceptFriendRequest{else if $friend==3}acceptFriendRequestOld{/if}" >
                    </div>
                    <a href="messageSend.php?rec={$ProfileLoad->ID}&from=new"><div id="messagner_{$ProfileLoad->ID}" class="message"></div></a>
                </div>      
            {/if}
           <img class="pic" src="{$PROJECT_PATH}{$ProfileLoad->ProfilePathHeadband()}" />
        </div>
        <div class="switch">
            <div class="subl">
                <ul id="lineTabs">
                    <li><a href="./index.php?id={$ProfileLoad->ID}" {if $numTab == 11 || $numTab == 12} class="active" {/if}>Заметки</a></li>
                    <li><a href="./personal.php?id={$ProfileLoad->ID}" {if $numTab == 41 || $numTab == 42} class="active" {/if}>Общее</a></li>
                    <!-- <li><a href="./albums.php?id={$ProfileLoad->ID}" {if $numTab == 51 || $numTab == 52 || $numTab == 53 } class="active" {/if}>Фотографии</a></li> -->
                </ul>
            </div>
            {$topUnionSub_subl}
        </div>
    </div> 
    <div class="right_side">
        <img id="ava" src="{$PROJECT_PATH}{$ProfileLoad->ProfilePathAvatar(1, true)}" />
    </div>
</div><!-- end union-->