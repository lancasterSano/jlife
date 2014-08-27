<div class="mainfield"> 
  <div class="content">
    
    {*TOP DIV (NAVIGATION BAR)*}
    <div class="next_prev">
      {*LEFT DIV (PREVIOUS WEEK)*}
      {if not $isfirstweek}
      <div class="prev">
        <a href="cabinet.php?school={$ProfileKo->idSchool}&type=k&s=2&ac={$currentpagetype}&i={$currentSelectedId}&w={$nextprevweekyear.prevweek}">Предыдущая неделя</a>
      </div>
      {/if}
      {*RIGHT DIV (NEXT WEEK)*}
      {if not $isfirstweek}
        <div style="float:left;margin-left: 180px;">{$nameduration}</div>
      {else}
        <div style="float:left;margin-left: 334px;">{$nameduration}</div>
      {/if}
      {*CENTER DIV (CURRENT SEMESTRE)*}
      {if not $islastweek}
      <div class="next">
        <a href="cabinet.php?school={$ProfileKo->idSchool}&type=k&s=2&ac={$currentpagetype}&i={$currentSelectedId}&w={$nextprevweekyear.nextweek}">Следующая неделя</a>
      </div>
      {/if}
    </div>
    {*END NAVIGATION BAR*}
    
    
    <div class="sortMenu">
      <div class="columns">  
          
      <ul>
      {assign var="i" value="1"}
      {foreach $headerScheduledata as $headerdataelement}
        {if $i%3 eq 1}
          <li>
              <a href="cabinet.php?school={$ProfileKo->idSchool}&type=k&s=2&ac={$currentpagetype}&i={$headerdataelement.id}">
                  <span>{$headerdataelement.name}</span>
              </a>
          </li>
        {/if}
       {assign var="i" value=$i+1}       
      {/foreach}
      </ul>
      <ul>
      {assign var="i" value="1"}
      {foreach $headerScheduledata as $headerdataelement}
        {if $i%3 eq 2}
          <li>
              <a href="cabinet.php?school={$ProfileKo->idSchool}&type=k&s=2&ac={$currentpagetype}&i={$headerdataelement.id}">
                  <span>{$headerdataelement.name}</span>
              </a>
          </li>
        {/if}
       {assign var="i" value=$i+1}       
      {/foreach}
      </ul>
      <ul>
      {assign var="i" value="1"}
      {foreach $headerScheduledata as $headerdataelement}
        {if $i%3 eq 0}
          <li>
              <a href="cabinet.php?school={$ProfileKo->idSchool}&type=k&s=2&ac={$currentpagetype}&i={$headerdataelement.id}">
                  <span>{$headerdataelement.name}</span>
              </a>
          </li>
        {/if}
       {assign var="i" value=$i+1} 
       {/foreach}
      </ul>
      </div>
      <div class="sortOpt">
        <ul>
          <li>
            <a href="cabinet.php?school={$ProfileKo->idSchool}&type=k&s=2&ac=1">
              <span>По классам</span>
            </a>
          </li>
          <li>
            <a href="cabinet.php?school={$ProfileKo->idSchool}&type=k&s=2&ac=2">
              <span>По преподавателям</span>
            </a>
          </li>
          <li>
            <a href="cabinet.php?school={$ProfileKo->idSchool}&type=k&s=2&ac=3">
              <span>По предметам</span>
            </a>
          </li>
          <li>
            <a href="cabinet.php?school={$ProfileKo->idSchool}&type=k&s=2&ac=4">
              <span>По кабинетам</span>
            </a>
          </li>
        </ul>  
      </div>
    </div>

    <div class="currentCls">
      <span>{$currentSelectedName}</span>
      <span class="moreOpt" onclick="location.href='schedulePattern.php?school={$ProfileKo->idSchool}';">Подробнее</span>
    </div>  

    <div class="tableHeader">
      <div class="number">
        <span>№</span>
      </div>
      <div class="subj">
        <span>Предмет</span>
      </div> 
      <div class="hometask">
        <span>Преподаватель/деятельность</span>
      </div>
      <div class="auditor">
        <span>Каб.</span>
      </div>
    </div>

    {foreach $days as $day}
        <div class="day" id="day_{$day.dateForJS}">
            {if $day.iscurrentstudyday or $day.isnextstudyday}
                <div id="daydate_{$day.dateForJS}" class="dayDate opened">
            {else}
                <div id="daydate_{$day.dateForJS}"class="dayDateR"> {*Для дней, которые свёрнуты, особый стиль*}
            {/if}
                <span class="dateSpan">{*Дата*}{$day.date}</span>
                <div 
                    class="{if $day.iscurrentstudyday}dayAct{else if $day.isnextstudyday}dayNext{else}dayUnact{/if}" 
                    onclick="scheduleKo.loadLessonsUI('{$day.dateForJS}', {$currentSelectedId}, {$currentpagetype}, {$ProfileKo->idKo}, '{addSlashes($currentSelectedName)}', {if $day.iscurrentstudyday}{$day.iscurrentstudyday}{else}0{/if}, {if $day.isnextstudyday}{$day.isnextstudyday}{else}0{/if});">
                    <span>{*День недели*}{$day.dayofweek}</span>
                </div>  
            </div>
            {if $day.iscurrentstudyday or $day.isnextstudyday}{*Выводим только сегодняшний и завтрашний день*}         
            <table id="timetable_{$day.dateForJS}" 
                   class="{if $day.iscurrentstudyday}dayContCur{else}dayContNext{/if}" 
                   cellspacing="0" cellpadding="0">
                <tbody>
                    {foreach $day.lessons as $number => $subgroupsLessons}
                    {foreach $subgroupsLessons as $idsubgroup => $lesson}
                    <tr>
                        <td class="number" style="background: {*Цвет предмета*}{$lesson.color};
                            border-bottom:3px solid {*Цвет рамки*}{$lesson.color};">
                            <span>{*Номер урока*}{$number}</span>
                        </td>

                        <td class="lesName" style="border-bottom:3px solid {*Цвет рамки*}{$lesson.color};">
                          <a href=""><span>{*Название предмета*}{$lesson.subjectname}</span></a>
                          <span class='descript'>{$lesson.nameclassgroup}</span>
                        </td>

                        <td class="{if $lesson.islast}dzL{else}dz{/if}"> {*dz если предмет идет не последним в таблице, иначе dzL*}
                            <div class='first'{if $idsubgroup eq 0}style="height:18px;"{/if}> 
                              {if $idsubgroup eq 0}
                              <span></span>
                              {else}
                              <span>{$lesson.Teacher->FIOInitials()}</span>
                              {/if}
                            </div>
                            <div class='last'{if $idsubgroup eq 0}style="height:18px;"{/if}>
                                {foreach $lesson.lessontypes as $lessontype}
                                {if $lessontype.islastlessontype} 
                                <span>{$lessontype.description}</span> {*если тип урока последний, то в конце не ставим запятую*}
                                {else}
                                <span>{$lessontype.description}</span>, {*если урок не последний, то запятой нет*}
                                {/if}
                                {foreachelse}{*Оставляем пустую строку*}
                                <span>&nbsp;</span>
                                {/foreach}
                            </div>     
                        </td>

                        <td class="{if $lesson.islast}auditorL{else}auditor{/if}"> {*auditor если предмет идет не последним в таблице, иначе auditorL*}
                            <span>{*Кабинет*}{$lesson.cabinet}</span>
                        </td>
                    </tr>
                    {/foreach}
                    {foreachelse}
                    <tr>
                      <td style="height: 20px;"></td>
                    </tr>
                    <tr>
                      <td style="text-align: center; font-weight: bold;">В этот день у Вас выходной!
                      </td>
                    </tr>
                    <tr>
                      <td style="height: 20px;">
                      </td>
                    </tr>
                    {/foreach}
                </tbody>
            </table>
            {/if}
        </div>
        {/foreach}
    
  </div>{*END CONTENT*}  
</div>{*END MAINFIELD*}