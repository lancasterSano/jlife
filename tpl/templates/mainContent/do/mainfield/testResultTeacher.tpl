<div class="mainfield"> 
    <div class="content">
    {if $isSubjectsAssigned}
          {*Все подгруппы*}
        <div class="subjects">
            {foreach $subgroups as $idsubgroup => $subgroup}
            <ul class="sub">
                <li> 
                    <a {if $idsubgroup == $curgroup}class="actSubj"{/if} href="cabinet.php?school={$ProfileTeacher->idSchool}&amp;g={$idsubgroup}&amp;type=s&s=4">
                          {$subgroup.namesubject}
                    </a>
                </li>
            </ul>
            {/foreach}
        </div>
        {if $isLearnersInGroup}
          <div class="sortBy">
            <a {if !isset($detailInfo)}class="actMenu"{/if} href="cabinet.php?school={$ProfileTeacher->idSchool}&g={$curgroup}&type=s&s=4">Общие результаты</a>
            <a {if isset($detailInfo)}class="actMenu"{/if}href="cabinet.php?school={$ProfileTeacher->idSchool}&g={$curgroup}&type=s&s=4&detailInfo=true">Результаты по параграфам</a>
            {if isset($detailInfo)}<span onclick="printDiv('test');">Печать</span>{/if}
          </div>

          {if $detailInfo == NULL}
          {*Все месяца года*}
          <div class="monthesCont">
              <div class="monthes">
                  <a {if $curmonth == 9}class="actMon"{/if} href="cabinet.php?school={$ProfileTeacher->idSchool}&m=9&g={$curgroup}&type=s&s=4">Сентябрь</a>
                  <a {if $curmonth == 10}class="actMon"{/if} href="cabinet.php?school={$ProfileTeacher->idSchool}&m=10&g={$curgroup}&type=s&type=s&s=4">Октябрь</a>
                  <a {if $curmonth == 11}class="actMon"{/if} href="cabinet.php?school={$ProfileTeacher->idSchool}&m=11&g={$curgroup}&type=s&s=4">Ноябрь</a>
                  <a {if $curmonth == 12}class="actMon"{/if} href="cabinet.php?school={$ProfileTeacher->idSchool}&m=12&g={$curgroup}&type=s&s=4">Декабрь</a>
                  <a {if $curmonth == 1}class="actMon"{/if} href="cabinet.php?school={$ProfileTeacher->idSchool}&m=1&g={$curgroup}&type=s&s=4">Январь</a>
                  <a {if $curmonth == 2}class="actMon"{/if} href="cabinet.php?school={$ProfileTeacher->idSchool}&m=2&g={$curgroup}&type=s&s=4">Февраль</a>
                  <a {if $curmonth == 3}class="actMon"{/if} href="cabinet.php?school={$ProfileTeacher->idSchool}&m=3&g={$curgroup}&type=s&s=4">Март</a>
                  <a {if $curmonth == 4}class="actMon"{/if} href="cabinet.php?school={$ProfileTeacher->idSchool}&m=4&g={$curgroup}&type=s&s=4">Апрель</a>
                  <a {if $curmonth == 5}class="actMon"{/if} href="cabinet.php?school={$ProfileTeacher->idSchool}&m=5&g={$curgroup}&type=s&s=4">Май</a>
                  <a {if $curmonth == 6}class="actMon"{/if} href="cabinet.php?school={$ProfileTeacher->idSchool}&m=6&g={$curgroup}&type=s&s=4">Июнь</a>
                  <a {if $curmonth == 7}class="actMon"{/if} href="cabinet.php?school={$ProfileTeacher->idSchool}&m=7&g={$curgroup}&type=s&s=4">Июль</a>
                  <a {if $curmonth == 8}class="actMon"{/if} href="cabinet.php?school={$ProfileTeacher->idSchool}&m=8&g={$curgroup}&type=s&s=4">Август</a>
              </div>    
          </div>

          <div class="magazine">
            <table class="magazine" cellspacing="0" cellpadding="0">
              <tr>
                <td class='leftAll'>
                  <div>
                    <table cellspacing="0" cellpadding="0">
                      <tbody>
                        <tr>
                          <td class='tabHeader' > <span> День недели / дата урока </span> </td>
                        </tr>
                        {assign var="i" value="0"}
                        {foreach $learners as $learner}
                        {assign var="i" value=$i+1}
                        <tr class='studentKo' onmouseout="testResultTeacher.onMouseOutLearner($(this), {$learner->idLearner});" onmouseover="testResultTeacher.onMouseOverLearner($(this), {$learner->idLearner});" 
                                   id="learner_{$learner->idLearner}">
                          <td class='student'>
                            <a href="{$PROJECT_PATH}/pages/index.php?id={$learner->idProfile}"><nobr>{$i}.{$learner->FI()}</nobr></a>
                          </td>
                        </tr>
                        {/foreach}
                      </tbody>
                    </table>
                  </div>
                </td>
                <td class='right'>
                  <div class='colsValues'>
                    <table cellspacing="0" cellpadding="0">
                      <tbody>

                        <tr>
                        {assign var="j" value="0"}
                        {foreach $ddates as $ddate}
                        {assign var="j" value=$j+1}
                          <td class='headerItem def' onmouseover="testResultTeacher.onMouseOverDate($(this));" onmouseout="testResultTeacher.onMouseOutDate($(this));" id="date_{$j}">
                            {$ddate}
                          </td>
                        {/foreach}
                          <td  class='headerItemSingle' id='resMarkTd'
                               onmouseover=""
                               onmouseout="">
                             Баллы
                          </td>

                          <td class='headerItemSingle fuckTheBorderR' id='ratingTd'
                               onmouseover="testResultTeacher.onMouseOverRatingColumn();"
                               onmouseout="testResultTeacher.onMouseOutRatingColumn();">
                              Рейтинг
                          </td>  
                        </tr>
                        {foreach $learners as $learner}
                        <tr>
                        {assign var="j" value="0"}
                        {foreach $indexdates as $idate}
                        {assign var="j" value=$j+1}
                        {assign var="idlearner" value=$learner->idLearner}
                          <td class="colItem" style="position: relative;" 
                              onclick="testResultTeacher.onClickMark($(this), {$idcurrentsubject}, '{$idate}', {$idlearner}, {$idcurrentmaterial}, {$ProfileTeacher->idSchool});" 
                              onmouseover="testResultTeacher.onMouseOverMark($(this));"
                              onmouseout="testResultTeacher.onMouseOutMark($(this));"
                              id="mark_{$idlearner}_{$j}">
                            {$marks.$idlearner.$idate.maxmark}
                            {if $marks.$idlearner.$idate.counttry > 1}/{$marks.$idlearner.$idate.counttry}{/if}
                          </td>
                        {/foreach}
                          <td id="mark_{$idlearner}_resMark"
                              class="colItem" 
                              style="position: relative;"
                              onmouseover="testResultTeacher.onMouseOverResMark($(this));"
                              onmouseout="testResultTeacher.onMouseOutResMark($(this));">
                            {$ratingNumber[$idlearner].rating}
                          </td>
                          <td id="mark_{$idlearner}_rating"
                              class="colItem fuckTheBorderR"
                              style="position: relative;"
                              onmouseover="testResultTeacher.onMouseOverRating($(this));"
                              onmouseout="testResultTeacher.onMouseOutRating($(this));">
                            {$ratingNumber[$idlearner].position}
                          </td>
                        </tr>
                        {/foreach}
                      </tbody>
                    </table>
                  </div>
                </td>
              </tr>
            </table>
          </div>
          {else}

          <div id='test'>
            <div class='testInfo' >
            {if $paragraphs != NULL}
            <div class='paragraph'>
              <strong>{$namesubject}</strong> 
                {foreach key=number item=paragraph from=$paragraphs name=A}
                  <a {if $idParagraph == $paragraph.id}class="actParagraph"{/if} href="cabinet.php?school={$ProfileTeacher->idSchool}&g={$curgroup}&type=s&s=4&detailInfo=true&par={$paragraph.id}" id="{$paragraph.id}">§{$paragraph.number}</a>
                {/foreach}
            </div>
            <div class='parInfo'>
              <strong>Тема:</strong><span>{$nameSection}</span>
            </div>  
            <div class='parInfo'>
              <strong>§{$numberParagraph}:</strong><span>{$nameParagraph}</span>
            </div>
            <table cellspacing='0' cellpadding='0'>
              <thead>
                <tr>
                  <td class='left'>
                      Ученики
                  </td>
                  <td colspan='5'>
                      Последняя статистика
                  </td>
                  <td colspan='1'>
                      Кол-во оценок
                  </td>
                  <td colspan='1'>
                      Наивысшая
                  </td>
                </tr>
              </thead>
              
              <tbody>
                  {assign var="i" value="0"}
                  {assign var="countMarks" value="0"}
                    {foreach key=idLearner item=learner from=$learners}
                    {$i = $i+1}
                    {$countMarks = 0}
                  <tr>
                    <td class='left'>{$i}.{$learner->FI()}</td>
                    {foreach key=idTest item=test from=$massOfTests[$idLearner]}
                      {if $idTest != countMarks || $idTest != maxMark}
                        {$countMarks = $countMarks+1}
                        <td>{$test.mark}б.<br>{$test.datetime}</td>
                      {/if}
                    {/foreach}
                    {for $foo=1 to 5-{$countMarks}}
                        <td></td>
                    {/for}
                      <td>{$massOfCountMaxMarks[$idLearner].countMarks}</td>
                      <td>{$massOfCountMaxMarks[$idLearner].maxMark}</td>
                  </tr>
                  {/foreach}
              </tbody>
            </table>
            {else}
              {$notices['MI_SUBJECT_HAS_NO_PARAGRAPHS']['messages'][] = $smarty.const.MI_SUBJECT_HAS_NO_PARAGRAPHS}
              {$notices['MI_SUBJECT_HAS_NO_PARAGRAPHS']['type'] = 3}
              <div class='conformation'>
                {include file="../../general/notice.tpl" IDNOTICE="MI_SUBJECT_HAS_NO_PARAGRAPHS"}
              </div>
            {/if}

            </div>
          </div>
          {/if}
      {else}
          {$notices['MI_TESTRESULT_TEACHER_HAS_NO_LEARNERS_TEXT']['messages'][] = $smarty.const.MI_TESTRESULT_TEACHER_HAS_NO_LEARNERS_TEXT}
          {$notices['MI_TESTRESULT_TEACHER_HAS_NO_LEARNERS_TEXT']['type'] = 3}
          <div class='conformation'>
            {include file="../../general/notice.tpl" IDNOTICE="MI_TESTRESULT_TEACHER_HAS_NO_LEARNERS_TEXT"}
          </div>
      {/if}

    {else}
      <div class='conformation'>
        {include file="../../general/notice.tpl" IDNOTICE="ME_TESTRESULT_TEACHER_HAS_NO_SUBGROUPS"}
      </div>
    {/if}
    </div>  {*    end content   *}
</div>      {*    end mainfield *}