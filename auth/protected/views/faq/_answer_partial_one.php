<!-- <div class="answer"> -->
  <div class="answerContent">
    <div class="answerText">
      <?php echo $answer->body; ?>
    </div>  
    <div class="oneline">
      <?php
        // print_r($answer);
        foreach ($answer->splabelanswers as $key => $labelLink) {
          echo CHtml::openTag('span', array('class'=>'actionItem')).$labelLink->idlabel0->title.CHtml::closeTag('span');
        }

      ?>
    </div>
  </div>  
<!-- </div> -->