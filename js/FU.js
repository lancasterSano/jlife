
	// // Simple JavaScript Templating
	// // John Resig - http://ejohn.org/ - MIT Licensed
	// (function (){
	// 	var cache = {};

	// 	this.tmpl = function tmpl(str, data){
	// 		// Figure out if we're getting a template, or if we need to
	// 		// load the template - and be sure to cache the result.
	// 		var fn = !/\W/.test(str) ?
	// 				cache[str] = cache[str] ||
	// 						tmpl(document.getElementById(str).innerHTML) :

	// 			// Generate a reusable function that will serve as a template
	// 			// generator (and which will be cached).
	// 				new Function("obj",
	// 						"var p=[],print=function() { p.push.apply(p,arguments); };" +

	// 							// Introduce the data as local variables using with(){}
	// 								"with(obj) { p.push('" +

	// 							// Convert the template into pure JavaScript
	// 								str
	// 										.replace(/[\r\t\n]/g, " ")
	// 										.split("<%").join("\t")
	// 										.replace(/((^|%>)[^\t]*)'/g, "$1\r")
	// 										.replace(/\t=(.*?)%>/g, "',$1,'")
	// 										.split("\t").join("');")
	// 										.split("%>").join("p.push('")
	// 										.split("\r").join("\\'")
	// 								+ "');}return p.join('');");

	// 		// Provide some basic currying to the user
	// 		return data ? fn(data) : fn;
	// 	};
	// })();

	var FU = {
		icon: {
			  def:   '//cdn1.iconfinder.com/data/icons/CrystalClear/32x32/mimetypes/unknown.png'
			, image: '//cdn1.iconfinder.com/data/icons/humano2/32x32/apps/synfig_icon.png'
			, audio: '//cdn1.iconfinder.com/data/icons/august/PNG/Music.png'
			, video: '//cdn1.iconfinder.com/data/icons/df_On_Stage_Icon_Set/128/Video.png'
		},

		files: [],
		index: 0,
		active: false,

		add: function (file){
			FU.files.push(file);
			if( /^image/.test(file.type) ){
				FileAPI.Image(file).preview(40).rotate('auto').get(function (err, img){
					if( !err ){
						FU._getEl(file, '.js-left')
							.addClass('b-file__left_border')
							.html(img)
						;
						//$('#imgcrop').attr('src',img.baseURI);
					}
				});
			}
		},

		getFileById: function (id){
			var i = FU.files.length;
			while( i-- ){
				if( FileAPI.uid(FU.files[i]) == id ){
					return	FU.files[i];
				}
			}
		},

		start: function (params){
			if( !FU.active && (FU.active = FU.files.length > FU.index) ){
				FU._upload(FU.files[FU.index],params);
			}
		},

		abort: function (id){
			var file = this.getFileById(id);
			if( file.xhr ){
				file.xhr.abort();
			}
		},

		_getEl: function (file, sel){
			var $el = $('#file-'+FileAPI.uid(file));
			return	sel ? $el.find(sel) : $el;
		},

		_upload: function (file, params){
			if( file ){
				file.xhr = FileAPI.upload({
					url: '../php/FU/ctrl.php',
					files: { file: file },
					imageTransform: {
						'userpic': {
							type: 'image/jpeg', 
							quality: 1,
							width: 650,
							height: 142,
							crop:{ x: params.avatar.x, y: params.avatar.y, w: params.avatar.w, h: params.avatar.w }
						},
						'avatar': {
							type: 'image/jpeg', 
							quality: 1,
							crop:{ x: params.avatar.x, y: params.avatar.y, w: params.avatar.w, h: params.avatar.h },
							width: 650, 
							height: 265
						},
						'aspect' : { 
							type: 'image/jpeg', 
							quality: 1, 
							width: 650
							// , crop:{ x: 0, y: 0, w: 101, h: 105 }
						},
						'original': { type: 'image/jpeg', quality: 1, maxWidth: 650, maxHeight: 142 },
					},
					imageOriginal: false,
					upload: function (){
						FU._getEl(file).addClass('b-file_upload');
						FU._getEl(file, '.js-progress')
							.css({ opacity: 0 }).show()
							.animate({ opacity: 1 }, 100)
						;
					},
					progress: function (evt){
						FU._getEl(file, '.js-bar').css('width', evt.loaded/evt.total*100+'%');
					},
					complete: function (err, xhr){
						var state = err ? 'error' : 'done';
							data = $.parseJSON('[' + xhr.response + ']'),
							state_data_error = data[0].errors ? true: false;

						$("div#slide_loader").hide();
						if(state_data_error){
							$("div#slide_error_structure").show()
								.find('div.pre-alert.pre-context.result')
								.css('display','block');

						} else {
							$("div#slide_THREE").show()
								.find('div.pre-alert.pre-context.result')
								.css('display','block');
						}

						// // $('div.pre-alert.pre-context').css('display','none');
						// var resultSlide = $('div.pre-alert.pre-context.result');

						// // resultSlide.parent().addClass(state_data_error ? 'fail':'');
						// // resultSlide.find('span').text('').append('<h2>'+state+'</h2>');
						// resultSlide.css('display','block');

						FU.index++;
						FU.active = false;

						FU.start();
					}
				});
			}
		}
	};

	function onFiles(files){
		FileAPI.each(files, function (file){
			if( file.size >= 25*FileAPI.MB ){
				alert('Sorrow.\nMax size 25MB')
			}
			else if( file.size === void 0 ){
				$('#oooops').show();
			}
			else {
				FU.add(file);
			}
		});
	}