{* mainfield страницы NNN. *}
{* Требования : *}

{include file='../global/inc_general_lim.tpl'}

<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="viewport" content="user-scalable=no, width=400, initial-scale=0.8, maximum-scale=0.8" />
<meta name="apple-mobile-web-app-capable" content="yes" />
<meta name="apple-mobile-web-app-status-bar-style" content="yes" />
<meta name="format-detection" content="email=no" />
<meta name="HandheldFriendly" content="true" />

<!-- File API -->
<!--<script>if( !window.jQuery )document.write('<script src="/js/jquery.dev.js"><'+'/script>');</script>-->
<script> var FileAPI = { debug: true , staticPath: '../dist/' }; </script>
<script src="{$PROJECT_PATH}/lib/lib_FileAPI/dist/FileAPI.js"></script> 
<script src="{$PROJECT_PATH}/lib/lib_FileAPI/plugins/FileAPI.id3.js"></script>
<script src="{$PROJECT_PATH}/lib/lib_FileAPI/plugins/FileAPI.exif.js"></script>
<!-- JCrop -->
<script src="{$PROJECT_PATH}/lib/lib_jcrop_1902fbc/jquery.Jcrop.js"></script>
<!-- <link rel="stylesheet" href="{$PROJECT_PATH}/lib/lib_jcrop_1902fbc_origin/demos/demo_files/main.css" type="text/css" /> -->
<!-- <link rel="stylesheet" href="{$PROJECT_PATH}/lib/lib_jcrop_1902fbc_origin/demos/demo_files/demos.css" type="text/css" /> -->
<link rel="stylesheet" href="{$PROJECT_PATH}/lib/lib_jcrop_1902fbc_origin/css/jquery.Jcrop.css" type="text/css" />
<!-- <link rel="stylesheet" href="{$PROJECT_PATH}/lib/lib_jcrop_1902fbc_origin/css/jquery.Jcrop.preview.css" type="text/css" /> -->

<script src="{$PROJECT_PATH}/lib/lib_jcrop_1902fbc/jquery.Jcrop.js"></script>

<script type="text/javascript" src="{$PROJECT_PATH}/js/FU.js"></script>

<!-- <link href="{$PROJECT_PATH}/css/popup.css" rel="stylesheet" type="text/css" /> -->

{include file='../../scripts/mc/g/tmpl-ch-ava.tpl'}