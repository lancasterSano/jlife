function RegStartWith(str, start){
    if(start.length <= 0) return false;
    REG_SOME = new RegExp("^" + start,""),
    ex = str.search(REG_SOME);
    return (ex == 0 ? true : false);
}
function ValidTextAreaToPost(elem){
    if(elem.value == "" || elem.value == $.data(elem,'default')) return false;
    if($.data(elem,'answer'))
    {
        if(elem.value != $.data(elem,'adress') && RegStartWith(elem.value,$.data(elem,'adress'))) return true;
        else return false;
    }
    else return true;
}
var Common = {
    append: function()
    {
        $("div.enterNewCom textarea").each(function() {
                $.data(this, 'default', notes_create_note_comment);
                $.data(this, 'answer', false);
                $.data(this, 'adress', '');
                $.data(this, 'idComment', '');
                $.data(this, 'idAuthor', '');
            }).focus(function() {
                if(!$.data(this, 'answer'))
                {
                    if($.data(this, 'edited')==undefined && (this.value == "" || this.value == $.data(this, 'default')))
                    {
                        this.value = "";
                        $.data(this, 'edited', false);
                    }
                    else if($.data(this, 'edited')!=undefined)
                    {
                        if($.data(this, 'edited') && (this.value == "")) 
                        { 
                            this.value = ""; 
                            $.data(this, 'edited', true);
                        }
                        if(!$.data(this, 'edited') && (this.value == "" || this.value == $.data(this, 'default'))) 
                        { 
                            this.value = ""; 
                            $.data(this, 'edited', false);
                        }
                    }                    
                }
                else if($.data(this, 'answer') && RegStartWith(this.value,$.data(this, 'adress')) ) 
                {
                    if(this.value == $.data(this, 'adress')) $.data(this, 'edited', false);
                    else $.data(this, 'edited', false);
                }
                else if($.data(this, 'answer') && !RegStartWith(this.value,$.data(this, 'adress')) ) 
                {
                    $.data(this, 'answer', false); $.data(this, 'adress', ''); $.data(this, 'idComment', ''); $.data(this, 'idAuthor', '');
                }
                var div = $(this).parents("div[id^=new_comment_]").removeClass('c_new_commUn').removeClass('c_new_comm').addClass('c_new_comm');
                
                var elem = this, pos = elem.value.length;
                if (elem.setSelectionRange) {
                    elem.setSelectionRange(pos, pos);
                } else if (elem.createTextRange) {
                    var range = elem.createTextRange();
                    range.collapse(true);
                    range.moveEnd('character', pos);
                    range.moveStart('character', pos);
                    range.select();
                }

            }).blur(function() {
                if(this.value == "")
                {
                    this.value = $.data(this, 'default');
                    $.data(this, 'edited', false);
                    var div = $(this).parents("div[id^=new_comment_]").removeClass('c_new_comm').removeClass('c_new_commUn').addClass('c_new_commUn');
                }
                else if(!$.data(this, 'answer')) { /*не пустая*/ }
                else if($.data(this, 'answer') && !RegStartWith(this.value,$.data(this, 'adress')) )
                {
                    // не пустая, без обращением, с текстом // сбросить ответ
                    $.data(this, 'answer', false);
                    $.data(this, 'adress', '');
                    $.data(this, 'idComment', '');
                    $.data(this, 'idAuthor', '');
                }
                else if($.data(this, 'answer') && RegStartWith(this.value,$.data(this, 'adress')) ) { /*не пустая, с обращением, с текстом*/ }
            }).keydown(function(event) {
                $.data(this, 'edited', true);
				if( this.value.search(REG_LCN_START_ADRESS_COMMENT) == -1 )
				{
					$.data(this, 'answer', false);
                    $.data(this, 'adress', '');
					$.data(this, 'idComment', '');
                    $.data(this, 'idAuthor', '');
				}
                if (event.keyCode == 27)
                { 
                    if($.data(this, 'answer'))
                    {
                        tmp = this.value;
                        tmp = tmp.replace(REG_LCN_START_ADRESS_COMMENT_FULL, '');
                        $.data(this, 'answer', false);
                        $.data(this, 'adress', '');
                        $.data(this, 'idComment', '');
                        $.data(this, 'idAuthor', '');
                        if(tmp == '') {$.data(this, 'edited', false); this.value = ""; this.blur();}
                        else this.value = tmp;
                    }
                    else {$.data(this, 'edited', false); this.value = ""; this.blur();}                    
                }
                // if (event.ctrlKey && event.keyCode == 13) 
                // {
                //     this.parentNode.parentNode.children[2].click();
                //     this.blur(); 
                // }
                
            }).bind('post', function(event) {
                this.value = $.data(this, 'default');
                var div = $(this).parents("div[id^=new_comment_]").removeClass('c_new_comm').removeClass('c_new_commUn').addClass('c_new_commUn');
                $.data(this, 'answer', false);
                $.data(this, 'adress', '');
                $.data(this, 'idComment', '');
                $.data(this, 'idAuthor', '');
                $.data(this, 'edited', false);
            }).bind('setanswer', function(event, data) { 
                
                $.data(this, 'adress', data.adress);
                $.data(this, 'idComment', data.idComment);
                $.data(this, 'idAuthor', data.idAuthor);
                if(this.value == $.data(this, 'default'))
                    this.value = data.adress;
                else 
                {
                    tmp = this.value;
                    if($.data(this,'answer')) { tmp = tmp.replace(REG_LCN_START_ADRESS_COMMENT_FULL, ''); }
					tmp = data.adress + tmp;
                    this.value = tmp;
                }                
                $.data(this, 'answer', true);
                this.focus();
            }).bind('validate', function(event){
                $.data(this,'valid', false);
                if(this.value == "" || (!$.data(this, 'edited') && this.value == $.data(this,'default'))) $.data(this,'valid', false);
                else if($.data(this,'answer'))
                {
                    if(this.value != $.data(this,'adress') && RegStartWith(this.value,$.data(this,'adress'))) $.data(this,'valid', true);
                }
                else { $.data(this,'valid', true); }
            });
    },
    refreshT: function(post)
    {
        $("div.enterNew textarea#" + post).trigger(jQuery.Event("post"));
        $("div.enterNewCom textarea#" + post).trigger(jQuery.Event("post"));
    },
    getValueT: function(post)
    {
        return $("textarea#" + post)[0].value;
    }
}
    
