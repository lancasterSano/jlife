{if isset($IDNOTICE)} {$notices = $notices[$IDNOTICE]} {/if}
{if isset($notices) && isset($notices.messages) && count($notices.messages)}

	{assign var="notice_type" value='info'}
	{if is_array($notices)}
		{if isset($notices.type)}
			{if $notices.type == 2} {$notice_type = 'error'}
			{elseif $notices.type == 3} {$notice_type = 'warning'}
			{elseif $notices.type == 4} {$notice_type = 'success'} {/if}
		{/if}
	
		{if isset($notices.messages)}


			{$messages = $notices.messages}
		{else} {$messages = $notices}
		{/if}
	{else}
		{$messages = array($notices)}
	{/if}

	<div class='confirmationMSG {$notice_type}'>
	    {foreach key=index item=m from=$messages}
		<span class='title'>{if is_array($m)}{$m.title}{else}{$m}{/if}</span>
		{if is_array($m) && isset($m.text)}<span class="text">{$m.text}</span>{/if}
		{/foreach}
	</div>
{/if}
