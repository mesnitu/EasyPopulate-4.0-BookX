/* 
 *  @package EP4Bookx - EP4 CSV fork to import Bookx fields - tested with Zencart 1.5.4
 *  @version  0.9.9 - Still in development, make your changes in a local environment
 *  @see BookX Module for ZenCart https://sourceforge.net/p/zencartbookx
 *  @see Readme-EP4Bookx
 *  @author mesnitu 
 *  @see https://github.com/mesnitu/EasyPopulate-4.0-BookX
 *  $Id: ep4bookx.js [UTF-8] - 16/mar/2016-19:48:01 mesnitu  $
 */

/**
	* Valida - jQuery plugin to validate forms in a as simple as possible way.
	* Copyright (c) 2011-2013, Rogério Taques.
	*
	* Licensed under MIT license:
	* http://www.opensource.org/licenses/mit-license.php
	*
	* Permission is hereby granted, free of charge, to any person obtaining a copy of this
	* software and associated documentation files (the "Software"), to deal in the Software
	* without restriction, including without limitation the rights to use, copy, modify, merge,
	* publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons
	* to whom the Software is furnished to do so, subject to the following conditions:
	*
	* The above copyright notice and this permission notice shall be included in all copies or
	* substantial portions of the Software.
	*
	* THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING
	* BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
	* NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM,
	* DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
	* OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
	*
	* @requires jQuery v1.9 or above
	* @version 2.1.6
	* @cat Plugins/Form Validation
	* @author Rogério Taques (rogerio.taques@gmail.com)
	* @see https://github.com/rogeriotaques/valida
	*
	* Contributors:
	* - Kosuke Hiraga (hiraga@brijcs.co.jp)
	*/
