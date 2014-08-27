<div class="mainfield">   
  <div class="content">
    {if $Classes != NULL}
      <div class="units">
        <table>
          <tr>

            {foreach key=keyLevel item=level from=$Classes name=groups}            
              <td>
                <ul>

                  {foreach key=keyLetter item=letter from=$level name=groups}
                    <li>
                      <a class="{if $actClass==$letter.id}actionItemActive{else}actionItem{/if}" href='cabinet.php?school={$ProfileKo->idSchool}&type=k&s=3&class={$letter.id}'>
                        {$letter.name}
                      </a>
                    </li>
                  {/foreach}

                </ul>
              </td>
            {/foreach}

          </tr>  
          </table>    
      </div>
    {else}
      {$notices['ERROR']['messages'][] = $smarty.const.MI_SCHOOL_HAS_NO_CLASSES}
      {$notices['ERROR']['type'] = 3}
        <div class='conformation'>
            {include file="../../general/notice.tpl" IDNOTICE="ERROR"}
        </div> 
    {/if}

    {*idClass -> {$idClass}
    <br>
    marks -> {$journalAllSubjects.marks}
    <br>
    learners -> {$journalAllSubjects.learners}
    <br>
    journalOneSubject -> {$journalOneSubject}
    <br>
    journalAllSubjects -> {$journalAllSubjects}
    <br>
    parentLearnerlist -> {$parentLearnerlist}
    <br>
    listResponsible(choosenMenu) -> {$choosenMenu}*}

                                            {*if $idClass != NULL && $journalAllSubjects.marks != NULL || $idClass != NULL && $journalOneSubject != NULL || $parentLearnerlist != NULL*}
    {if $idClass != NULL || $choosenMenu != NULL}
      <div class="sortBy">
        <a class="{if !isset($actSubj) && !isset($choosenMenu)}actionItemActive{else}actionItem{/if}" href='cabinet.php?school={$idSchool}&type=k&s=3&class={$idClass}'>
          Список учеников
        </a>

        <a class="{if isset($choosenMenu)}actionItemActive{else}actionItem{/if}" href='cabinet.php?school={$idSchool}&type=k&s=3&class={$idClass}&plist=true'>
          Список родителей
        </a>
      </div>
                                              
      {if !$choosenMenu }
        <div class="columns">
          {if $subjects != NULL && $journalAllSubjects.learners != NULL || count($journalOneSubject) > 3}
            <ul>
               <li>
                  <a class="{if !isset($actSubj)}actionItemActive{else}actionItem{/if}" style ='color: red;' href='cabinet.php?school={$ProfileKo->idSchool}&type=k&s=3&class={$idClass}'>
                    Все предметы
                  </a> 
               </li>
            </ul>

            {foreach from=$subjects item=subject key=number}
              <ul>
                 <li>
                    <a class="{if $actSubj==$subject.subjectId}actionItemActive{else}actionItem{/if}" href='cabinet.php?school={$ProfileKo->idSchool}&type=k&s=3&class={$idClass}&subject={$subject.subjectId}' id="{$subject.subjectId}">
                      {$subject.name}{if $subject.countGroup >= 2}({$subject.countGroup}){/if}
                    </a>
                 </li>
              </ul>
            {/foreach}

        </div>
          {else}
            {$notices['ERROR']['messages'][] = $smarty.const.MW_CLASS_HAS_NO_LEARNERS}
            {$notices['ERROR']['type'] = 3}
          {/if}
                                                
        {if $subjects != NULL}      
          {if !isset($actSubj)}
            <div class='magazine'> 
              <table class='magazine' cellspacing='0' cellpadding='0'>
                <tr>
                  <td class='leftAll'>
                    <div>
                      <table  cellspacing='0' cellpadding='0'>
                        <tr>
                          <td class='tabHeader' colspan='2' >
                            <span>Ученики</span>
                            
                            <span onclick='JournalKo.sortByFIOsBack();' class="button">Сортировка</span>
                          </td>
                        </tr>

                        <tr id='helpRowLeft'>
                          <td  class='subGroup' colspan='2'>
                            <!-- <a onclick='JournalKo.sortByFIOsBack();' style="margin-left: 137px;">A</a> -->
                          </td>
                        </tr>

                        {assign var="number" value=1}

                        {foreach key=keyLearner item=learner from=$journalAllSubjects.learners name=A}
                          <tr class='studentKo' id="learner-{$keyLearner}-{$number}">
                            <td class='student'>   
                              <nobr>
                                <a  href='../index.php?id={$learner->idProfile}'>{$number}. {$learner->FIO()}</a>
                              </nobr>
                            </td>

                            <td class="option">
                              <div class="options" ></div>
                            </td>  
                          </tr>
                          {$number = $number + 1}
                        {/foreach}

                      </table>
                    </div>
                  </td>

                  <td class='rightAll'>
                    <div class='colsValues'>
                      <table cellspacing='0' cellpadding='0' id='rightPart'>                          
                        <tr>
                          {$number = 1}

                          {foreach key=keyLearner item=subject from=$subjectsOneClass name=A}
                            <td class='headerItem def'>
                              <span>{$subject.name}</span>

                              <div class='add'>
                                <div class='finnish'>
                                  <span onclick="JournalKo.sortByAverage('{$number}');">Сортировать</span>

                                  <div class='displayMenu' style='display:none; marh'>
                                    <span class='headermenu'>Сортировать</span>

                                    <span id='avg_{$number}' class='menuitem' onclick="JournalKo.sortByAverageForward('{$number}');">Средний балл</span>

                                    <span id='count_{$number}' class='menuitem' onclick="JournalKo.sortByCountMarksForward('{$number}');">Количество оценок</span>

                                    <span id='RAbsent_{$number}' class='menuitem' onclick="JournalKo.sortByReasonAbsentForward('{$number}');">Пропуск по причине</span>

                                    <span id='NRAbsent_{$number}' class='menuitem' onclick="JournalKo.sortByNoReasonAbsentForward('{$number}');">Пропуск без причины</span>
                                  </div> <!-- displayMenu -->
                                </div> <!-- finnish -->
                              </div> <!-- add -->
                            </td>

                            {$number = $number + 1} 
                          {/foreach}

                        </tr>
                            
                        <tr id="helpRowRight">
                          <td colspan='32' class='grMaster'></td>
                        </tr>

                        {foreach key=keyLearner item=learner from=$journalAllSubjects.marks name=A}
                          {$number = 1}
                          <tr id='marks-{$keyLearner}-{$number}' class='colsTabs'>

                            {foreach key=keySubgroup item=subgroup from=$learner name=A}
                              <td id='{$keyLearner}_{$number}'class='colItem'>
                                {if !$subgroup.error}
                                  <span class='first'>{$subgroup.averagemark}</span>/
                                  <span class='second'>{$subgroup.countmark}</span>/
                                  <span class='third'>{$subgroup.countabsent}</span>/
                                  <span class='fourth'>{$subgroup.countabsentreason}</span>
                                {/if}
                              </td>

                              {$number = $number+1}
                            {/foreach}

                          </tr>
                        {/foreach}

                      </table>
                    </div> <!-- colsValues -->
                  </td>  
                </tr>
              </table>
            </div> <!-- magazine -->
          {else}
            {if $journalOneSubject != NULL}
              <div class='monthesCont'>
                <div class='monthes'>

                  {foreach from=$months item=month}
                    <a class="{if $actMon==$month.id}actionItemActive{else}actionItem{/if}"href='cabinet.php?school={$ProfileKo->idSchool}&type=k&s=3&class={$idClass}&subject={$actSubj}&month={$month.id}'>
                        {$month.name}
                    </a>
                  {/foreach}

                </div>    
              </div> <!-- monthesCont --> 

              <div class='magazine'>
                <table class='magazine' cellspacing='0' cellpadding='0'>
                  <tr>
                    <td class='left'>
                      <div>
                        <table cellspacing="0" cellpadding="0">
                          <tbody>
                            <tr >
                              <td class='tabHeader'  colspan='2'> <span> Тема урока </span> </td>
                            </tr>

                            <tr>
                              <td class='tabHeader' colspan='2' > <span> День недели / дата урока </span> </td>
                            </tr>
                                            
                            {foreach key=key item=group from=$journalOneSubject name=groups}
                              {if $group.group != NULL}
                                <tr>
                                  <td class='subGroup' colspan='2'>
                                    <span>
                                      {$group.group.name} - 
                                    </span>
                                      <a href='../index.php?id={$group.teacher.iduser}'> {$group.teacher.FIOInitials}</a>
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

                                  <td class="option">
                                    <div class="options"> </div>
                                  </td>  
                                </tr>
                                
                                {$number = $number+1}
                              {/foreach}

                              {if $key != lessons && $key != marks && $key != hometasks}
                                <tr>  
                                  <td class='subGroupD' id='group_{$group.group.id}' colspan='2'
                                       onmouseover ="Journal.mouseOverTextHometask('{$group.group.id}')"
                                       onmouseout = "Journal.mouseOutTextHometask('{$group.group.id}')">
                                    <span>Домашнее задание</span>
                                  </td>
                                </tr>
                              {/if}
                            {/foreach}
                          </tbody>
                        </table>
                      </div>
                    </td> <!-- left --> 

                    <td class='right'>
                      <div class='colsValues'>
                        <table cellspacing="0" cellpadding="0">
                          <tbody>
                            {* ТЕМА УРОКА *}
                            <tr>
                              {assign var="number" value=0}{* НОМЕР ТИПА ПО ПОРЯДКУ В ТАБЛИЦЕ*}
                              {assign var="classType" value=''}
                                {if $journalOneSubject.lessons.spisoklessonstype != null}
                                  {$classType = 'headerItem'}
                                  {else}
                                    {$classType = 'headerItemSingle'}
                                {/if}

                              {foreach key=key item=lesson from=$journalOneSubject.lessons name=A}

                                {foreach key=key1 item=lessonType from=$lesson->spisoklessonstypes name=B}
                                  {$number = $number+1}

                                  <td class='headerItem def' id="type_{$number}" 
                                      onmouseover="Journal.mouseOverType('{$number}')"
                                      onmouseout="Journal.mouseOutType('{$number}')">
                                      <span>{$lessonType->name}</span>
                                  </td>
                                {/foreach}

                              {/foreach}

                              {****** rowspanCount - переменная для подсчета кол-ва lessons ******}
                              {if count($journalOneSubject.lessons) >0}
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
                              
                              {foreach key=key item=lesson from=$journalOneSubject.lessons name=A}
                                
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
                                                                      
                            {*ПЕРЕМЕННАЯ ДЛЯ ОПРЕДЕЛЕНИЯ КОЛИЧЕСТВА ГРУПП*}
                            {assign var="countGroups" value=$journalOneSubject|@count}
                            {if $countGroups - 3 > 1}

                              {foreach key=key item=group from=$journalOneSubject name=groups}
                                {if $group.teacher != NULL}
                                  <tr>  
                                    <td colspan='{$number+5}' class='grMaster'></td>
                                  </td>
                                {/if}

                                {assign var="row" value=1}

                                {foreach key=idw item=learner from=$group.learners name=learners}
                                  <tr>
                                    {assign var="markNumber" value=1}
                                    {assign var="result" value=1}
                                    {assign var="idL" value=$learner->idLearner}

                                      {foreach key=keyLes item=lesson from=$journalOneSubject.marks[$idL] name=A}

                                        {foreach key=keyLessT item=lessonType from=$lesson name=A}
                                          {assign var="groupIdRowIdMarkId" value=$group.group.id|cat:'_'|cat:$row|cat:'_'|cat:$markNumber}
                                            <td 
                                                {if $keyLess == avg}
                                                  id='avg_{$groupIdRowIdMarkId}_{$result}' class='colItem' 
                                                  onmouseover="Journal.mouseOverMark('avg_{$groupIdRowIdMarkId}_{$result}')"
                                                  onmouseout="Journal.mouseOutMark('avg_{$groupIdRowIdMarkId}_{$result}')"
                                                  {$result=$result+1}
                                                  {else}
                                                    id='mark_{$groupIdRowIdMarkId}' class='colItem' 
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

                                  {foreach key=keyL item=idLesson from=$journalOneSubject.hometasks[$idGr] name=A}

                                    {foreach key=keyLT item=idLessonType from=$idLesson name=A}
                                      <td 
                                          id='hometask_{$idGr}_{$keyL}_{$keyLT}_{$hometaskNumber}' class='colItem'
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
                              {/foreach}

                            {else}
                                                                        
                              {foreach key=key item=group from=$journalOneSubject name=groups name=oneGroup}
                                {if $smarty.foreach.oneGroup.iteration <= 1}
                                  {if $group.teacher != NULL}
                                    <tr> 
                                      <td colspan='{$number+3}' class='grMaster'></td>
                                    </tr>
                                  {/if}        
                              
                                  {assign var="row" value=1} 

                                  {foreach key=keyL item=learner from=$journalOneSubject.marks name=A}
                                    <tr>
                                    {assign var="markNumber" value=1}
                                    {assign var="result" value=1}

                                      {foreach key=keyLes item=lesson from=$learner name=A}

                                        {foreach key=keyLesT item=lessonType from=$lesson name=A}
                                          {assign var="groupIdRowIdMarkId" value=$group.group.id|cat:'_'|cat:$row|cat:'_'|cat:$markNumber}

                                          <td 
                                              {if $keyLes == avg}id='avg_{$groupIdRowIdMarkId}_{$result}' class='colItem' 
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
                                    {if $journalOneSubject.hometasks[$idGr] != null}
                                      {assign var="idGr" value=$group.group.id}
                                      {$hometaskNumber = 1}

                                      {foreach key=keyL item=idLesson from=$journalOneSubject.hometasks[$idGr] name=A}

                                        {foreach key=keyLT item=idLessonType from=$idLesson name=A}
                                          <td 
                                              id='hometask_{$idGr}_{$keyL}_{$keyLT}_{$hometaskNumber}' class='colItem' {if $idLessonType.status == 'true'}onclick="Journal.createHomeworkPopup('{$idGr}_{$keyL}_{$keyLT}_{$idSchool}')"{/if}
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
                                                               <!-- </div> -->
                                {/if}
                              {/foreach}
                            {/if}
                          </tbody>
                        </table>
                      </div> <!-- colsValues -->   
                    </td> <!-- right --> 
                  </tr>
                </table>
              </div><!-- magazine --> 
            {/if}
          {/if}
        {else}
          {$notices['ERROR']['messages'][] = $smarty.const.MW_CLASS_HAS_NO_SUBGROUPS}
          {$notices['ERROR']['type'] = 3}
        {/if}
      {else}
        <div class="journal">
          <span class='deleteAll' onclick='JournalKo.deleteCheckedLearnerResponsible({$idSchool}, {$idClass});'> Удалить отмеченных </span>
            <table cellspacing='0' cellpadding='0' id='tableLeRe'>
              <thead>
                <tr>
                  <td><input id="checkLearnerAll" type="checkbox" onchange="JournalKo.changeLearnersAllCheckBox($(this));">Ученики</td>
                  <td><input id="checkResponsibleAll" type="checkbox" onchange="JournalKo.changeResponsiblesAllCheckBox( $(this) );">Родители</td>
                </tr>
              </thead>
              <tbody>
                <!-- Номер ученика по списку -->
                {assign var="numberLearner" value=1}

                {foreach key=keyLearner item=learner from=$parentLearnerlist name=A}
                  <!-- Количество родителей одного ученика -->
                  {assign var="countResponsibles" value=$learner.responsibles|@count}
                  <!-- Счетчик для цикла родителей -->
                  {assign var="countFors" value=1}
                  <!-- Номер родителя по списку -->
                  {assign var="numberResponsible" value=1}
                  <tr>
                    <td rowspan='{$countResponsibles}' class='leftside'>
                      <nobr>
                        <input id="checkLearner_{$keyLearner}" type="checkbox" 
                               onchange="JournalKo.changeStateLearnerCheckBox( $(this) );">
                        <a href="../index.php?id={$learner.idProfile}">{$numberLearner}. {$learner.fio}</a>
                        <div id='learner_{$keyLearner}' class='options'
                             onclick="JournalKo.createLearnerPopup($(this), {$idClass}, {$idSchool});">
                        </div>  
                      </nobr>
                    </td>
                    
                    {foreach key=keyResponsible item=responsible from=$learner.responsibles name=A}
                      {if {$countFors} < 2}
                        <td {if $responsible.error != NULL}class="none"{/if}>
                          {if $responsible.error == NULL}
                            <nobr>
                              <input id="checkResponsible_{$keyResponsible}_learner_{$keyLearner}" type="checkbox" onchange="JournalKo.changeStateResponsibleCheckBox( $(this) );">
                              <a href="../index.php?id={$responsible.idProfile}">1. {$responsible.fio}</a>
                              <div id="responsible_{$keyResponsible}_learner_{$keyLearner}" class='options' onclick="JournalKo.createResponsiblePopup($(this), {$idClass}, {$idSchool});">
                              </div> 
                            </nobr>
                          {/if}
                        </td>
                      {else}
                        {if $countResponsibles > 1}
                          <tr>
                            <td>
                              <nobr>
                                <input id="checkResponsible_{$keyResponsible}_learner_{$keyLearner}" type="checkbox" onchange="JournalKo.changeStateResponsibleCheckBox( $(this) );">
                                <a href="../index.php?id={$responsible.idProfile}">{$numberResponsible}. {$responsible.fio}</a>
                                <div id="responsible_{$keyResponsible}_learner_{$keyLearner}" class='options' onclick="JournalKo.createResponsiblePopup($(this), {$idClass}, {$idSchool});">
                                </div> 
                              </nobr>
                            </td>
                          </tr>
                        {/if}
                      {/if}
                      
                      {$countFors = $countFors + 1}
                      {$numberResponsible = $numberResponsible + 1}
                    {/foreach}
                    
                    {$numberLearner = $numberLearner + 1}
                  </tr>
                {/foreach}

              </tbody>
            </table>

            <div class='addNewSt'>
              <span id='textSearchLearner' onclick="JournalKo.hideShowAddNewLearner();">
                Добавить нового ученика
              </span>
              <span id='textSearchResponsible' onclick="JournalKo.hideShowAddNewResponsible(0);">
                Добавить нового родителя
              </span>
            </div>

            <div id='searchStud' class='searchRslt' style='display:none;'>
              <div class='querry'>
                <input id='searchLearner' type='text'/>
                <!-- <input id='surnameSearch' type='text'/> -->
                <span onclick="JournalKo.searchLearnerOrResponsible($(this),'school_{$idSchool}_class_{$idClass}');">Поиск</span>
              </div>
            </div>

            <!-- <div class='addNewSt'>
              <span onclick="JournalKo.hideShowAddNewLearner();">
                Добавить нового ученика
              </span>
            </div> -->
            <div id='searchResp' class='searchRslt' style='display:none;'>
              <select id="learnersList">
                <option style="background-color: #bbb; color: #000" value="0">
                  --Выберите ученика--
                </option>

                {foreach key=keyLearner item=learner from=$parentLearnerlist name=A}
                  <option value="{$keyLearner}">
                    {$learner.fio}
                  </option>
                {/foreach}

              </select>

              <div class='querry'>
                <input id='searchResponsible' type='text'/>
                <!-- <input id='surnameSearch' type='text'/> -->
                <span onclick="JournalKo.searchLearnerOrResponsible($(this),'school_{$idSchool}_class_{$idClass}');">Поиск</span>
              </div>
            </div>
            <div class="countSerchedUsersAll" value="" hidden="true"></div>
        </div>  
      {/if}
      </div>
    {/if}

    {if $notices != NULL}  
      <div class='conformation'>
        {include file="../../general/notice.tpl" IDNOTICE="ERROR"}
      </div>  
    {/if}
  </div><!--end content-->
</div> <!--end mainfield-->