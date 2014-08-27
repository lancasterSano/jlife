<script id="popupAssignMaterial" type="text/js">
<div class="displayMenu">
    <% for(var i in subgroups) { %>
    <span onclick="subjectTeacher.assignMaterialToGroup(<%=material.id%>, <%=material.notstudy%> ,<%=subgroups[i].id%>,<%=subgroups[i].state%>)">
        <% if(material.notstudy == "1") { %>
            <% if(subgroups[i].hasMaterial) { %>
            Переназначить материал группе <%=subgroups[i].name%>
            <% } else { %>
            Назначить материал группе <%=subgroups[i].name%>
            <% } %>
        <% } else { %>
            <% if(subgroups[i].state) { %>
            Отвязать материал от группы <%=subgroups[i].name%>
            <% } else { %>
            Назначить материал группе <%=subgroups[i].name%>
            <% } %>
        <% } %>
    </span>
    <% } %>
</div>
</script>