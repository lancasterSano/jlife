<div class="mainfield"> 
  <div class="searchCont">
    <div class="findCont">
      <input id="searchSubscribersInput" type="text" value=" Поиск контакта..."  
             onfocus="if(this.value == ' Поиск контакта...')this.value = '';" 
             onblur="if (this.value == '') this.value =' Поиск контакта...';" 
             onkeyup="Subscriber.searchSubscribersUI(event);" />
    </div>
    <div class="searchManage" onclick="Subscriber.searchSubscribersUI();">
      <img src="../img/searchcontact.png"/>
    </div>
  </div>
  <div id="contacts_{$ProfileLoad->ID}" class="content">
  {assign var="headerType" value="1"}
  {include file='./page_part/headerSubscriberGroup.tpl'}
  {if $allSubscribers.countsubscriptions == 0}
      {$notices['MI_SUBSCRIBERS_NO_SUBSCRIPTIONS']['type'] = 1}
      {$notices['MI_SUBSCRIBERS_NO_SUBSCRIPTIONS']['messages'][]['title'] = $smarty.const.MI_SUBSCRIBERS_NO_SUBSCRIPTIONS_TEXT}
    <div class="friend" id="subscriptions_std_empty">
        <div class='conformation'>
            {include file="../general/notice.tpl" IDNOTICE="MI_SUBSCRIBERS_NO_SUBSCRIPTIONS"}
        </div>
    </div>
  {else}
    <div class="friend" id="subscriptions_std_part">
    {foreach $allSubscribers.subscriptions as $contact}
      {include file='./page_part/subscriber.tpl'}
    {/foreach}
    </div>				 			 
  {/if}
  {assign var="headerType" value="2"}
  {include file='./page_part/headerSubscriberGroup.tpl'}
  {if $allSubscribers.countsubscribers == 0}
      {$notices['MI_SUBSCRIBERS_NO_SUBSCRIBERS']['type'] = 1}
      {$notices['MI_SUBSCRIBERS_NO_SUBSCRIBERS']['messages'][]['title'] = $smarty.const.MI_SUBSCRIBERS_NO_SUBSCRIBERS_TEXT}
    <div class="friend" id="subscribers_std_empty">
      <div class='conformation'>
        {include file="../general/notice.tpl" IDNOTICE="MI_SUBSCRIBERS_NO_SUBSCRIBERS"}
      </div>
    </div>
  {else}
    <div class="friend" id="subscribers_std_part">
    {foreach $allSubscribers.subscribers as $contact}
      {include file='./page_part/subscriber.tpl'}
    {/foreach}
    </div>				 			 
  {/if}	
  </div><!--end content-->   
</div> <!--end mainfield-->
	
	