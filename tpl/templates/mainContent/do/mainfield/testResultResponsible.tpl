<div class="mainfield"> 
  <div class="content"> 
  {if $isSubjects}
  {*Верхняя полоса(предыдущий и следующий года, текущий год)*}
    <div class="next_prev">
    {if $startyear eq $yearofstart}
      <div style="float:left;margin-left: 341px;">
        {$startyear}/{$endyear} уч.г.
      </div>
    {else}
      <div class="prev">
        <a href="cabinet.php?learner={$currentLearner->idLearner}&type=l&s=4&m=9&y={$startyear-1}">Предыдущий год</a>
      </div>
      <div style="float:left;margin-left: 210px;">
        {$startyear}/{$endyear} уч.г.
      </div>
    {/if}
    {if $endyear eq $yearofend}
    {else}
      <div class="next">
        <a href="cabinet.php?learner={$currentLearner->idLearner}&type=l&s=4&m=9&y={$endyear}">Следующий год</a>
      </div>
    {/if}
    </div>
        
    {*Все месяца года*}
    <div class="monthesCont">
      <div class="monthes">
        <a {if $curmonth == 9}class="actMon"{/if} 
                              href="cabinet.php?learner={$currentLearner->idLearner}&type=l&s=4&m=9&y={$startyear}">Сентябрь</a>
        <a {if $curmonth == 10}class="actMon"{/if} 
                               href="cabinet.php?learner={$currentLearner->idLearner}&type=l&s=4&m=10&y={$startyear}">Октябрь</a>
        <a {if $curmonth == 11}class="actMon"{/if} 
                               href="cabinet.php?learner={$currentLearner->idLearner}&type=l&s=4&m=11&y={$startyear}">Ноябрь</a>
        <a {if $curmonth == 12}class="actMon"{/if} 
                               href="cabinet.php?learner={$currentLearner->idLearner}&type=l&s=4&m=12&y={$startyear}">Декабрь</a>
        <a {if $curmonth == 1}class="actMon"{/if} 
                              href="cabinet.php?learner={$currentLearner->idLearner}&type=l&s=4&m=1&y={$endyear}">Январь</a>
        <a {if $curmonth == 2}class="actMon"{/if} 
                              href="cabinet.php?learner={$currentLearner->idLearner}&type=l&s=4&m=2&y={$endyear}">Февраль</a>
        <a {if $curmonth == 3}class="actMon"{/if} 
                              href="cabinet.php?learner={$currentLearner->idLearner}&type=l&s=4&m=3&y={$endyear}">Март</a>
        <a {if $curmonth == 4}class="actMon"{/if} 
                              href="cabinet.php?learner={$currentLearner->idLearner}&type=l&s=4&m=4&y={$endyear}">Апрель</a>
        <a {if $curmonth == 5}class="actMon"{/if} 
                              href="cabinet.php?learner={$currentLearner->idLearner}&type=l&s=4&m=5&y={$endyear}">Май</a>
        <a {if $curmonth == 6}class="actMon"{/if} 
                              href="cabinet.php?learner={$currentLearner->idLearner}&type=l&s=4&m=6&y={$endyear}">Июнь</a>
        <a {if $curmonth == 7}class="actMon"{/if} 
                              href="cabinet.php?learner={$currentLearner->idLearner}&type=l&s=4&m=7&y={$endyear}">Июль</a>
        <a {if $curmonth == 8}class="actMon"{/if} 
                              href="cabinet.php?learner={$currentLearner->idLearner}&type=l&s=4&m=8&y={$endyear}">Август</a>
      </div>    
    </div>
    {*Табличка с оценками*}
    <div class="magazine">
      <table class="magazine" cellspacing="0" cellpadding="0">
        <tbody>
          <tr>
            <td class='leftAll'>
              <div>
                <table cellspacing="0" cellpadding="0">
                  <tbody>
                    <tr>
                      <td class='tabHeader'>
                        <span> День недели / дата урока </span>
                      </td>
                    </tr>
                    {assign var="i" value="0"}
                    {foreach $subjects as $subject}
                      {assign var="i" value=$i+1}
                      <tr id="subject_{$subject.id}" 
                          class='studentKo' 
                          onmouseout="testResultResponsible.onMouseOutSubject($(this), {$subject.id});" 
                          onmouseover="testResultResponsible.onMouseOverSubject($(this), {$subject.id});">
                        <td class='student'>
                          <a href="cabinet.php?learner={$currentLearner->idLearner}&s=2&subject={$subject.id}&group={$subject.learnergroup}">
                            <nobr>{$i}.{$subject.name}</nobr>
                          </a>
                        </td>
                      </tr>
                    {/foreach}
                  </tbody>
                </table>
              </div>
            </td>
                        
            <td class='right'>
              <div class="colsValues">
                <table cellspacing="0" cellpadding="0">
                  <tbody>
                    <tr>
                    {assign var="j" value="0"}
                    {foreach $datesTry as $dateTry}
                      {assign var="j" value=$j+1}
                      <td id="date_{$j}" 
                          class='headerItem def' 
                          onmouseover="testResultResponsible.onMouseOverDate($(this));" 
                          onmouseout="testResultResponsible.onMouseOutDate($(this));">
                          {$dateTry}
                      </td>
                    {/foreach}

                        <td  class='headerItemSingle' id='resMarkTd' style="height: 40px;"
                             onmouseover="testResultResponsible.onMouseOverResMarkColumn();"
                             onmouseout="testResultResponsible.onMouseOutResMarkColumn();">
                           Баллы
                        </td>

                        <td  class='headerItemSingle' id='ratingTd'
                             onmouseover="testResultResponsible.onMouseOverRatingColumn();"
                             onmouseout="testResultResponsible.onMouseOutRatingColumn();">
                            Рейтинг
                        </td>  
                    </tr>
                    {foreach $allmarks as $idsubject => $subjectmarks}
                      <tr>
                      {assign var="j" value="0"}
                      {foreach $subjectmarks as $ddate => $subjectmark}
                        {assign var="j" value=$j+1}
                        <td id="mark_{$idsubject}_{$j}" 
                            class="colItem" 
                            style="position: relative;" 
                            onclick="testResultResponsible.onClickMark
                                (
                                    $(this),
                                    {$idsubject},
                                    '{$ddate}',
                                    {$currentLearner->idLearner},
                                    {$currentLearner->idSchool}
                                );" 
                            onmouseover="testResultResponsible.onMouseOverMark($(this));" 
                            onmouseout="testResultResponsible.onMouseOutMark($(this));">
                          {$subjectmark.maxmark}
                          {if {$subjectmark.counttry} > 1}
                            /{$subjectmark.counttry}
                          {/if}
                        </td>
                      {/foreach}
                        <td id="mark_{$idsubject}_resMark"
                            class="colItem" 
                            style="position: relative;"
                            onmouseover="testResultResponsible.onMouseOverResMark($(this));" 
                            onmouseout="testResultResponsible.onMouseOutResMark($(this));">
                          {$rating[$idsubject].rating}
                        </td>
                        <td id="mark_{$idsubject}_rating"
                            class="colItem"
                            style="position: relative;" 
                            onmouseover="testResultResponsible.onMouseOverRating($(this));"
                            onmouseout="testResultResponsible.onMouseOutRating($(this));">
                        {$rating[$idsubject].position}
                        </td>
                      </tr>
                    {foreachelse}
                      {foreach $subjects as $subject}
                      <tr>
                        <td id="mark_{$subject.id}_resMark"
                            class="colItem" 
                            style="position: relative;"
                            onmouseover="testResultResponsible.onMouseOverResMark($(this));" 
                            onmouseout="testResultResponsible.onMouseOutResMark($(this));">
                          {$rating[$subject.id].rating}
                        </td>
                        <td id="mark_{$subject.id}_rating"
                            class="colItem"
                            style="position: relative;" 
                            onmouseover="testResultResponsible.onMouseOverRating($(this));"
                            onmouseout="testResultResponsible.onMouseOutRating($(this));">
                        {$rating[$subject.id].position}
                        </td>
                      </tr>
                      {/foreach}
                    {/foreach}
                  </tbody>
                </table>
              </div>
            </td>  
          </tr>
        </tbody>
      </table>
    </div>
    {else}
    <div class='conformation'>
      {include file="../../general/notice.tpl" IDNOTICE="MW_TESTRESULT_LEARNER_HAS_NO_SUBJECTS"}
    </div>
    {/if}
  </div><!--end content-->   
</div>{*end mainfield*}