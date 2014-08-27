<script id="subscriber" type="text/js">
    <div class="reciverSmallReq" id="contact_<%=PM.idLoad%>_<%=contact.id%>_<%=contact.type%>">
    <div class="reciverImgsmall">
      <a href="index.php?id=<%=contact.id%>"><img src="..<%=contact.avaPath%>" /></a>
    </div>
    <div class="reciverNamesmall">
      <a href="index.php?id=<%=contact.id%>"><p><%=contact.FI%></p></a>
      <p id="reciverClass"></p>
      <% if(PM.idAuth == PM.idLoad){ %>
         <% if(contact.type == 1){ %>
          <p onclick="Subscriber.delSubscriberUI(<%=contact.id%>); event.stopPropagation();" 
             class="manageAdd">Отписаться
          </p>
        <% } %>
        <% if(contact.type == 2){ %>
          <p id="manageSubscriber_<%=contact.id%>_2"
             onclick="Subscriber.addSubscriberFromSubscribersUI(<%=contact.id%>); event.stopPropagation();"
             class="manageAdd">Подписаться</p>
        <% } %>
        <% if(contact.type == 3){ %>
          <p id="manageSubscriber_<%=contact.id%>_2"
             onclick="Subscriber.addSubscriberFromSubscribersUI(<%=contact.id%>); event.stopPropagation();" 
             style="display:none;"
             class="manageAdd">Подписаться</p>
       <% } %>
    <% } %>
    </div>
  <div class="reciverManageSubscriber"></div>
</div>
</script>