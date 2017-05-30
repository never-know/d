/******base*******/
/*toast,dialog*/
!function(a){"use strict";a.fn.transitionEnd=function(a){function e(f){if(f.target===this)for(a.call(this,f),c=0;c<b.length;c++)d.off(b[c],e)}var c,b=["webkitTransitionEnd","transitionend","oTransitionEnd","MSTransitionEnd","msTransitionEnd"],d=this;if(a)for(c=0;c<b.length;c++)d.on(b[c],e);return this},a.support=function(){var a={touch:!!("ontouchstart"in window||window.DocumentTouch&&document instanceof window.DocumentTouch)};return a}(),a.touchEvents={start:a.support.touch?"touchstart":"mousedown",move:a.support.touch?"touchmove":"mousemove",end:a.support.touch?"touchend":"mouseup"},a.getTouchPosition=function(a){return a=a.originalEvent||a,"touchstart"===a.type||"touchmove"===a.type||"touchend"===a.type?{x:a.targetTouches[0].pageX,y:a.targetTouches[0].pageY}:{x:a.pageX,y:a.pageY}},a.fn.scrollHeight=function(){return this[0].scrollHeight},a.fn.transform=function(a){var b,c;for(b=0;b<this.length;b++)c=this[b].style,c.webkitTransform=c.MsTransform=c.msTransform=c.MozTransform=c.OTransform=c.transform=a;return this},a.fn.transition=function(a){var b,c;for("string"!=typeof a&&(a+="ms"),b=0;b<this.length;b++)c=this[b].style,c.webkitTransitionDuration=c.MsTransitionDuration=c.msTransitionDuration=c.MozTransitionDuration=c.OTransitionDuration=c.transitionDuration=a;return this},a.getTranslate=function(a,b){var c,d,e,f;return"undefined"==typeof b&&(b="x"),e=window.getComputedStyle(a,null),window.WebKitCSSMatrix?f=new WebKitCSSMatrix("none"===e.webkitTransform?"":e.webkitTransform):(f=e.MozTransform||e.OTransform||e.MsTransform||e.msTransform||e.transform||e.getPropertyValue("transform").replace("translate(","matrix(1, 0, 0, 1,"),c=f.toString().split(",")),"x"===b&&(d=window.WebKitCSSMatrix?f.m41:16===c.length?parseFloat(c[12]):parseFloat(c[4])),"y"===b&&(d=window.WebKitCSSMatrix?f.m42:16===c.length?parseFloat(c[13]):parseFloat(c[5])),d||0},a.requestAnimationFrame=function(a){return window.requestAnimationFrame?window.requestAnimationFrame(a):window.webkitRequestAnimationFrame?window.webkitRequestAnimationFrame(a):window.mozRequestAnimationFrame?window.mozRequestAnimationFrame(a):window.setTimeout(a,1e3/60)},a.cancelAnimationFrame=function(a){return window.cancelAnimationFrame?window.cancelAnimationFrame(a):window.webkitCancelAnimationFrame?window.webkitCancelAnimationFrame(a):window.mozCancelAnimationFrame?window.mozCancelAnimationFrame(a):window.clearTimeout(a)},a.fn.join=function(a){return this.toArray().join(a)}}($),+function(a){"use strict";var e,c=function(b,c){var e,f;c=c||"",a("<div class='weui_mask_transparent'></div>").appendTo(document.body),e='<div class="weui_toast '+c+'">'+b+"</div>",f=a(e).appendTo(document.body),f.show(),f.addClass("weui_toast_visible")},d=function(){a(".weui_mask_transparent").hide(),a(".weui_toast_visible").removeClass("weui_toast_visible").transitionEnd(function(){a(this).remove()})};a.toast=function(a,b){var f;"cancel"==b?f="weui_toast_cancel":"forbidden"==b&&(f="weui_toast_forbidden"),c('<i class="weui_icon_toast"></i><p class="weui_toast_content">'+(a||"已经完成")+"</p>",f),setTimeout(function(){d()},e.duration)},a.showLoading=function(a){var d,b='<div class="weui_loading">';for(d=0;12>d;d++)b+='<div class="weui_loading_leaf weui_loading_leaf_'+d+'"></div>';b+="</div>",b+='<p class="weui_toast_content">'+(a||"数据加载中")+"</p>",c(b,"weui_loading_toast")},a.hideLoading=function(){d()},e=a.toast.prototype.defaults={duration:2e3}}($),+function(a){"use strict";var b;a.modal=function(c){var d,e,f,g;c=a.extend({},b,c),d=c.buttons,e=d.map(function(a){return'<a href="javascript:;" class="weui_btn_dialog '+(a.className||"")+'">'+a.text+"</a>"}).join(""),f='<div class="weui_dialog"><div class="weui_dialog_hd"><strong class="weui_dialog_title">'+c.title+"</strong></div>"+(c.text?'<div class="weui_dialog_bd">'+c.text+"</div>":"")+'<div class="weui_dialog_ft">'+e+"</div>"+"</div>",g=a.openModal(f),g.find(".weui_btn_dialog").each(function(b,e){var f=a(e);f.click(function(){c.autoClose&&a.closeModal(),d[b].onClick&&d[b].onClick()})})},a.openModal=function(b){var d,c=a("<div class='weui_mask'></div>").appendTo(document.body);return c.show(),d=a(b).appendTo(document.body),d.show(),c.addClass("weui_mask_visible"),d.addClass("weui_dialog_visible"),d},a.closeModal=function(){a(".weui_mask_visible").removeClass("weui_mask_visible").transitionEnd(function(){a(this).remove()}),a(".weui_dialog_visible").removeClass("weui_dialog_visible").transitionEnd(function(){a(this).remove()})},a.alert=function(c,d,e){return"function"==typeof d&&(e=arguments[1],d=void 0),a.modal({text:c,title:d,buttons:[{text:b.buttonOK,className:"primary",onClick:e}]})},a.confirm=function(c,d,e,f){return"function"==typeof d&&(f=arguments[2],e=arguments[1],d=void 0),a.modal({text:c,title:d,buttons:[{text:b.buttonCancel,className:"default",onClick:f},{text:b.buttonOK,className:"primary",onClick:e}]})},a.prompt=function(c,d,e,f,o){return"function"==typeof d&&(f=arguments[2],e=arguments[1],d=void 0),a.modal({text:"<p class='weui-prompt-text'>"+(c||"")+"</p><input type='text' class='weui_input weui-prompt-input' id='weui-prompt-input' value='"+(o||"")+"' />",title:d,buttons:[{text:b.buttonCancel,className:"default",onClick:f},{text:b.buttonOK,className:"primary",onClick:function(){e&&e(a("#weui-prompt-input").val())}}]})},b=a.modal.prototype.defaults={title:"提示",text:void 0,buttonOK:"确定",buttonCancel:"取消",buttons:[{text:"确定",className:"primary"}],autoClose:!0}}($);


