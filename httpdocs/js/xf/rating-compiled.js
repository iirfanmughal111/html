!function(t){"function"==typeof define&&define.amd?define(["jquery"],t):"object"==typeof module&&module.exports?module.exports=t(require("jquery")):t(jQuery)}(function(t){var e=function(){function e(){var e=this,n=function(){var n=["br-wrapper"];""!==e.options.theme&&n.push("br-theme-"+e.options.theme),e.$elem.wrap(t("<div />",{"class":n.join(" ")}))},i=function(){e.$elem.unwrap()},a=function(n){return t.isNumeric(n)&&(n=Math.floor(n)),t('option[value="'+n+'"]',e.$elem)},r=function(){var n=e.options.initialRating;return n?a(n):t("option:selected",e.$elem)},o=function(){var n=e.$elem.find('option[value="'+e.options.emptyValue+'"]');return!n.length&&e.options.allowEmpty?(n=t("<option />",{value:e.options.emptyValue}),n.prependTo(e.$elem)):n},l=function(t){var n=e.$elem.data("barrating");return"undefined"!=typeof t?n[t]:n},s=function(t,n){null!==n&&"object"==typeof n?e.$elem.data("barrating",n):e.$elem.data("barrating")[t]=n},u=function(){var t=r(),n=o(),i=t.val(),a=t.data("html")?t.data("html"):t.text(),l=null!==e.options.allowEmpty?e.options.allowEmpty:!!n.length,u=n.length?n.val():null,d=n.length?n.text():null;s(null,{userOptions:e.options,ratingValue:i,ratingText:a,originalRatingValue:i,originalRatingText:a,allowEmpty:l,emptyRatingValue:u,emptyRatingText:d,readOnly:e.options.readonly,ratingMade:!1})},d=function(){e.$elem.removeData("barrating")},c=function(){return l("ratingText")},f=function(){return l("ratingValue")},g=function(){var n=t("<div />",{"class":"br-widget"});return e.$elem.find("option").each(function(){var i,a,r,o;i=t(this).val(),i!==l("emptyRatingValue")&&(a=t(this).text(),r=t(this).data("html"),r&&(a=r),o=t("<a />",{href:"#","data-rating-value":i,"data-rating-text":a,html:e.options.showValues?a:""}),n.append(o))}),e.options.showSelectedRating&&n.append(t("<div />",{text:"","class":"br-current-rating"})),e.options.reverse&&n.addClass("br-reverse"),e.options.readonly&&n.addClass("br-readonly"),n},p=function(){return l("userOptions").reverse?"nextAll":"prevAll"},h=function(t){a(t).prop("selected",!0),e.$elem.change()},m=function(){t("option",e.$elem).prop("selected",function(){return this.defaultSelected}),e.$elem.change()},v=function(t){t=t?t:c(),t==l("emptyRatingText")&&(t=""),e.options.showSelectedRating&&e.$elem.parent().find(".br-current-rating").text(t)},y=function(t){return Math.round(Math.floor(10*t)/10%1*100)},b=function(){e.$widget.find("a").removeClass(function(t,e){return(e.match(/(^|\s)br-\S+/g)||[]).join(" ")})},w=function(){var n,i,a=e.$widget.find('a[data-rating-value="'+f()+'"]'),r=l("userOptions").initialRating,o=t.isNumeric(f())?f():0,s=y(r);if(b(),a.addClass("br-selected br-current")[p()]().addClass("br-selected"),!l("ratingMade")&&t.isNumeric(r)){if(o>=r||!s)return;n=e.$widget.find("a"),i=a.length?a[l("userOptions").reverse?"prev":"next"]():n[l("userOptions").reverse?"last":"first"](),i.addClass("br-fractional"),i.addClass("br-fractional-"+s)}},$=function(t){return l("allowEmpty")&&l("userOptions").deselectable?f()==t.attr("data-rating-value"):!1},x=function(n){n.on("click.barrating",function(n){var i,a,r=t(this),o=l("userOptions");return n.preventDefault(),i=r.attr("data-rating-value"),a=r.attr("data-rating-text"),$(r)&&(i=l("emptyRatingValue"),a=l("emptyRatingText")),s("ratingValue",i),s("ratingText",a),s("ratingMade",!0),h(i),v(a),w(),o.onSelect.call(e,f(),c(),n),!1})},R=function(e){e.on("mouseenter.barrating",function(){var e=t(this);b(),e.addClass("br-active")[p()]().addClass("br-active"),v(e.attr("data-rating-text"))})},V=function(t){e.$widget.on("mouseleave.barrating blur.barrating",function(){v(),w()})},O=function(e){e.on("touchstart.barrating",function(e){e.preventDefault(),e.stopPropagation(),t(this).click()})},C=function(t){t.on("click.barrating",function(t){t.preventDefault()})},S=function(t){x(t),e.options.hoverState&&(R(t),V(t))},T=function(t){t.off(".barrating")},j=function(t){var n=e.$widget.find("a");O&&O(n),t?(T(n),C(n)):S(n)};this.show=function(){l()||(n(),u(),e.$widget=g(),e.$widget.insertAfter(e.$elem),w(),v(),j(e.options.readonly),e.$elem.hide())},this.readonly=function(t){"boolean"==typeof t&&l("readOnly")!=t&&(j(t),s("readOnly",t),e.$widget.toggleClass("br-readonly"))},this.set=function(t){var n=l("userOptions");0!==e.$elem.find('option[value="'+t+'"]').length&&(s("ratingValue",t),s("ratingText",e.$elem.find('option[value="'+t+'"]').text()),s("ratingMade",!0),h(f()),v(c()),w(),n.silent||n.onSelect.call(this,f(),c()))},this.clear=function(){var t=l("userOptions");s("ratingValue",l("originalRatingValue")),s("ratingText",l("originalRatingText")),s("ratingMade",!1),m(),v(c()),w(),t.onClear.call(this,f(),c())},this.destroy=function(){var t=f(),n=c(),a=l("userOptions");T(e.$widget.find("a")),e.$widget.remove(),d(),i(),e.$elem.show(),a.onDestroy.call(this,t,n)}}return e.prototype.init=function(e,n){return this.$elem=t(n),this.options=t.extend({},t.fn.barrating.defaults,e),this.options},e}();t.fn.barrating=function(n,i){return this.each(function(){var a=new e;if(t(this).is("select")||t.error("Sorry, this plugin only works with select fields."),a.hasOwnProperty(n)){if(a.init(i,this),"show"===n)return a.show(i);if(a.$elem.data("barrating"))return a.$widget=t(this).next(".br-widget"),a[n](i)}else{if("object"==typeof n||!n)return i=n,a.init(i,this),a.show();t.error("Method "+n+" does not exist on jQuery.barrating")}})},t.fn.barrating.defaults={theme:"",initialRating:null,allowEmpty:null,emptyValue:"",showValues:!1,showSelectedRating:!0,deselectable:!0,reverse:!1,readonly:!1,fastClicks:!0,hoverState:!0,silent:!1,onSelect:function(t,e,n){},onClear:function(t,e){},onDestroy:function(t,e){}},t.fn.barrating.BarRating=e});
//# sourceMappingURL=jquery.barrating.min.js.map
'use strict';!function(h,m,n,p){XF.Rating=XF.Element.newHandler({options:{theme:"fontawesome-stars",initialRating:null,ratingHref:null,readonly:!1,deselectable:!1,showSelected:!0},ratingOverlay:null,$widget:null,$ratings:null,init:function(){var a=this.$target,b=this.options,c=b.initialRating,d=b.showSelected,k=b.readonly;a.barrating({theme:b.theme,initialRating:c,readonly:k?!0:!1,deselectable:b.deselectable?!0:!1,showSelectedRating:d?!0:!1,onSelect:XF.proxy(this,"ratingSelected")});b=a.next(".br-widget");
var g=b.find("[data-rating-text]");this.$widget=b;this.$ratings=g;c&&a.val(c);d&&b.addClass("br-widget--withSelected");if(!k){d=a.attr("id");a=null;d&&(d=h('label[for="'+d+'"]'),d.length&&(a=d.xfUniqueId()));b.attr({role:"radiogroup","aria-labelledby":a});g.each(function(){var e=h(this),f=c&&e.attr("data-rating-value")==c;e.attr({role:"radio","aria-checked":f?"true":"false","aria-label":e.attr("data-rating-text"),tabindex:f?0:-1})});c||g.first().attr("tabindex",0);var l=this;g.on("keydown",function(e){var f=
!1;switch(e.keyCode){case 37:case 38:f=!0;l.keySelectPrevious();break;case 39:case 40:f=!0,l.keySelectNext()}f&&(e.preventDefault(),e.stopPropagation())})}},keySelect:function(a){var b=this.$target,c=b.val();if(a=b.find('option[value="'+c+'"]')[a]())a=a.val(),b.barrating("set",a),this.$ratings.filter('[data-rating-value="'+a+'"]').focus()},keySelectPrevious:function(){this.keySelect("prev")},keySelectNext:function(){this.keySelect("next")},ratingSelected:function(a,b,c){this.options.readonly||(this.$ratings.attr({"aria-checked":"false",
tabindex:-1}),a?this.$ratings.filter('[data-rating-value="'+a+'"]').attr({"aria-checked":"true",tabindex:0}):this.$ratings.first().attr("tabindex",0),this.options.ratingHref&&(this.ratingOverlay&&this.ratingOverlay.destroy(),this.$target.barrating("clear"),XF.ajax("get",this.options.ratingHref,{rating:a},XF.proxy(this,"loadOverlay"))))},loadOverlay:function(a){if(a.html){var b=this;XF.setupHtmlInsert(a.html,function(c,d){c=XF.getOverlayHtml({html:c,title:d.h1||d.title});b.ratingOverlay=XF.showOverlay(c)})}}});
XF.Element.register("rating","XF.Rating")}(jQuery,window,document);