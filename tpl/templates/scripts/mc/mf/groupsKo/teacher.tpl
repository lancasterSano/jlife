<script id="teacherScript" type="text/js">
<tr class="default" style="display:none;" id="teacher_<%=teacher.id%>">
    <td>
        <div class="roll" onclick="groupsKo.loadTeacherSubjectsUI(<%=teacher.id%>, <%=idSchool%>);"></div>
        <div class="teacherNum"><span><%=teacher.number%>.</span></div>
        <div class="teachersFIO"><span><%=teacher.lastname%> <%=teacher.firstname%> <%=teacher.middlename%></span></div>
        <div class="stNumb"><span><%=teacher.category%></span></div>
        <div class="help" id="teacherManage_<%=teacher.id%>" onclick="groupsKo.showManageTeacherPopup($(this), <%=teacher.id%>)"></div>
    </td> 
</tr>
</script>