/***infinite*/
+function(t){"use strict";var e=function(e,n){this.container=t(e),this.container.data("infinite",this),this.distance=n||50,this.attachEvents()};e.prototype.scroll=function(){var e=this.container,n=e.scrollHeight()-(t(window).height()+e.scrollTop());n<=this.distance&&e.trigger("infinite")},e.prototype.attachEvents=function(e){var n=this.container,i="BODY"===n[0].tagName.toUpperCase()?t(document):n;i[e?"off":"on"]("scroll",t.proxy(this.scroll,this))},e.prototype.detachEvents=function(t){this.attachEvents(!0)};t.fn.infinite=function(t){return this.each(function(){new e(this,t)})},t.fn.destroyInfinite=function(){return this.each(function(){var e=t(this).data("infinite");e&&e.detachEvents&&e.detachEvents()})}}($);

/*****tab bar *******/
/* global $:true */
+function ($) {
  "use strict";

  var ITEM_ON = "weui_bar_item_on";

  var showTab = function(a) {
    var $a = $(a);
    if($a.hasClass(ITEM_ON)) return;
    var href = $a.attr("href");

    if(!/^#/.test(href)) return ;

    $a.parent().find("."+ITEM_ON).removeClass(ITEM_ON);
    $a.addClass(ITEM_ON);

    var bd = $a.parents(".weui_tab").find(".weui_tab_bd");

    bd.find(".weui_tab_bd_item_active").removeClass("weui_tab_bd_item_active");

    $(href).addClass("weui_tab_bd_item_active");
  }

  $.showTab = showTab;

  $(document).on("click", ".weui_tabbar_item, .weui_navbar_item", function(e) {
    var $a = $(e.currentTarget);
    var href = $a.attr("href");
    if($a.hasClass(ITEM_ON)) return;
    if(!/^#/.test(href)) {
		$a.parent().find("."+ITEM_ON).removeClass(ITEM_ON);
		$a.addClass(ITEM_ON);
		return;
	}
    e.preventDefault();

    showTab($a);
  });

}($);


/******action sheet *******/


+ function($) {
  "use strict";

  var defaults;
  
  var show = function(params) {

    var mask = $("<div class='weui_mask weui_actions_mask'></div>").appendTo(document.body);

    var actions = params.actions || [];

    var actionsHtml = actions.map(function(d, i) {
      return '<div class="weui_actionsheet_cell ' + (d.className || "") + '">' + d.text + '</div>';
    }).join("");

    var titleHtml = "";
    
    if (params.title) {
      titleHtml = '<div class="weui_actionsheet_title">' + params.title + '</div>';
    }

    var tpl = '<div class="weui_actionsheet " id="weui_actionsheet">'+
                titleHtml +
                '<div class="weui_actionsheet_menu">'+
                actionsHtml +
                '</div>'+
                '<div class="weui_actionsheet_action">'+
                  '<div class="weui_actionsheet_cell weui_actionsheet_cancel">取消</div>'+
                  '</div>'+
                '</div>';
    var dialog = $(tpl).appendTo(document.body);

    dialog.find(".weui_actionsheet_menu .weui_actionsheet_cell, .weui_actionsheet_action .weui_actionsheet_cell").each(function(i, e) {
      $(e).click(function() {
        $.closeActions();
        params.onClose && params.onClose();
        if(actions[i] && actions[i].onClick) {
          actions[i].onClick();
        }
      })
    });

    mask.show();
    dialog.show();
    mask.addClass("weui_mask_visible");
    dialog.addClass("weui_actionsheet_toggle");
  };

  var hide = function() {
    $(".weui_mask").removeClass("weui_mask_visible").transitionEnd(function() {
      $(this).remove();
    });
    $(".weui_actionsheet").removeClass("weui_actionsheet_toggle").transitionEnd(function() {
      $(this).remove();
    });
  }

  $.actions = function(params) {
    params = $.extend({}, defaults, params);
    show(params);
  }

  $.closeActions = function() {
    hide();
  }

  $(document).on("click", ".weui_actions_mask", function() {
    $.closeActions();
  });

  var defaults = $.actions.prototype.defaults = {
    title: undefined,
    onClose: undefined,
    /*actions: [{
      text: "菜单",
      className: "color-danger",
      onClick: function() {
        console.log(1);
      }
    },{
      text: "菜单2",
      className: "color-success",
      onClick: function() {
        console.log(2);
      }
    }]*/
  }

}($);

