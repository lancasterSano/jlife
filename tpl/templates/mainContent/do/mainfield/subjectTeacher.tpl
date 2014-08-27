<div class="mainfield">
    <div class="content">
    {if $isSubjectsAssigned}
                <div style="display: none;" class="subjSearch">
            <a href="">
                <img src="../../img/searchcontact.png" />
                <span>Поиск</span>
            </a>
        </div>
        
        <div class="subjTable">
            <div class="passedSubj">
                <div class="passedtitle">
                    <span>Текущие материалы</span>
                </div>
            </div>
        {assign var="i" value="1"}
        {if $isMaterials}
        {foreach $materialsActive as $subject => $subjectDetails}
            {assign var="j" value="1"}
            {if isset($subjectDetails.levels)}
            <div class="subj{if $i%2 eq 1}Left{else}Right{/if}">
                <table id="materialsList_{$subjectDetails.color}" class="subjItem" cellspacing="0" cellpadding="0"> {*Таблица с материалом для классов определённого level по определенному предмету*}
                    <tr style="background: {$subjectDetails.color};" align="center">
                        <td colspan="7">
                            <span class="subjectname" style="color: #000;font-weight: bold;">{*Название предмета-->*}{$subject}</span>
                            <a style="display: none;" href="">
                                <img src="../../img/secure.png" />
                            </a>
                        </td>
                    </tr>

                    <tr class="colsNamed" >
                        <td>№</td>
                        <td>Класс</td>
                        <td>§</td>
                        <td title="Разделы">Разд.</td>
                        <td>Классы</td>
                        <td colspan="2">Сложность</td>
                    </tr>
                    {foreach $subjectDetails.levels as $level => $materials}
                    {foreach $materials as $material}
                    <tr class="defaultA">
                         <td onclick="location.href='cabinet.php?school={$ProfileTeacher->idSchool}&type=s&s=21&material={$material.id}';">{*Номер по порядку*}{$j}.</td>
                        <td onclick="location.href='cabinet.php?school={$ProfileTeacher->idSchool}&type=s&s=21&material={$material.id}';">{*level класса, которому составлен материал-->*}{$level}</td>
                        <td onclick="location.href='cabinet.php?school={$ProfileTeacher->idSchool}&type=s&s=21&material={$material.id}';">
                                {*Количество активных и неактивных параграфов-->*}{$material.countparagraphactive}/{$material.countparagraph}
                        </td>
                        <td onclick="location.href='cabinet.php?school={$ProfileTeacher->idSchool}&type=s&s=21&material={$material.id}';">{*Количество разделов-->*}{$material.countsection}</td>
                        
                        <td onclick="location.href='cabinet.php?school={$ProfileTeacher->idSchool}&type=s&s=21&material={$material.id}';">
                            {foreach $material.classes as $class}
                                {*Классы, которым материал преподается (через пробел)-->*}{$class.name} 
                            {/foreach}
                        </td>
                        <td onclick="location.href='cabinet.php?school={$ProfileTeacher->idSchool}&type=s&s=21&material={$material.id}';">
                            {$material.complexity}
                        </td>
                        <td id="menuMaterial_{$material.id}"
                            style="width: 18px;"
                            onclick="subjectTeacher.showAssignMaterialPopup($(this), {$ProfileTeacher->idTeacher},{$material.id});">
                        </td>
                    </tr>
                    {assign var="j" value=$j+1}
                    {/foreach}
                    {/foreach}
                </table>
            </div>
            {assign var="i" value=$i+1}
            {/if}
            {/foreach}
            {else}
                {$notices['MI_SUBJECTS_TEACHER_HAS_NO_ACTIVEMATERIALS']['type'] = 1}
                {$notices['MI_SUBJECTS_TEACHER_HAS_NO_ACTIVEMATERIALS']['messages'][]['text'] = $smarty.const.MI_SUBJECTS_TEACHER_HAS_NO_ACTIVEMATERIALS_TEXT}
              <div class='conformation'>
                {include file="../../general/notice.tpl" IDNOTICE="MI_SUBJECTS_TEACHER_HAS_NO_ACTIVEMATERIALS"}
              </div>
            {/if}
            <div class="passedSubj">
                <div class="passedtitle">
                    <span>Архив материалов</span>
                </div>
                <div class="addNewSNote">
                    <span id="addConspectSpan" class='actionItem'
                        style="margin: 0;" 
                        onclick="subjectTeacher.showAddMaterialPopup($(this), {$ProfileTeacher->idTeacher}, {$ProfileTeacher->idSchool});">
                      <img class="addConspect" src="../../img/add.png"/>
                      <span>Добавить новый конспект</span>
                    </span>
                </div>
            </div>
            <div id="archiveDiv">
            {if $isArchiveMaterials}
            {assign var="i" value="1"}
            {foreach $materialsArchive as $subject => $subjectDetails}
            {assign var="j" value="1"}
            <div class="subj{if $i%2 eq 1}Left{else}Right{/if} archiveM">
                <table class="subjItem" cellspacing="0" cellpadding="0" >
                    <tr class="archiv"  align="center">
                        <td colspan="8">
                            <span class="subjectnamearchive">{$subject}</span>
                        <a style="display: none;" href="#"><img src="../img/secure.png" /></a>
                        </td>
                    </tr>

                    <tr class="colsNamed">
                        <td>№</td>
                        <td>Класс</td>
                        <td>§</td>
                        <td>Дата</td>
                        <td title="Разделы">Разд.</td>
                        <td>Статус</td>
                        <td colspan="2">Сложность</td>
                    </tr>
                    {foreach $subjectDetails.levels as $level => $materials}
                    {foreach $materials as $material}
                    {if $material.countsubgroups}
                    <tr class="defaultA">
                        <td onclick="location.href='cabinet.php?school={$ProfileTeacher->idSchool}&type=s&s=21&material={$material.id}&t={$ProfileTeacher->idTeacher}';">{*Номер по порядку*}{$j}.</td>
                        <td onclick="location.href='cabinet.php?school={$ProfileTeacher->idSchool}&type=s&s=21&material={$material.id}&t={$ProfileTeacher->idTeacher}';">{$level}</td>
                        <td onclick="location.href='cabinet.php?school={$ProfileTeacher->idSchool}&type=s&s=21&material={$material.id}&t={$ProfileTeacher->idTeacher}';">{$material.countparagraphactive}/{$material.countparagraph}</td>
                        <td onclick="location.href='cabinet.php?school={$ProfileTeacher->idSchool}&type=s&s=21&material={$material.id}&t={$ProfileTeacher->idTeacher}';">{$material.date}</td>
                        <td onclick="location.href='cabinet.php?school={$ProfileTeacher->idSchool}&type=s&s=21&material={$material.id}&t={$ProfileTeacher->idTeacher}';">{$material.countsection}</td>
                        <td onclick="location.href='cabinet.php?school={$ProfileTeacher->idSchool}&type=s&s=21&material={$material.id}&t={$ProfileTeacher->idTeacher}';">{$material.status}</td>
                        <td onclick="location.href='cabinet.php?school={$ProfileTeacher->idSchool}&type=s&s=21&material={$material.id}&t={$ProfileTeacher->idTeacher}';">{$material.complexity}</td>
                        <td id="menuMaterial_{$material.id}"
                            style="width: 18px;" 
                            onclick="subjectTeacher.showAssignMaterialPopup($(this), {$ProfileTeacher->idTeacher},{$material.id});"></td>
                    </tr>
                    {else}
                    <tr class="default">
                        <td onclick="location.href='cabinet.php?type=s&s=21&material={$material.id}&t={$ProfileTeacher->idTeacher}';">{*Номер по порядку*}{$j}.</td>
                        <td onclick="location.href='cabinet.php?type=s&s=21&material={$material.id}&t={$ProfileTeacher->idTeacher}';">{$level}</td>
                        <td onclick="location.href='cabinet.php?type=s&s=21&material={$material.id}&t={$ProfileTeacher->idTeacher}';">{$material.countparagraphactive}/{$material.countparagraph}</td>
                        <td onclick="location.href='cabinet.php?type=s&s=21&material={$material.id}&t={$ProfileTeacher->idTeacher}';">{$material.date}</td>
                        <td onclick="location.href='cabinet.php?type=s&s=21&material={$material.id}&t={$ProfileTeacher->idTeacher}';">{$material.countsection}</td>
                        <td onclick="location.href='cabinet.php?type=s&s=21&material={$material.id}&t={$ProfileTeacher->idTeacher}';">{$material.status}</td>
                        <td onclick="location.href='cabinet.php?type=s&s=21&material={$material.id}&t={$ProfileTeacher->idTeacher}';">{$material.complexity}</td>
                    </tr>
                    {/if}
                    {assign var="j" value=$j+1}
                    {/foreach}
                    {/foreach}
                </table> 
            </div>
            {assign var="i" value=$i+1}
            {/foreach}
            {else}
                {$notices['MI_SUBJECTS_TEACHER_HAS_NO_ARCHIVEMATERIALS']['type'] = 1}
                {$notices['MI_SUBJECTS_TEACHER_HAS_NO_ARCHIVEMATERIALS']['messages'][]['text'] = $smarty.const.MI_SUBJECTS_TEACHER_HAS_NO_ARCHIVEMATERIALS_TEXT}
              <div class='conformation'>
                {include file="../../general/notice.tpl" IDNOTICE="MI_SUBJECTS_TEACHER_HAS_NO_ARCHIVEMATERIALS"}
              </div>
            {/if}
            </div>
        </div>
    {else}
      <div class='conformation'>
        {include file="../../general/notice.tpl" IDNOTICE="MW_SUBJECTS_TEACHER_HAS_NO_SUBJECTS"}
      </div>
    {/if}

    </div><!--end content-->   
</div> <!--end mainfield-->