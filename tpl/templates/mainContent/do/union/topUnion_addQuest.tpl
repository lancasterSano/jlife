
<div class="union">
        <div class="choose">
          <a style="background: {$subject.color}" href="" class="urHere">{$subject.name}</a>
          <a href="addpar.php?school={$school}&paragraph={$paragraph.id}" {if $numTab == 1} class="active" {/if} >Конспект</a>
          <a href="" class="disabled">Обсуждения</a>
          <a href="" class="disabled">Дополнительный материал</a>
          <a  {if $numTab == 4} class="active" {else} class = "test"  {/if} href="addQuest.php?school={$school}&paragraph={$paragraph.id}">Конфигурация теста</a>
    </div>
        
<div class="divide">
      <div class="firstLine">
        <div class="divideName">
          <img src="../../img/list.png">
          <span>{$section.number}.&nbsp;Раздел:&nbsp;</span>
          <span> {$section.name}</span>
          </div>
        <div class="ownerName">
          <span>{$teacher.name}</span>
          <img src="../../img/pupil2.png">
        </div>
      </div>  
      <div class="defaultLine">
        <div class="divideName">
          <img src="../../img/paragr.png">
          <span>{$paragraph.number}.&nbsp;</span>

          <span class="partextTtl">{$paragraph.name}</span>
          <!-- <a  href='#s_newPart' class="addPar"><img src="../../img/add.png"> <span   onclick="$('#s_newPart').show(); " style="float:right">Добавить новый раздел</span></a> -->
          </div>
        
      </div>  
    </div>
  </div>
 <!-- end union-->