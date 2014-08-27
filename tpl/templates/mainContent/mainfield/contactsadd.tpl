<div class="mainfield">
  <div class="searchCont">
    <div class="findCont">
      <input id="searchRequestsInput" type="text" value=" Поиск контакта..."  
             onfocus="if(this.value == ' Поиск контакта...')this.value = '';" 
             onblur="if (this.value == '') this.value =' Поиск контакта...';" 
             onkeyup="Requests.searchRequestsUI(event);" />
    </div>
    <div class="searchManage" onclick="Requests.searchRequestsUI();">
      <img src="../img/searchcontact.png"/>
    </div>
  </div>
  <div id="contacts_{$ProfileAuth->ID}" class="content">
  {assign var="headerType" value="1"}
  {include file='./page_part/headerRequestGroup.tpl'}
  {if $allRequests.countoutbox > 0}
    <div class="friend" id="outboxRequests_std_part">
    {foreach $allRequests.outbox.requests as $request}
      {include file='./page_part/requestContact.tpl'}
    {/foreach}
    </div>
  {else}
{*    <div class="friend" id="outboxRequests_empty">
      <p id="norequests">В группе <b>Исходящие</b> нет контактов.</p>
    </div>*}
    {$notices['MI_CONTACTS_USER_HAS_NO_OUTBOX_REQUESTS']['type'] = 1}
    {$notices['MI_CONTACTS_USER_HAS_NO_OUTBOX_REQUESTS']['messages'][]['title'] = $smarty.const.MI_CONTACTS_USER_HAS_NO_OUTBOX_REQUESTS_TEXT}
    <div class="friend" id="outboxRequests_empty">
      <div class='conformation'>
        {include file="../general/notice.tpl" IDNOTICE="MI_CONTACTS_USER_HAS_NO_OUTBOX_REQUESTS"}
      </div>
    </div>
  {/if}
  {assign var="headerType" value="2"}
  {include file='./page_part/headerRequestGroup.tpl'}
  {if $allRequests.countinbox > 0}
    <div class="friend" id="inboxRequests_std_part">
    {foreach $allRequests.inbox.requests as $request}
      {include file='./page_part/requestContact.tpl'}
    {/foreach}
    </div>
  {else}
    {$notices['MI_CONTACTS_USER_HAS_NO_INBOX_REQUESTS']['type'] = 1}
    {$notices['MI_CONTACTS_USER_HAS_NO_INBOX_REQUESTS']['messages'][]['title'] = $smarty.const.MI_CONTACTS_USER_HAS_NO_INBOX_REQUESTS_TEXT}
    <div class="friend" id="outboxRequests_empty">
      <div class='conformation'>
        {include file="../general/notice.tpl" IDNOTICE="MI_CONTACTS_USER_HAS_NO_INBOX_REQUESTS"}
      </div>
    </div>
  {/if}
  </div><!--end content-->
</div> <!--end mainfield-->
