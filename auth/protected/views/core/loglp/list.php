<br/>
<script type="text/javascript">
	jQuery(function ($) {
		var messagesList = $('#messages');
		function updateMessages (clear) {
			if(clear !== undefined && clear) messagesList.empty();
			lastID = messagesList.data('last_log');
			if(lastID === undefined) lastID=0;
			$.ajax({
				url: "<?php echo $this->createUrl('data') ?>",
				data: {"last":lastID},
				dataType: 'json',
				cache: false,
				success: function (answer) {
					if(answer.data.logrows.length){
						var buffer = [];
						$(answer.data.logrows).each(function () {
							cur_task = this;
							cur_task_number = parseInt(cur_task.ns_task);
							if(buffer[cur_task_number] === undefined) buffer[cur_task_number] = [];
							if(cur_task.ns_task == 4)
							{
								buffer[cur_task_number].push( cur_task );
								if(cur_task.debug_backtrace=="") LOGER.addmsg(this.ns_groupunic_id, this.ns_task, this.number, "new", this.nameopp);
								else LOGER.internalTaskEvoluation(cur_task);								
							}
							else LOGER.addmsg(this.ns_groupunic_id, this.ns_task, this.number, "new", this.nameopp);
							messagesList.prepend(this);
						});
						messagesList.data('last_log', answer.data.lastID);
					}
				}
			});
		}
		updateMessages(true);
		// setInterval(function () { updateMessages() }, 10000);
	});
</script>

<div id="messages">
	<div class="msg new" style="background-color:#8f8;">
		<b>ns_2014-05-06|11:57:16</b><b>4</b><div style="display: -webkit-inline-box;padding-left:10px;color:#00b;"><b>1</b>internalTaskEvoluation Start</div>
	</div>
	<div class="msg new" style="background-color:#8f8;">
		<b>ns_2014-05-06|11:57:16</b><b>4</b><div style="display: -webkit-inline-box;padding-left:10px;color:#00b;"><b>2</b>internalTaskEvoluation End</div>
		<div class="data">[object Object] </div>
	</div>
</div>
