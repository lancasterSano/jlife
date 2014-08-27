<script type="text/javascript">
	{assign var="cspf" value=$ProfileAuth->changeAvatar_checkRequirementsDiretory()}
	var PM = { 
		idAuth:{$ProfileAuth->ID},
		{if $ProfileLoad}idLoad:{$ProfileLoad->ID},{/if}
		
		navigate:"{$PROJECT_PATH}",
		pv:{$ProfileAuth->valid|default:0},
		pua:{if $ProfileAuth->is_acceptlicense != 0}1{else}0{/if},
		cspf: {if count($cspf)!=4}0{else}1{/if},

	};
	{$timeout_context}
</script>