<div class="mainfield"> 
    <div class="content" id="contentId">
      {*ВЫГРУЖАЕМ МЕСЯЦА НА ТЕКУЩИЙ СЕМЕСТР*}
      {if $educationNotStartYet != true}
        {if $months != NULL}
          {if $journalResponsible.subjects != NULL}
            <div class='monthesCont'>
              <div class='monthes'>
                {foreach from=$months item=month}
                  <a class="{if $actMon==$month.id}actionItemActive{else}actionItem{/if}"href='cabinet.php?learner={$currentLearner->idLearner}&type=l&s=3&m={$month.id}{*&y={$startYear}*}'>
                      {$month.name}
                    </a>
                {/foreach}
              </div>    
            </div>

            <div class='magazine'>
              <table class='magazine' cellspacing='0' cellpadding='0'>
                <tr>
                  <td class="left">
                    <div>
                      <table cellspacing="0" cellpadding="0">
                        <tbody>
                          <tr>
                            <td class='tabHeader' > <span> День недели / дата урока </span> </td>
                          </tr>
                          
                          {assign var="row" value=1}
                          
                          {foreach key=keySub item=subject from=$journalResponsible.subjects name=groups name=oneGroup}
                            <tr>  
                              <td id = 'subject_{$keySub}' class='student'
                                   onmouseover="JournalResponsible.mouseOverSubject('subject_{$keySub}')"
                                   onmouseout="JournalResponsible.mouseOutSubject('subject_{$keySub}')">
                                  <a href=''><nobr>{$row}.{$subject.name} </nobr></a>
                              </td>
                            </tr>
                            
                            {$row = $row+1}
                          {/foreach}

                        </tbody>
                      </table>
                    </div>
                  </td>

                  <td class='right'>
                    <div class='colsValues'>
                      <table cellspacing="0" cellpadding="0">
                        <tbody>
                          {assign var="number" value=0}{* НОМЕР ТИПА ПО ПОРЯДКУ В ТАБЛИЦЕ*}
                          {* ДАТА УРОКА *}
                          <tr>
                            {$number = 1}
                            
                            {foreach key=keyDate item=d from=$date name=groups}
                              <td class='headerItem def' id="date_{$d.number}_{$number}"
                                  onmouseover="JournalResponsible.mouseOverType('{$number}')"
                                  onmouseout="JournalResponsible.mouseOutType('{$number}')">
                                  <span>{$lesson->date|date_format:"%d"} {$d.dateForTpl}</span>
                              </td>

                              {$number = $number+1}
                            {/foreach}

                            <td class='headerItemSingle end' id='result_{$number}'
                                    onmouseover="JournalResponsible.mouseOverResult({$number})"
                                    onmouseout="JournalResponsible.mouseOutResult({$number})">
                                  <span>Оценок</span>
                            </td>
                            {$number = $number + 1}
                            
                            <td class='headerItemSingle end' id='result_{$number}'
                                  onmouseover="JournalResponsible.mouseOverResult({$number})"
                                  onmouseout="JournalResponsible.mouseOutResult({$number})">
                                  <span>Средняя</span>
                            </td>  
                            {$number = $number + 1}
                              
                            <td class='headerItemSingle end' id='result_{$number}'
                                  onmouseover="JournalResponsible.mouseOverResult({$number})"
                                  onmouseout="JournalResponsible.mouseOutResult({$number})">
                                  <span>Пропуски</span>
                            </td>

                          </tr>
                            
                          {foreach key=keySubject item=idSubject from=$journalResponsible.marks name=A}
                            <tr>
                              {assign var="markNumber" value=1}
                              
                              {foreach key=keyDate item=dateNumber from=$idSubject name=A}
                                
                                {foreach key=keyLess item=idLesson from=$dateNumber name=A}
                                  {assign var="result" value=1}
                                    <td id='mark_{$keySubject}_{$currentLearner->idLearner}_{$keyLess}_{$markNumber}' class='colItem {if ($keyLess == error && $keyDate != avg)}noLesson{/if}'
                                        {if $keyLess != error && $idLesson.maxMark != "" && $idLesson.countMarks != "" && $keyDate != avg }
                                        onclick="JournalResponsible.createMarksPopup({$keySubject}, {$currentLearner->idLearner}, {$keyLess})"
                                        {/if}
                                        onmouseover="JournalResponsible.mouseOverMark('mark_{$keySubject}_{$keyLess}_{$markNumber}')"
                                        onmouseout="JournalResponsible.mouseOutMark('mark_{$keySubject}_{$keyLess}_{$markNumber}')"
                                        >
                                            {if $keyLess == countmark || $keyLess == countabsent || $keyLess == averagemark}
                                                    {$idLesson}
                                              {else if $keyLess != error && $idLesson.maxMark && $idLesson.countMarks }
                                                {$idLesson.maxMark}/{$idLesson.countMarks}
                                            {/if}
                                    </td>
                                    
                                    {$markNumber=$markNumber+1} 
                                {/foreach}

                              {/foreach}

                            </tr>
                          {/foreach}
                          
                        </tbody>
                      </table>
                    </div>   
                  </td>  
                </tr>
              </table>
            </div>
          </div>
          {else}
            {$notices['ERROR']['messages'][] = $smarty.const.MW_CLASS_YOUR_CHILD_HAS_NO_SUBGROUPS}
            {$notices['ERROR']['type'] = 3}
          {/if}
        {else}
          {$notices['ERROR']['messages'][] = $smarty.const.MW_SCHOOL_HAS_NO_STUDYDURATION}
          {$notices['ERROR']['type'] = 3}
        {/if}
      {else}
        {$notices['ERROR']['messages'][] = $smarty.const.MI_EDUCATION_NOT_START_YET}
      {/if}
        
      {if $notices['ERROR']['messages'] != NULL}  
        <div class='conformation'>
          {include file="../../general/notice.tpl" IDNOTICE="ERROR"}
        </div>
      {/if}
    </div><!--end content-->   
</div> <!--end mainfield-->