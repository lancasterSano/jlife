$(document).ready(function(){
    var index = $("div.mainfield div.content span a");
    index.cPopup({
        confirm: false,
        idWND: "changeAvatarWND_"+PM.idLoad,
        btns: window._DISTINCT,
        location : {
            position: "relative",
            	parentDependence: $("div#container"),
				сonsiderVisibleAreaPD: false,
            	offset: {left: 50, top: -35 },
            	proportions: { width: 915, height: 600 },
            quarters: [3,4]
        }
    });
    
});
var DialogSettings = {
	createOk: function() { return '<a><div id="ok" class="toAdd"><span>Добавить</span></div></a>'; },
	createCancel: function() { return '<a><div id="cancel" class="notToAdd"><span>Не сейчас</span></div></a>'; },
	createHeader_Avatar_: function() { return '<!--<img class="logo" src="../img/logo.png"/>--><h3 class="headerDoc">Смена аватар-изображения на вашей личной странице</h3>'; },
	createHeader_Handband_: function() { return '<h3 class="headerDoc">Смена подложки для Вашей личной странице</h3>'; },
	openDialogAvatar : function(el){
		$(el).bind(
				"afterShowPopup",
				function(e, data){ 
					// debugger
					if(data.cur_struct_pf !== undefined && !data.cur_struct_pf) {
						$("div#slide_ONE").hide();
						$('#sh-l-one span').removeClass('cp');
						$("div#slide_error_structure").show();
					}
					else
					f_init_attention_avatar();
				}
			)
			.cPopup('open', {
					bg: true,
					btnesc: true,
					useHead: true,
					closeoutside:false,
					head: DialogSettings.createHeader_Avatar_(),
					body:  $("#ch-ava").html(),
					data: { cur_struct_pf: PM.cspf},
					distinctBtns: "<div id='cancel' class='btn btn-d-cancel'><span>Закрыть</span></div> <div id='' class='btn btn-d-save btn-send disable'><span>Сохранить</span></div> <div id='' class='btn btn-d-upload btn_upload'><span>Загрузить</span></div>",
				}
			);
	},
}

