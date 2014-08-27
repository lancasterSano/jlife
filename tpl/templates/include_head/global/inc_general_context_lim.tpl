<link rel="shortcut icon" href="{$PROJECT_PATH}/img/{$smarty.const.JLIFE_ICON}.ico" type="image/x-icon" />
<link href="{$PROJECT_PATH}/css/do/style_zoom.css" rel="stylesheet" type="text/css" />
{if isset($block_page_sys) } <link href="{$PROJECT_PATH}/css/do/style_{$block_page_sys}.css" rel="stylesheet" type="text/css" /> {/if}
<!--[if IE]> <link href="{$PROJECT_PATH}/css/styleie.css" rel="stylesheet" type="text/css"  /> <![endif]-->
<link href="{$PROJECT_PATH}/css/common/jquery.popup.css" rel="stylesheet" type="text/css" />
<link href="{$PROJECT_PATH}/css/popup.UserAgreement.css" rel="stylesheet" type="text/css" />

<!-- JS -->
<noscript><meta http-equiv="refresh" content="0; URL=./../badbrowser.php"></noscript>
<!-- PM -->
{include file='./inc_system_PM.tpl'}
<script type="text/javascript" src="{$PROJECT_PATH}/lib/lib_jquery/jquery-1.9.1.js"></script>
<script type="text/javascript" src="{$PROJECT_PATH}/js/common/jquery.popup.js"></script>
<script type="text/javascript" src="{$PROJECT_PATH}/js/DOM.js"></script>
<script type="text/javascript" src="{$PROJECT_PATH}/js/common.js"></script>
<script type="text/javascript" src="{$PROJECT_PATH}/js/ll/gn.js"></script>
<script type="text/javascript" src="{$PROJECT_PATH}/lib/lib_jsmt/jsmt.js"></script>

{if isset($block_page_sys)} <script type="text/javascript" src="{$PROJECT_PATH}/js/{if isset($is_do) && $is_do==true}do/{/if}{$block_page_sys}.js"></script>{/if}
{if isset($block2_page_sys)} <script type="text/javascript" src="{$PROJECT_PATH}/js/{if isset($is_do) && $is_do==true}do/{/if}{$block2_page_sys}.js"></script>{/if}
{if isset($block3_page_sys)} <script type="text/javascript" src="{$PROJECT_PATH}/js/{if isset($is_do) && $is_do==true}do/{/if}{$block3_page_sys}.js"></script>{/if}

{include file='../../scripts/mc/g/tmpl-m.tpl'}
{include file='../../scripts/mc/g/tmpl-userAgreement.tpl'}