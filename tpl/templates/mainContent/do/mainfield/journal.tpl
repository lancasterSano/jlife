<div class="mainfield"> 
    <div class="content">

        {*ВЫГРУЖАЕМ ВСЕ ПРЕДМЕТЫ КЛАССА*}

        {if $subjects != NULL}
          
          <div class='subjects'>
            <ul class='sub'>
            {assign var="i" value="1"}
            {foreach from=$subjects item=subject key=number}
                {if $i%3 eq 1}
                    <li> 
                        <a class="{if $actSubj==$subject.subjectId}actionItemActive{else}actionItem{/if}" href='study.php?subject={$subject.subjectId}&s=3' id="{$subject.subjectId}">
                            {$subject.name}{if $subject.countGroup >= 2}({$subject.countGroup}){/if}
                        </a>
                    </li>
                {/if}
            {assign var="i" value=$i+1} 
            {/foreach}
            </ul>
            <ul class='sub'>
            {assign var="i" value="1"}
            {foreach from=$subjects item=subject key=number}
                {if $i%3 eq 2}
                    <li> 
                        <a class="{if $actSubj==$subject.subjectId}actionItemActive{else}actionItem{/if}" href='study.php?subject={$subject.subjectId}&s=3' id="{$subject.subjectId}">
                            {$subject.name}{if $subject.countGroup >= 2}({$subject.countGroup}){/if}
                        </a>
                    </li>
                {/if}
            {assign var="i" value=$i+1} 
            {/foreach}
            </ul>
            <ul class='sub'>
            {assign var="i" value="1"}
            {foreach from=$subjects item=subject key=number}
                {if $i%3 eq 0}
                    <li> 
                        <a class="{if $actSubj==$subject.subjectId}actionItemActive{else}actionItem{/if}" href='study.php?subject={$subject.subjectId}&s=3' id="{$subject.subjectId}">
                            {$subject.name}{if $subject.countGroup >= 2}({$subject.countGroup}){/if}
                        </a>
                    </li>
                {/if}
            {assign var="i" value=$i+1} 
            {/foreach}
            </ul>
          </div>

        {else}
            {$notices['MW_CLASS_HAS_NO_SUBGROUPS']['messages'][] = $smarty.const.MW_CLASS_HAS_NO_SUBGROUPS}
            {$notices['MW_CLASS_HAS_NO_SUBGROUPS']['type'] = 3}
            <div class='conformation'>
              {include file="../../general/notice.tpl" IDNOTICE="MW_CLASS_HAS_NO_SUBGROUPS"}
            </div>
        {/if}


        {if $idSubject != NULL}

        {*ВЫГРУЖАЕМ МЕСЯЦА НА ТЕКУЩИЙ СЕМЕСТР*}
        {if $months != NULL}

        <div class='monthesCont'>
            <div class='monthes'>
                  {foreach from=$months item=month}
                  <a class="{if $actMon==$month.id}actionItemActive{else}actionItem{/if}" href='study.php?subject={$idSubject}&s=3&month={$month.id}'>
                      {$month.name}
                  </a>
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
                                <td id = 'learner_{$group.group.id}_{$number}' class='student' 
                                     onmouseover="Journal.mouseOverLearner('{$group.group.id}_{$number}')"
                                     onmouseout="Journal.mouseOutLearner('{$group.group.id}_{$number}')">
                                    <a href='../index.php?id={$learner->idProfile}'><nobr>{$number}. {$learner->FIO()}</nobr></a>
                                </td>
                              </tr>
                              {$number = $number+1}
                              {/foreach}

                              {if $key != lessons && $key != marks && $key != hometasks}
                              <tr>  
                                  <td class='subGroupD' id='group_{$group.group.id}'
                                       onmouseover ="Journal.mouseOverTextHometask('{$group.group.id}')"
                                       onmouseout = "Journal.mouseOutTextHometask('{$group.group.id}')"><span>Домашнее задание</span>
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

                            {*КОСТЫЛИ ДЛЯ ВЕРСТКИ (ОТРИСОВКА КОЛОНОК ДАТА/ТИП УРОКА БЕЗ ПРОПУСКОВ ПИКСЕЛЕЙ)*}

                            {assign var="count" value = 0}
                            {foreach key=key item=lesson from=$learner_subgroup.lessons name=A}
                                        {foreach key=key1 item=lessonType from=$lesson->spisoklessonstypes name=B}
                                        {$count=$count + 1}
                                        {/foreach}
                            {/foreach}
                            {$count = $count * 22}
                            {*******************************************************************************}

                                    {* ТЕМА УРОКА *}
                                    <tr>

                                      {assign var="number" value=0}{* НОМЕР ТИПА ПО ПОРЯДКУ В ТАБЛИЦЕ*}
                                      {assign var="classType" value=''}
                                            {if $learner_subgroup.lessons != null}
                                              {$classType = 'headerItem'}
                                            {else}
                                              {$classType = 'headerItemSingle'}
                                            {/if}
                                      {foreach key=key item=lesson from=$learner_subgroup.lessons name=A}
                                        {foreach key=key1 item=lessonType from=$lesson->spisoklessonstypes name=B}
                                            {$number = $number+1}
                                            <td class='headerItem def' id="type_{$number}" 
                                                onmouseover="Journal.mouseOverType('{$number}')"
                                                onmouseout="Journal.mouseOutType('{$number}')">
                                                <span> {$lessonType->name} </span>
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
                                                  onmouseover="Journal.mouseOverResult('1')"
                                                  onmouseout="Journal.mouseOutResult('1')">
                                                <span>Оценок</span>
                                            </td>

                                            <td rowspan="{$rowspanCount}" class='{$classType} end' id='result_2'
                                                onmouseover="Journal.mouseOverResult('2')"
                                                onmouseout="Journal.mouseOutResult('2')">
                                                <span>Средняя</span>
                                            </td>  

                                            <td rowspan="{$rowspanCount}" class='{$classType} end' id='result_3'
                                                onmouseover="Journal.mouseOverResult('3')"
                                                onmouseout="Journal.mouseOutResult('3')">
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
                                                onmouseover="Journal.mouseOverType('{$number}')"
                                                onmouseout="Journal.mouseOutType('{$number}')">
                                                <span>{$lesson->date|date_format:"%d.%m"}</span>
                                            </td>
                                        {/foreach}
                                      {/foreach}
                                    </tr> 
                          
                          {assign var="countGroups" value=$learner_subgroup|@count-3} {*ПЕРЕМЕННАЯ ДЛЯ ОПРЕДЕЛЕНИЯ КОЛИЧЕСТВА ГРУПП*}
                          {if $countGroups > 1}
                          {foreach key=key item=group from=$learner_subgroup name=groups}
                            {if $smarty.foreach.groups.iteration <= $countGroups}
                              
                              {*if $group.teacher != NULL*}
                              <tr>  
                                <td colspan='{$number+3}' class='grMaster'>
                                </td>
                              </tr>
                              {*/if*}

                                  {assign var="row" value=1} 
                                  {foreach key=idw item=learner from=$group.learners name=learners}
                              <tr>
                                  {assign var="markNumber" value=1}
                                  {assign var="result" value=1}
                                  {assign var="idL" value=$learner->idLearner}
                                      {foreach key=keyLes item=lesson from=$learner_subgroup.marks[$idL] name=A}
                                        {foreach key=keyLessT item=lessonType from=$lesson name=A}
                                         {assign var="groupIdRowIdMarkId" value=$group.group.id|cat:'_'|cat:$row|cat:'_'|cat:$markNumber}
                                            
                                            <td {if $keyLess == avg}id='avg_{$groupIdRowIdMarkId}_{$result}' class='colItem' 
                                                        onmouseover="Journal.mouseOverMark('avg_{$groupIdRowIdMarkId}_{$result}')"
                                                        onmouseout="Journal.mouseOutMark('avg_{$groupIdRowIdMarkId}_{$result}')"
                                                  {$result=$result+1}
                                                  {else}id='mark_{$groupIdRowIdMarkId}' class='colItem' 
                                                        onmouseover="Journal.mouseOverMark('mark_{$groupIdRowIdMarkId}')"
                                                        onmouseout="Journal.mouseOutMark('mark_{$groupIdRowIdMarkId}')"
                                                  {/if}>
                                                    {if isset($lessonType.error) && $lessonType.error == "Нет урока"}
                                                      <img src="../../img/noLesson.png"/>
                                                    {else}
                                                      {if $keyLes == avg}
                                                        {$lessonType}
                                                      {else} {if $lessonType.value == '0' }
                                                            П
                                                        {else} {if $lessonType.value == '13' }
                                                              Н
                                                          {else}
                                                                {$lessonType.value}
                                                               {/if}
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
                              {*if $group.teacher != NULL*}
                                  <tr>
                                    {assign var="idGr" value=$group.group.id}
                                    {assign var="hometaskNumber" value=1}
                                      {foreach key=keyL item=idLesson from=$learner_subgroup.hometasks[$idGr] name=A}
                                        {foreach key=keyLT item=idLessonType from=$idLesson name=A}
                                            <td id='hometask_{$idGr}_{$keyL}_{$keyLT}_{$hometaskNumber}' class='colItem' 
                                                {if $idLessonType.status == 'true'}
                                                    onclick="Journal.createHomeworkPopup('{$idGr}_{$keyL}_{$keyLT}_{$idSchool}')"
                                                {/if}
                                                 onmouseover="Journal.mouseOverHometask('hometask_{$idGr}_{$hometaskNumber}')"
                                                 onmouseout="Journal.mouseOutHometask('hometask_{$idGr}_{$hometaskNumber}')">
                                              {foreach key=keyPar item=idParagraph from=$idLessonType name=A}
                                                  {if $keyPar == error}
                                                    <img src="../../img/noLesson.png"/>
                                                  {else}
                                                    {if $idParagraph == 'true'}
                                                      V
                                                    {/if}
                                                  {/if}
                                              {/foreach}
                                            </td>
                                        {$hometaskNumber=$hometaskNumber+1}
                                        {/foreach}
                                      {/foreach}
                                  </tr>
                                {/if}
                          {/foreach}


                          {else}
                            
                            {foreach key=key item=group from=$learner_subgroup name=groups name=oneGroup}
                            {if $smarty.foreach.oneGroup.iteration <= 1}

                              {*if $group.teacher != NULL*}
                              <tr> 
                                  <td colspan='{$number+5}' class='grMaster'>
                                  </td>
                              </tr>
                              {*/if*}        
                            
                                {assign var="row" value=1} 
                                {foreach key=keyL item=learner from=$learner_subgroup.marks name=A}
                              <tr>
                                {assign var="markNumber" value=1}
                                {assign var="result" value=1}
                                      {foreach key=keyLes item=lesson from=$learner name=A}
                                        {foreach key=keyLesT item=lessonType from=$lesson name=A}
                                          
                                          {assign var="groupIdRowIdMarkId" value=$group.group.id|cat:'_'|cat:$row|cat:'_'|cat:$markNumber}
                                          
                                            <td {if $keyLes == avg}id='avg_{$groupIdRowIdMarkId}_{$result}' class='colItem' 
                                                        onmouseover="Journal.mouseOverMark('avg_{$groupIdRowIdMarkId}_{$result}')"
                                                        onmouseout="Journal.mouseOutMark('avg_{$groupIdRowIdMarkId}_{$result}')"
                                                  {$result=$result+1}
                                                  {else}id='mark_{$groupIdRowIdMarkId}' class='colItem' 
                                                        onmouseover="Journal.mouseOverMark('mark_{$groupIdRowIdMarkId}')"
                                                        onmouseout="Journal.mouseOutMark('mark_{$groupIdRowIdMarkId}')"
                                                  {/if}>
                                                      {if isset($lessonType.error) && $lessonType.error == "Нет урока"}
                                                    <img src="../../img/noLesson.png"/>
                                                  {else}
                                                    {if $keyLes == avg}
                                                      {$lessonType}
                                                    {else} {if $lessonType.value == '0' }
                                                          П
                                                      {else} {if $lessonType.value == '13' }
                                                            Н
                                                        {else}
                                                              {$lessonType.value}
                                                             {/if}
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
                                          <td id='hometask_{$idGr}_{$keyL}_{$keyLT}_{$hometaskNumber}' class='colItem' {if $idLessonType.status == 'true'}onclick="Journal.createHomeworkPopup('{$idGr}_{$keyL}_{$keyLT}_{$idSchool}')"{/if}
                                                    onmouseover="Journal.mouseOverHometask('hometask_{$idGr}_{$hometaskNumber}')"
                                                    onmouseout="Journal.mouseOutHometask('hometask_{$idGr}_{$hometaskNumber}')">
                                              {foreach key=keyPar item=idParagraph from=$idLessonType name=A}
                                                  {if $keyPar == error}
                                                    <img src="../../img/noLesson.png"/>
                                                  {else}
                                                    {if $idParagraph == 'true'}
                                                      V
                                                    {/if}
                                                  {/if}
                                            {/foreach}
                                          </td>
                                          {$hometaskNumber = $hometaskNumber + 1}
                                      {/foreach}
                                    {/foreach}
                                    {else}
                                      <td style='height:25px;'></td>
                                    {/if}
                                  </tr>

                            </div>
                                {/if}
                            {/foreach}
                            {/if}
                          </tbody>
                        </table>
                      </div>   
                    </td>  
                  </tr>
            </table>
        </div>
        {else}
          {$notices['MW_SCHOOL_HAS_NO_STUDYDURATION']['messages'][] = $smarty.const.MW_SCHOOL_HAS_NO_STUDYDURATION}
            {$notices['MW_SCHOOL_HAS_NO_STUDYDURATION']['type'] = 3}
            <div class='conformation'>
              {include file="../../general/notice.tpl" IDNOTICE="MW_SCHOOL_HAS_NO_STUDYDURATION"}
            </div>  
        {/if}
        {/if}
    </div><!--end content-->   
</div> <!--end mainfield-->