$(document).ready(function(){
    // Common.append(PM.ididLoad);
    Common.append();
    $("div.enterNew textarea").each(function() {
        $.data(this, 'default', notes_create_note);
        }).focus(function() {
            if(this.value == "" || this.value == $.data(this, 'default')) { this.value = ""; }
            $.data(this, 'edited', false);
            var div = $(this).parents("div[id^=new_entity_]").removeClass('createnewUn').removeClass('createnew').addClass('createnew');
                            
        }).blur(function() {
            if(this.value == "" || this.value == $.data(this, 'default'))
            {
                this.value = $.data(this, 'default');
                var div = $(this).parents("div[id^=new_entity_]").removeClass('createnew').removeClass('createnewUn').addClass('createnewUn');
            }
        }).keydown(function(event) {
            $.data(this, 'edited', true);
            if (event.keyCode == 27) { $.data(this, 'edited', false); this.value = ""; this.blur();}
            // if (event.ctrlKey && event.keyCode == 13) { this.parentNode.parentNode.children[2].click(); this.blur(); }
        }).bind('post', function(event) {
            this.value = $.data(this, 'default');
            var div = $(this).parents("div[id^=new_entity_]").removeClass('createnew').removeClass('createnewUn').addClass('createnewUn');
        });
        // change(function() { $.data(this, 'edited', this.value != ""); })
});

var htmlSpecialChars = function(string, reverse)
{
	var specialChars = { "&": "&amp;", "<": "&lt;", ">": "&gt;", '"': "&quot;" }, x;

	if (typeof(reverse) != "undefined")
	{
		reverse = [];
		for (x in specialChars)
			reverse.push(x);
		reverse.reverse();
		for (x = 0; x < reverse.length; x++)
			string = string.replace(
				new RegExp(specialChars[reverse[x]], "g"),
				reverse[x]
			);
		return string;
	}

	for (x in specialChars)
		string = string.replace(new RegExp(x, "g"), specialChars[x]);
	return string;
};
	
var norm = function(string)
{
    string = string.replace(/\r\n/g, "<br />");
    string = string.replace(/\r/g, "<br />");
    string = string.replace(/\n{2,}/g, "<br /><br />");
	string = string.replace(/\n{1}/g, "<br />");
	string = string.replace(/\s{2,}/g, " ");
	return string;
}

var normToEdite = function(string)
{
    // string = string.replace(/\r\n/g, "<br />");
    // string = string.replace(/\r/g, "<br />");
    string = string.replace(/<br\/>{2,}/g, "\n\n");
    string = string.replace(/<br\/>{1}/g, "\n");
    // string = string.replace(/\s{2,}/g, " ");
    return string;
}


///////////////////////////////////////////////////////////////
//////////////////// COOKIES JAVASCRIPT ///////////////////////
///////////////////////////////////////////////////////////////

function setCookie(name, value, options) {
    options = options || {};
    var expires = options.expires;
    if (typeof expires == "number" && expires) {
        var d = new Date();
        d.setTime(d.getTime() + expires*1000);
        expires = options.expires = d;
    }
    if (expires && expires.toUTCString) { 
        options.expires = expires.toUTCString();
    }
    value = encodeURIComponent(value);
    var updatedCookie = name + "=" + value;
    for(var propName in options) {
        updatedCookie += "; " + propName;
        var propValue = options[propName];    
        if (propValue !== true) { 
            updatedCookie += "=" + propValue;
        }
    }
    document.cookie = updatedCookie;
}

// возвращает cookie с именем name, если есть, если нет, то undefined
function getCookie(name) {
  var matches = document.cookie.match(new RegExp(
    "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
  ));
  return matches ? decodeURIComponent(matches[1]) : undefined;
}

