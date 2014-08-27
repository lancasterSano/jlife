window.onload = function () {
    positionPage();
}
  // function
    function onBodyResize(force) {
      var dwidth = Math.max(intval(window.innerWidth), intval(document.documentElement.offsetWidth));
        if (window.lastWindowWidth != dwidth) {
            window.lastWindowWidth = dwidth;
            // if (browser.msie6) { return; }
            var pl = $(force).offsetWidth, sbw = sbWidth();
            if (document.body.offsetWidth < pl) {
                // document.body.style.overflowX = "auto";
                dwidth = pl + sbw + 2;
            } else {
                // document.body.style.overflowX = "hidden";
            }
            if (dwidth) {
                geO("scrollFix").style.width = dwidth - sbw - 2 + "px";
            }
        }
    }
    function positionPage () {
      var siteBlock = geO("site_block");
      if(siteBlock) {
        window._bodyNode = document.getElementsByTagName("body")[0];
        $(window._bodyNode).css('margin','-1000px 0 0 0');
        onBodyResize(siteBlock); // upd size page
        $(window._bodyNode).css('margin','0px');    
      }
    }