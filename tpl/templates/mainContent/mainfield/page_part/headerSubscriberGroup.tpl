<div class="friendsnum"
     id="header{if $headerType eq 1}Subscriptions{/if}{if $headerType eq 2}Subscribers{/if}">
  <span style="color: #8eb4e3">
    {if $headerType eq 1}
    Подписки (<span class="countSpan" id="mySubscriptionsSpan">{$allSubscribers.countsubscriptions}</span>)
    {/if}
    {if $headerType eq 2}
    Подписчики (<span class="countSpan" id="mySubscribersSpan">{$allSubscribers.countsubscribers}</span>)
    {/if}
  </span>
  <span class="groupExpand" 
        id="showAll_{if $headerType eq 1}Subscriptions{/if}{if $headerType eq 2}Subscribers{/if}" 
        {if $headerType eq 1}{if $allSubscribers.countsubscriptions <= $SETTING_COUNT_FIRST_LOAD_SUBSCRIBERS}style="display:none;"{/if}{/if}
        {if $headerType eq 2}{if $allSubscribers.countsubscribers <= $SETTING_COUNT_FIRST_LOAD_SUBSCRIBERS}style="display:none;"{/if}{/if}
        onclick="Subscriber.expandMinimizeSubsribersUI({if $headerType eq 1}1{/if}{if $headerType eq 2}2{/if});">
    Показать всех
  </span>
</div>