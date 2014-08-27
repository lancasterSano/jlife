<div class="mainfield"> 
    <div class="content">
        <div class='conspectCont'>
            {if $partParagraphs != NULL}
                {assign var='numberQuestion' value = 1}
                
                {foreach key=keyPartPar item=partParagraph from=$partParagraphs name=A}
                    {$numberQuestion = 1}
                    <div id='partParagraph_{$keyPartPar}' class='questAll' >
                        <div  id='turnButtonPartParagraph_{$keyPartPar}' {if $partParagraph.questions == NULL}class='cutNoActive'{else}class='cutCl' onclick="Questions.loadQuestionsUI('partParagraph_{$keyPartPar}');"{/if}></div>
                    
                        <div class='type'>
                            <span>{$partParagraph.number}.{$partParagraph.name} (<span id='countQuestions_{$keyPartPar}'class='count'>{$partParagraph.countquestion}</span>)
                                <!-- Начало "Управление" -->
                                <div id='' class="questMenu" style='float:right;'>
                                    <div id='' class='finnish'>
                                        <span>
                                            Управление<img src='../../img/settings.png'>
                                        </span>
                                    
                                        <div class='displayMenu' style='display:none; marh'>
                                            <span class='headermenu'>Управление<img src='../../img/settings.png'></span>
                                            <!-- <span id='editQuestion_{$question.id}' class='menuitem' onclick="Questions.editQuestion('editQuestion_{$question.id}_{$number}_{$question.countanswer}_{$question.complexity}')">Редактировать</span> -->
                                            <span id='' class='menuitem' onclick="Questions.addQuestionUI('partParagraph_{$keyPartPar}')">Добавить вопрос</span>
                                            <span id='addQuestMenu_{$keyPartPar}_2' class='menuitem' onclick="Questions.loadQuestionsUI('partParagraph_{$keyPartPar}');">Свернуть</span>
                                        </div>
                                    </div>
                                </div>
                              <!-- Конец "Управление" -->
                            </span>
                        </div>

                        {foreach key=keyQuestion item=question from=$partParagraph.questions name=B}
                            <div id ='question_{$question.id}' class='questLvl'>
                                <div id='questName' class='questName'>
                                    <div id='turnButtonQuestion_{$keyPartPar}_{$keyQuestion}'class='cutCl' onclick="Questions.loadAnswersUI('question_{$question.id}_{$keyPartPar}');"> </div>

                                    <span id='title'>{$numberQuestion}. {$question.text}(<span class='count'>{$question.countanswer}</span>)</span>
                                
                                    <div id='' style='float:right;'>
                                        <div id='' class='finnish'>
                                            <span>
                                                Управление<img src='../../img/settings.png'>
                                            </span>
                                            
                                            <div class='displayMenu' style='display:none;'>
                                                <span class='headermenu'>Управление<img src='../../img/settings.png'></span>
                                                <span id='addAnswerFast_{$keyPartPar}_{$keyQuestion}' class='menuitem' onclick="Questions.addAnswerFast('{$keyPartPar}','{$keyQuestion}')">Добавить ответ</span>
                                                <span id='editQuestion_{$question.id}' class='menuitem' onclick="Questions.editQuestion('editQuestion_{$question.id}_{$numberQuestion}_{$question.countanswer}_{$question.complexity}')">Редактировать</span>
                                                <span id='' class='menuitem' onclick="Questions.deleteQuestion('question_{$question.id}_{$keyPartPar}');">Удалить</span>
                                                <span id='addQuestMenu_3_{$question.id}_2' class='menuitem' onclick="Questions.loadAnswersUI('question_{$question.id}_{$keyPartPar}');">Развернуть</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div id='questTools' class='questTools'>
                                    <span>
                                          сложность вопроса   - {$question.complexity}
                                    </span>
                                </div>
                            </div>

                            {$numberQuestion = $numberQuestion + 1}
                        {/foreach}

                    </div> 

                    <hr> 
                {/foreach}
                
            {else}
                {$notices['NO_PARTPARAGRAPHS']['messages'][] = $smarty.const.MW_PARAGRAPH_HAS_NO_PARTPARAGRAPHS}
                {$notices['NO_PARTPARAGRAPHS']['type'] = 3}
                <div class='conformation'>
                  {include file="../../general/notice.tpl" IDNOTICE="NO_PARTPARAGRAPHS"}
                </div>
            {/if}
        </div>
    </div><!--end content-->   
</div> <!--end mainfield-->