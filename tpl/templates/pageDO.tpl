<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>{$PageTitle}</title>

{$block_include_head}

</head>

<body class="twoColLiqLt">
    <div id="container">
        {$block_sidebar}
        {include file='./general/sidebardo.tpl'}
        <div id="mainContent">
            {$block_union_general}
            {$block_mainField} 
            <!-- end #<map></map>inContent -->
        </div>
        <!-- end #container -->
    </div>
 </body>
</html>