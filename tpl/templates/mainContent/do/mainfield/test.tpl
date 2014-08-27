<div class="mainfield"> 
  <div class="content">

   
    <div class='conspectCont'>
    {if $Questions != NULL}
      <div id="result3" class='resultB' onclick="Test.finishTest('{$paragraph.id}_{$school}_{$subject.id}_{$paragraph.material}')" >
          <a  class='finnish' >Завершить тест</a>
          </div>
      <div style="display: none;" id="result1" class='result'>
        <span  id="result"></span>
        <a href="paragraph.php?school={$school}&paragraph={$paragraph.id}" class='finnish' >Завершить тестирование</a>
      </div>  
          {foreach $Questions as $Question}

         <div id = "Question_{$Question.id}" class='testBlock' style="border-color: {$subject.color}">
         
                <span>

                  {$Question.number}.&nbsp;{$Question.text}
                </span>
                  <ol >
                  {foreach $Question.answers as $Answer}
                    <li><a onclick="Test.SelectAnswer('Answer_{$Question.id}_{$Answer.id}');" id="Answer_{$Question.id}_{$Answer.id}" >{$Answer.number}.&nbsp;{$Answer.text} </a></li>
                   
                  {/foreach}
                  </ol>
          
              </div> 
          
          {/foreach}
   

          <div id="result4" class='resultB' onclick="Test.finishTest('{$paragraph.id}_{$school}_{$subject.id}_{$paragraph.material}')" >
          <a  class='finnish' >Завершить тест</a>
          </div>

          <div style="display: none;" id="result2" class='resultB'>
          <a href="paragraph.php?school={$school}&paragraph={$paragraph.id}" class='finnish' >Завершить тестирование</a>
          </div>
    {else}
      {$notices['TEST_NOT_READY']['messages'][] = $smarty.const.MW_TEST_NOT_READY}
      {$notices['TEST_NOT_READY']['type'] = 3}
      <div class='conformation'>
        {include file="../../general/notice.tpl" IDNOTICE="TEST_NOT_READY"}
      </div>
    {/if}
    </div>
  </div><!--end content-->   
</div> <!--end mainfield-->