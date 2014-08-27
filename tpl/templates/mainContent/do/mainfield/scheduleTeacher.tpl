<div class="mainfield">
  <div class="content">
  {if $isStudyDuration}
    <div class="next_prev">
    {if not $isfirstweek}
      <div class="prev">
        <a href="cabinet.php?school={$ProfileTeacher->idSchool}&s=1&w={$nextprevweekyear.prevweek}">
          Предыдущая неделя
        </a>
      </div>
    {/if}
    {if not $isfirstweek}
      <div style="float:left;margin-left: 180px;">{$nameduration}</div>
    {else}
      <div style="float:left;margin-left: 334px;">{$nameduration}</div>
    {/if}
    {if not $islastweek}
      <div class="next">
        <a href="cabinet.php?school={$ProfileTeacher->idSchool}&s=1&w={$nextprevweekyear.nextweek}">
          Следующая неделя
        </a>
      </div>
    {/if}
    </div>
    <div class="tableHeader">
      <div class="number"><span>№</span></div>
      <div class="subj"><span>Предмет</span></div>
      <div class="hometask"><span>Домашнее задание</span></div>
      <div class="class"><span>Класс</span></div>
      <div class="auditor" title="Кабинет"><span>Каб.</span></div>
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
            onclick="ScheduleTeacher.loadLessonsUI('{$day.dateForJS}', '{$ProfileTeacher->idTeacher}', '{$ProfileTeacher->idSchool}', {if $day.iscurrentstudyday}{$day.iscurrentstudyday}{else}0{/if}, {if $day.isnextstudyday}{$day.isnextstudyday}{else}0{/if});">
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
            {if $idsubgroup eq 0}
            <td class="lesName" style="border-bottom:3px solid {*Цвет рамки*}{$lesson.color};">
              <span>{*Название предмета*}{$lesson.subjectname}</span>
            </td>
            {else}
            <td class="lesName" style="border-bottom:3px solid {*Цвет рамки*}{$lesson.color};">
                {if isset($lesson.idmaterial)}
              <a href="sections.php?material={$lesson.idmaterial}"><span>{*Название предмета*}{$lesson.subjectname}</span></a>
                {else}
              <a href="sections.php"><span>{*Название предмета*}{$lesson.subjectname}</span></a>
                {/if}
            </td>
            {/if}
            <td class="{if $lesson.islast}dzL{else}dz{/if}"> {*dz если предмет идет не последним в таблице, иначе dzL*}
              <div> 
              {foreach $lesson.paragraphs as $paragraph}
                <span class="paragraphspan">
                  <a class="paragraphhref" {if $paragraph.notstudy}style="color:red;" {/if}
                     href="addpar.php?school={$ProfileTeacher->idSchool}&paragraph={$paragraph.id}">
                    §{*Номер параграфа, в котором задано задание*}{$paragraph.number}.
                        {*Название параграфа  в котором задано задание
                        Пример: Теорема Пифагора (часть 3)*}
                        {$paragraph.name}
                  </a>

                  {*Названия частей параграфов, в которых задано задание*}
                  {*assign var*}
                  {foreach $paragraph.partparagraphs as $partparagraph}
                  <a class="partparagraphhref" {if $paragraph.notstudy}style="color:red;" {/if}
                     href="addpar.php?school={$ProfileTeacher->idSchool}&paragraph={$paragraph.id}#s_{$partparagraph.id}">
                      Часть {$partparagraph.number}
                  </a>
                  {/foreach}
                </span>
              {/foreach}
              </div>
              <div><span>{*Домашнее задание*}{$lesson.hometask}</span></div>     
            </td>
            <td class="{if $lesson.islast}classL{else}class{/if}"> 
              <span>{*Класс*}
                <a href="cabinet.php?subject={$lesson.idsubject}&class={$lesson.idclass}&subgroup={$lesson.idsubgroup}&s=3">
                  {$lesson.nameclass}
                </a>
              </span>
            </td>
            <td class="{if $lesson.islast}auditorL{else}auditor{/if}"> {*auditor если предмет идет не последним в таблице, иначе auditorL*}
              <span>{*Кабинет*}{$lesson.cabinet}</span>
            </td>
          </tr>
          {/foreach}
          {foreachelse}
          <tr><td style="height: 20px;"></td></tr>
          <tr><td style="text-align: center; font-weight: bold;">В этот день у Вас выходной!</td></tr>
          <tr><td style="height: 20px;"></td></tr>
          {/foreach}
        </tbody>
      </table>
      {/if}
    </div>
    {/foreach}
    {else}
      <div class='conformation'>
        {include file="../../general/notice.tpl" IDNOTICE="ME_SCHEDULE_TEACHER_HAS_NO_STUDYDURATION"}
      </div>
    {/if}
  </div>
</div>