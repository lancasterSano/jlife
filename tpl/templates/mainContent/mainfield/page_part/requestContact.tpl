<div class="reciverSmallReq" id="contact_{$ProfileAuth->ID}_{$request.id}">
    <div class="reciverImgsmall"><a href="index.php?id={$request.id}"><img src="..{$request.avaPath}" /></a></div>
    <div class="reciverNamesmall">
      <a href="index.php?id={$request.id}"><p>{$request.FI}</p></a>
      <p id="reciverClass"></p>
      {if $request.type eq 1}
        <p onclick="Requests.cancelOutboxRequestUI({$request.id});" class="manageAdd">
          Отменить
        </p>
      {/if}
      {if $request.type eq 2}
        <p id="add_{$request.id}" 
          class="manageAdd acceptFriendRequest" 
          onclick="Friend.requestAddFriend($(this), {$request.id});">
          Добавить
        </p>
        <p id="notNow_{$request.id}" onclick="Requests.watchRequestUI({$request.id});" class="manageNoAdd">
          Не сейчас
        </p>
      {/if}
      {if $request.type eq 3}
        <p id="add_{$request.id}" 
          class="manageAdd acceptFriendRequestOld" 
          onclick="Friend.requestAddFriend($(this), {$request.id});">
          Добавить
        </p>
      {/if}
    </div>
    <div class="reciverManage"></div>
</div>