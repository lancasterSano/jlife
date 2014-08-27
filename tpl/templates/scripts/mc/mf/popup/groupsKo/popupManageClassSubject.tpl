<script id="popupManageClassSubject" type="text/js">
    <div class="displayMenu" style="display:block; width: 180px;">
        <span class="manageClassSubjectSpan" 
              id="addGroupClassSubject_<%=idClass%>_<%=idSubject%>" 
              onclick="groupsKo.drawAddNewGroup(<%=idClass%>, <%=idSubject%>, <%=idSchool%>);">
            Добавить группу
        </span>
        <span class="manageClassSubjectSpan" 
              id="editClassSubjectSpan_<%=idClass%>_<%=idSubject%>" 
              onclick="groupsKo.editClassSubject(<%=idClass%>, <%=idSubject%>,<%=idGroup%>, <%=idTeacher%>, <%=idSchool%>);">
            Редактировать
        </span>
        <span class="manageClassSubjectSpan" 
              id="delClassSubjectSpan_<%=idClass%>_<%=idSubject%>" 
              onclick="groupsKo.deleteClassSubject(<%=idClass%>, <%=idSubject%>,<%=idGroup%>);">
            Удалить
        </span>
    </div>
</script>