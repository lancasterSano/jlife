
<div class="union">
        <div class="choose">
          <a style="background: {$subject.color}" href="" class="urHere">{$subject.name}</a>
          <a href="paragraph.php?school={$school}&paragraph={$paragraph.id}" {if $numTab == 1} class="active" {/if} >Конспект</a>
          <a href="" class="disabled">Обсуждения</a>
          <a href="" class="disabled">Дополнительный материал</a>
          <!-- <a id="idForCursor" {if $numTab == 4} class="active" {/if} {if $paragraph.valid == 1} href="test.php?school={$school}&paragraph={$paragraph.id}" {else} onclick="alert('К параграфу `{$paragraph.name}` тест недоступен.');" {/if}>Тест</a>
          <a{if $numTab == 4} class="testActive" {else} class = "test" {/if} {if $paragraph.valid == 1} href="test.php?school={$school}&paragraph={$paragraph.id}" {else} onclick="alert('К параграфу `{$paragraph.name}` тест недоступен.');" {/if}>Тест</a> -->
          <a id="idForCursor" {if $numTab == 4} class="active" {/if} href="test.php?school={$school}&paragraph={$paragraph.id}">Тест</a>
          <a{if $numTab == 4} class="testActive" {else} class = "test" {/if} href="test.php?school={$school}&paragraph={$paragraph.id}">Тест</a>
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
          </div>
        
      </div>  
    </div>
  </div>
 <!-- end union-->