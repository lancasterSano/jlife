<div class="mainfield">
  <div class="searchCont">
  {if $ProfileAuth->ID == $ProfileLoad->ID}
  {*
   * Комбо-бокс выбора групп, доступен только 
   * если пользователь зашёл на страницу своих друзей.
   * У пользователя нет возможности посмотреть группы других 
   * пользователей или произвести их фильтрацию.
   *}
    <div class="filter">
      <div class="styled-select2">
        <select onchange="Contact.selectGroup(this.value);" id="groupSelect">
          <option value="0">Все группы</option>
          {foreach item=group from=$groups}
            <option  value="{$group.id}" >{$group.name}</option>
          {/foreach}
        </select>
      </div>
    </div>
  {/if}
    <div class="findCont">
      <input id="searchInput" type="text" value=" Поиск контакта..."  
             onfocus="if(this.value == ' Поиск контакта...') this.value = '';" 
             onblur="if(this.value == '') this.value =' Поиск контакта...';" 
             onkeyup="Contact.searchFriendsUI(event);"/>
    </div>
    <div class="searchManage" onclick="Contact.searchFriendsUI();">
      <span class="searchSpan"><img src="../img/searchcontact.png" /></span>
    </div>
  </div>
  <div id="contacts_{$ProfileLoad->ID}" class="content">
  {if $ProfileAuth->ID == $ProfileLoad->ID} {* Мы смотрим своих друзей *}
    {foreach $groups as $group}
      {include file='./page_part/headerContactGroup.tpl'}
      {if $group.countfriends > 0}
        <div class="friend" id="group_{$ProfileLoad->ID}_{$group.id}_{$locationmode}_{$showmode}">
        {foreach $group.friends as $friend}
          {include file='./page_part/contact.tpl'}
        {/foreach}
        </div>
      {else}
        {$notices['MI_CONTACTS_USER_HAS_NO_FRIENDS_IN_GROUP']['type'] = 1}
        {$notices['MI_CONTACTS_USER_HAS_NO_FRIENDS_IN_GROUP']['messages'][]['title'] = preg_replace("/%GROUPNAME/", $group.name, $smarty.const.MI_CONTACTS_USER_HAS_NO_FRIENDS_IN_GROUP_TEXT)}
        <div class="emptyFriends" id="group_{$ProfileLoad->ID}_{$group.id}_std_empty">
          <div class='conformation'>
            {include file="../general/notice.tpl" IDNOTICE="MI_CONTACTS_USER_HAS_NO_FRIENDS_IN_GROUP"}
          </div>
        </div>
      {/if}
    {foreachelse}
      {$notices['MI_CONTACTS_USER_HAS_NO_GROUPS']['type'] = 1}
      {$notices['MI_CONTACTS_USER_HAS_NO_GROUPS']['messages'][]['title'] = $smarty.const.MI_CONTACTS_USER_HAS_NO_GROUPS_TEXT}
      <div class="emptyFriends">
          <div class='conformation'>
            {include file="../general/notice.tpl" IDNOTICE="MI_CONTACTS_USER_HAS_NO_GROUPS"}
          </div>
      </div>
    {/foreach}
  {else} {* Мы смотрим чужим друзей *}
    {foreach $groups as $group}
      {include file='./page_part/headerContactGroup.tpl'}
      {if $group.countfriends > 0}
        <div class="friend" id="group_{$ProfileLoad->ID}_{$countFriends}">
        {foreach $friends as $friend}
          {include file='./page_part/contact.tpl'}
        {/foreach}
        </div>
      {else}
      {$notices['MI_CONTACTS_FRIEND_HAS_NO_FRIENDS']['type'] = 1}
      {$notices['MI_CONTACTS_FRIEND_HAS_NO_FRIENDS']['messages'][]['title'] = $smarty.const.MI_CONTACTS_FRIEND_HAS_NO_FRIENDS_TEXT}
        <div class="emptyFriends">
          <div class='conformation'>
            {include file="../general/notice.tpl" IDNOTICE="MI_CONTACTS_FRIEND_HAS_NO_FRIENDS"}
          </div>
        </div>
      {/if}
    {/foreach}
  {/if}
  </div>
</div> <!--end mainfield-->