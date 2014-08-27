<script id="notice-h" type="text/js">
	<span class="title"><%=title%></span>
</script>

<script id="notice-hb" type="text/js">
	<span class="title"><%=title%></span>
	<span class="text"><%=text%></span>
</script>

<script id="notices" type="text/js">
	<%
		var style = null;
		if(type==2) style='error';
		else if(type==3) style='warning';
		else if(type==4) style='success';
		else style='info';
	%>
	<div class="confirmationMSG <%=style%>">
	<% for(var i=0;i<notices.length;i++) { %>
		<%=notices[i]%>
	<% } %>
	</div>
</script>