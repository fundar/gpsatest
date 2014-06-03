/*!
 * jQuery Cookie Plugin v1.4.0
 * https://github.com/carhartl/jquery-cookie
 *
 * Copyright 2013 Klaus Hartl
 * Released under the MIT license
 */
(function (factory) {
	if (typeof define === 'function' && define.amd) {
		// AMD. Register as anonymous module.
		define(['jquery'], factory);
	} else {
		// Browser globals.
		factory(jQuery);
	}
}(function ($) {

	var pluses = /\+/g;

	function encode(s) {
		return config.raw ? s : encodeURIComponent(s);
	}

	function decode(s) {
		return config.raw ? s : decodeURIComponent(s);
	}

	function stringifyCookieValue(value) {
		return encode(config.json ? JSON.stringify(value) : String(value));
	}

	function parseCookieValue(s) {
		if (s.indexOf('"') === 0) {
			// This is a quoted cookie as according to RFC2068, unescape...
			s = s.slice(1, -1).replace(/\\"/g, '"').replace(/\\\\/g, '\\');
		}

		try {
			// Replace server-side written pluses with spaces.
			// If we can't decode the cookie, ignore it, it's unusable.
			// If we can't parse the cookie, ignore it, it's unusable.
			s = decodeURIComponent(s.replace(pluses, ' '));
			return config.json ? JSON.parse(s) : s;
		} catch(e) {}
	}

	function read(s, converter) {
		var value = config.raw ? s : parseCookieValue(s);
		return $.isFunction(converter) ? converter(value) : value;
	}

	var config = $.cookie = function (key, value, options) {

		// Write

		if (value !== undefined && !$.isFunction(value)) {
			options = $.extend({}, config.defaults, options);

			if (typeof options.expires === 'number') {
				var days = options.expires, t = options.expires = new Date();
				t.setTime(+t + days * 864e+5);
			}

			return (document.cookie = [
				encode(key), '=', stringifyCookieValue(value),
				options.expires ? '; expires=' + options.expires.toUTCString() : '', // use expires attribute, max-age is not supported by IE
				options.path    ? '; path=' + options.path : '',
				options.domain  ? '; domain=' + options.domain : '',
				options.secure  ? '; secure' : ''
			].join(''));
		}

		// Read

		var result = key ? undefined : {};

		// To prevent the for loop in the first place assign an empty array
		// in case there are no cookies at all. Also prevents odd result when
		// calling $.cookie().
		var cookies = document.cookie ? document.cookie.split('; ') : [];

		for (var i = 0, l = cookies.length; i < l; i++) {
			var parts = cookies[i].split('=');
			var name = decode(parts.shift());
			var cookie = parts.join('=');

			if (key && key === name) {
				// If second argument (value) is a function it's a converter...
				result = read(cookie, value);
				break;
			}

			// Prevent storing a cookie that we couldn't decode.
			if (!key && (cookie = read(cookie)) !== undefined) {
				result[name] = cookie;
			}
		}

		return result;
	};

	config.defaults = {};

	$.removeCookie = function (key, options) {
		if ($.cookie(key) === undefined) {
			return false;
		}

		// Must not alter options, thus extending a fresh object...
		$.cookie(key, '', $.extend({}, options, { expires: -1 }));
		return !$.cookie(key);
	};

}));

jQuery(document).ready(function($){
// Create a jquery plugin that prints the given element.
jQuery.fn.print = function(){
// NOTE: We are trimming the jQuery collection down to the
// first element in the collection.
if (this.size() > 1){
this.eq( 0 ).print();
return;
} else if (!this.size()){
return;
}
 
// ASSERT: At this point, we know that the current jQuery
// collection (as defined by THIS), contains only one
// printable element.
 
// Create a random name for the print frame.
var strFrameName = ("printer-" + (new Date()).getTime());
 
// Create an iFrame with the new name.
var jFrame = $( "<iframe name='" + strFrameName + "'>" );
 
// Hide the frame (sort of) and attach to the body.
jFrame
.css( "width", "1px" )
.css( "height", "1px" )
.css( "position", "absolute" )
.css( "left", "-9999px" )
.appendTo( $( "body:first" ) )
;
 
// Get a FRAMES reference to the new frame.
var objFrame = window.frames[ strFrameName ];
 
// Get a reference to the DOM in the new frame.
var objDoc = objFrame.document;
 
// Grab all the style tags and copy to the new
// document so that we capture look and feel of
// the current document.
 
// Create a temp document DIV to hold the style tags.
// This is the only way I could find to get the style
// tags into IE.
var jStyleDiv = $( "<div>" ).append(
$( "style" ).clone()
);
 
// Write the HTML for the document. In this, we will
// write out the HTML of the current element.
objDoc.open();
objDoc.write( "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">" );
objDoc.write( "<html>" );
objDoc.write( "<body>" );
objDoc.write( "<head>" );
objDoc.write( "<title>" );
objDoc.write( document.title );
objDoc.write( "</title>" );
objDoc.write( jStyleDiv.html() );
objDoc.write( "</head>" );
objDoc.write( this.html() );
objDoc.write( "</body>" );
objDoc.write( "</html>" );
objDoc.close();
 
// Print the document.
objFrame.focus();
objFrame.print();
 
// Have the frame remove itself in about a minute so that
// we don't build up too many of these frames.
setTimeout(
function(){
jFrame.remove();
},
(60 * 1000)
);
}
});
