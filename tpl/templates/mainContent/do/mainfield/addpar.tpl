<div class="mainfield"> 
  <div class="content">  
    <div class='conspectCont'>
      <div class='parItem'>
        {if $partparagraphs != NULL}
          
          {foreach $partparagraphs as $partparagraph}
            <div id='DeleteSure_{$partparagraph.id}' class='btn' style='display:none;' >
              <div>Удалить эту часть параграфа?</div>
              <span  onclick = "addpar.Delete('addparMenu_3_{$partparagraph.id}');" >&nbspДа&nbsp</span>
              <span onclick='$("div[id^=DeleteSure_{$partparagraph.id}]").hide();'>&nbspНет&nbsp</span>
            </div>
            
            <div id = "namePartParagraph_{$partparagraph.id}" class='questName'>
              <div id = "turnButton_{$partparagraph.id}" class='cutCl' onclick="addpar.Turn('addparMenu_4_{$partparagraph.id}');"></div>
              <span id = "number_{$partparagraph.number}" >{$partparagraph.number}.{$partparagraph.name}</span>
              
              <!-- Меню действий НАЧАЛО-->
              <div id = 's_{$partparagraph.id}' style="float:right;">
                <div id="some_2" class='finnish'>
                  <span>Управление<img src='{$PROJECT_PATH}/img/settings.png' \></span>
                  <div class='displayMenu' style='display:none;'>
                    <span class="headermenu">Управление<img src='{$PROJECT_PATH}/img/settings.png'\></span>
                    <span id = "addparMenu_1_{$partparagraph.id}" class="menuitem" onclick="location.href='addQuest.php?school={$school}&paragraph={$paragraph.id}&partparagraph={$partparagraph.id}'">Добавить вопрос</span>
                    <!-- <span id = "addparMenu_2_{$partparagraph.id}" class="menuitem" onclick="addpar.Edit('addparMenu_2_{$partparagraph.id}_{$paragraph.id}');">Редактировать</span> -->
                    <span id = "addparMenu_2_{$partparagraph.id}" class="menuitem" onclick="addpar.Edit('addparMenu_2_{$partparagraph.id}_{$paragraph.id}');">Редактировать</span>
                    <span id = "addparMenu_3_{$partparagraph.id}" class="menuitem" onclick='$("div[id^=DeleteSure_]").hide(); $("div[id^=DeleteSure_{$partparagraph.id}]").show();'>Удалить</span>
                    <span id = "addparMenu_4_{$partparagraph.id}_2" class="menuitem" onclick="addpar.Turn('addparMenu_4_{$partparagraph.id}');">Cвернуть</span>
                  </div>
                </div>       
              </div>
              <!-- Меню действий КОНЕЦ -->
            </div>

            <div id = "partParagraph_{$partparagraph.id}" class='questTools'> 
              <div class="partext">
                <p >{$partparagraph.text}</p> 
              </div>
            </div>  
            <div id = "EditParagraph_{$partparagraph.id}"> </div>
          {/foreach}

        {else}
          {$notices['NO_PARTPARAGRAPHS']['messages'][] = $smarty.const.MW_PARAGRAPH_HAS_NO_PARTPARAGRAPHS}
          {$notices['NO_PARTPARAGRAPHS']['type'] = 3}
          
          <div class='conformation'>
            {include file="../../general/notice.tpl" IDNOTICE="NO_PARTPARAGRAPHS"}
          </div>
        {/if}
        
        <div id="s_newPart" style='display:none;' class='questName'>
          <div class="styled-select">
            <select>
              <option id="newPart_number" selected value="{$paragraph.countpart+1}">{$paragraph.countpart+1}</option>
            </select>
          </div>

          <textarea id="newPart_name"></textarea>
          
          <div class='manage'>
            <a id = 'addPart_{$paragraph.id}' onclick = "addpar.addPart('addPart_{$paragraph.id}')" >  <span style='margin-bottom:3px;'>Сохранить</span></a>
            <a onclick="$('#s_newPart').hide();">  <span>Отменить</span></a>
          </div>
          
          <div class='editorContainer'>
            <textarea id="newPart_text" class='quest' onfocus='addpar.showCKEditor();'></textarea>
          </div>
        </div>
      </div>
    </div>
  </div><!--end content-->   
</div> <!--end mainfield-->