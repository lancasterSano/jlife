<div class="{if $ProfileAuth->ID == $ProfileLoad->ID}reciverSmall{else}reciverSmallS{/if}" id="contact_{$ProfileLoad->ID}_{$friend.id}">
    <div class="reciverImgsmall" onclick="Contact.gotoContact({$friend.id});"><img src="..{$friend.avaPath}" /></div>
    <div class="reciverNamesmall" onclick="Contact.gotoContact({$friend.id});"><p>{$friend.FI}</p><p id="reciverClass"></p></div>
    {if $ProfileAuth->ID == $ProfileLoad->ID}
    <div id="friendButton_{$group.id}_{$friend.id}" class="reciverManage" 
         onclick="Contact.showContactPopup($(this), {$group.id}, {$friend.id});"></div>
    {else}
    <div id="friendButton_{$group.id}_{$friend.id}" class="reciverManage"></div>
    {/if}
</div>