(function(i){var m="2.1.6",g={form_validate:"novalidate",form_autocomplete:"off",tag:"p",lost_focus:true,highlight:{marker:"*",position:"post"},messages:{submit:"Wait ...",required:"Required field",invalid:"Field with invalid data",textarea_help:'Typed <span class="at-counter">{0}</span> of {1}'},use_filter:true,before_validate:null,after_validate:null},d={email:/^[\w!#$%&'*+\/=?^`{|}~-]+(\.[\w!#$%&'*+\/=?^`{|}~-]+)*@(([\w-]+\.)+[A-Za-z]{2,}|\[\d{1,3}(\.\d{1,3}){3}\])$/,url:/^(http[s]?:\/\/|ftp:\/\/)?(www\.)?(([\w-]+\.)+[A-Za-z]{2,}|\[\d{1,3}(\.\d{1,3}){3}\])$/,number:/^([0-9])+$/,decimal:/^([0-9]{0,3}(\,|\.){0,1}){0,2}[0-9]{1,3}(\,[0-9]{2}|\.[0-9]{2}){0,1}$/,date_br:/^([0-9]|[0,1,2][0-9]|3[0,1])\/(0[1-9]|1[0,1,2])\/\d{4}$/,date:/^\d{4}-(0[0-9]|1[0,1,2])-([0,1,2][0-9]|3[0,1])$/,time:/^([0-1][0-9]|[2][0-3])(:([0-5][0-9])){1,2}$/,phone_br:/^\(?\d{2}\)?[\s-]?([9]){0,1}\d{4}-?\d{4}$/,phone_jp:/^(((\(0\d{1}\)[\s-]?|0\d{1}-?)[2-9]\d{3}|(\(0\d{2}\)[\s-]?|0\d{2}-?)[2-9]\d{2,3}|(\(0\d{3}\)[\s-]?|0\d{3}-?)[2-9]\d{1}|(\(0\d{4}\)[\s-]?|0\d{4}-?)[2-9])-?\d{4}|(\(0\d{3}\)[\s-]?|0\d{3}-?)[2-9]\d{2}-?\d{3})$/,zipcode_us:/^([A-Z][0-9]){3}$/,zipcode_br:/^[0-9]{2}\.{0,1}[0-9]{3}\-[0-9]{3}$/,zipcode_jp:/^[0-9]{3}\-?[0-9]{4}$/,valid_ip:/^\b(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\b$/,min_length:function(r,o){return(r.length>=o)},max_length:function(r,o){return(r.length<=o)},matches:function(r,o){try{o=(i("#"+o).val()||o)}catch(s){}return(r==o)},greater_than:function(o,s){if(!o.match(d.number)||!s.match(d.number)){return false}return(parseInt(o,10)>parseInt(s,10))},less_than:function(o,s){if(!o.match(d.number)||!s.match(d.number)){return false}return(parseInt(o,10)<parseInt(s,10))}},c={},e={version:function(){return this.each(function(){i(this).html(m)})},init:function(o){c=i.extend({},g,o);return this.each(function(){var s=i(this),r=false;s.data("old-autocomplete",s.attr("autocomplete"));s.data("old-novalidate",s.attr("novalidate"));s.attr("autocomplete",(c.autocomplete||c.form_autocomplete?(c.autocomplete||c.form_autocomplete):"off"));s.attr("novalidate",(c.novalidate||c.form_novalidate?(c.novalidate||c.form_novalidate):"novalidate"));s.find("button[type=reset], input[type=reset]").each(function(t,u){i(u).on("click.valida",function(v){b();i("input, select, textarea").filter(":first").focus()})});s.find("input, select, textarea").each(function(t,u){u=i(u);if(k(u)){return}if(u.is("[required]")&&c.highlight!==null&&c.highlight!==false){i("[for="+u.attr("id")+"]").html((c.highlight.position=="pre"?'<span class="at-required-highlight" >'+c.highlight.marker+"</span>&nbsp;"+i("[for="+u.attr("id")+"]").html():i("[for="+u.attr("id")+"]").html()+'&nbsp;<span class="at-required-highlight" >'+c.highlight.marker+"</span>"))}if(c.lost_focus){u.on("blur.valida",function(v){if((u.is("select")&&u.is("[required]")&&(u.find("option").filter(":selected").val()===""||!u.find("option").length))||(u.is("textarea")&&u.is("[required]")&&u.val()==="")||(u.is("input")&&u.prop("type")==="checkbox"&&u.is("[required]")&&!u.is(":checked"))||(u.is("input")&&u.prop("type")!=="checkbox"&&u.is("[required]")&&u.val()==="")){q(u,u.is("[type=checkbox]"))}})}if(u.is("textarea")&&u.attr("maxlength")){u.after(i("<div />",{"class":"help-block at-description",html:(u.data("help")||(c.messages.textarea_help?c.messages.textarea_help:'Typed <span class="at-counter">{0}</span> of {1}')).replace("{1}",u.attr("maxlength")).replace("{0}",u.val().length)}));u.on("keyup.valida",function(v){a(v,u,u.attr("maxlength"))}).on("keydown.valida",function(v){a(v,u,u.attr("maxlength"))})}u.on("change.valida",function(v){if(u.val()!==""&&u.is("select")){n(u)}});if(u.is("input")&&u.prop("type")==="checkbox"&&u.is("[required]")){u.on("click.valida",function(v){n(u);if(!u.is(":checked")){q(u,u.is("[type=checkbox]"))}})}else{u.on("keyup.valida",function(v){if(u.val()!==""){j(u)}}).on("keydown.valida",function(v){j(u)})}});s.on("submit.valida",function(u){var t=false;b();if(c.before_validate&&i.isFunction(c.before_validate)){t=!c.before_validate(u)}s.find("button[type=submit], input[type=submit]").each(function(w,x){x=i(x);x.prop("disabled",true).data("old-value",(x.is("input")?x.val():x.html()));if(x.is("input")){x.val(c.messages.submit||"Wait ...")}else{x.html(c.messages.submit||"Wait ...")}});s.find("input, select, textarea").each(function(w,x){x=i(x);t=!p(x,true,false,t)});if(c.after_validate&&i.isFunction(c.after_validate)){t=!c.after_validate(u,t)}if(t){u.preventDefault();s.find("button[type=submit], input[type=submit]").each(function(w,x){x=i(x);x.prop("disabled",false);if(x.is("input")){x.val(x.data("old-value"))}else{x.html(x.data("old-value"))}});var v=Math.ceil(i(window).height()/2);if(i(".at-required:visible, .at-invalid:visible").length>0){i("html,body").animate({scrollTop:i(".at-required:visible, .at-invalid:visible").filter(":first").offset().top-v},"fast",function(w){i(".at-required:visible, .at-invalid:visible").filter(":first").focus()})}return false}})})},destroy:function(){var o=i(this);o.attr("autocomplete",o.data("old-autocomplete"));o.attr("novalidate",o.data("old-novalidate"));o.find("button, input[type=reset]").off("valida");o.find("button, input[type=reset]").unbind(".valida");o.find("input, select, textarea").off("valida");o.find("input, select, textarea").unbind(".valida");o.find(".help-block.at-description").remove();o.off("valida");o.unbind(".valida");o.valida=null},partial:function(r,t,o){var s=p(r,t,o);if(s){var u=Math.ceil(i(window).height()/2);if(i(".at-required:visible, .at-invalid:visible").length>1){i("html,body").animate({scrollTop:i(".at-required:visible, .at-invalid:visible").filter(":first").offset().top-u},"fast",function(v){i(".at-required:visible, .at-invalid:visible").filter(":first").focus()})}}return s}};function p(s,u,o,r){var t=(r===undefined?false:r);u=(u===undefined?true:u);o=(o===undefined?true:o);s=(typeof s=="object"?s:i(s));if(o){n(s)}if(!s.length||k(s)){return !t}if(s.is("[required]")&&((!s.is("[type=checkbox]")&&s.val()=="")||(s.is("[type=checkbox]")&&!s.is(":checked"))||(s.is("select")&&!s.find("option:selected").length))){t=true;if(u){q(s,s.is("[type=checkbox]"))}}else{if(c.use_filter&&s.attr("filter")&&!f(s)){t=true;if(u){l(s,s.is("[type=checkbox]"))}}}return !t}function j(r){var o=!f(r,c);if(c.use_filter&&r.attr("filter")&&o){l(r,r.is("[type=checkbox]"))}else{if(r.val()!==""&&r.attr("filter")&&!o){h(r,r.is("[type=checkbox]"))}else{n(r)}}}function a(s,r,o){if(r.val().length>o){s.preventDefault();return}r.siblings(".at-description").find("span.at-counter").html(r.val().length)}function f(s){var u=s.attr("filter")!==undefined&&s.attr("filter").indexOf("|")!==-1?s.attr("filter").split("|"):[s.attr("filter")],t=/^(\w\d)+/,o=false,r;for(r in u){t=u[r];if(typeof t!="undefined"&&t.indexOf(":")!==-1){t=u[r].split(":");o=(s.val()!==""&&!(typeof d[t[0]]!="undefined"&&d[t[0]](s.val(),(s.parents("form").find("#"+t[1]).val()||t[1]))));if(o){break}}else{t=d[t];o=(s.val()!==""&&!s.val().match(t));if(o){break}}}return !o}function h(r,o){n(r);r.addClass("at-success");r.closest(".form-group").addClass("has-success").addClass("has-feedback");if(!o&&r.is(":visible")){r.after(i("<span />",{"class":"glyphicon glyphicon-ok form-control-feedback"}))}}function l(s,r){var t=(s.data("invalid")||(c.messages.invalid?c.messages.invalid:"This field has invalid data")),o=(s.data("place-after")||s);n(s);s.addClass("at-invalid");s.closest(".form-group").addClass("has-warning").addClass("has-feedback");if(!r){i(o).after(i("<"+(c.tag?c.tag:"p")+"/>",{"class":"at-warning",html:t}));if(s.is(":visible")){i(o).after(i("<span />",{"class":"glyphicon glyphicon-warning-sign form-control-feedback"}))}}else{s.parent("label").after(i("<span />",{"class":"at-warning",html:t}))}}function q(t,r){var u=(t.data("required")||(c.messages.required?c.messages.required:"This field is required")),o=(t.data("place-after")||t),s=(t.data("place-after")||(t.closest(".form-group").hasClass("input-group")?t.closest(".form-group"):t));n(t);t.addClass("at-required");t.closest(".form-group").addClass("has-error").addClass("has-feedback");if(!r){i(s).after(i("<"+(c.tag?c.tag:"p")+"/>",{"class":"at-error",html:u}));if(t.is(":visible")){i(o).after(i("<span />",{"class":"glyphicon glyphicon-remove form-control-feedback"}))}}else{t.parent().find(".at-error,.at-warning,.at-info,.at-success").remove();t.next("label").after(i("<span />",{"class":"at-error",html:"&nbsp;"+u}))}}function n(o){o.removeClass("at-required at-invalid at-success");if(!o.is("[type=checkbox]")){if(o.closest(".form-group").hasClass("input-group")){o.closest(".form-group").siblings(".form-control-feedback, .at-error, .at-warning, .at-info").remove()}o.closest(".form-group").removeClass("has-error has-warning has-success has-feedback");o.siblings(".form-control-feedback, .at-error, .at-warning, .at-info, .at-success").remove()}else{o.siblings(".form-control-feedback, .at-error, .at-warning, .at-info, .at-success").remove()}}function b(){i(".at-error, .at-warning, .at-info, .form-control-feedback").remove();i(".at-required, .at-invalid, .at-success, .has-error, .has-warning, .has-feedback, .has-success").removeClass("at-required at-invalid at-success has-error has-warning has-feedback has-success")}function k(o){var r=["submit","reset","button","image"];return !o.is("input")||(o.is("input")&&i.inArray(o.prop("type"),r))===-1?false:true}i.fn.valida=function(o){if(e[o]){return e[o].apply(this,Array.prototype.slice.call(arguments,1))}else{if(typeof o==="object"||!o){return e.init.apply(this,arguments)}else{i.error("Method "+o+" does not exist on jQuery.valida()")}}}})(jQuery);


/* http://nanobar.micronube.com/  ||  https://github.com/jacoborus/nanobar/    MIT LICENSE */

(function (root, factory) {
	if (typeof exports === 'object') {
		// CommonJS
		factory(exports);
	} else if (typeof define === 'function' && define.amd) {
		// AMD. Register as an anonymous module.
		define(['exports'], factory);
	} else {
		// Browser globals
		factory(root);
	}
} (this, function(exports) {
	exports.Nanobar = (function () {

		'use strict';
		var addCss, Bar, Nanobar, move, place, init,
			// container styles
			cssCont = {
				width: '100%',
				height: '10px',
				zIndex: 9999,
				top : '0'
			},
			// bar styles
			cssBar = {
				width:0,
				height: '100%',
				clear: 'both',
				transition: 'height .3s'
			};


		// add `css` to `el` element
		addCss = function (el, css ) {
			var i;
			for (i in css) {
				el.style[i] = css[i];
			}
			el.style.float = 'left';
		};

		// animation loop
		move = function () {
			var self = this,
				dist = this.width - this.here;
	
			if (dist < 0.1 && dist > -0.1) {
				place.call( this, this.here );
				this.moving = false;
				if (this.width == 100) {
					this.el.style.height = 0;
					setTimeout( function () {
						self.cont.el.removeChild( self.el );
						self.cont.bars.splice( self.cont.bars.indexOf( self ), 1 );
					}, 300);
				}
			} else {
				place.call( this, this.width - (dist/4) );
				setTimeout( function () {
					self.go();
				}, 16);
			}
		};

		// set bar width
		place = function (num) {
			this.width = num;
			this.el.style.width = this.width + '%';
		};

		// create and insert bar in DOM and this.bars array
		init = function () {
			var bar = new Bar( this );
			this.bars.unshift( bar );
		};

		Bar = function ( cont ) {
			// create progress element
			this.el = document.createElement( 'div' );
			this.el.style.backgroundColor = cont.opts.bg;
			this.width = 0;
			this.here = 0;
			this.moving = false;
			this.cont = cont;
			addCss( this.el, cssBar);
			cont.el.appendChild( this.el );
		};

		Bar.prototype.go = function (num) {
			if (num) {
				this.here = num;
				if (!this.moving) {
					this.moving = true;
					move.call( this );
				}
			} else if (this.moving) {
				move.call( this );
			}
		};


		Nanobar = function (opt) {

			var opts = this.opts = opt || {},
				el;

			// set options
			opts.bg = opts.bg || '#000';
			this.bars = [];


			// create bar container
			el = this.el = document.createElement( 'div' );
			// append style
			addCss( this.el, cssCont);
			if (opts.id) {
				el.id = opts.id;
			}
			// set CSS position
			el.style.position = !opts.target ? 'fixed' : 'relative';

			// insert container
			if (!opts.target) {
				document.getElementsByTagName( 'body' )[0].appendChild( el );
			} else {
				opts.target.insertBefore( el, opts.target.firstChild);
			}

			init.call( this );
		};


		Nanobar.prototype.go = function (p) {
			// expand bar
			this.bars[0].go( p );
	
			// create new bar at progress end
			if (p == 100) {
				init.call( this );
			}
		};

		return Nanobar;
	})();
	return exports.Nanobar;
}));