$(document).ready(function(){
	ComboList.initAction();
});

var ComboList = {
	initAction: function() {
		$("div.finnish").mouseenter(function(el){
			var position = $(el.currentTarget).offset();
			// console.log($(el.currentTarget).find("div.displayMenu"));
			$("div.displayMenu").each(function () {
				$(this).css("display","none");
			})
			// alert('fv');
			$(el.currentTarget).find("div.displayMenu").css("display","block").offset(position);
		});
		
		$("div.displayMenu").mouseleave(function(el){
			$(el.currentTarget).css("display","none");
		});
	
		$("div.displayMenu span").click(function(el){
			// console.log($(el.currentTarget));
			if(!$(el.currentTarget).hasClass("headermenu"))
			$(el.currentTarget.parentElement).css("display","none");
		});
	}
}