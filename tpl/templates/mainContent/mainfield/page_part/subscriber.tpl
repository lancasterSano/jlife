<div class="reciverSmallReq" id="contact_{$ProfileLoad->ID}_{$contact.id}_{$contact.type}">
    <div class="reciverImgsmall">
      <a href="index.php?id={$contact.id}"><img src="..{$contact.avaPath}" /></a>
    </div>
    <div class="reciverNamesmall">
      <a href="index.php?id={$contact.id}"><p>{$contact.FI}</p></a>
      <p id="reciverClass"></p>
      {if $ProfileAuth->ID == $ProfileLoad->ID}
        {if $contact.type eq 1}
          <p onclick="Subscriber.delSubscriberUI({$contact.id}); event.stopPropagation();" 
             class="manageAdd">Отписаться
          </p>
        {/if}
        {if $contact.type eq 2}
          <p id="manageSubscriber_{$contact.id}_{$contact.type}"
             onclick="Subscriber.addSubscriberFromSubscribersUI({$contact.id}); event.stopPropagation();"
             class="manageAdd">Подписаться</p>
        {/if}
        {if $contact.type eq 3}
          <p id="manageSubscriber_{$contact.id}_{$contact.type}"
             onclick="Subscriber.addSubscriberFromSubscribersUI({$contact.id}); event.stopPropagation();" 
             style="display:none;"
             class="manageAdd">Подписаться</p>
        {/if}
      {/if}
    </div>
  <div class="reciverManageSubscriber"></div>
</div>