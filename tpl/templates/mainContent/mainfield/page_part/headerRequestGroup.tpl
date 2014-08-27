<div class="friendsnum" 
     id="header{if $headerType eq 1}Outbox{/if}{if $headerType eq 2}Inbox{/if}Requests">
  <span style="color: #8eb4e3">
    {if $headerType eq 1}
    Исходящие (<span class="countSpan" id="countOutboxRequestsSpan">{$allRequests.countoutbox}</span>)
    {/if}
    {if $headerType eq 2}
    Входящие (<span class="countSpan" id="countInboxRequestsSpan">{$allRequests.countinbox}</span>)
    {/if}
  </span>
  <span class="groupExpand" 
        id="showAll_{if $headerType eq 1}outbox{/if}{if $headerType eq 2}inbox{/if}" 
        {if $headerType eq 1}{if $allRequests.countoutbox <= $SETTING_COUNT_FIRST_LOAD_REQUESTS}style="display:none;"{/if}{/if}
        {if $headerType eq 2}{if $allRequests.countinbox <= $SETTING_COUNT_FIRST_LOAD_REQUESTS}style="display:none;"{/if}{/if}
        onclick="Requests.expandMinimizeRequestsUI({if $headerType eq 1}1{/if}{if $headerType eq 2}2{/if});">
    Показать всех
  </span>
</div>