var debug = { value:{if $smarty.const.SETTING_debug_js==1}{$smarty.const.SETTING_debug_js}{else}0{/if} },
	debug_js_ignore = { value:{if $smarty.const.SETTING_debug_js_ignore==1}{$smarty.const.SETTING_debug_js_ignore}{else}0{/if} },
	tmp_data = {
		tup: {$smarty.const.SETTING_TIME_COOCKIE_REFRESH_ONLINE},
		texp: {$smarty.const.SETTING_TIME_COOCKIE_ONLINE},
	};
