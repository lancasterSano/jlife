<div class="mainfield">
  <div class="content">
    <div class="sammary">
      <table class="sammary" cellpadding="0">
        <tbody>
          <tr>
            <td class='sort'>
  		<a href="cabinet.php?school={$idschool}&type=k&s=1&ac=1"><span>По классам             </span></a>
                <a href="cabinet.php?school={$idschool}&type=k&s=1&ac=2"><span>По преподавателям      </span></a>
                <a href="cabinet.php?school={$idschool}&type=k&s=1&ac=3"><span>По кабинетам           </span></a>
                <a href="cabinet.php?school={$idschool}&type=k&s=1&ac=4"><span>По предметам           </span></a> 
            </td>
          </tr>  
          {if $currentpagetype eq 1}
          <tr class="headerSam">
            <td>
              <div class="group">       <span>Класс             </span></div>
              <div class="stNumb">      <span>Учеников          </span></div>
              <div class="classLead">   <span>Кл. рук.          </span></div>
              <div class="showhideall" 
                   id="showhideallbut"><span>Показать все      </span></div>
            </td>
          </tr>
          {foreach $allclasses as $level => $classesbylevel}
          <tr id="headClass_{$level}" class="clsNumb{if not $classesbylevel} hiddenParralel{/if}"{if not $classesbylevel} style="display:none"{/if}>
            <td>
              <span>{*Уровень класса*}{$level}-e классы</span>
            </td>
          </tr>
          
          {foreach $classesbylevel as $idclass => $classbylevel}
          <tr class="default" id="class_{$idclass}">
            <td>
                <div class="roll" onclick="groupsKo.loadClassSubjectsUI({$idclass}, {$idschool});"></div>{*Стрелочка*}
              
              <div class="group"><span>{$classbylevel.name}{*Номер класса*}</span></div>
              
              <div class="stNumb"><span>{$classbylevel.countlearner}{*Количество учеников*}</span></div>
              
              <div class="classLead" id="yoda_{$idclass}">
                <span>
                    {if isset($classbylevel.Yoda)}
                  <a href="{$PROJECT_PATH}/pages/index.php?id={$classbylevel.Yoda->idProfile}">
                    {$classbylevel.Yoda->FIOInitials()}{*Фамилия и инициалы препода*}
                  </a>
                    {else}
                        Не назначен
                    {/if}
                </span>
              </div>
              
              <div class="manage" id="manage_{$idclass}">
                <span><a href=""><img src="{$PROJECT_PATH}/img/doc.png">Журнал</a></span>
                <span style='padding-right:0;'><a href=""><img src="{$PROJECT_PATH}/img/phot.png">Расписание</a></span>
              </div>
              <div id="help_{$idclass}" class='help' 
                   onclick="groupsKo.drawPopupClass($(this), {$idclass}, {$ProfileKo->idSchool}, {if isset($classbylevel.Yoda->idYoda)}{$classbylevel.Yoda->idYoda}{else}0{/if}, {if isset($classbylevel.Yoda->idProfile)}{$classbylevel.Yoda->idProfile}{else}0{/if}, {$level});">
              </div>
            </td> 
          </tr>
          {/foreach}
          <tr id="addClass_{$level}"{if not $classesbylevel} style="display:none" class="hiddenParralel"{/if}>
            <td>
              <span class="addNewClass" onclick="groupsKo.drawAddClass({$level}, {$ProfileKo->idSchool});">
                Добавить новый класс
              </span>
            </td>
          </tr>
          {/foreach}
          {/if}
          {if $currentpagetype eq 2}
          <tr class="headerSam">
            <td>
              <div class="teachersFIOHeader">       <span>ФИО преподавателя             </span></div>
              <div class="category">          <span>Категория                     </span></div>
            </td>
          </tr>        
          {assign var="i" value="1"}
          {foreach $teachers as $idteacher => $teacher}
          <tr class="default" id="teacher_{$teacher.id}">
            <td>
              <div class="roll" onclick="groupsKo.loadTeacherSubjectsUI({$teacher.id}, {$teacher.idschool});{*открыть список предметов, какие он может преподавать*}"></div>{*Стрелочка*}
              
              <div class="teacherNum"><span>{$i}.</span></div>
              
              <div class="teachersFIO"><span>{$teacher.FIO}{*ФИО препода*}</span></div>
                            
              <div class="stNumb"><span>{$teacher.category}{*Категория учителя*}</span></div>
              
              <div class='help' id='teacherManage_{$teacher.id}'
                   onclick="groupsKo.showManageTeacherPopup($(this),{$teacher.id});{*открыть попап с возможностью редактирования, удаления и добавления предмета преподу*}">
              </div>
            </td> 
          </tr>
          {assign var="i" value={$i+1}}
          {/foreach}
          <tr>
            <td>
                <span id="addNewTeacher" class="addNewClass" onclick="groupsKo.showHideTeacherSearch({$idschool});{*Возможность добавить нового препода*}">
                Добавить нового преподавателя
              </span>
            </td>
          </tr>
          {/if}
        </tbody>
      </table>
    </div>
  </div> {*END CONTENT*}
</div> {*END MAINFIELD*}