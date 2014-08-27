<script id="subscriberHeader" type="text/js">
<div class="friendsnum"
     <%if(headerType == 1){ %>id="headerSubscriptions"
     <%}%>
     <%if(headerType == 2){ %>id="headerSubscribers"
     <%}%>
  <span style="color: #8eb4e3">
    <%if(headerType == 1){ %>
    Подписки (<span class="countSpan" id="mySubscriptionsSpan"><%=countsubscriptions%></span>)
    <%}%>
    <%if(headerType == 2){ %>
    Подписчики (<span class="countSpan" id="mySubscribersSpan"><%=countsubscribers%></span>)
    <%}%>
  </span>
  <span class="groupExpand" 
        <%if(headerType == 1){ %>
            id="showAll_Subscriptions"
        <%}%>
        <%if(headerType == 2){ %>
            id="showAll_Subscribers"
        <%}%> 
        <%if(headerType == 1){ %><%if(countsubscriptions <= SETTING_COUNT_FIRST_LOAD_SUBSCRIBERS){ %>style="display:none;"<%}%><%}%>
        <%if(headerType == 2){ %><%if(countsubscribers <= SETTING_COUNT_FIRST_LOAD_SUBSCRIBERS){ %>style="display:none;"<%}%><%}%>
        onclick="Subscriber.expandMinimizeSubsribersUI(<%if(headerType == 1){ %>1<%}%><%if(headerType == 2){ %>2<%}%>);">
    Показать всех
  </span>
</div>
</script>