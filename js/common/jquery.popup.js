(function($){
	window._OK=1, window._OKCANCEL=2, window._DISTINCT=3, window._NONE=0;
	// POPUP Object
		var Popup = {
			fn: {
				defCont: function () { return '<div class="content"><span>Повторите действие позже.</span></div>'; },
				defBtnOk: function () { return '<div id="ok" class="w-mb-s"><span>Ок</span></div>'; },
				defBtnCancel: function () { return '<div id="cancel" class="w-mb-ns"><span>Отмена</span></div>'; }
			},
			// defaultoption: {},	
			// _loadCSS: function() {var callbackFunc = function() { }; var head = document.getElementsByTagName("head")[0]; var fileref = document.createElement("link"); fileref.setAttribute("rel", "stylesheet"); fileref.setAttribute("type", "text/css"); fileref.setAttribute("href", "../css/common/jquery.popup.css"); fileref.onload = callbackFunc(); head.insertBefore(fileref, head.firstChild); },
			preparePopupHTML: function(wnd, option){
				var prepareHTML="", ins="", HTML_HEAD="", HTML_MAIN="", HTML_MANAGE ="",
					wnd_cont = wnd.find('div#popupCon');
					
					if(option.create.head  === true ) {
						HTML_HEAD += '<div id="popupHead" class="pHead pHeadH curved-hz-1" >';
						if(!(typeof option.create.fn_getHeader  === 'undefined')) HTML_HEAD += option.create.fn_getHeader(); 
						else HTML_HEAD += '<h3 class="headerDoc">HEAD</h3>';
						HTML_HEAD += '</div>';
						
						wnd_cont.prepend(HTML_HEAD);
					}
					
					HTML_MAIN += '<div id="popupMain" class="pMain">';
					HTML_MAIN += option.create.fn_getContent(option.create.data);
					HTML_MAIN += '</div>';
					wnd_cont.append(HTML_MAIN);

					if(option.create.btns) {
						HTML_MANAGE += '<div id="manageReq" class="manageReq"><div class="btns">';
						switch(option.create.btns){
							case window._NONE: HTML_MANAGE += ""; break;
							case window._OK: HTML_MANAGE += Popup.fn.defBtnOk(); break;
							case window._OKCANCEL: 
								HTML_MANAGE += Popup.fn.defBtnCancel(); 
								HTML_MANAGE += Popup.fn.defBtnOk();
								break;
							case window._DISTINCT:
								// if(!(typeof option.create.fn_getBtnOk === 'undefined')){
								// 	HTML_MANAGE += option.create.fn_getBtnOk();
								// } else if(option.debug) { console.log("Property 'option.create.fn_getBtnOk' is not defined."); }
								// if(!(typeof option.create.fn_getBtnOk === 'undefined')){
								// 	HTML_MANAGE += option.create.fn_getBtnCancel();
								// } else if(option.debug) { console.log("Property 'option.create.fn_getBtnCancel' is not defined."); }							
								HTML_MANAGE += option.create.fn_getBtnsDistinct();
								break;
							default:
								HTML_MANAGE += "";
						}

						HTML_MANAGE += '</div></div>';
						wnd_cont.append(HTML_MANAGE);
					}					
		        return (prepareHTML);
			},
			
			_construct: function(option){
				// this._loadCSS();
				var wnd = $("div[id=" + option.idWND + "]").eq(0);
				if(wnd.length && option.reload) { wnd.remove(); }
				if(!(wnd.length && !option.reload))
				{
					// 1. Сформировать HTML окна
					var wnd = $('<div id="'+option.idWND+'" class="popup" style="/*display:none;left:0px;*/"/>').html('<div id="popupCon" class="listcontReq"/>');
					$("body").prepend(wnd);
					this.preparePopupHTML(wnd, option);
					// var prepareHTML = this.preparePopupHTML(wnd, option);
					//wnd.html(prepareHTML);
				}
				return wnd;
			},
			
			showCreate: function(p_el, option){
				// Construct WND
				wnd = this._construct(option);
				// Include in DOM structure
				// $("body").prepend(wnd);
				// Init action in events DOM
				this.initAction(wnd, p_el, option);
				// Open WND by rules from option
				this.showWND(wnd, p_el, option);
			},
			resize: function(e, data) {
				wnd = $('div.popup');
				this.showWND(wnd, data.p_el, data.options);
			},
			showWND: function(wnd, p_el, option){
				
				var params = "",
					documentHeight = $(document).height(),
					documentWidth = $(document).width(),
					pD = { 
						width : undefined, height : undefined, 
						infelicityOffset : { x : 0, y : 0 },
						padding : { left: 0, bottom: 0, right: 0, top: 0 },
						border : { left: 0, bottom: 0, right: 0, top: 0 },
						margin : { left: 0, bottom: 0, right: 0, top: 0 },
						A : { x : undefined, y : undefined }, 
						B : { x : undefined, y : undefined }, 
						D: { x : undefined, y : undefined }
					};
				if(!(typeof option.location.proportions === 'undefined'))
				{
					// Вычисление погрешности из-за padding, border, margin
					pD.padding.left = parseFloat(wnd.css('padding-left'));
					pD.padding.bottom = parseFloat(wnd.css('padding-bottom'));
					pD.padding.right = parseFloat(wnd.css('padding-right'));
					pD.padding.top = parseFloat(wnd.css('padding-top'));
					
					pD.border.left = parseFloat(wnd.css('border-left'));
					pD.border.bottom = parseFloat(wnd.css('border-bottom'));
					pD.border.right = parseFloat(wnd.css('border-right'));
					pD.border.top = parseFloat(wnd.css('border-top'));

					pD.margin.left = parseFloat(wnd.css('margin-left'));
					pD.margin.bottom = parseFloat(wnd.css('margin-bottom'));
					pD.margin.right = parseFloat(wnd.css('margin-right'));
					pD.margin.top = parseFloat(wnd.css('margin-top'));

					pD.padding.left = isNaN(pD.padding.left) ? 0 : Math.ceil(pD.padding.left);
					pD.padding.bottom = isNaN(pD.padding.bottom) ? 0 : Math.ceil(pD.padding.bottom);
					pD.padding.right = isNaN(pD.padding.right) ? 0 : Math.ceil(pD.padding.right);
					pD.padding.top = isNaN(pD.padding.top) ? 0 : Math.ceil(pD.padding.top);
					
					pD.border.left = isNaN(pD.border.left) ? 0 : Math.ceil(pD.border.left);
					pD.border.bottom = isNaN(pD.border.bottom) ? 0 : Math.ceil(pD.border.bottom);
					pD.border.right = isNaN(pD.border.right) ? 0 : Math.ceil(pD.border.right);
					pD.border.top = isNaN(pD.border.top) ? 0 : Math.ceil(pD.border.top);

					pD.margin.left = isNaN(pD.margin.left) ? 0 : Math.ceil(pD.margin.left);
					pD.margin.bottom = isNaN(pD.margin.bottom) ? 0 : Math.ceil(pD.margin.bottom);
					pD.margin.right = isNaN(pD.margin.right) ? 0 : Math.ceil(pD.margin.right);
					pD.margin.top = isNaN(pD.margin.top) ? 0 : Math.ceil(pD.margin.top);


					var WNDinfelicityOffset = {
							x : pD.padding.left + pD.padding.right + pD.border.left + pD.border.right + pD.margin.left + pD.margin.right,
							y : pD.padding.top + pD.padding.bottom + pD.border.top + pD.border.bottom + pD.margin.top + pD.margin.bottom
						};
					if(!(typeof option.location.proportions.width === 'undefined')){
						wnd.width(option.location.proportions.width - WNDinfelicityOffset.x);
					}
					if(!(typeof option.location.proportions.height === 'undefined')) {
						wnd.height(option.location.proportions.height - WNDinfelicityOffset.y);
						var tmp_h = option.location.proportions.height - wnd.find('div#popupHead').outerHeight(true) - wnd.find('div#manageReq').outerHeight(true) - WNDinfelicityOffset.y;
						wnd.find('div#popupCon div#popupMain').eq(0).height(tmp_h);
					}
				}

				var pD_infelicityOffset_x, pD_infelicityOffset_y;
				if(typeof option.location.parentDependence === 'undefined')
				{
					pD.width = documentWidth;
					pD.height = documentHeight;
					pD.A.x = 0;
					pD.A.y = 0;
				}
				else
				{
					// var count_pD = p_el.closest(option.location.parentDependence).length;
					var count_pD = $(option.location.parentDependence).length;
					if ( count_pD != 1) {
						if(option.debug) { console.log( (count_pD > 1) ? "elements 'parentDependence' more one" : "Not found element 'parentDependence'"); }
						return;
					}
					option.location.parentDependence = $(option.location.parentDependence);

					cur_pD = option.location.parentDependence;

					cur_pD_padding_left = parseFloat(cur_pD.css('padding-left'));
					cur_pD_padding_top = parseFloat(cur_pD.css('padding-top'));
					cur_pD_border_left = parseFloat(cur_pD.css('border-left'));
					cur_pD_border_top = parseFloat(cur_pD.css('border-top'));
					cur_pD_padding_left = isNaN(cur_pD_padding_left) ? 0 : Math.ceil(cur_pD_padding_left);
					cur_pD_padding_top = isNaN(cur_pD_padding_top) ? 0 : Math.ceil(cur_pD_padding_top);
					cur_pD_border_left = isNaN(cur_pD_border_left) ? 0 : Math.ceil(cur_pD_border_left);
					cur_pD_border_top = isNaN(cur_pD_border_top) ? 0 : Math.ceil(cur_pD_border_top);

					pD_infelicityOffset_x = cur_pD_padding_left + cur_pD_border_left;
					pD_infelicityOffset_y = cur_pD_padding_top + cur_pD_border_top;
					// pD.width = cur_pD.outerWidth(true);
					// pD.height = cur_pD.outerHeight(true);
					pD.width = cur_pD.width();
					pD.height = cur_pD.height();
					pD.A.x = cur_pD.offset().left + pD.infelicityOffset.x;
					pD.A.y = cur_pD.offset().top + pD.infelicityOffset.y;
				}
				pD.infelicityOffset.x = pD_infelicityOffset_x;
				pD.infelicityOffset.y = pD_infelicityOffset_y;
				pD.B.x = pD.A.x;
				pD.B.y = pD.A.y + pD.height;
				pD.D.x = pD.A.x + pD.width;
				pD.D.y = pD.A.y;

				

				var body = $('body'),
					scrollPosTop = body.scrollTop(),
					scrollPosLeft = body.scrollLeft(),
					bodyHeight = document.body.offsetHeight,
					bodyWidth = document.body.offsetWidth,

					windowHeight = $(window).height(),
					windowWidth = $(window).width(),
					
					wndHeight = wnd.outerHeight(),
					wndWidth = wnd.outerWidth(),
					
					wnd_coordinate = { x: undefined, y: undefined };

				var pDA = { width: undefined, height: undefined, A: {}, B: {}, D: {} },

					inscribed = { top: undefined, left: undefined, bottom: undefined, right: undefined };
					// A1 = {
					// 	x : ( (scrollPosLeft) > pD.A.x) ? scrollPosLeft : pD.A.x,
					// 	y : ( (scrollPosTop) > pD.A.y ) ? scrollPosTop : pD.A.y
					// },
					// B1 = { 
					// 	x : A1.x,
					// 	y : ((scrollPosTop + windowHeight) > pD.B.y) ? pD.B.y : (scrollPosTop + windowHeight)
					// },
					// D2 = { 
					// 	x : ((scrollPosLeft + windowWidth) > pD.D.x) ? pD.D.x : (scrollPosLeft + windowWidth),
					// 	y : A1.y
					// },

				pDA.A.x = ( (scrollPosLeft) > pD.A.x) ? scrollPosLeft : pD.A.x;
				pDA.A.y = ( (scrollPosTop) > pD.A.y ) ? scrollPosTop : pD.A.y;
				pDA.B.x = pDA.A.x;
				pDA.B.y = ((scrollPosTop + windowHeight) > pD.B.y) ? pD.B.y : (scrollPosTop + windowHeight);
				pDA.D.x = ((scrollPosLeft + windowWidth) > pD.D.x) ? pD.D.x : (scrollPosLeft + windowWidth);
				pDA.D.y = pDA.A.y;

				pDA.height = pDA.B.y - pDA.A.y;
				pDA.width = pDA.D.x - pDA.A.x;



				switch(option.location.position)
				{
					default : 
					case "default" : 
						var p_el_position = p_el.offset(),
							p_elHeight = p_el.outerHeight(),
							p_elWidth = p_el.outerWidth(),
							accessArea = { };

						var left = p_el_position.left - pDA.A.x;
						var right = pDA.D.x - (p_el_position.left + p_elWidth);
						var top = p_el_position.top - pDA.A.y;
						var bottom = pDA.B.y - (p_el_position.top + p_elHeight);

						var access = new Array();
						if(!option.location.сonsiderVisibleAreaPD) {
							access[1] = ( right>=wndWidth && top>=wndHeight ) ? true : false;
							access[2] = ( left>=wndWidth && top>=wndHeight ) ? true : false;
							access[3] = ( left>=wndWidth && bottom>=wndHeight ) ? true : false;
							access[4] = ( right>=wndWidth && bottom>=wndHeight ) ? true : false;
						}
						else {
							access[1] = access[2] = access[3] = access[4] = true;
						}

						// 2 | 1
						// -----
						// 3 | 4
						var needQuaterQueue = $.isArray(option.location.quarters) ? option.location.quarters : [option.location.quarters];
						for (var i = 0; i < needQuaterQueue.length; i++) {
							// console.log(needQuaterQueue[i] + " " + access[needQuaterQueue[i]]);
							if(access[needQuaterQueue[i]]) {
								// Координаты вершины окна вокруг кнопки
								switch(needQuaterQueue[i]){
									default:
									case 4:
										wnd_coordinate.y = p_el_position.top + p_elHeight;
										wnd_coordinate.x = p_el_position.left + p_elWidth;
										break;
									case 1: 
										wnd_coordinate.y = p_el_position.top - wndHeight;
										wnd_coordinate.x = p_el_position.left + p_elWidth;
										break;
									case 2: 
										wnd_coordinate.y = p_el_position.top - wndHeight;
										wnd_coordinate.x = p_el_position.left - wndWidth;
										break;
									case 3: 
										wnd_coordinate.y = (p_el_position.top) + p_elHeight; 
										wnd_coordinate.x = p_el_position.left - wndWidth;
										break;
								}
								break;						
							}
						};
						break;
					case "centerscreen":
						// Координаты вершины окна по средине
						if(option.location.сonsiderVisibleAreaPD) {
							wnd_coordinate.x = pDA.A.x + (pDA.width * 0.5) - (wndWidth * 0.5) + option.location.offset.left;
							wnd_coordinate.y =  pDA.A.y + (pDA.height * 0.5) - (wndHeight * 0.5) + option.location.offset.top;
						}
						else {
							wnd_coordinate.x = pD.A.x + (pD.width * 0.5) - (wndWidth * 0.5) + option.location.offset.left;
							wnd_coordinate.y =  pD.A.y + (pD.height * 0.5) - (wndHeight * 0.5) + option.location.offset.top;
						}
						// console.log(pD.A.x + " " + pD.A.y + " " + pD.width + " " + pD.height);
						// console.log(pDA.A.x + " " + pDA.A.y + " " + pDA.width + " " + pDA.height);
						break;
					case "relative":
						wnd_coordinate.x = pD.A.x + option.location.offset.left;
						wnd_coordinate.y =  pD.A.y + option.location.offset.top;				
						break;
				}

				if(option.debug){
					switch(option.location.position) {
						case "centerscreen" :
						case 'default' :
							var body = $("body");
							$('div.debug').remove();
							// углы pD
							body.prepend($("<div class='debug pD top'>A</div>").offset({top: pD.A.y , left: pD.A.x }));
							body.prepend($("<div class='debug pD bottom'>B</div>").offset({top: pD.B.y-60 , left: pD.B.x }));
							body.prepend($("<div class='debug pD top'>D</div>").offset({top: pD.D.y , left: pD.D.x - 60 }));
							
							// // 	// углы pDA
							body.prepend($("<div class='debug pDA top' >A</div>").offset({top: pDA.A.y , left: pDA.A.x }));
							body.prepend($("<div class='debug pDA bottom' >B</div>").offset({top: pDA.B.y-40 , left: pDA.B.x }));
							body.prepend($("<div class='debug pDA top' >D</div>").offset({top: pDA.D.y , left: pDA.D.x - 40 }));
							
							// // углы A1 A2 B1 D2
							// body.prepend($("<div class='debug ABD top' style='background:red;'>D2</div>").offset({top: D2.y, left: D2.x-40 }));
							// body.prepend($("<div class='debug ABD bottom' style='background:blue;'>B1</div>").offset({top: B1.y-40, left: B1.x }));
							// body.prepend($("<div class='debug ABD top' style='background:yellow;'>A1</div>").offset({top: A1.y, left: A1.x }));

							break;
					}
				}

				if(!(typeof wnd_coordinate.x === 'undefined') && !(typeof wnd_coordinate.y === 'undefined')) {
					wnd.offset({ 
						top: wnd_coordinate.y, 
						left: wnd_coordinate.x
					});
					wnd.show();
					if(option.create.bg) {
						if( $('div#bg_layer_' + option.idWND).length == 0 ) { wnd.before('<div id="bg_layer_' + option.idWND + '" class="bg_layer"></div>'); }
					}
					// after show
					$(wnd[0]).trigger(jQuery.Event('afterShowWND'));			
				}
				else {
					if(option.debug) { console.error("Error: Calculate coordinates of position display. Will not be displayed anywhere."); }
					wnd.trigger(jQuery.Event("closeWND")); 
				}		
			},
			initAction: function(wnd, p_el, option){
				var idWndLocal = wnd.prop("id");
				/* press OK/CANCEL */
				var action = !(typeof option.events === 'undefined');
				if(typeof option.create.default === 'undefined' && action)
				{
					wnd.find('#ok').click(function(){ 
						if(!(typeof option.events.fn_ok === 'undefined')) option.events.fn_ok();
						wnd.trigger(jQuery.Event("closeWND"));
					});
					wnd.find('#cancel').click(function(){ 
						if(!(typeof option.events.fn_cancel === 'undefined')) option.events.fn_cancel();
						wnd.trigger(jQuery.Event("closeWND"));
					});
				}
				
				/* Close out of WND / Keypress ESC */
				var firstClick = true;
				if(option.create.closeoutside){
					$(document).on('click.outWND' + idWndLocal, { wnd: wnd[0], options: option }, function(e) {
						if (!firstClick && $(e.target).closest("#" + e.data.wnd.id).length == 0) {
							if(e.data.options.confirm) {
								if (confirm('Вы уверены, что хотите покинуть данное окно ?')) {
									$(wnd).trigger(jQuery.Event('closeWND')); 
								}
							} else {
								$(wnd).trigger(jQuery.Event('closeWND')); 
							}
						}
						firstClick = false;
					});
				}

				if(option.create.btnesc){
					$(document).on('keydown', { wnd: wnd[0], options: option }, function(e) { 
							if (e.keyCode == 27) { 
								if(e.data.options.confirm) {
									if (confirm('Вы уверены, что хотите покинуть данное окно ?')) {
										$(wnd).trigger(jQuery.Event('closeWND')); 
									}
								} else {
									$(wnd).trigger(jQuery.Event('closeWND')); 
								}
							}  
					});
				}
				
				// handler cleseWND
				wnd.bind('closeWND', { wnd: wnd[0], options: option }, function(e) { 
					$('html').css('overflow','auto');
					if(e.data.options.debug) { $('div.pD').remove(); $('div.ABD').remove(); $('div.pDA').remove(); }
					$(document).off('click.outWND' + e.data.wnd.id).off('keydown');
					$(wnd).trigger(jQuery.Event('afterCloseWND'));

					if(option.create.bg) { wnd.off('resize'); $('div#bg_layer_' + option.idWND).remove();}
					if(e.data.options.reload) wnd.remove();
				});

				// handler afterShowWND
				$(wnd).on('afterShowWND', { wnd: wnd[0], options: option }, function(e) {
					if(!(typeof option.handler.aftershow === 'undefined')) { e.data.options.handler.aftershow(e.data); }
				});

				// handler afterCloseWND
				$(wnd).on('afterCloseWND', { wnd: wnd[0], options: option }, function(e) {
					if(!(typeof option.handler.afterclose === 'undefined')) { e.data.options.handler.afterclose(e.data); }
				});

				// return wnd;
				//Данные строки следят за правильным отображением, если даже пользователь изменил размер окна браузера
				wnd.bind('resize', { options: option, p_el: p_el }, function(e){ console.log('resize popup'); Popup.resize(e, e.data); });
			}
		};
	// End Popup

	jQuery.fn.PopupShowOnClick = function(options){
		options = jQuery.extend(true, {
			debug: false,
			idWND: "defwd",
			reload: true,
	        confirm: false, 
			location: {
				position: "default",
				parentDependence: undefined,
				сonsiderVisibleAreaPD: false,
				proportions: { width: undefined, height: undefined },
				offset: { top: 0, left: 0 }
			},
			create: { bg: false, head: false, btns: window._OK, btnesc: false, closeoutside: true, fn_getContent: Popup.fn.defCont, fn_getBtnOk: Popup.fn.defBtnOK },
			events: { fn_ok: undefined, fn_cancel: undefined },
			handler: { aftershow: undefined, afterclose:undefined }
		}, options);
		if (typeof options.location.quarters == 'undefined') { options.location.quarters = [4, 3, 2, 1] };
		return this.each(function(){
	  		$(this).click( function() { Popup.showCreate($(this), options); });
	  	});
	};

	jQuery.fn.PopupShowInstantly = function(options){
		options = jQuery.extend(true, {
			debug: false,
			idWND: "defwd",
			reload: true,
	        confirm: false,
			location: {
				position: "default",
				parentDependence: undefined,
				сonsiderVisibleAreaPD: false,
				proportions: { width: undefined, height: undefined },
				offset: { top: 0, left: 0 }
			},
			create: { bg: false, head: false, btns: window._OK, btnesc: false, closeoutside: true, fn_getContent: Popup.fn.defCont, fn_getBtnOk: Popup.fn.defBtnOK },
			events: { fn_ok: undefined, fn_cancel: undefined },
			handler: { aftershow: undefined, afterclose:undefined }
		}, options);
		if (typeof options.location.quarters == 'undefined') { options.location.quarters = [4, 3, 2, 1] };
		return this.each(function(){ Popup.showCreate($(this), options); });
	};
})(jQuery);