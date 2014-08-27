<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>{$PageTitle}</title>

{$block_include_head}

</head>

<body class="twoColLiqLt" onresize="onBodyResize()" style="margin:-1000px 0 0 0;">
    <!-- <div id="box_layer_bg" class="fixed bg_dark" style="height: 624px;"></div> -->
    <div id="scrollFix">
       <div id="site_block" class="site_block"> 
        	{*$block_header*}
            {include file='./general/header.tpl'}
        	<div id="container">
                {include file='./general/sidebar.tpl'}
                <div id="mainContent">
            		{$block_union_general}            
                    {$block_mainField}            
                </div><!-- end #mainContent -->
            </div><!-- end #container -->
            {include file='./general/footer.tpl'}
        </div>
    </div>    
</body>
</html>