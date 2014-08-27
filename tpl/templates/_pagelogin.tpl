<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="shortcut icon" href="{$PROJECT_PATH}/img/{$smarty.const.JLIFE_ICON}.ico" type="image/x-icon" />
<title>{$PageTitle}</title>
<link href="../css/{$block_css}.css" rel="stylesheet" type="text/css" />
<!--[if IE]> <link href="../css/styleie.css" rel="stylesheet" type="text/css"  /> <![endif]-->
<link href="{$PROJECT_PATH}/css/common/jquery.popup.css" rel="stylesheet" type="text/css" />

<!-- JS -->
<noscript><meta http-equiv="refresh" content="0; URL=./../badbrowser.php"></noscript>
<script type="text/javascript" src="{$PROJECT_PATH}/lib/lib_jquery/jquery-1.9.1.js"></script>
<script type="text/javascript" src="{$PROJECT_PATH}/js/common/jquery.popup.js"></script>
<script> $(document).ready(function(){ $("div.RegistrFields input[name='login']").focus(); }); </script>
</head>

<body class="twoColLiqLt">
<!--     <div class="menu">
    	<img src="../img/logo.png"/>
        <div class="registr">           
            <a href="register.php"><span>[Регистрация]</span></a>
            <a href="login.php"><span>[Вход]</span></a>
        </div>
    </div> -->
	<div id="container" style="margin-left: -50px; margin-top: 100px;">
        <div id="mainContent">
            {$block_mainContent}
        </div>
<!--         <div class="downbar2">
            <div class="ulist2">
                  <ul>
                      <li><a href="#">Просто по вопросам </a></li>
                      <li><a href="#">По вопросам рекламы </a></li>
                      <li><a href="#">По вопросам рекламы</a></li>
                      <li><a href="#">Если будет чо интересно</a></li>
                  </ul>    
            </div>      
        </div> -->
<!--         <div class="downbar3">
            <div class="ulist3">
              <ul>
                  <li><a href="#" alt="По вопросам рекламы" title="По вопросам рекламы"><img src="../img/menu/home.png" name="image1"></a></li>
                  <li><a href="#" alt="Просто по вопросам" title="Просто по вопросам"><img src="../img/menu/home.png" name="image1"></a></li>
                  <li><a href="#" alt="По вопросам рекламы" title="По вопросам рекламы"><img src="../img/menu/home.png" name="image1"></a></li>
                  <li><a href="#" alt="Если будет чо интересно" title="Если будет чо интересно"><img src="../img/menu/home.png" name="image1"></a></li>
              </ul>    
            </div>
        </div> -->
<!--         <div class="downbar4">
            <div class="ulist4">
              <ul>
                  <li><a href="#" alt="По вопросам рекламы" title="По вопросам рекламы"><span>Просто по вопросам</span><img src="../img/menu/home.png" name="image1"></a></li>
                  <li><a href="#" alt="По вопросам рекламы" title="По вопросам рекламы">По вопросам рекламы<img src="../img/menu/home.png" name="image1"></a></li>
                  <li><a href="#" alt="Если будет чо интересно" title="Если будет чо интересно">Если будет чо интересно<img src="../img/menu/home.png" name="image1"></a></li>
                  <li><a id="jLifeUserAgreement" onclick="DialogSettings.openDialogAvatar(this);" alt="Пользовательское соглашение" title="Пользовательское соглашение">Пользовательское соглашение<img src="../img/menu/home.png" name="image1"></a></li>
              </ul>    
            </div>
        </div> -->
<!--         <div class="downbar">
            <div class="ulist">
                  <ul>
                      <li><a href="#">По вопросам рекламы </a></li>
                      <li><a href="#">Просто по вопросам </a></li>
                      <li><a href="#">По вопросам рекламы</a></li>
                      <li><a href="#">Если будет чо интересно</a></li>
                  </ul>    
            </div>      
        </div>  -->   
    </div>
    {*include file='./general/footer.tpl'*}
</body>
</html>