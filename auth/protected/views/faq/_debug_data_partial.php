<?php
  echo '<table><tr><td>';
  echo '<br/><br/><b> SCHOOLS: </b>';
  foreach ($this->schoolsOld as $idSchool => $school) {
    foreach ($school as $idRole => $role) {
      if(!count($role['learners'])) echo '<br/> School:'.$idSchool.' - '.$role['schoolname'].' '.$role['roleTitle'].' '.$role['idadress'];
      foreach ($role['learners'] as $key => $learner) {
        echo '<br/> School:'.$idSchool.' - '.$role['schoolname'].' '.$role['roleTitle'].' '.$role['idadress'].' '.$learner['namelearner'].' '.$learner['idlearner'];        
      }
    }
  }
  echo '</td><td>';
    echo '<br/><br/><b>getUrl:</b> '.Yii::app()->getRequest()->getUrl();
    echo '<br/><b>getHostInfo:</b> '.Yii::app()->getRequest()->getHostInfo();
    echo '<br/><b>getPathInfo:</b> '.Yii::app()->getRequest()->getPathInfo();
    echo '<br/><b>getRequestUri:</b> '.Yii::app()->getRequest()->getRequestUri();
    echo '<br/><b>getQueryString:</b> '.Yii::app()->getRequest()->getQueryString();
  echo '</td></tr>';

  echo '<tr><td colspan="2">';
    echo '<br/> _GET: '; var_dump($_GET);
  echo '</td></tr></table>';
  // Yii::app()->request->getParam('email')
?>