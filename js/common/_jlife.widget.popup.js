(function( $ ) {
window._OK=1, window._OKCANCEL=2, window._DISTINCT=3, window._NONE=0;

  // Зададим плагин cPopup, наследуемый от dialog
$.widget("my.cPopup", {

  _defCont: function () { return '<div class="content"><span>Повторите действие позже.</span></div>'; },
  _defBtnOk: function () { return '<div id="ok" class="w-mb-s"><span>Ок</span></div>'; },
  _defBtnCancel: function () { return '<div id="cancel" class="w-mb-ns"><span>Отмена</span></div>'; },
  _wndT: function () { return $('<div id="'+this.options.idWND+'" class="popup" style="display:none;"/>').html("<div id=\"popupCon\" class=\"listcontReq listcontReq-b\" />"); },
  _bg_wndT: function () { return $('<div id="bg_layer_"' + this.options.idWND + '" class="bg_layer" style="display:none;"></div>'); },
  _defBusy: function () { return '<div class="busy"><div class="confirmationMSG info"> <span class="title">Сохранение</span> </div></div>';
  },
  options: {
    debug: false, idWND: "defwnd", reload: false, confirm: false,
    location: {
      position: "default",
      parentDependence: undefined,
      сonsiderVisibleAreaPD: false,
      proportions: { width: undefined, height: undefined },
      offset: { top: 0, left: 0 }
    },
    btns: window._OK,
    create: { 
      head: this._defHead,
      body: this._defCont,
      data: null,

      fn_ok: null,
      fn_cl: null,

      bg: false, 
      btnesc: false, 
      closeoutside: true, 
      fn_getBtnOk: this._defBtnOK 
    },
    data: null,
    events: { fn_ok: undefined, fn_cl: undefined },
    handler: { aftershow: undefined, afterclose:undefined }
  },

  _preparePopupHTML: function(wnd){
    var prepareHTML="", ins="", HTML_HEAD="", HTML_MAIN="", HTML_MANAGE ="",
      wnd_cont = wnd.find('div#popupCon');
      
      if(this.options.create.head  === true ) {
        HTML_HEAD += '<div id="popupHead" class="pHead pHeadH curved-hz-1 block" >';
        if(!(typeof this.options.create.head  === 'undefined')) HTML_HEAD += this.options.create.fn_getHeader(); 
        else HTML_HEAD += '<h3 class="headerDoc">HEAD</h3>';
        HTML_HEAD += '</div>';        
        wnd_cont.prepend(HTML_HEAD);
      }
      wnd_cont.append('<div id="popupMain" class="pMain">'+this.options.create.body+'</div>');
      if(this.options.btns) {
        HTML_MANAGE += '<div id="manageReq" class="manageReq">';
        HTML_MANAGE += this._defBusy();
        HTML_MANAGE += '<div class="btns">';
        switch(this.options.btns){
          case window._NONE: HTML_MANAGE += ""; break;
          case window._OK: HTML_MANAGE += this._defBtnOk(); break;
          case window._OKCANCEL: 
            HTML_MANAGE += this._defBtnCancel(); 
            HTML_MANAGE += this._defBtnOk();
            break;
          case window._DISTINCT:
            // if(!(typeof this.options.create.fn_getBtnOk === 'undefined')){
            //  HTML_MANAGE += this.options.create.fn_getBtnOk();
            // } else if(this.options.debug) { console.log("Property 'this.options.create.fn_getBtnOk' is not defined."); }
            // if(!(typeof this.options.create.fn_getBtnOk === 'undefined')){
            //  HTML_MANAGE += this.options.create.fn_getBtnCancel();
            // } else if(this.options.debug) { console.log("Property 'this.options.create.fn_getBtnCancel' is not defined."); }             
            HTML_MANAGE += this.options.create.fn_getBtnsDistinct();
            break;
          default:
            HTML_MANAGE += "";
        }

        HTML_MANAGE += '</div></div>';
        wnd_cont.append(HTML_MANAGE);
      }         
      return (prepareHTML);
  },
  _resize: function(e, data) {
    wnd = $('div.popup');
    this.showWND(wnd, data.p_el, data.options);
  },

  _showWND: function(wnd, p_el){
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
    if(!(typeof this.options.location.proportions === 'undefined'))
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
      if(!(typeof this.options.location.proportions.width === 'undefined')){
        wnd.width(this.options.location.proportions.width - WNDinfelicityOffset.x);
      }
      if(!(typeof this.options.location.proportions.height === 'undefined')) {
        wnd.height(this.options.location.proportions.height - WNDinfelicityOffset.y);
        wnd.show()
        var popupHead = wnd.find("#popupHead").outerHeight(true);
        var manageReq = wnd.find("#manageReq").outerHeight(true);
        wnd.hide();
        // console.log("height: " + h);
        var tmp_h = this.options.location.proportions.height - popupHead - manageReq - WNDinfelicityOffset.y;
        wnd.find('div#popupCon div#popupMain').eq(0).height(tmp_h);
      }
    }

    var pD_infelicityOffset_x, pD_infelicityOffset_y;
    if(typeof this.options.location.parentDependence === 'undefined')
    {
      pD.width = documentWidth;
      pD.height = documentHeight;
      pD.A.x = 0;
      pD.A.y = 0;
    }
    else
    {
      // var count_pD = p_el.closest(this.options.location.parentDependence).length;
      var count_pD = $(this.options.location.parentDependence).length;
      if ( count_pD != 1) {
        if(this.options.debug) { console.log( (count_pD > 1) ? "elements 'parentDependence' more one" : "Not found element 'parentDependence'"); }
        return;
      }
      this.options.location.parentDependence = $(this.options.location.parentDependence);

      cur_pD = this.options.location.parentDependence;

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
      //  x : ( (scrollPosLeft) > pD.A.x) ? scrollPosLeft : pD.A.x,
      //  y : ( (scrollPosTop) > pD.A.y ) ? scrollPosTop : pD.A.y
      // },
      // B1 = { 
      //  x : A1.x,
      //  y : ((scrollPosTop + windowHeight) > pD.B.y) ? pD.B.y : (scrollPosTop + windowHeight)
      // },
      // D2 = { 
      //  x : ((scrollPosLeft + windowWidth) > pD.D.x) ? pD.D.x : (scrollPosLeft + windowWidth),
      //  y : A1.y
      // },

    pDA.A.x = ( (scrollPosLeft) > pD.A.x) ? scrollPosLeft : pD.A.x;
    pDA.A.y = ( (scrollPosTop) > pD.A.y ) ? scrollPosTop : pD.A.y;
    pDA.B.x = pDA.A.x;
    pDA.B.y = ((scrollPosTop + windowHeight) > pD.B.y) ? pD.B.y : (scrollPosTop + windowHeight);
    pDA.D.x = ((scrollPosLeft + windowWidth) > pD.D.x) ? pD.D.x : (scrollPosLeft + windowWidth);
    pDA.D.y = pDA.A.y;

    pDA.height = pDA.B.y - pDA.A.y;
    pDA.width = pDA.D.x - pDA.A.x;



    switch(this.options.location.position)
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
        if(!this.options.location.сonsiderVisibleAreaPD) {
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
        var needQuaterQueue = $.isArray(this.options.location.quarters) ? this.options.location.quarters : [this.options.location.quarters];
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
        if(this.options.location.сonsiderVisibleAreaPD) {
          wnd_coordinate.x = pDA.A.x + (pDA.width * 0.5) - (wndWidth * 0.5) + this.options.location.offset.left;
          wnd_coordinate.y =  pDA.A.y + (pDA.height * 0.5) - (wndHeight * 0.5) + this.options.location.offset.top;
        }
        else {
          wnd_coordinate.x = pD.A.x + (pD.width * 0.5) - (wndWidth * 0.5) + this.options.location.offset.left;
          wnd_coordinate.y =  pD.A.y + (pD.height * 0.5) - (wndHeight * 0.5) + this.options.location.offset.top;
        }
        // console.log(pD.A.x + " " + pD.A.y + " " + pD.width + " " + pD.height);
        // console.log(pDA.A.x + " " + pDA.A.y + " " + pDA.width + " " + pDA.height);
        break;
      case "relative":
        wnd_coordinate.x = pD.A.x + this.options.location.offset.left;
        wnd_coordinate.y =  pD.A.y + this.options.location.offset.top;        
        break;
    }

    if(this.options.debug){
      switch(this.options.location.position) {
        case "centerscreen" :
        case 'default' :
          var body = $("body");
          $('div.debug').remove();
          // углы pD
          body.prepend($("<div class='debug pD top'>A</div>").offset({top: pD.A.y , left: pD.A.x }));
          body.prepend($("<div class='debug pD bottom'>B</div>").offset({top: pD.B.y-60 , left: pD.B.x }));
          body.prepend($("<div class='debug pD top'>D</div>").offset({top: pD.D.y , left: pD.D.x - 60 }));
          
          // //   // углы pDA
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
    }
    else {
      if(this.options.debug) { console.error("Error: Calculate coordinates of position display. Will not be displayed anywhere."); }
      // wnd.trigger(jQuery.Event("closeWND")); 
      e.data.w.element.cPopup('close', e.data.wnd);
    }   
  },
  _fn_widget_ok: function(e){
    e.data.w.element.trigger('pressOkPopup', e.data.w.options.create.data);
      e.data.w.element.cPopup('onBusy');        
      setTimeout(function(){
        setTimeout(function(){
          setTimeout(function(){
            if(!(typeof e.data.w.options.events.fn_ok === 'undefined')) {
              e.data.w.options.events.fn_ok(e.data.w.options.create.data);
            }
            if(!e.data.w.element.cPopup('isBusy')) {
                e.data.w.element.cPopup('close', e.data.wnd);
            }
          }, 0);
          e.data.w.element.cPopup('offBusy');
        }, 0);
      }, 0);        
  },
  _fn_widget_cl: function(e){ 
    e.data.w.element.trigger('pressCancelPopup', e.data.w.options.create.data);
      e.data.w.element.cPopup('onBusy');        
      setTimeout(function(){
        setTimeout(function(){
          setTimeout(function(){
            if(!(typeof e.data.w.options.events.fn_ok === 'undefined')) {
              e.data.w.options.events.fn_cl(e.data.w.options.create.data);
            }
            if(!e.data.w.element.cPopup('isBusy')) {
                e.data.w.element.cPopup('close', e.data.wnd);
            }
          }, 0);
          e.data.w.element.cPopup('offBusy');
        }, 0);
      }, 0);        
  },
  _initAction: function(wnd, p_el){
    var idWndLocal = wnd.prop("id");
    
    /* press OK/CANCEL */
    if(!(typeof this.options.events === 'undefined') && typeof this.options.create.default === 'undefined')
    {
      wnd.find('#ok').on('click', { wnd: wnd[0], w: this }, this._fn_widget_ok);
      wnd.find('#cancel').on('click', { wnd: wnd[0], w: this }, this._fn_widget_cl);
    }
    
    /* Close out of WND / Keypress ESC */
    var firstClick = true;
    if(this.options.create.closeoutside){
      $(document).on('click.outWND' + idWndLocal, { wnd: wnd[0], w: this }, function(e) {
        if (!firstClick && $(e.target).closest("#" + e.data.wnd.id).length == 0) {
          if(e.data.w.options.confirm) {
            if (confirm('Вы уверены, что хотите покинуть данное окно ?')) {
              e.data.w.element.cPopup('close', e.data.wnd);
            }
          } else {
            e.data.w.element.cPopup('close', e.data.wnd);
          }
        }
        firstClick = false;
      });
    }

    if(this.options.create.btnesc){
      $(document).on('keydown', { wnd: wnd[0], w: this }, function(e) { 
          if (e.keyCode == 27) { 
            if(e.data.w.confirm) {
              if (confirm('Вы уверены, что хотите покинуть данное окно ?')) {
                e.data.w.element.cPopup('close', e.data.wnd);
              }
            } else {
              e.data.w.element.cPopup('close', e.data.wnd);
            }
          }  
      });
    }
    
    // handler cleseWND
    wnd.bind('closeWND', { wnd: wnd[0], w: this }, function(e) { 
      // $('html').css('overflow','auto');
      if(e.data.w.debug) { $('div.pD').remove(); $('div.ABD').remove(); $('div.pDA').remove(); }
      $(document).off('click.outWND' + e.data.wnd.id).off('keydown');
      e.data.w.hide();
    });

    // return wnd;
    //Данные строки следят за правильным отображением, если даже пользователь изменил размер окна браузера
    // wnd.bind('resize', { options: this.options, p_el: p_el }, function(e){ console.log('resize popup'); this._resize(e, e.data); });
  },
  _close_wnd: function (wnd) { },
  _create: function() { this.element.trigger('createPopup', this.options.create.data); },
  open: function () {
    var init = false;
    if(!(arguments[0]===undefined)) init = true;
    if(init) {
      if (this.options.wnd != undefined) this.options.wnd.remove();
      this.options.create.head = (!(arguments[0].head === undefined)) ? arguments[0].head : this.options.create.head;
      this.options.create.body = (!(arguments[0].body === undefined)) ? arguments[0].body : this.options.create.body;
      this.options.create.data = (!(arguments[0].data === undefined)) ? arguments[0].data : this.options.create.data;
      this.options.events.fn_ok = (!(arguments[0].fn_ok === undefined)) ? arguments[0].fn_ok : this.options.events.fn_ok;
      this.options.events.fn_cl = (!(arguments[0].fn_cl === undefined)) ? arguments[0].fn_cl : this.options.events.fn_cl;
    
      this.element.trigger('startInitializingPopup', this.options.create.data);
        this.options.wnd = this._wndT();
        $("body").prepend(this.options.wnd);
      this.element.trigger('endInitializedPopup', this.options.create.data);

      if(this.options.create.bg) {
        // if( $('div#bg_layer_' + this.options.idWND).length == 0 ) { }
          this.options.bg_wnd = this._bg_wndT();
          this.options.wnd.before(this.options.bg_wnd);
      }

    }

    this.element.trigger('loadingPopup', this.options.create.data);
      if(init) { 
        this._preparePopupHTML(this.options.wnd);
      }
      this._initAction(this.options.wnd, this.element);
      if(init) { 
        this.element.trigger('startPositionedPopup', this.options.create.data);
          this._showWND(this.options.wnd, this.element);
        this.element.trigger('endPositioningPopup', this.options.create.data);
      }
        // this.element.trigger('starShowPopup', this.options.create.data);
        //   this.show();
        // this.element.trigger('endShowPopup', this.options.create.data);
    this.element.trigger('loadedPopup', this.options.create.data);

    this.element.trigger('beforeShowPopup', this.options.create.data);
      this.show(this.options.wnd);
    this.element.trigger('afterShowPopup', this.options.create.data);
  },
  _show: function () { this.options.wnd.css('display', 'block'); },
  show: function (){
    this._show();
    this.options.wnd.off('resize');
    if(this.options.create.bg) this.options.bg_wnd.css('display', 'block');
  },
  _hide: function (){this.options.wnd.css('display', 'none'); },
  hide: function (){ 
    this._hide();
    this.options.wnd.off('resize');
    if(this.options.create.bg) this.options.bg_wnd.css('display', 'none');
  },
  close: function (wnd){
    this.element.trigger('closingPopup', this.options.create.data);
      this.options.wnd.trigger(jQuery.Event('closeWND')); 
    this.element.trigger('closedPopup', this.options.create.data);
  },
  isInit: function () { return this.options.wnd ? true : false; },
  isShow: function () { return this.options.wnd.css('display') == 'display' ? true : false; },
  isBusy: function () { return $('div.load-layer.display').length ? true : false; },
  onBusy: function () { this.options.wnd.find('div#manageReq div.busy').css('display', 'block'); },
  offBusy: function () { this.options.wnd.find('div#manageReq div.busy').css('display', 'none'); },
  destroy: function() { },
  _setOption: function(option, value) { $.Widget.prototype._setOption.apply( this, arguments ); }
});

}( jQuery ) );