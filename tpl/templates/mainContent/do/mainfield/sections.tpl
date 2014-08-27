<div class="mainfield"> 
  <div class="content">
    <div class="subjTable" id="subjTable_{$ProfileTeacher->idTeacher}_{$idmaterial}_{$idschool}">
      <table class="subjItem" cellspacing="0" cellpadding="0">
        
        <tr class="subject" align="center" style="background-color: {$subjectcolor};">
          <td colspan="4">
            <span class="subjectTitle">{$subjectname}</span>
              <a class="drop teacherTitle" href="">{*TODO: вписать сюда ссылку на страницу учителя*}
                <span class="teacherTitle">{$ProfileTeacher->FIO()}</span>
              </a>
          </td>
        </tr>
        
        <tr style="display: none;" class="subTitle" align="center">
          <td colspan="4">
            <a href="">
              <span>Другие конспекты по программированию для студентов 3 курса</span>
            </a>
          </td>
        </tr>
                
        {if $isSections}
        <tr class="subTitle" align="center">
          <td style="text-align: center; color: #000;" colspan="4">Активные разделы</td>
        </tr>
                
        <tr class='headsectiontr'>
          <td class='forMenu' ></td>

          <td>
              <span class='fst'>№ Название раздела</span>
          </td>

          <td>

          </td>

          <td title="Активных параграфов/Всего параграфов" class='trd'>
              <span>Акт/Кол</span>
          </td>
        </tr>
        {*Вывод активных секций и параграфов*}
        {foreach $sectionsActive as $section}
          <tr id="section_{$section.id} " class="defaultS section">
            <td class='forMenu'
            </td>
            {if $section.id neq $expandsection}
              {assign var="tdclass" value=" hidden"}
            {else}
              {assign var="tdclass" value=""}
            {/if}
            <td class="NoteDescription{$tdclass}" id="sectiontd_{$section.id}" colspan='2'>
              <span class="drop">
                <p>{$section.number}. </p>
                {$section.name}
              </span>
            </td>
            
            <td class='default'>
              <span style="color: #000;">
                {$section.countactiveparagraphs}/{count($section.paragraphs)}
              </span>
            </td>
          </tr>
          {if $section.paragraphs}
            <tr class="sectiontr title_{$section.id}"  {if $section.id neq $expandsection}style="display:none;"{else}{/if}>
              <td class='forMenu'>

              </td> 

              <td>
                <span class='fst'>№ Название параграфа</span>
              </td>

              <td>
                <span class='def'>Тест</span>
              </td>

              <td>
                <span title="Частей Вопросов" class='def'>ч. в.</span>
              </td>
            </tr>
            {foreach $section.paragraphs as $paragraph}
              <tr class="paragraph_{$section.id} default" {if $section.id neq $expandsection}style="display: none;"{else}{/if}>
                <td class='forMenu' id='menu_{$section.id}_{$paragraph->idParagraph}'
                    onclick="sections.onForMenuClick($(this), {$paragraph->idParagraph}, {$idschool}, {$section.id}, {$ProfileTeacher->idTeacher}, {$idmaterial});">

                </td>

                <td id="paragraphtd_{$paragraph->idParagraph}" class="NoteDescription paragraphClickable">
                {if $paragraph->notstudy}
                  <span id="par_{$paragraph->idParagraph}" class="paragr unact">
                {else}
                  <span id="par_{$paragraph->idParagraph}" class="paragr drop">
                {/if}
                    <p>§{$paragraph->number}</p>
                    <div id="parname_{$paragraph->idParagraph}" style="float: left;width: 585px;"><span style="margin: 0">{$paragraph->name}</span></div>
                  </span>
                </td>

                <td class='default'>
                  <span {if $paragraph->isTestReady}title="Тест доступен"{else}title="Тест недоступен"{/if} class="testAnswer">
                    {if $paragraph->isTestReady}+{else}-{/if}
                  </span>
                </td>

                <td class='default'>
                  <span title="Частей: {$paragraph->countpart}. Вопросов: {$paragraph->countquestion}" class="testAnswer">
                    {$paragraph->countpart} {$paragraph->countquestion}
                  </span>
                </td>
              </tr>
              {/foreach}
            {/if}
            <tr id="addParagraph_{$section.id}" {if $section.id neq $expandsection}style="display:none;"{else}{/if}>
              <td class='addNew' colspan="4">
                <div id='addPar_{$section.id}' class='addPar' 
                     onclick="sections.showAddingParagraph($(this), {$section.id});">
                  <span>Добавить параграф</span>
                </div>
                <div id="info_section_{$section.id}" style="float: right; margin-right: 10px;">
                    <span onclick="sections.openInfoSection({$section.id});">Подробнее...</span>
                </div>
                <table id="infosection_{$section.id}" style="display:none;" class='infoSubj' cellspacing="0" cellpadding="0">
                  <thead>
                    <tr>
                      <td class='hourNum'>К-сть годин</td>
                      <td class='desccol'>Зміст навчального матеріалу</td>
                      <td class='desccol'>Державні вимоги до рівня загальноосвітньої підготовки учнів</td>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td class="hourNum">{$section.counthours}</td>
                      <td class='desccoluniq'>
                        <strong>Тема {$section.number}. {$section.nameUpperCase}</strong>
                        {foreach $section.topics as $topic}
                            {$topic}.&nbsp
                        {/foreach}
                      </td>
                      <td>
                          {foreach $section.requirements as $requirement}
                              <span>
                                <strong>{$requirement.action}</strong>
                                {$requirement.description}
                              </span>
                          {/foreach}
                      </td>
                    </tr>
                  </tbody>
                </table>
                <div id="parText_{$section.id}" class='parText' style="display: none;">
                    <div class='addNew' contenteditable="true" onkeyup="sections.onChangePar({$section.id});" id="textarea_{$section.id}"></div>
                  <span id="infospan_{$section.id}" style="width: 97%;color: darkred;">
                  </span>
                  <span id="saveOrYesBut_{$section.id}" class="confirmOrYes">
                      Сохранить
                  </span>
                  <span id="cancelOrNoBut_{$section.id}" class="cancelOrNo">
                      Отменить
                  </span>
                </div>
              </td>
            </tr>
        {/foreach}
                    
                {*Вывод архивных секций и параграфов*}
                {if $sectionsDeleted}
                <tr class="subTitle" align="center">
                    <td style="text-align: center; color: #000;" colspan="3">Архивные разделы</td>
                </tr>
                <tr class='headsectiontr'>
                    <td><span class='fst'>№ Название</span></td>
                    <td></td>
                    <td class='trd'><span>Акт/Кол</span></td>
                </tr>
                {foreach $sectionsDeleted as $section}
                   <tr id="section_{$section.id} " class="default section">
                        <td class="NoteDescription hidden" id="sectiontd_{$section.id}"><a class="unact" href="#"><span>{$section.number}. {$section.name}</span></a></td>
                        
                        <td></td>
                        <td class='default'><span style="color: #000;">0/{count($section.paragraphs)}</span></td>
                    </tr>
                    {if isset($section.paragraphs)}
                         <tr class="sectiontr title_{$section.id}" style="display:none;">
                                 <td><span class='fst'>№</span></td>
                                 <td><span class='def'>Тест</span></td>
                                 <td><span class='def'>ч. в.</span></td>
                              </tr>
                        {foreach $section.paragraphs as $paragraph}
                            
                            <tr class="paragraph_{$section.id} default" style="display: none;">
                                <td class="NoteDescription paragraphClickable" onclick="location.href='../do/addpar.php?school={$idschool}&paragraph={$paragraph->idParagraph}';">
                                    <span class="paragr unact">
                                        <p>§{$paragraph->number}</p>{$paragraph->name}
                                    </span>
                                </td>
                                <td class='default'><span style="color: #000;">{if $paragraph->isTestReady}+{else}-{/if}</span></td>
                                <td class='default'><span style="color: #000;">{$paragraph->countpart} {$paragraph->countquestion}</span>
                                </td>
                            </tr>
                        {/foreach}
                    {/if}
                {/foreach}
                {/if}
            {else}
              <tr><td style="padding: 7px 12px 0px 10px">
                <div class='conformation'>
                  {include file="../../general/notice.tpl" IDNOTICE="MW_SECTIONSMATERIAL_HAS_NO_SECTIONS"}
                </div>
              </td></tr>
            {/if}
            </table>    
        </div>
    </div> <!--end content-->
</div>   <!--end mainfield-->
