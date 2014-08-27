<div class="mainfield"> 
  <div class="content" id="contentId">

    {*
     * ВЫГРУЖАЕМ ВСЕ ПОД ГРУППЫ, В КОТОРЫХ ПРЕПОДАЕТ УЧИТЕЛЬ
     *}

    {if $subjects != NULL}
      <div class='subjects'>

        {foreach key=number item=idTeacher from=$subjects name=A}

          {foreach key=number item=idClass from=$idTeacher name=A}

            {foreach key=keyIdSubgroup item=idSubgroup from=$idClass name=A}
              <ul class='sub'>
                <li>
                  <a class="{if $actSubgroup==$keyIdSubgroup && $actClass==$idSubgroup.classId}actionItemActive{else}actionItem{/if}"
                     href='cabinet.php?subject={$idSubgroup.subjectId}&class={$idSubgroup.classId}&subgroup={$idSubgroup.id}&s=3&type=s' 
                     id="{$subject.subjectId}">
                      {$idSubgroup.name} {$idSubgroup.level}-{$idSubgroup.letter} {$idSubgroup.groupName}
                  </a>
                </li>
              </ul>
            {/foreach}

          {/foreach}

        {/foreach}
        
      </div>
    {else}
      {$notices['MW_TEACHER_HAS_NO_SUBGROUPS']['messages'][] = $smarty.const.MW_TEACHER_HAS_NO_SUBGROUPS}
      {$notices['MW_TEACHER_HAS_NO_SUBGROUPS']['type'] = 3}
      <div class='conformation'>
        {include file="../../general/notice.tpl" IDNOTICE="MW_TEACHER_HAS_NO_SUBGROUPS"}
      </div>
    {/if}
      
    <!-- <div class='links'></div> -->

    {*
     *ВЫГРУЖАЕМ МЕСЯЦА НА ТЕКУЩИЙ СЕМЕСТР
     *}

    {if $idSubject != NULL}
      {if $months != NULL}
        {if count($learner_subgroup) > 3}
          <div class='monthesCont'>
            <div class='monthes'>

              {foreach key=keyS item=sub from=$subject name=A}

                {foreach from=$months item=month}
                  <a class="{if $actMon==$month.id}actionItemActive{else}actionItem{/if}" href='cabinet.php?subject={$idSubject}&class={$sub.classId}&subgroup={$sub.id}&s=3&month={$month.id}&type=s'>
                    {$month.name}
                  </a>
                {/foreach}

              {/foreach}

            </div>    
          </div>

          <div class='magazine'>
            <table class='magazine' cellspacing='0' cellpadding='0'>
              <tr>
                <td class='left'>
                  <div>
                    <table cellspacing="0" cellpadding="0">
                      <tbody>
                        <tr>
                          <td class='tabHeader' > <span> Тема урока </span> </td>
                        </tr>

                        <tr>
                            <td class='tabHeader' > <span> День недели / дата урока </span> </td>
                        </tr>

                        {foreach key=key item=group from=$learner_subgroup name=groups}
                          {if $group.group != NULL}
                            <tr>  
                              <td class='subGroup'>
                                <span>
                                    {$group.group.name} - 
                                </span>

                                <a class='actionItem' href='../index.php?id={$group.teacher.iduser}'> {$group.teacher.FIOInitials}</a>
                              </td>
                            </tr>
                          {/if}
                            {assign var="number" value=1}

                            {foreach key=idw item=learner from=$group.learners name=learners}
                              <tr class='studentKo'>  
                                <td id = 'learner_{$group.group.id}_{$number}_{$learner->idLearner}' class='student' 
                                     onmouseover="JournalTeacher.mouseOverLearner('{$group.group.id}_{$number}_{$learner->idLearner}')"
                                     onmouseout="JournalTeacher.mouseOutLearner('{$group.group.id}_{$number}_{$learner->idLearner}')">
                                    <a href='../index.php?id={$learner->idProfile}'><nobr>{$number}. {$learner->FIO()}</nobr></a>
                                </td>
                              </tr>
                              
                              {$number = $number+1}
                            {/foreach}

                            {if $key != lessons && $key != marks && $key != hometasks}
                              <tr>  
                                <td class='subGroupD' id='group_{$group.group.id}'
                                     onmouseover ="JournalTeacher.mouseOverTextHometask('{$group.group.id}')"
                                     onmouseout = "JournalTeacher.mouseOutTextHometask('{$group.group.id}')"><span>Домашнее задание</span>
                                </td>
                              </tr>
                            {/if}
                        {/foreach}

                      </tbody>
                    </table>
                  </div>
                </td>

                <td class='right'>
                  <div class='colsValues'>
                    <table cellspacing="0" cellpadding="0">
                      <tbody>
                        {* ТЕМА УРОКА *}

                        <tr>
                          {assign var="number" value=0}{* НОМЕР ТИПА ПО ПОРЯДКУ В ТАБЛИЦЕ*}
                          {assign var="classType" value=''}
                              {if $learner_subgroup.lessons != null}
                                {$classType = 'headerItem'}
                              {else}
                                {$classType = 'headerItemSingle'}
                              {/if}

                          {foreach key=keyLes item=lesson from=$learner_subgroup.lessons name=A}

                            {foreach key=keySpisokLesT item=lessonType from=$lesson->spisoklessonstypes name=B}
                                {$number = $number+1}

                                <td class='headerItem abr' id="type_{$number}"
                                    onclick="JournalTeacher.createLessonTypePopup('type_{$number}_lesson_{$keyLes}_lessonType_{$keySpisokLesT}')"
                                    onmouseover="JournalTeacher.mouseOverType('{$number}')"
                                    onmouseout="JournalTeacher.mouseOutType('{$number}')">
                                     {$lessonType->name} 
                                </td>
                            {/foreach}

                          {/foreach}

                          {****** rowspanCount - переменная для подсчета кол-ва lessons ******}
                          {if count($learner_subgroup.lessons) >0}
                            {assign var="rowspanCount" value=2}
                            {else}
                              {assign var="rowspanCount" value=0}
                          {/if}

                          <td rowspan="{$rowspanCount}" class='{$classType} end' id='result_1'
                              onmouseover="JournalTeacher.mouseOverResult('1')"
                              onmouseout="JournalTeacher.mouseOutResult('1')">
                              <span>Оценок</span>
                          </td>

                          <td rowspan="{$rowspanCount}" class='{$classType} end' id='result_2'
                              onmouseover="JournalTeacher.mouseOverResult('2')"
                              onmouseout="JournalTeacher.mouseOutResult('2')">
                              <span>Средняя</span>
                          </td>  

                          <td rowspan="{$rowspanCount}" class='{$classType} end' id='result_3'
                              onmouseover="JournalTeacher.mouseOverResult('3')"
                              onmouseout="JournalTeacher.mouseOutResult('3')">
                              <span>Пропуски</span>
                          </td>
                          
                        </tr>

                        {* ДАТА УРОКА *}
                        
                        <tr>
                          {$number = 0}

                          {foreach key=key item=lesson from=$learner_subgroup.lessons name=A}

                            {foreach key=key1 item=lessonType from=$lesson->spisoklessonstypes name=B}
                                {$number = $number+1} 

                                <td class='headerItem def' id="date_{$number}"
                                    onmouseover="JournalTeacher.mouseOverType('{$number}')"
                                    onmouseout="JournalTeacher.mouseOutType('{$number}')">
                                    <span>{$lesson->date|date_format:"%d.%m"}</span>
                                </td>
                            {/foreach}

                          {/foreach}
                        </tr>

                            
                        {foreach key=key item=group from=$learner_subgroup name=groups name=oneGroup}
                          {if $smarty.foreach.oneGroup.iteration <= 1}
                            {if $group.teacher != NULL}
                              <tr>  
                                <td colspan='{$number+3}' class='grMaster'> </td>
                              </tr>
                            {/if}        
                              
                            {assign var="row" value=1} 
                            
                            {foreach key=keyL item=learner from=$learner_subgroup.marks name=A}
                              <tr>
                                {assign var="markNumber" value=1}
                                {assign var="result" value=1}
                                
                                {foreach key=keyLes item=lesson from=$learner name=A}

                                  {foreach key=keyLesT item=lessonType from=$lesson name=A}
                                    {assign var="groupIdRowIdMarkId" value=$group.group.id|cat:'_'|cat:$row|cat:'_'|cat:$markNumber}
                                    {assign var="LearnerIdLessonIdLessonTId" value=$keyL|cat:'_'|cat:$keyLes|cat:'_'|cat:$keyLesT}

                                    <td {if $keyLes == avg}id='avg_{$groupIdRowIdMarkId}_{$result}' class='colItem' 
                                                onmouseover="JournalTeacher.mouseOverMark('avg_{$groupIdRowIdMarkId}_{$result}')"
                                                onmouseout="JournalTeacher.mouseOutMark('avg_{$groupIdRowIdMarkId}_{$result}')"
                                          {$result=$result+1}
                                          {else}id='mark_{$groupIdRowIdMarkId}' class='colItem'
                                                onclick="JournalTeacher.createMarksPopup('{$groupIdRowIdMarkId}_{$LearnerIdLessonIdLessonTId}_{if $lessonType.value == '' && isset($lessonType.id)}13{else}{$lessonType.value}{/if}_{$subject.0.id}')"
                                                onmouseover="JournalTeacher.mouseOverMark('mark_{$groupIdRowIdMarkId}')"
                                                onmouseout="JournalTeacher.mouseOutMark('mark_{$groupIdRowIdMarkId}')"
                                                data-id="{$idSchool}"
                                          {/if}>
                                          {if $keyLes == avg}
                                              {$lessonType}
                                              
                                            {else} {if $lessonType.value == '0' }
                                                  П
                                              {else} {if $lessonType.value == '' && isset($lessonType.id)}
                                                    Н
                                                {else}
                                                      {$lessonType.value}
                                                     {/if}
                                                   {/if}
                                          {/if}
                                    </td>
                                    
                                    {$markNumber=$markNumber+1}  
                                  {/foreach}
                                {/foreach}
                              </tr>  
                              
                              {$row = $row+1}
                            {/foreach}

                            <tr>
                              {if $learner_subgroup.lessons != null}
                                {assign var="idGr" value=$group.group.id}
                                {$hometaskNumber = 1}

                                  {foreach key=keyL item=idLesson from=$learner_subgroup.hometasks[$idGr] name=A}
                                    
                                    {foreach key=keyLT item=idLessonType from=$idLesson name=A}
                                      <td title='Открыть домашнее задание' id='hometask_{$idGr}_{$keyL}_{$keyLT}_{$hometaskNumber}' class='colItem'
                                          onclick="JournalTeacher.creatingHomeworkUI($(this), {$idSubject}, {$keyL}, {$idSchool}, {$idGr})"
                                           onmouseover="JournalTeacher.mouseOverHometask('hometask_{$idGr}_{$hometaskNumber}')"
                                           onmouseout="JournalTeacher.mouseOutHometask('hometask_{$idGr}_{$hometaskNumber}')">
                                          {foreach key=keyPar item=idParagraph from=$idLessonType name=A}
                                              {if $idParagraph == 'true'}
                                                V
                                              {/if}
                                          {/foreach}
                                      </td>
                                      
                                      {$hometaskNumber=$hometaskNumber+1}
                                    {/foreach}
                                  {/foreach}
                                {else}
                                  <td style='height:25px;'></td>
                              {/if}
                            </tr>
                          {/if}
                        {/foreach}
                      </tbody>
                    </table>
                  </div>   
                </td>  
              </tr>
            </table>
          </div>
          {else}
            {$notices['message_noLearners_noStudyduration']['messages'][] = $smarty.const.MW_CLASS_HAS_NO_LEARNERS}
            {$notices['message_noLearners_noStudyduration']['type'] = 3}
        {/if} <!-- if count($learner_subgroup) > 3 -->

        {else}
          {$notices['message_noLearners_noStudyduration']['messages'][] = $smarty.const.MW_SCHOOL_HAS_NO_STUDYDURATION}
          {$notices['message_noLearners_noStudyduration']['type'] = 2}
            <!-- {$notices['MI_CLASS_HAS_NO_LEARNERS']['messages'][] = $smarty.const.MI_CLASS_HAS_NO_LEARNERS} -->
      {/if} <!-- if $months != NULL -->
      
      {if $notices != NULL}  
        <div class='conformation'>
          {include file="../../general/notice.tpl" IDNOTICE="message_noLearners_noStudyduration"}
        </div>  
      {/if}
    {/if} <!-- if $idSubject != NULL -->
  </div><!--end content-->   
</div> <!--end mainfield-->