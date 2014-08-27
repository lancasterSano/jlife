<div class="mainfield">
  <div class="content">
    <table class="fix" cellspacing="0" cellpadding="0">
      <tbody>
        {foreach $days as $day} {*столько раз, сколько у нас дней недели*}
        <tr>
          {foreach $classes as $class}
          <td{if $class.islast} class="last"{/if}{if $day.islast} class="lastBot"{/if}>
            <div class="day" id="dayLessons_{$class.id}_{$day.number}">
              <table>
                <thead>
                  <tr>
                    <td class="instrument" onclick="SchedulePattern.loadEditDayUI({$class.id}, {$day.number});"></td>
                    <td class="group">{$class.name}</td>
                    <td>
                      <span class="yesBorder">Смена</span>
                      <span>{$class.shift}</span>
                    </td>
                    <td class="help"></td>
                  </tr>
                </thead>
                <tbody>
                {assign var="countlessonsday" value="0"}
                {foreach $classday[$day.number][$class.id].lessons as $number => $subgroupslesson}
                {foreach $subgroupslesson as $idsubgroup => $lesson}
                    {assign var="countlessonsday" value=$countlessonsday+1}
                {/foreach}
                {/foreach}
                    
                {assign var="i" value="1"}
                {foreach $classday[$day.number][$class.id].lessons as $number => $subgroupslesson}
                {foreach $subgroupslesson as $idsubgroup => $lesson}
                <tr>
                  {if $i eq 1}
                  <td class="dayName" rowspan="{$countlessonsday}">
                    <p class="mon"><span>{$day.name}</span></p>
                  </td>
                  {/if}
                  <td class="lesson" colspan="3">
                    <table cellspacing="0" cellpadding="0">
                      <tbody>
                        <tr class="rowOne">
                          <td class="lesName">{$number}. {$lesson.subjectname}</td>
                          <td class="rihgtCol">{$lesson.cabinet}</td>
                        </tr>
                        <tr class="rowTwo">
                          <td class="lesMaster">{$lesson.TeacherFIO}</td>
                          <td>{if isset($lesson.subjectCountHours)}{$lesson.subjectCountHours}ч{/if}</td>
                        </tr>
                      </tbody>    
                    </table>
                  </td>
                </tr>
                {assign var="i" value=$i+1}
                {/foreach}
                {/foreach}
                </tbody>
              </table>
            </div>
          </td>
          {/foreach}
        </tr>
        {/foreach}
      </tbody>
    </table>
  </div>
</div>