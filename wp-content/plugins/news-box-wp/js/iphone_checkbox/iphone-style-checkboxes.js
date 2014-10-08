// 2013-04-11 version

(function(){var h,f,l=[].slice;null==jQuery.browser&&(f=navigator.userAgent||"",jQuery.uaMatch=function(a){a=a.toLowerCase();a=/(chrome)[ \/]([\w.]+)/.exec(a)||/(webkit)[ \/]([\w.]+)/.exec(a)||/(opera)(?:.*version)?[ \/]([\w.]+)/.exec(a)||/(msie) ([\w.]+)/.exec(a)||0>a.indexOf("compatible")&&/(mozilla)(?:.*? rv:([\w.]+))?/.exec(a)||[];return{browser:a[1]||"",version:a[2]||"0"}},f=jQuery.uaMatch(f),jQuery.browser={},f.browser&&(jQuery.browser[f.browser]=!0,jQuery.browser.version=f.version),jQuery.browser.webkit&&
(jQuery.browser.safari=!0));h=function(){function a(d,b){var c,e,g;this.elem=jQuery(d);e=jQuery.extend({},a.defaults,b);for(c in e)g=e[c],this[c]=g;this.elem.data(this.dataName,this);this.wrapCheckboxWithDivs();this.attachEvents();this.disableTextSelection();this.calculateDimensions()}a.prototype.calculateDimensions=function(){this.resizeHandle&&this.optionallyResize("handle");this.resizeContainer&&this.optionallyResize("container");return this.initialPosition()};a.prototype.isDisabled=function(){return this.elem.is(":disabled")};
a.prototype.wrapCheckboxWithDivs=function(){this.elem.wrap("<div class='"+this.containerClass+"' />");this.container=this.elem.parent();this.offLabel=jQuery("<label class='"+this.labelOffClass+"'>\n  <span>"+this.uncheckedLabel+"</span>\n</label>").appendTo(this.container);this.offSpan=this.offLabel.children("span");this.onLabel=jQuery("<label class='"+this.labelOnClass+"'>\n  <span>"+this.checkedLabel+"</span>\n</label>").appendTo(this.container);this.onSpan=this.onLabel.children("span");return this.handle=
jQuery("<div class='"+this.handleClass+"'>\n  <div class='"+this.handleRightClass+"'>\n    <div class='"+this.handleCenterClass+"' />\n  </div>\n</div>").appendTo(this.container)};a.prototype.disableTextSelection=function(){if(jQuery.browser.msie)return jQuery([this.handle,this.offLabel,this.onLabel,this.container]).attr("unselectable","on")};a.prototype._getDimension=function(a,b){return null!=jQuery.fn.actual?a.actual(b):a[b]()};a.prototype.optionallyResize=function(a){var b,c,e;b=this.onLabel.find("span");
e=this._getDimension(b,"width");e+=parseInt(b.css("padding-left"),10);c=this.offLabel.find("span");b=this._getDimension(c,"width");b+=parseInt(c.css("padding-right"),10);return"container"===a?(a=(e>b?e:b)+(this._getDimension(this.handle,"width")+this.handleMargin),this.container.css({width:a})):this.handle.css({width:e>b?e:b})};a.prototype.onMouseDown=function(d){d.preventDefault();if(!this.isDisabled())return d=d.pageX||d.originalEvent.changedTouches[0].pageX,a.currentlyClicking=this.handle,a.dragStartPosition=
d,a.handleLeftOffset=parseInt(this.handle.css("left"),10)||0};a.prototype.onDragMove=function(d,b){var c,e;if(a.currentlyClicking===this.handle)return e=(b+a.handleLeftOffset-a.dragStartPosition)/this.rightSide,0>e&&(e=0),1<e&&(e=1),c=e*this.rightSide,this.handle.css({left:c}),this.onLabel.css({width:c+this.handleRadius}),this.offSpan.css({marginRight:-c}),this.onSpan.css({marginLeft:-(1-e)*this.rightSide})};a.prototype.onDragEnd=function(d,b){var c;if(a.currentlyClicking===this.handle&&!this.isDisabled())return a.dragging?
(c=(b-a.dragStartPosition)/this.rightSide,this.elem.prop("checked",0.5<=c)):this.elem.prop("checked",!this.elem.prop("checked")),a.currentlyClicking=null,a.dragging=null,this.didChange()};a.prototype.refresh=function(){return this.didChange()};a.prototype.didChange=function(){var a;if("function"===typeof this.onChange)this.onChange(this.elem,this.elem.prop("checked"));if(this.isDisabled())return this.container.addClass(this.disabledClass),!1;this.container.removeClass(this.disabledClass);a=this.elem.prop("checked")?
this.rightSide:0;this.handle.animate({left:a},this.duration);this.onLabel.animate({width:a+this.handleRadius},this.duration);this.offSpan.animate({marginRight:-a},this.duration);return this.onSpan.animate({marginLeft:a-this.rightSide},this.duration)};a.prototype.attachEvents=function(){var a,b,c;c=this;a=function(a){return c.onGlobalMove.apply(c,arguments)};b=function(e){c.onGlobalUp.apply(c,arguments);jQuery(document).unbind("mousemove touchmove",a);return jQuery(document).unbind("mouseup touchend",
b)};this.elem.change(function(){return c.refresh()});return this.container.bind("mousedown touchstart",function(e){c.onMouseDown.apply(c,arguments);jQuery(document).bind("mousemove touchmove",a);return jQuery(document).bind("mouseup touchend",b)})};a.prototype.initialPosition=function(){var a,b;a=this._getDimension(this.container,"width");this.offLabel.css({width:a-this.containerRadius});b=this.containerRadius+1;jQuery.browser.msie&&7>jQuery.browser.version&&(b-=3);this.rightSide=a-this._getDimension(this.handle,
"width")-b;this.elem.is(":checked")?(this.handle.css({left:this.rightSide}),this.onLabel.css({width:this.rightSide+this.handleRadius}),this.offSpan.css({marginRight:-this.rightSide})):(this.onLabel.css({width:0}),this.onSpan.css({marginLeft:-this.rightSide}));if(this.isDisabled())return this.container.addClass(this.disabledClass)};a.prototype.onGlobalMove=function(d){var b;if(!this.isDisabled()&&a.currentlyClicking)return d.preventDefault(),b=d.pageX||d.originalEvent.changedTouches[0].pageX,!a.dragging&&
Math.abs(a.dragStartPosition-b)>this.dragThreshold&&(a.dragging=!0),this.onDragMove(d,b)};a.prototype.onGlobalUp=function(d){var b;if(a.currentlyClicking)return d.preventDefault(),b=d.pageX||d.originalEvent.changedTouches[0].pageX,this.onDragEnd(d,b),!1};a.defaults={duration:200,checkedLabel:"ON",uncheckedLabel:"OFF",resizeHandle:!0,resizeContainer:!0,disabledClass:"iPhoneCheckDisabled",containerClass:"iPhoneCheckContainer",labelOnClass:"iPhoneCheckLabelOn",labelOffClass:"iPhoneCheckLabelOff",handleClass:"iPhoneCheckHandle",
handleCenterClass:"iPhoneCheckHandleCenter",handleRightClass:"iPhoneCheckHandleRight",dragThreshold:5,handleMargin:15,handleRadius:4,containerRadius:5,dataName:"iphoneStyle",onChange:function(){}};return a}();jQuery.iphoneStyle=this.iOSCheckbox=h;jQuery.fn.iphoneStyle=function(){var a,d,b,c,e,g,f,k,m;a=1<=arguments.length?l.call(arguments,0):[];b=null!=(c=null!=(g=a[0])?g.dataName:void 0)?c:h.defaults.dataName;k=this.filter(":checkbox");g=0;for(f=k.length;g<f;g++)d=k[g],c=jQuery(d).data(b),null!=
c?(d=a[0],e=2<=a.length?l.call(a,1):[],null!=(m=c[d])&&m.apply(c,e)):new h(d,a[0]);return this};jQuery.fn.iOSCheckbox=function(a){null==a&&(a={});a=jQuery.extend({},a,{resizeHandle:!1,disabledClass:"iOSCheckDisabled",containerClass:"iOSCheckContainer",labelOnClass:"iOSCheckLabelOn",labelOffClass:"iOSCheckLabelOff",handleClass:"iOSCheckHandle",handleCenterClass:"iOSCheckHandleCenter",handleRightClass:"iOSCheckHandleRight",dataName:"iOSCheckbox"});return this.iphoneStyle(a)}}).call(this);