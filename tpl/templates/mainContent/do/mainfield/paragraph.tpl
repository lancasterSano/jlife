<div class="mainfield"> 
  <div class="content">
    <div class='conspectCont'>
      {if $partparagraphs != NULL}
        <h3>План конспекта</h3>
        
        <ul>
        
          {foreach $partparagraphs as $partparagraph}
            <li><a href='#s_{$partparagraph.id}'>{$partparagraph.number}. {$partparagraph.name}</a></li>
          {/foreach}
        
        </ul>
         
        <div class='parItem'>
          <h3>Конспект</h3>
          
          {foreach $partparagraphs as $partparagraph}
            <a name='{$partparagraph.id}'></a>
            
            <span id = 's_{$partparagraph.id}'>
              {$partparagraph.number}. {$partparagraph.name}
            </span>
            
            <p>
              {$partparagraph.text}
            </p>
          {/foreach}

        </div>
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