function deleteCookie(name) {
  setCookie(name, "", { expires: -1 })
}





///////////////////////////////////////////////////////////////
/////////////////////// FUNCTION USE //////////////////////////
///////////////////////////////////////////////////////////////
/**
 * trigger a DOM event via script
 * @param {Object,String} element a DOM node/node id
 * @param {String} event a given event to be fired - click,dblclick,mousedown,etc.
 */
var fireEvent = function(element, event) {
    var evt;
    var isString = function(it) {
        return typeof it == "string" || it instanceof String;
    }
    element = (isString(element)) ? document.getElementById(element) : element;
    if (document.createEventObject) {
        // dispatch for IE
        evt = document.createEventObject();
        return element.fireEvent('on' + event, evt)
    }
    else {
        // dispatch for firefox + others
        evt = document.createEvent("HTMLEvents");
        evt.initEvent(event, true, true); // event type,bubbling,cancelable
        return !element.dispatchEvent(evt);
    }
}



//
//  Arrays, objects
//

function eachOBJ(object, callback) {
  var name, i = 0, length = object.length;

  if (length === undefined) {
    for (name in object)
      if (callback.call(object[name], name, object[name]) === false)
        break;
  } else {
    for (var value = object[0];
      i < length && callback.call(value, i, value) !== false;
        value = object[++i]) {}
  }

  return object;
}

function setStyle2(elem, name, value){
  elem = geO(elem);
  if (!elem) return;
  if (typeof name == 'object') return eachOBJ(name, function(k, v) { setStyle2(elem,k,v); });
  if (name == 'opacity') {
    if (browser.msie) {
      if ((value + '').length) {
        if (value !== 1) {
          elem.style.filter = 'alpha(opacity=' + value * 100 + ')';
        } else {
          elem.style.filter = '';
        }
      } else {
        elem.style.cssText = elem.style.cssText.replace(/filter\s*:[^;]*/gi, '');
      }
      elem.style.zoom = 1;
    };
    elem.style.opacity = value;
  } else {
    try{
      var isN = typeof(value) == 'number';
      if (isN && (/height|width/i).test(name)) value = Math.abs(value);
      elem.style[name] = isN && !(/z-?index|font-?weight|opacity|zoom|line-?height/i).test(name) ? value + 'px' : value;
    } catch(e){debugLog('setStyle2 error: ', [name, value]);}
  }
}

// Extending object by another
function extendextend() {
  var a = arguments, target = a[0] || {}, i = 1, l = a.length, deep = false, options;

  if (typeof target === 'boolean') {
    deep = target;
    target = a[1] || {};
    i = 2;
  }

  if (typeof target !== 'object' && !isFunction(target)) target = {};

  for (; i < l; ++i) {
    if ((options = a[i]) != null) {
      for (var name in options) {
        var src = target[name], copy = options[name];

        if (target === copy) continue;

        if (deep && copy && typeof copy === 'object' && !copy.nodeType) {
          target[name] = extendextend(deep, src || (copy.length != null ? [] : {}), copy);
        } else if (copy !== undefined) {
          target[name] = copy;
        }
      }
    }
  }

  return target;
}

function cece(tagName, attr, style) {
  var el = document.createElement(tagName);
  if (attr) extendextend(el, attr);
  if (style) setStyle2(el, style);
  return el;
}

function sbWidth() {
  if (window._sbWidth === undefined) {
    var t = cece('div', {innerHTML: '<div style="height: 75px;">1<br>1</div>'}, {
      overflowY: 'scroll',
      position: 'absolute',
      width: '50px',
      height: '50px'
    });
    window._bodyNode.appendChild(t);
    window._sbWidth = Math.max(0, t.offsetWidth - t.firstChild.offsetWidth - 1);
    window._bodyNode.removeChild(t);
  }
  return window._sbWidth;
}

// function langNumeric(count, vars, formatNum) {
//   if (!vars || !window.langConfig) { return count; }
//   var res;
//   if (!isArray(vars)) {
//     res = vars;
//   } else {
//     res = vars[1];
//     if(count != Math.floor(count)) {
//       res = vars[langConfig.numRules['float']];
//     } else {
//       each(langConfig.numRules['int'], function(i,v){
//         if (v[0] == '*') { res = vars[v[2]]; return false; }
//         var c = v[0] ? count % v[0] : count;
//         if(indexOf(v[1], c) != -1) { res = vars[v[2]]; return false; }
//       });
//     }
//   }
//   if (formatNum) {
//     var n = count.toString().split('.'), c = [];
//     for(var i = n[0].length - 3; i > -3; i -= 3) {
//       c.unshift(n[0].slice(i > 0 ? i : 0, i + 3));
//     }
//     n[0] = c.join(langConfig.numDel);
//     count = n.join(langConfig.numDec);
//   }
//   res = (res || '%s').replace('%s', count);
//   return res;
// }
function makeRandomStr(len)
{
    if(len === undefined) len=6;
    var text = "";
    var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

    for( var i=0; i < len; i++ )
        text += possible.charAt(Math.floor(Math.random() * possible.length));

    return text;
}