var f_init_attention_avatar  = function() {

	var imgcropavatar = $('#imgcropavatar'),
	$preview_avatar = $('#preview-pane-avatar'),
	$pcnt_avatar = $('#preview-pane-avatar .preview-container'),
	$pimg_avatar = $('#preview-pane-avatar .preview-container img');

	var xsize = $pcnt_avatar.width(), ysize = $pcnt_avatar.height(); // 205 * 265 from css

	var jcrop_api, boundx, boundy,
	    W, H, WM, HM,
	    cxm, cym, cwm, chm,
	    cxa, cya, cwa, chm,
	    cxas, cyas, cwas, chas, KWom, KHom;

	if( !(FileAPI.support.cors || FileAPI.support.flash) ) { $('#oooops').show(); }

	if( FileAPI.support.dnd ){
		// $('#drag-n-drop').show();
		// $(document).dnd(function (over){
		// 	$('#drop-zone').toggle(over); 
		// }, function (files){
		// 	onFiles(files); 
		// });
	}


	$('input[type="file"]').on('change', function (evt){
		// Jquery.Crop
		readURL(this);
		$("#sendpanel").css("display","block");
		$("#preview-pane-avatar").css("display","block");
		// FileAPI
		var files = FileAPI.getFiles(evt);
		onFiles(files);
		FileAPI.reset(evt.currentTarget);
	});

	$('.btn_upload').on('click', function (evt){
		$('input[type="file"]').click();
	});

	function readURL(input)
	{
		if (input.files && input.files[0])
		{
        	var reader = new FileReader();
			reader.onloadend = function (e)
	  		{
	  			var img = new Image();
	  			img.id = "preview_full";
	  			img.src = e.target.result; 
	  			$pimg_avatar.attr("src",e.target.result);
	  			// $pimg_userpic.attr("src",e.target.result);
	  			img.onload = function (e)
	  			{
	  				rel = img.naturalWidth / img.naturalHeight;
		          	if(img.naturalWidth > img.naturalHeight) {
		          		if(img.naturalWidth > 610) { img.width = 610; img.height = img.width * (1/rel); }
	          			if (img.height > 380) { img.height = 380; img.width = img.height * rel; };
		      		}
		      		else {
		          		if(img.naturalHeight > 380) { img.height = 380; img.width = img.height * rel; }
	          			if (img.width > 610) { img.width = 610; img.height = img.width * (1/rel); };
		      		}
		      		//////////////////////
		      		var xww = ((610 - img.width)  + 205 + 35)*(-1);
		      		$('#preview-pane-avatar').css('right', xww);
		      		//////////////////////

		      		imgcropavatar.empty().append(img);

		      		// Параметры всей перспективы (реальные и пропорционально уменшенные размеры)
		      		WM = img.width; HM = img.height;
		      		W = img.naturalWidth; H = img.naturalHeight;
		      		// Соотношение сторон оригинала к переспективе
		      		KWom = W / WM;
		      		KHom = H / HM;

		      		// Параметры Jcrop области при загрузке
		      		var x1, y1, x2, y2;

		      		if(WM > HM)
		      		{
		      			// Ширина больше
		      			var a = 0.02 * HM,
		      			yln = HM - 2 * a,
		      			y1 = a, y2 = a + yln,
		      			xln = (xsize / ysize) * yln,
		      			b = (WM - xln) / 2;
		      			x1 = b, x2 = x1 + xln;
		      		}
		      		else
		      		{
		      			// Высота больше
		      			var b = 0.02 * WM,
		      			xln = WM - (2 * b),
		      			x1 = b, x2 = b + xln,
		      			yln = ( xln ) / (xsize / ysize),
		      			a = (HM - yln) / 2,
		      			y1 = a, y2 = y1 + yln;
		      		}

		      		$('#preview_full').Jcrop(
		      			{
		      				aspectRatio:  xsize / ysize,
		      				setSelect: [ 0, 0, 205, 265],
		      				onChange:   updatePreview,
		      				onSelect:   updatePreview
		      			},function(){
					      // Use the API to get the real image size
					      var bounds = this.getBounds();
					      boundx = bounds[0];
					      boundy = bounds[1];
					      // Store the API in the jcrop_api variable
					      jcrop_api = this;
					      // Move the preview into the jcrop container for css positioning
					      $preview_avatar.appendTo(jcrop_api.ui.holder);
					    }

				    );

      		 		jcrop_api.animateTo([x1, y1, x2, y2],'');

      		 		// fixed
					$("div#slide_ONE").hide();
					$("div#slide_TWO").show();
					$("div#slide_THREE").hide();
					
					$('#sh-c-two').addClass('done');
					$('#sh-l-two').addClass('done');
					$('#sh-l-two span').addClass('cp');
					
					$('#sh-c-three').removeClass('done');
					$('#sh-l-three').removeClass('done');
					$('#sh-l-three span').removeClass('cp');

					$('div.btn.btn-d-save.btn-send.disable').removeClass('disable');

					//сбросить состояние
					$('div.pre-alert.pre-context').css('display','block');
					$('div.pre-alert.pre-context.result').css('display','none');

	      		}
	      	};
           	reader.readAsDataURL(input.files[0]);
		}
	}

	function updatePreview(c)
	{
		// Параметры выделенной области (размеры jCrop)
		cxm = c.x; cym = c.y;
		cwm = c.x2 - cxm; chm = c.y2 - cym;
		updatePreviewAvatar(c);

		// updatePreviewUserPic(c);
	}
	function updatePreviewAvatar(c)
	{
		if (parseInt(c.w) > 0)
		{
			var rx =  xsize / c.w;
			var ry = ysize / c.h;

			$pimg_avatar.css({
			  width: Math.round(rx * boundx) + 'px',
			  height: Math.round(ry * boundy) + 'px',
			  marginLeft: '-' + Math.round(rx * c.x) + 'px',
			  marginTop: '-' + Math.round(ry * c.y) + 'px'
			});
		}
	};

	$(document)
		.on('click', '.js-file', function (evt) { if( !evt.isDefaultPrevented() ){ FU.showLayer(evt.currentTarget.id.split('-')[1]); evt.preventDefault(); } })
		.on('click', '.js-abort', function (evt) { FU.abort($(evt.target).closest('.js-file').attr('id').split('-')[1]); evt.preventDefault(); })
		.on('click', '.btn-send', function (evt){
		
			if($('#slide_TWO').css('display') != 'block') return;
			// позиция и параметры нарезки для аватарки и юзерпика
			cwa = cwm * KWom; cha = chm * KHom;
			cxa = cxm * KWom; cya = cym * KHom;

			// позиция и параметры нарезки для перспективы
			FU.start(
				{
					userpic: { x:cxa, y:cya, w:cwa, h:cha },
					avatar: { x:cxa, y:cya, w:cwa, h:cha },
					aspect: { x:0, y:0, w:0, h:0 }
				}
			);
			
			$("div#slide_ONE").hide();
			$("div#slide_TWO").hide();
			$("div#slide_loader").show();

			$("#sendpanel").css("display","none");
			$("#preview-pane-avatar").css("display","none");

			$('#sh-c-three').addClass('done');
			$('#sh-l-three').addClass('done');

			$('#sh-l-two span').removeClass('cp');
			$('div.btn.btn-d-save.btn-send').addClass('disable');
		});
};