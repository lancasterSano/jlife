var ajax = {
    post: function(url, query, funSuccess, funError) {
        if (url.substr(0, 1) != '/') url = '/' + url;
        jQuery.ajax({type: "POST",data: query,url: PM.navigate + "/php" + url,dataType: "json",success: funSuccess,error: funError});
    },
    post_bs: function(url, query, funBeforeSend, funSuccess, funError) {
        if (url.substr(0, 1) != '/') url = '/' + url;
        jQuery.ajax({type: "POST",data: query,url: PM.navigate + "/php" + url,dataType: "json", beforeSend: funBeforeSend, success: funSuccess,error: funError});
    },
    post_timeout: function(url, query, funBeforeSend, funSuccess, funError) {
        if (url.substr(0, 1) != '/') url = '/' + url;
        jQuery.ajax({type: "POST",data: query,url: PM.navigate + "/php" + url,dataType: "json",
            beforeSend: funBeforeSend,
            success: funSuccess,
            error: funError});
    },
    post_sync: function(url, query, funSuccess, funError) {
        if (url.substr(0, 1) != '/') url = '/' + url;
        jQuery.ajax({type: "POST",data: query,url: PM.navigate + "/php" + url,dataType: "json", async:false, success: funSuccess, error: funError});
    },
    post_sync_bs: function(url, query, funBeforeSend, funSuccess, funError) {
        if (url.substr(0, 1) != '/') url = '/' + url;
        jQuery.ajax({type: "POST",data: query,url: PM.navigate + "/php" + url,dataType: "json", beforeSend: funBeforeSend, async:false, success: funSuccess, error: funError});
    },
}
if(!String.prototype.trim) {  
  String.prototype.trim = function () {  
    return this.replace(/^\s+|\s+$/g,'');  
  };  
}
function intval(value) {
  if (value === true) return 1;
  return parseInt(value) || 0;
}
// DOM
jQuery.fn.exist = function() { return this.length>0; };
function ge(el) { return $('#' + el + ":first"); }
function geO(el) {
  return (typeof el == 'string' || typeof el == 'number') ? document.getElementById(el) : el;
}
function hasClass(el, name) { return el.hasClass(name); }
function val(el) { return el.text(); }

Date.prototype.yyyymmdd = function(spl) {
    spl = typeof spl !== 'undefined' ? spl : ''; 
    var yyyy = this.getFullYear().toString(); 
    var mm = (this.getMonth()+1).toString(); // getMonth() is zero-based 
    var dd  = this.getDate().toString(); 
    return yyyy + spl + (mm[1]?mm:"0"+mm[0]) + spl + (dd[1]?dd:"0"+dd[0]); // padding 
};


// function geByTag(searchTag, node) {}
// function geByTag1(searchTag, node) {}
// function geByClass(searchClass, node, tag) {}
// function ce(tagName, attr, style) {}
// function re(el) {}
// function se(html) {}
// function rs(html, repl) {}
// function psr(html) {}
// function domEL(el, p) {}
// function domNS(el) {}
// function domPS(el) {}
// function domFC(el) {}
// function domLC(el) {}
// function domPN(el) {}

//////////////
// VALIDATE //
function validateEmailCanEmpty (email) { if ((REG_LITERAL_EMAIL.test(email)) || email=="" || email==null) return true; else return false; }
function validateEmail (email) { if (REG_LITERAL_EMAIL.test(email)) return true; else return false; }
function validateTitle (title) { if (title=="" || title==null) return false; else return true; }
function validateTitleLength (title, min, max) { if (title.length>=min && title.length<=max) return true; else return false; }
function validatePSWD (pswd) {return REG_LITERAL_PSWD.test(pswd) ? true : false;}
function hiddenEmail(email) {if(email == undefined) return null; var r = email.match(/^./g); return email.replace(/^[\w-\._\+%]+/g,r[0]+"***");
}

var REG_LITERAL_EMAIL = /^([a-z0-9_\.-]+)@([a-z0-9_\.-]+)\.([a-z\.]{2,6})$/,
REG_LITERAL_PSWD = /^[a-zA-Z0-9\u0410-\u044f_-]{6,32}$/;