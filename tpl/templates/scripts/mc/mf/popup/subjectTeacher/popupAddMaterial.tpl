<script id="popupAddMaterial" type="text/js">
    <div class="addMatCont">
        <div class="lblItemV1">
            <span>Выберите предмет</span>
            <div class="NewSelect">
                <select id="selectSubjects" onchange="subjectTeacher.loadLevelsPopupUI(<%=idTeacher%>);">
                    <option disabled="" selected="" value="0">---</option>
                    <% for(var i in subjects) { %>
                    <option value="<%=subjects[i].id%>"><%=subjects[i].name%></option>
                    <% } %>
                </select>
            </div>
        </div>
        <div class="lblItemV2">
            <span>Выберите класс</span>
            <div class="NewSelect">
                <select id="selectLevels" onchange="subjectTeacher.loadComplexitiesPopupUI(<%=idTeacher%>);">
                    <option disabled="" selected="" value=""></option>
                </select>
            </div>
        </div>
        <div class="lblItemV3">
            <span>Выберите сложность</span>
            <div class="NewSelect">
                <select id="selectComplexities">
                    <option disabled="" selected="" value=""></option>
                </select>
            </div>
        </div>
        <div class="manage">
            <span class="actionItem" onclick="subjectTeacher.hidePopup();">Отменить</span>
            <span class="actionItem" onclick="subjectTeacher.addMaterialUI(<%=idTeacher%>,<%=idschool%>);">Создать</span>
        </div>
    </div>
</script>