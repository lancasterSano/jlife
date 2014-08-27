<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>{$PageTitle}</title>
<!-- JS -->
<noscript><meta http-equiv="refresh" content="0; URL=./../badbrowser.php"></noscript>
<link href="../css/{$block_css}.css" rel="stylesheet" type="text/css" />
<!--[if IE]> <link href="../css/styleie.css" rel="stylesheet" type="text/css"  /> <![endif]-->
</head>

<body class="twoColLiqLt">
    <div class="menu">
    	<img src="../img/logo.png"/>
        <div class="registr">           
            <a href="register.php"><span>[Регистрация]</span></a>
            <a href="/auth/index.php?r=auth/login"><span>[Вход]</span></a>
        </div>
    </div>
	<div id="container">
        <div id="mainContent">
            {$block_mainContent}
        </div>
        <div class="downbar">
    		<div class="ulist">
    			  <ul>
                      <li><a href="#">Просто по вопросам </a></li>
    				  <li><a href="#">По вопросам рекламы </a></li>
    				  <li><a href="#">По вопросам рекламы</a></li>
    				  <li><a href="#">Если будет чо интересно</a></li>
    			  </ul>    
    		</div>      
    	</div>    
    </div>
    {*include file='./general/footer.tpl'*}
</body>
</html>