<link rel="shortcut icon" href="{$PROJECT_PATH}/img/{$smarty.const.JLIFE_ICON}.ico" type="image/x-icon" />
<link href="{$PROJECT_PATH}/css/header.css" rel="stylesheet" type="text/css"/>
<link href="{$PROJECT_PATH}/css/style.css" rel="stylesheet" type="text/css" />
<!-- [if IE]> <link href="{$PROJECT_PATH}/css/styleie.css" rel="stylesheet" type="text/css"  /> <![endif] -->
{if isset($block_page_sys)} <link href="{$PROJECT_PATH}/css/{if isset($is_do) && $is_do==true}do/{/if}style_{$block_page_sys}.css" rel="stylesheet" type="text/css" /> {/if}
<link href="{$PROJECT_PATH}/css/common/jquery.popup.css" rel="stylesheet" type="text/css" />
<link href="{$PROJECT_PATH}/css/popup.UserAgreement.css" rel="stylesheet" type="text/css" />

<!-- JS -->
<noscript><meta http-equiv="refresh" content="0; URL=./../badbrowser.php"></noscript>
<!-- PM -->
{include file='./inc_system_PM.tpl'}
<!-- <script type="text/javascript" src="{$PROJECT_PATH}/lib/lib_jquery/jquery-1.9.1.js"></script> -->
<!-- <link type="text/css" href="{$PROJECT_PATH}/lib/lib_jquery/ui/css/no-theme/jquery-ui-1.9.2.custom.css" rel="Stylesheet" />	 -->
<script type="text/javascript" src="{$PROJECT_PATH}/lib/lib_jquery/ui/js/jquery-1.8.3.js"></script>
<script type="text/javascript" src="{$PROJECT_PATH}/lib/lib_jquery/ui/js/jquery-ui-1.9.2.custom.min.js"></script>

<script type="text/javascript" src="{$PROJECT_PATH}/lib/jquery.popup/jlife.widget.popup.js"></script>
<!-- <script type="text/javascript" src="{$PROJECT_PATH}/js/common/jquery.popup.js"></script> -->


<script type="text/javascript" src="{$PROJECT_PATH}/js/DOM.js"></script>
<script type="text/javascript" src="{$PROJECT_PATH}/js/common.js"></script>
<script type="text/javascript" src="{$PROJECT_PATH}/js/ll/gn.js"></script>
<script type="text/javascript" src="{$PROJECT_PATH}/js/sidebar.js"></script>
<script type="text/javascript" src="{$PROJECT_PATH}/lib/lib_jsmt/jsmt.js"></script>

{if isset($block_page_sys)} <script type="text/javascript" src="{$PROJECT_PATH}/js/{if isset($is_do) && $is_do==true}do/{/if}{$block_page_sys}.js"></script>{/if}
{if isset($block2_page_sys)} <script type="text/javascript" src="{$PROJECT_PATH}/js/{if isset($is_do) && $is_do==true}do/{/if}{$block2_page_sys}.js"></script>{/if}
{if isset($block3_page_sys)} <script type="text/javascript" src="{$PROJECT_PATH}/js/{if isset($is_do) && $is_do==true}do/{/if}{$block3_page_sys}.js"></script>{/if}

{include file='../../scripts/mc/g/tmpl-m.tpl'}
{include file='../../scripts/mc/g/tmpl-userAgreement.tpl'}