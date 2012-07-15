define(function() { return function($) {
  var jQuery = $;
/*
 * TextLimit - jQuery plugin for counting and limiting characters for input and textarea fields
 * 
 * pass '-1' as speed if you don't want the char-deletion effect. (don't just put 0)
 * Example: jQuery("Textarea").textlimit('span.counter',256)
 *
 * $Version: 2009.07.25 +r2
 * Copyright (c) 2009 Yair Even-Or
 * vsync.design@gmail.com
*/
(function(jQuery) {
	jQuery.fn.textlimit=function(counter_el, thelimit, speed) {
		var charDelSpeed = speed || 15;
		var toggleCharDel = speed != -1;
		var toggleTrim = true;
		var that = this[0];
		var isCtrl = false; 
		updateCounter();

		function updateCounter(){
			if(typeof that == "object")
				jQuery(counter_el).text(thelimit - that.value.length+" 个字剩余");
		};
		this.keydown (function(e){ 
			if(e.which == 17) isCtrl = true;
			var ctrl_a = (e.which == 65 && isCtrl == true) ? true : false; // detect and allow CTRL + A selects all.
			var ctrl_v = (e.which == 86 && isCtrl == true) ? true : false; // detect and allow CTRL + V paste.
			// 8 is 'backspace' and 46 is 'delete'
			if( this.value.length >= thelimit && e.which != '8' && e.which != '46' && ctrl_a == false && ctrl_v == false)
				e.preventDefault();
		})
		.keyup (function(e){
			updateCounter();
			if(e.which == 17)
				isCtrl=false;
			if( this.value.length >= thelimit && toggleTrim ){
				if(toggleCharDel){
					// first, trim the text a bit so the char trimming won't take forever
					// Also check if there are more than 10 extra chars, then trim. just in case.
					if ( (this.value.length - thelimit) > 10 )
						that.value = that.value.substr(0,thelimit+100);
					var init = setInterval
						( 
							function(){ 
								if( that.value.length <= thelimit ){
									init = clearInterval(init); updateCounter() 
								}
								else{
									// deleting extra chars (one by one)
									that.value = that.value.substring(0,that.value.length-1); jQuery(counter_el).text('Trimming... '+(thelimit - that.value.length));
								}
							} ,charDelSpeed 
						);
				}
				else this.value = that.value.substr(0,thelimit);
			}
		});
	};
})(jQuery);

/*
* Placeholder plugin for jQuery
* ---
* Copyright 2010, Daniel Stocks (http://webcloud.se)
* Released under the MIT, BSD, and GPL Licenses.
*/
(function($) {
    function Placeholder(input) {
        this.input = input;
        if (input.attr('type') == 'password') {
            this.handlePassword();
        }
        // Prevent placeholder values from submitting
        $(input[0].form).submit(function() {
            if (input.hasClass('placeholder') && input[0].value == input.attr('placeholder')) {
                input[0].value = '';
            }
        });
    }
    Placeholder.prototype = {
        show : function(loading) {
            // FF and IE saves values when you refresh the page. If the user refreshes the page with
            // the placeholders showing they will be the default values and the input fields won't be empty.
            if (this.input[0].value === '' || (loading && this.valueIsPlaceholder())) {
                if (this.isPassword) {
                    try {
                        this.input[0].setAttribute('type', 'text');
                    } catch (e) {
                        this.input.before(this.fakePassword.show()).hide();
                    }
                }
                this.input.addClass('placeholder');
                this.input[0].value = this.input.attr('placeholder');
            }
        },
        hide : function() {
            if (this.valueIsPlaceholder() && this.input.hasClass('placeholder')) {
                this.input.removeClass('placeholder');
                this.input[0].value = '';
                if (this.isPassword) {
                    try {
                        this.input[0].setAttribute('type', 'password');
                    } catch (e) { }
                    // Restore focus for Opera and IE
                    this.input.show();
                    this.input[0].focus();
                }
            }
        },
        valueIsPlaceholder : function() {
            return this.input[0].value == this.input.attr('placeholder');
        },
        handlePassword: function() {
            var input = this.input;
            input.attr('realType', 'password');
            this.isPassword = true;
            // IE < 9 doesn't allow changing the type of password inputs
            if ($.browser.msie && input[0].outerHTML) {
                var fakeHTML = $(input[0].outerHTML.replace(/type=(['"])?password\1/gi, 'type=$1text$1'));
                this.fakePassword = fakeHTML.val(input.attr('placeholder')).addClass('placeholder').focus(function() {
                    input.trigger('focus');
                    $(this).hide();
                });
                $(input[0].form).submit(function() {
                    fakeHTML.remove();
                    input.show()
                });
            }
        }
    };
    var NATIVE_SUPPORT = !!("placeholder" in document.createElement( "input" ));
    $.fn.placeholder = function() {
        return NATIVE_SUPPORT ? this : this.each(function() {
            var input = $(this);
            var placeholder = new Placeholder(input);
            placeholder.show(true);
            input.focus(function() {
                placeholder.hide();
            });
            input.blur(function() {
                placeholder.show(false);
            });

            // On page refresh, IE doesn't re-populate user input
            // until the window.onload event is fired.
            if ($.browser.msie) {
                $(window).load(function() {
                    if(input.val()) {
                        input.removeClass("placeholder");
                    }
                    placeholder.show(true);
                });
                // What's even worse, the text cursor disappears
                // when tabbing between text inputs, here's a fix
                input.focus(function() {
                    if(this.value == "") {
                        var range = this.createTextRange();
                        range.collapse(true);
                        range.moveStart('character', 0);
                        range.select();
                    }
                });
            }
        });
    }
})(jQuery);
/**
 * jQuery Validation Plugin 1.9.0
 *
 * http://bassistance.de/jquery-plugins/jquery-plugin-validation/
 * http://docs.jquery.com/Plugins/Validation
 *
 * Copyright (c) 2006 - 2011 Jörn Zaefferer
 *
 * Dual licensed under the MIT and GPL licenses:
 *   http://www.opensource.org/licenses/mit-license.php
 *   http://www.gnu.org/licenses/gpl.html
 */

(function($) {

$.extend($.fn, {
  // http://docs.jquery.com/Plugins/Validation/validate
  validate: function( options ) {

    // if nothing is selected, return nothing; can't chain anyway
    if (!this.length) {
      options && options.debug && window.console && console.warn( "nothing selected, can't validate, returning nothing" );
      return;
    }

    // check if a validator for this form was already created
    var validator = $.data(this[0], 'validator');
    if ( validator ) {
      return validator;
    }

    // Add novalidate tag if HTML5.
    this.attr('novalidate', 'novalidate');

    validator = new $.validator( options, this[0] );
    $.data(this[0], 'validator', validator);

    if ( validator.settings.onsubmit ) {

      var inputsAndButtons = this.find("input, button");

      // allow suppresing validation by adding a cancel class to the submit button
      inputsAndButtons.filter(".cancel").click(function () {
        validator.cancelSubmit = true;
      });

      // when a submitHandler is used, capture the submitting button
      if (validator.settings.submitHandler) {
        inputsAndButtons.filter(":submit").click(function () {
          validator.submitButton = this;
        });
      }

      // validate the form on submit
      this.submit( function( event ) {
        if ( validator.settings.debug )
          // prevent form submit to be able to see console output
          event.preventDefault();

        function handle() {
          if ( validator.settings.submitHandler ) {
            if (validator.submitButton) {
              // insert a hidden input as a replacement for the missing submit button
              var hidden = $("<input type='hidden'/>").attr("name", validator.submitButton.name).val(validator.submitButton.value).appendTo(validator.currentForm);
            }
            validator.settings.submitHandler.call( validator, validator.currentForm );
            if (validator.submitButton) {
              // and clean up afterwards; thanks to no-block-scope, hidden can be referenced
              hidden.remove();
            }
            return false;
          }
          return true;
        }

        // prevent submit for invalid forms or custom submit handlers
        if ( validator.cancelSubmit ) {
          validator.cancelSubmit = false;
          return handle();
        }
        if ( validator.form() ) {
          if ( validator.pendingRequest ) {
            validator.formSubmitted = true;
            return false;
          }
          return handle();
        } else {
          validator.focusInvalid();
          return false;
        }
      });
    }

    return validator;
  },
  // http://docs.jquery.com/Plugins/Validation/valid
  valid: function() {
        if ( $(this[0]).is('form')) {
            return this.validate().form();
        } else {
            var valid = true;
            var validator = $(this[0].form).validate();
            this.each(function() {
        valid &= validator.element(this);
            });
            return valid;
        }
    },
  // attributes: space seperated list of attributes to retrieve and remove
  removeAttrs: function(attributes) {
    var result = {},
      $element = this;
    $.each(attributes.split(/\s/), function(index, value) {
      result[value] = $element.attr(value);
      $element.removeAttr(value);
    });
    return result;
  },
  // http://docs.jquery.com/Plugins/Validation/rules
  rules: function(command, argument) {
    var element = this[0];

    if (command) {
      var settings = $.data(element.form, 'validator').settings;
      var staticRules = settings.rules;
      var existingRules = $.validator.staticRules(element);
      switch(command) {
      case "add":
        $.extend(existingRules, $.validator.normalizeRule(argument));
        staticRules[element.name] = existingRules;
        if (argument.messages)
          settings.messages[element.name] = $.extend( settings.messages[element.name], argument.messages );
        break;
      case "remove":
        if (!argument) {
          delete staticRules[element.name];
          return existingRules;
        }
        var filtered = {};
        $.each(argument.split(/\s/), function(index, method) {
          filtered[method] = existingRules[method];
          delete existingRules[method];
        });
        return filtered;
      }
    }

    var data = $.validator.normalizeRules(
    $.extend(
      {},
      $.validator.metadataRules(element),
      $.validator.classRules(element),
      $.validator.attributeRules(element),
      $.validator.staticRules(element)
    ), element);

    // make sure required is at front
    if (data.required) {
      var param = data.required;
      delete data.required;
      data = $.extend({required: param}, data);
    }

    return data;
  }
});

// Custom selectors
$.extend($.expr[":"], {
  // http://docs.jquery.com/Plugins/Validation/blank
  blank: function(a) {return !$.trim("" + a.value);},
  // http://docs.jquery.com/Plugins/Validation/filled
  filled: function(a) {return !!$.trim("" + a.value);},
  // http://docs.jquery.com/Plugins/Validation/unchecked
  unchecked: function(a) {return !a.checked;}
});

// constructor for validator
$.validator = function( options, form ) {
  this.settings = $.extend( true, {}, $.validator.defaults, options );
  this.currentForm = form;
  this.init();
};

$.validator.format = function(source, params) {
  if ( arguments.length == 1 )
    return function() {
      var args = $.makeArray(arguments);
      args.unshift(source);
      return $.validator.format.apply( this, args );
    };
  if ( arguments.length > 2 && params.constructor != Array  ) {
    params = $.makeArray(arguments).slice(1);
  }
  if ( params.constructor != Array ) {
    params = [ params ];
  }
  $.each(params, function(i, n) {
    source = source.replace(new RegExp("\\{" + i + "\\}", "g"), n);
  });
  return source;
};

$.extend($.validator, {

  defaults: {
    messages: {},
    groups: {},
    rules: {},
    errorClass: "error",
    validClass: "valid",
    errorElement: "label",
    focusInvalid: true,
    errorContainer: $( [] ),
    errorLabelContainer: $( [] ),
    onsubmit: true,
    ignore: ":hidden",
    ignoreTitle: false,
    onfocusin: function(element, event) {
      this.lastActive = element;

      // hide error label and remove error class on focus if enabled
      if ( this.settings.focusCleanup && !this.blockFocusCleanup ) {
        this.settings.unhighlight && this.settings.unhighlight.call( this, element, this.settings.errorClass, this.settings.validClass );
        this.addWrapper(this.errorsFor(element)).hide();
      }
    },
    onfocusout: function(element, event) {
      if ( !this.checkable(element) && (element.name in this.submitted || !this.optional(element)) ) {
        this.element(element);
      }
    },
    onkeyup: function(element, event) {
      if ( element.name in this.submitted || element == this.lastElement ) {
        this.element(element);
      }
    },
    onclick: function(element, event) {
      // click on selects, radiobuttons and checkboxes
      if ( element.name in this.submitted )
        this.element(element);
      // or option elements, check parent select in that case
      else if (element.parentNode.name in this.submitted)
        this.element(element.parentNode);
    },
    highlight: function(element, errorClass, validClass) {
      if (element.type === 'radio') {
        this.findByName(element.name).addClass(errorClass).removeClass(validClass);
      } else {
        $(element).addClass(errorClass).removeClass(validClass);
      }
    },
    unhighlight: function(element, errorClass, validClass) {
      if (element.type === 'radio') {
        this.findByName(element.name).removeClass(errorClass).addClass(validClass);
      } else {
        $(element).removeClass(errorClass).addClass(validClass);
      }
    }
  },

  // http://docs.jquery.com/Plugins/Validation/Validator/setDefaults
  setDefaults: function(settings) {
    $.extend( $.validator.defaults, settings );
  },

  messages: {
    required: "This field is required.",
    remote: "Please fix this field.",
    email: "Please enter a valid email address.",
    url: "Please enter a valid URL.",
    date: "Please enter a valid date.",
    dateISO: "Please enter a valid date (ISO).",
    number: "Please enter a valid number.",
    digits: "Please enter only digits.",
    creditcard: "Please enter a valid credit card number.",
    equalTo: "Please enter the same value again.",
    accept: "Please enter a value with a valid extension.",
    maxlength: $.validator.format("Please enter no more than {0} characters."),
    minlength: $.validator.format("Please enter at least {0} characters."),
    rangelength: $.validator.format("Please enter a value between {0} and {1} characters long."),
    range: $.validator.format("Please enter a value between {0} and {1}."),
    max: $.validator.format("Please enter a value less than or equal to {0}."),
    min: $.validator.format("Please enter a value greater than or equal to {0}.")
  },

  autoCreateRanges: false,

  prototype: {

    init: function() {
      this.labelContainer = $(this.settings.errorLabelContainer);
      this.errorContext = this.labelContainer.length && this.labelContainer || $(this.currentForm);
      this.containers = $(this.settings.errorContainer).add( this.settings.errorLabelContainer );
      this.submitted = {};
      this.valueCache = {};
      this.pendingRequest = 0;
      this.pending = {};
      this.invalid = {};
      this.reset();

      var groups = (this.groups = {});
      $.each(this.settings.groups, function(key, value) {
        $.each(value.split(/\s/), function(index, name) {
          groups[name] = key;
        });
      });
      var rules = this.settings.rules;
      $.each(rules, function(key, value) {
        rules[key] = $.validator.normalizeRule(value);
      });

      function delegate(event) {
        var validator = $.data(this[0].form, "validator"),
          eventType = "on" + event.type.replace(/^validate/, "");
        validator.settings[eventType] && validator.settings[eventType].call(validator, this[0], event);
      }
      $(this.currentForm)
             .validateDelegate("[type='text'], [type='password'], [type='file'], select, textarea, " +
            "[type='number'], [type='search'] ,[type='tel'], [type='url'], " +
            "[type='email'], [type='datetime'], [type='date'], [type='month'], " +
            "[type='week'], [type='time'], [type='datetime-local'], " +
            "[type='range'], [type='color'] ",
            "focusin focusout keyup", delegate)
        .validateDelegate("[type='radio'], [type='checkbox'], select, option", "click", delegate);

      if (this.settings.invalidHandler)
        $(this.currentForm).bind("invalid-form.validate", this.settings.invalidHandler);
    },

    // http://docs.jquery.com/Plugins/Validation/Validator/form
    form: function() {
      this.checkForm();
      $.extend(this.submitted, this.errorMap);
      this.invalid = $.extend({}, this.errorMap);
      if (!this.valid())
        $(this.currentForm).triggerHandler("invalid-form", [this]);
      this.showErrors();
      return this.valid();
    },

    checkForm: function() {
      this.prepareForm();
      for ( var i = 0, elements = (this.currentElements = this.elements()); elements[i]; i++ ) {
        this.check( elements[i] );
      }
      return this.valid();
    },

    // http://docs.jquery.com/Plugins/Validation/Validator/element
    element: function( element ) {
      element = this.validationTargetFor( this.clean( element ) );
      this.lastElement = element;
      this.prepareElement( element );
      this.currentElements = $(element);
      var result = this.check( element );
      if ( result ) {
        delete this.invalid[element.name];
      } else {
        this.invalid[element.name] = true;
      }
      if ( !this.numberOfInvalids() ) {
        // Hide error containers on last error
        this.toHide = this.toHide.add( this.containers );
      }
      this.showErrors();
      return result;
    },

    // http://docs.jquery.com/Plugins/Validation/Validator/showErrors
    showErrors: function(errors) {
      if(errors) {
        // add items to error list and map
        $.extend( this.errorMap, errors );
        this.errorList = [];
        for ( var name in errors ) {
          this.errorList.push({
            message: errors[name],
            element: this.findByName(name)[0]
          });
        }
        // remove items from success list
        this.successList = $.grep( this.successList, function(element) {
          return !(element.name in errors);
        });
      }
      this.settings.showErrors
        ? this.settings.showErrors.call( this, this.errorMap, this.errorList )
        : this.defaultShowErrors();
    },

    // http://docs.jquery.com/Plugins/Validation/Validator/resetForm
    resetForm: function() {
      if ( $.fn.resetForm )
        $( this.currentForm ).resetForm();
      this.submitted = {};
      this.lastElement = null;
      this.prepareForm();
      this.hideErrors();
      this.elements().removeClass( this.settings.errorClass );
    },

    numberOfInvalids: function() {
      return this.objectLength(this.invalid);
    },

    objectLength: function( obj ) {
      var count = 0;
      for ( var i in obj )
        count++;
      return count;
    },

    hideErrors: function() {
      this.addWrapper( this.toHide ).hide();
    },

    valid: function() {
      return this.size() == 0;
    },

    size: function() {
      return this.errorList.length;
    },

    focusInvalid: function() {
      if( this.settings.focusInvalid ) {
        try {
          $(this.findLastActive() || this.errorList.length && this.errorList[0].element || [])
          .filter(":visible")
          .focus()
          // manually trigger focusin event; without it, focusin handler isn't called, findLastActive won't have anything to find
          .trigger("focusin");
        } catch(e) {
          // ignore IE throwing errors when focusing hidden elements
        }
      }
    },

    findLastActive: function() {
      var lastActive = this.lastActive;
      return lastActive && $.grep(this.errorList, function(n) {
        return n.element.name == lastActive.name;
      }).length == 1 && lastActive;
    },

    elements: function() {
      var validator = this,
        rulesCache = {};

      // select all valid inputs inside the form (no submit or reset buttons)
      return $(this.currentForm)
      .find("input, select, textarea")
      .not(":submit, :reset, :image, [disabled]")
      .not( this.settings.ignore )
      .filter(function() {
        !this.name && validator.settings.debug && window.console && console.error( "%o has no name assigned", this);

        // select only the first element for each name, and only those with rules specified
        if ( this.name in rulesCache || !validator.objectLength($(this).rules()) )
          return false;

        rulesCache[this.name] = true;
        return true;
      });
    },

    clean: function( selector ) {
      return $( selector )[0];
    },

    errors: function() {
      return $( this.settings.errorElement + "." + this.settings.errorClass, this.errorContext );
    },

    reset: function() {
      this.successList = [];
      this.errorList = [];
      this.errorMap = {};
      this.toShow = $([]);
      this.toHide = $([]);
      this.currentElements = $([]);
    },

    prepareForm: function() {
      this.reset();
      this.toHide = this.errors().add( this.containers );
    },

    prepareElement: function( element ) {
      this.reset();
      this.toHide = this.errorsFor(element);
    },

    check: function( element ) {
      element = this.validationTargetFor( this.clean( element ) );

      var rules = $(element).rules();
      var dependencyMismatch = false;
      for (var method in rules ) {
        var rule = { method: method, parameters: rules[method] };
        try {
          var result = $.validator.methods[method].call( this, element.value.replace(/\r/g, ""), element, rule.parameters );

          // if a method indicates that the field is optional and therefore valid,
          // don't mark it as valid when there are no other rules
          if ( result == "dependency-mismatch" ) {
            dependencyMismatch = true;
            continue;
          }
          dependencyMismatch = false;

          if ( result == "pending" ) {
            this.toHide = this.toHide.not( this.errorsFor(element) );
            return;
          }

          if( !result ) {
            this.formatAndAdd( element, rule );
            return false;
          }
        } catch(e) {
          this.settings.debug && window.console && console.log("exception occured when checking element " + element.id
             + ", check the '" + rule.method + "' method", e);
          throw e;
        }
      }
      if (dependencyMismatch)
        return;
      if ( this.objectLength(rules) )
        this.successList.push(element);
      return true;
    },

    // return the custom message for the given element and validation method
    // specified in the element's "messages" metadata
    customMetaMessage: function(element, method) {
      if (!$.metadata)
        return;

      var meta = this.settings.meta
        ? $(element).metadata()[this.settings.meta]
        : $(element).metadata();

      return meta && meta.messages && meta.messages[method];
    },

    // return the custom message for the given element name and validation method
    customMessage: function( name, method ) {
      var m = this.settings.messages[name];
      return m && (m.constructor == String
        ? m
        : m[method]);
    },

    // return the first defined argument, allowing empty strings
    findDefined: function() {
      for(var i = 0; i < arguments.length; i++) {
        if (arguments[i] !== undefined)
          return arguments[i];
      }
      return undefined;
    },

    defaultMessage: function( element, method) {
      return this.findDefined(
        this.customMessage( element.name, method ),
        this.customMetaMessage( element, method ),
        // title is never undefined, so handle empty string as undefined
        !this.settings.ignoreTitle && element.title || undefined,
        $.validator.messages[method],
        "<strong>Warning: No message defined for " + element.name + "</strong>"
      );
    },

    formatAndAdd: function( element, rule ) {
      var message = this.defaultMessage( element, rule.method ),
        theregex = /\$?\{(\d+)\}/g;
      if ( typeof message == "function" ) {
        message = message.call(this, rule.parameters, element);
      } else if (theregex.test(message)) {
        message = jQuery.format(message.replace(theregex, '{$1}'), rule.parameters);
      }
      this.errorList.push({
        message: message,
        element: element
      });

      this.errorMap[element.name] = message;
      this.submitted[element.name] = message;
    },

    addWrapper: function(toToggle) {
      if ( this.settings.wrapper )
        toToggle = toToggle.add( toToggle.parent( this.settings.wrapper ) );
      return toToggle;
    },

    defaultShowErrors: function() {
      for ( var i = 0; this.errorList[i]; i++ ) {
        var error = this.errorList[i];
        this.settings.highlight && this.settings.highlight.call( this, error.element, this.settings.errorClass, this.settings.validClass );
        this.showLabel( error.element, error.message );
      }
      if( this.errorList.length ) {
        this.toShow = this.toShow.add( this.containers );
      }
      if (this.settings.success) {
        for ( var i = 0; this.successList[i]; i++ ) {
          this.showLabel( this.successList[i] );
        }
      }
      if (this.settings.unhighlight) {
        for ( var i = 0, elements = this.validElements(); elements[i]; i++ ) {
          this.settings.unhighlight.call( this, elements[i], this.settings.errorClass, this.settings.validClass );
        }
      }
      this.toHide = this.toHide.not( this.toShow );
      this.hideErrors();
      this.addWrapper( this.toShow ).show();
    },

    validElements: function() {
      return this.currentElements.not(this.invalidElements());
    },

    invalidElements: function() {
      return $(this.errorList).map(function() {
        return this.element;
      });
    },

    showLabel: function(element, message) {
      var label = this.errorsFor( element );
      if ( label.length ) {
        // refresh error/success class
        label.removeClass( this.settings.validClass ).addClass( this.settings.errorClass );

        // check if we have a generated label, replace the message then
        label.attr("generated") && label.html(message);
      } else {
        // create label
        label = $("<" + this.settings.errorElement + "/>")
          .attr({"for":  this.idOrName(element), generated: true})
          .addClass(this.settings.errorClass)
          .html(message || "");
        if ( this.settings.wrapper ) {
          // make sure the element is visible, even in IE
          // actually showing the wrapped element is handled elsewhere
          label = label.hide().show().wrap("<" + this.settings.wrapper + "/>").parent();
        }
        if ( !this.labelContainer.append(label).length )
          this.settings.errorPlacement
            ? this.settings.errorPlacement(label, $(element) )
            : label.insertAfter(element);
      }
      if ( !message && this.settings.success ) {
        label.text("");
        typeof this.settings.success == "string"
          ? label.addClass( this.settings.success )
          : this.settings.success( label );
      }
      this.toShow = this.toShow.add(label);
    },

    errorsFor: function(element) {
      var name = this.idOrName(element);
        return this.errors().filter(function() {
        return $(this).attr('for') == name;
      });
    },

    idOrName: function(element) {
      return this.groups[element.name] || (this.checkable(element) ? element.name : element.id || element.name);
    },

    validationTargetFor: function(element) {
      // if radio/checkbox, validate first element in group instead
      if (this.checkable(element)) {
        element = this.findByName( element.name ).not(this.settings.ignore)[0];
      }
      return element;
    },

    checkable: function( element ) {
      return /radio|checkbox/i.test(element.type);
    },

    findByName: function( name ) {
      // select by name and filter by form for performance over form.find("[name=...]")
      var form = this.currentForm;
      return $(document.getElementsByName(name)).map(function(index, element) {
        return element.form == form && element.name == name && element  || null;
      });
    },

    getLength: function(value, element) {
      switch( element.nodeName.toLowerCase() ) {
      case 'select':
        return $("option:selected", element).length;
      case 'input':
        if( this.checkable( element) )
          return this.findByName(element.name).filter(':checked').length;
      }
      return value.length;
    },

    depend: function(param, element) {
      return this.dependTypes[typeof param]
        ? this.dependTypes[typeof param](param, element)
        : true;
    },

    dependTypes: {
      "boolean": function(param, element) {
        return param;
      },
      "string": function(param, element) {
        return !!$(param, element.form).length;
      },
      "function": function(param, element) {
        return param(element);
      }
    },

    optional: function(element) {
      return !$.validator.methods.required.call(this, $.trim(element.value), element) && "dependency-mismatch";
    },

    startRequest: function(element) {
      if (!this.pending[element.name]) {
        this.pendingRequest++;
        this.pending[element.name] = true;
      }
    },

    stopRequest: function(element, valid) {
      this.pendingRequest--;
      // sometimes synchronization fails, make sure pendingRequest is never < 0
      if (this.pendingRequest < 0)
        this.pendingRequest = 0;
      delete this.pending[element.name];
      if ( valid && this.pendingRequest == 0 && this.formSubmitted && this.form() ) {
        $(this.currentForm).submit();
        this.formSubmitted = false;
      } else if (!valid && this.pendingRequest == 0 && this.formSubmitted) {
        $(this.currentForm).triggerHandler("invalid-form", [this]);
        this.formSubmitted = false;
      }
    },

    previousValue: function(element) {
      return $.data(element, "previousValue") || $.data(element, "previousValue", {
        old: null,
        valid: true,
        message: this.defaultMessage( element, "remote" )
      });
    }

  },

  classRuleSettings: {
    required: {required: true},
    email: {email: true},
    url: {url: true},
    date: {date: true},
    dateISO: {dateISO: true},
    dateDE: {dateDE: true},
    number: {number: true},
    numberDE: {numberDE: true},
    digits: {digits: true},
    creditcard: {creditcard: true}
  },

  addClassRules: function(className, rules) {
    className.constructor == String ?
      this.classRuleSettings[className] = rules :
      $.extend(this.classRuleSettings, className);
  },

  classRules: function(element) {
    var rules = {};
    var classes = $(element).attr('class');
    classes && $.each(classes.split(' '), function() {
      if (this in $.validator.classRuleSettings) {
        $.extend(rules, $.validator.classRuleSettings[this]);
      }
    });
    return rules;
  },

  attributeRules: function(element) {
    var rules = {};
    var $element = $(element);

    for (var method in $.validator.methods) {
      var value;
      // If .prop exists (jQuery >= 1.6), use it to get true/false for required
      if (method === 'required' && typeof $.fn.prop === 'function') {
        value = $element.prop(method);
      } else {
        value = $element.attr(method);
      }
      if (value) {
        rules[method] = value;
      } else if ($element[0].getAttribute("type") === method) {
        rules[method] = true;
      }
    }

    // maxlength may be returned as -1, 2147483647 (IE) and 524288 (safari) for text inputs
    if (rules.maxlength && /-1|2147483647|524288/.test(rules.maxlength)) {
      delete rules.maxlength;
    }

    return rules;
  },

  metadataRules: function(element) {
    if (!$.metadata) return {};

    var meta = $.data(element.form, 'validator').settings.meta;
    return meta ?
      $(element).metadata()[meta] :
      $(element).metadata();
  },

  staticRules: function(element) {
    var rules = {};
    var validator = $.data(element.form, 'validator');
    if (validator.settings.rules) {
      rules = $.validator.normalizeRule(validator.settings.rules[element.name]) || {};
    }
    return rules;
  },

  normalizeRules: function(rules, element) {
    // handle dependency check
    $.each(rules, function(prop, val) {
      // ignore rule when param is explicitly false, eg. required:false
      if (val === false) {
        delete rules[prop];
        return;
      }
      if (val.param || val.depends) {
        var keepRule = true;
        switch (typeof val.depends) {
          case "string":
            keepRule = !!$(val.depends, element.form).length;
            break;
          case "function":
            keepRule = val.depends.call(element, element);
            break;
        }
        if (keepRule) {
          rules[prop] = val.param !== undefined ? val.param : true;
        } else {
          delete rules[prop];
        }
      }
    });

    // evaluate parameters
    $.each(rules, function(rule, parameter) {
      rules[rule] = $.isFunction(parameter) ? parameter(element) : parameter;
    });

    // clean number parameters
    $.each(['minlength', 'maxlength', 'min', 'max'], function() {
      if (rules[this]) {
        rules[this] = Number(rules[this]);
      }
    });
    $.each(['rangelength', 'range'], function() {
      if (rules[this]) {
        rules[this] = [Number(rules[this][0]), Number(rules[this][1])];
      }
    });

    if ($.validator.autoCreateRanges) {
      // auto-create ranges
      if (rules.min && rules.max) {
        rules.range = [rules.min, rules.max];
        delete rules.min;
        delete rules.max;
      }
      if (rules.minlength && rules.maxlength) {
        rules.rangelength = [rules.minlength, rules.maxlength];
        delete rules.minlength;
        delete rules.maxlength;
      }
    }

    // To support custom messages in metadata ignore rule methods titled "messages"
    if (rules.messages) {
      delete rules.messages;
    }

    return rules;
  },

  // Converts a simple string to a {string: true} rule, e.g., "required" to {required:true}
  normalizeRule: function(data) {
    if( typeof data == "string" ) {
      var transformed = {};
      $.each(data.split(/\s/), function() {
        transformed[this] = true;
      });
      data = transformed;
    }
    return data;
  },

  // http://docs.jquery.com/Plugins/Validation/Validator/addMethod
  addMethod: function(name, method, message) {
    $.validator.methods[name] = method;
    $.validator.messages[name] = message != undefined ? message : $.validator.messages[name];
    if (method.length < 3) {
      $.validator.addClassRules(name, $.validator.normalizeRule(name));
    }
  },

  methods: {

    // http://docs.jquery.com/Plugins/Validation/Methods/required
    required: function(value, element, param) {
      // check if dependency is met
      if ( !this.depend(param, element) )
        return "dependency-mismatch";
      switch( element.nodeName.toLowerCase() ) {
      case 'select':
        // could be an array for select-multiple or a string, both are fine this way
        var val = $(element).val();
        return val && val.length > 0;
      case 'input':
        if ( this.checkable(element) )
          return this.getLength(value, element) > 0;
      default:
        return $.trim(value).length > 0;
      }
    },

    // http://docs.jquery.com/Plugins/Validation/Methods/remote
    remote: function(value, element, param) {
      if ( this.optional(element) )
        return "dependency-mismatch";

      var previous = this.previousValue(element);
      if (!this.settings.messages[element.name] )
        this.settings.messages[element.name] = {};
      previous.originalMessage = this.settings.messages[element.name].remote;
      this.settings.messages[element.name].remote = previous.message;

      param = typeof param == "string" && {url:param} || param;

      if ( this.pending[element.name] ) {
        return "pending";
      }
      if ( previous.old === value ) {
        return previous.valid;
      }

      previous.old = value;
      var validator = this;
      this.startRequest(element);
      var data = {};
      data[element.name] = value;
      $.ajax($.extend(true, {
        url: param,
        mode: "abort",
        port: "validate" + element.name,
        dataType: "json",
        data: data,
        success: function(response) {
          validator.settings.messages[element.name].remote = previous.originalMessage;
          var valid = response === true;
          if ( valid ) {
            var submitted = validator.formSubmitted;
            validator.prepareElement(element);
            validator.formSubmitted = submitted;
            validator.successList.push(element);
            validator.showErrors();
          } else {
            var errors = {};
            var message = response || validator.defaultMessage( element, "remote" );
            errors[element.name] = previous.message = $.isFunction(message) ? message(value) : message;
            validator.showErrors(errors);
          }
          previous.valid = valid;
          validator.stopRequest(element, valid);
        }
      }, param));
      return "pending";
    },

    // http://docs.jquery.com/Plugins/Validation/Methods/minlength
    minlength: function(value, element, param) {
      return this.optional(element) || this.getLength($.trim(value), element) >= param;
    },

    // http://docs.jquery.com/Plugins/Validation/Methods/maxlength
    maxlength: function(value, element, param) {
      return this.optional(element) || this.getLength($.trim(value), element) <= param;
    },

    // http://docs.jquery.com/Plugins/Validation/Methods/rangelength
    rangelength: function(value, element, param) {
      var length = this.getLength($.trim(value), element);
      return this.optional(element) || ( length >= param[0] && length <= param[1] );
    },

    // http://docs.jquery.com/Plugins/Validation/Methods/min
    min: function( value, element, param ) {
      return this.optional(element) || value >= param;
    },

    // http://docs.jquery.com/Plugins/Validation/Methods/max
    max: function( value, element, param ) {
      return this.optional(element) || value <= param;
    },

    // http://docs.jquery.com/Plugins/Validation/Methods/range
    range: function( value, element, param ) {
      return this.optional(element) || ( value >= param[0] && value <= param[1] );
    },

    // http://docs.jquery.com/Plugins/Validation/Methods/email
    email: function(value, element) {
      // contributed by Scott Gonzalez: http://projects.scottsplayground.com/email_address_validation/
      return this.optional(element) || /^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))$/i.test(value);
    },

    // http://docs.jquery.com/Plugins/Validation/Methods/url
    url: function(value, element) {
      // contributed by Scott Gonzalez: http://projects.scottsplayground.com/iri/
      return this.optional(element) || /^(https?|ftp):\/\/(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(\#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i.test(value);
    },

    // http://docs.jquery.com/Plugins/Validation/Methods/date
    date: function(value, element) {
      return this.optional(element) || !/Invalid|NaN/.test(new Date(value));
    },

    // http://docs.jquery.com/Plugins/Validation/Methods/dateISO
    dateISO: function(value, element) {
      return this.optional(element) || /^\d{4}[\/-]\d{1,2}[\/-]\d{1,2}$/.test(value);
    },

    // http://docs.jquery.com/Plugins/Validation/Methods/number
    number: function(value, element) {
      return this.optional(element) || /^-?(?:\d+|\d{1,3}(?:,\d{3})+)(?:\.\d+)?$/.test(value);
    },

    // http://docs.jquery.com/Plugins/Validation/Methods/digits
    digits: function(value, element) {
      return this.optional(element) || /^\d+$/.test(value);
    },

    // http://docs.jquery.com/Plugins/Validation/Methods/creditcard
    // based on http://en.wikipedia.org/wiki/Luhn
    creditcard: function(value, element) {
      if ( this.optional(element) )
        return "dependency-mismatch";
      // accept only spaces, digits and dashes
      if (/[^0-9 -]+/.test(value))
        return false;
      var nCheck = 0,
        nDigit = 0,
        bEven = false;

      value = value.replace(/\D/g, "");

      for (var n = value.length - 1; n >= 0; n--) {
        var cDigit = value.charAt(n);
        var nDigit = parseInt(cDigit, 10);
        if (bEven) {
          if ((nDigit *= 2) > 9)
            nDigit -= 9;
        }
        nCheck += nDigit;
        bEven = !bEven;
      }

      return (nCheck % 10) == 0;
    },

    // http://docs.jquery.com/Plugins/Validation/Methods/accept
    accept: function(value, element, param) {
      param = typeof param == "string" ? param.replace(/,/g, '|') : "png|jpe?g|gif";
      return this.optional(element) || value.match(new RegExp(".(" + param + ")$", "i"));
    },

    // http://docs.jquery.com/Plugins/Validation/Methods/equalTo
    equalTo: function(value, element, param) {
      // bind to the blur event of the target in order to revalidate whenever the target field is updated
      // TODO find a way to bind the event just once, avoiding the unbind-rebind overhead
      var target = $(param).unbind(".validate-equalTo").bind("blur.validate-equalTo", function() {
        $(element).valid();
      });
      return value == target.val();
    }

  }

});

// deprecated, use $.validator.format instead
$.format = $.validator.format;

})(jQuery);

// ajax mode: abort
// usage: $.ajax({ mode: "abort"[, port: "uniqueport"]});
// if mode:"abort" is used, the previous request on that port (port can be undefined) is aborted via XMLHttpRequest.abort()
;(function($) {
  var pendingRequests = {};
  // Use a prefilter if available (1.5+)
  if ( $.ajaxPrefilter ) {
    $.ajaxPrefilter(function(settings, _, xhr) {
      var port = settings.port;
      if (settings.mode == "abort") {
        if ( pendingRequests[port] ) {
          pendingRequests[port].abort();
        }
        pendingRequests[port] = xhr;
      }
    });
  } else {
    // Proxy ajax
    var ajax = $.ajax;
    $.ajax = function(settings) {
      var mode = ( "mode" in settings ? settings : $.ajaxSettings ).mode,
        port = ( "port" in settings ? settings : $.ajaxSettings ).port;
      if (mode == "abort") {
        if ( pendingRequests[port] ) {
          pendingRequests[port].abort();
        }
        return (pendingRequests[port] = ajax.apply(this, arguments));
      }
      return ajax.apply(this, arguments);
    };
  }
})(jQuery);

// provides cross-browser focusin and focusout events
// IE has native support, in other browsers, use event caputuring (neither bubbles)

// provides delegate(type: String, delegate: Selector, handler: Callback) plugin for easier event delegation
// handler is only called when $(event.target).is(delegate), in the scope of the jquery-object for event.target
;(function($) {
  // only implement if not provided by jQuery core (since 1.4)
  // TODO verify if jQuery 1.4's implementation is compatible with older jQuery special-event APIs
  if (!jQuery.event.special.focusin && !jQuery.event.special.focusout && document.addEventListener) {
    $.each({
      focus: 'focusin',
      blur: 'focusout'
    }, function( original, fix ){
      $.event.special[fix] = {
        setup:function() {
          this.addEventListener( original, handler, true );
        },
        teardown:function() {
          this.removeEventListener( original, handler, true );
        },
        handler: function(e) {
          arguments[0] = $.event.fix(e);
          arguments[0].type = fix;
          return $.event.handle.apply(this, arguments);
        }
      };
      function handler(e) {
        e = $.event.fix(e);
        e.type = fix;
        return $.event.handle.call(this, e);
      }
    });
  };
  $.extend($.fn, {
    validateDelegate: function(delegate, type, handler) {
      return this.bind(type, function(event) {
        var target = $(event.target);
        if (target.is(delegate)) {
          return handler.apply(target, arguments);
        }
      });
    }
  });
})(jQuery);

/*
 * jQuery showLoading plugin v1.0
 * 
 * Copyright (c) 2009 Jim Keller
 * Context - http://www.contextllc.com
 * 
 * Dual licensed under the MIT and GPL licenses.
 *
 */

  jQuery.fn.showLoading = function(options) {
    
    var indicatorID;
          var settings = {
            'addClass': '',
            'beforeShow': '', 
            'afterShow': '',
            'hPos': 'center', 
            'vPos': 'center',
            'indicatorZIndex' : 5001, 
            'overlayZIndex': 5000, 
            'parent': '',
            'marginTop': 0,
            'marginLeft': 0,
            'overlayWidth': null,
            'overlayHeight': null,
            'img': true
          };

    jQuery.extend(settings, options);
        
          var loadingDiv = jQuery('<div></div>');
    var overlayDiv = jQuery('<div></div>');

    //
    // Set up ID and classes
    //
    if ( settings.indicatorID ) {
      indicatorID = settings.indicatorID;
    }
    else {
      indicatorID = jQuery(this).attr('id');
    }
      
    jQuery(loadingDiv).attr('id', 'loading-indicator-' + indicatorID );
    jQuery(loadingDiv).addClass('loading-indicator');

    if ( !settings.img ){
      jQuery(loadingDiv).addClass('loading-indicator-noimg');
    }
    
    if ( settings.addClass ){
      jQuery(loadingDiv).addClass(settings.addClass);
    }
    // Create the overlay
    //
    jQuery(overlayDiv).css('display', 'none');
    
    // Append to body, otherwise position() doesn't work on Webkit-based browsers
    jQuery(document.body).append(overlayDiv);
    
    //
    // Set overlay classes
    //
    jQuery(overlayDiv).attr('id', 'loading-indicator-' + indicatorID + '-overlay');
    
    jQuery(overlayDiv).addClass('loading-indicator-overlay');
    
    if ( settings.addClass ){
      jQuery(overlayDiv).addClass(settings.addClass + '-overlay');
    }
    
    //
    // Set overlay position
    //
    
    var overlay_width;
    var overlay_height;
    
    var border_top_width = jQuery(this).css('border-top-width');
    var border_left_width = jQuery(this).css('border-left-width');
    
    //
    // IE will return values like 'medium' as the default border, 
    // but we need a number
    //
    border_top_width = isNaN(parseInt(border_top_width)) ? 0 : border_top_width;
    border_left_width = isNaN(parseInt(border_left_width)) ? 0 : border_left_width;
    
    var overlay_left_pos = jQuery(this).offset().left + parseInt(border_left_width);
    var overlay_top_pos = jQuery(this).offset().top + parseInt(border_top_width);
    
    if ( settings.overlayWidth !== null ) {
      overlay_width = settings.overlayWidth;
    }
    else {
      overlay_width = parseInt(jQuery(this).width()) + parseInt(jQuery(this).css('padding-right')) + parseInt(jQuery(this).css('padding-left'));
    }

    if ( settings.overlayHeight !== null ) {
      overlay_height = settings.overlayWidth;
    }
    else {
      overlay_height = parseInt(jQuery(this).height()) + parseInt(jQuery(this).css('padding-top')) + parseInt(jQuery(this).css('padding-bottom'));
    }


    jQuery(overlayDiv).css('width', overlay_width.toString() + 'px');
    jQuery(overlayDiv).css('height', overlay_height.toString() + 'px');

    jQuery(overlayDiv).css('left', overlay_left_pos.toString() + 'px');
    jQuery(overlayDiv).css('position', 'absolute');

    jQuery(overlayDiv).css('top', overlay_top_pos.toString() + 'px' );
    jQuery(overlayDiv).css('z-index', settings.overlayZIndex);

    //
    // Set any custom overlay CSS   
    //
          if ( settings.overlayCSS ) {
            jQuery(overlayDiv).css ( settings.overlayCSS );
          }


    //
    // We have to append the element to the body first
    // or .width() won't work in Webkit-based browsers (e.g. Chrome, Safari)
    //
    jQuery(loadingDiv).css('display', 'none');
    jQuery(document.body).append(loadingDiv);
    
    jQuery(loadingDiv).css('position', 'absolute');
    jQuery(loadingDiv).css('z-index', settings.indicatorZIndex);

    //
    // Set top margin
    //

    var indicatorTop = overlay_top_pos;
    
    if ( settings.marginTop ) {
      indicatorTop += parseInt(settings.marginTop);
    }
    
    var indicatorLeft = overlay_left_pos;
    
    if ( settings.marginLeft ) {
      indicatorLeft += parseInt(settings.marginTop);
    }
    
    
    //
    // set horizontal position
    //
    if ( settings.hPos.toString().toLowerCase() == 'center' ) {
      jQuery(loadingDiv).css('left', (indicatorLeft + ((jQuery(overlayDiv).width() - parseInt(jQuery(loadingDiv).width())) / 2)).toString()  + 'px');
    }
    else if ( settings.hPos.toString().toLowerCase() == 'left' ) {
      jQuery(loadingDiv).css('left', (indicatorLeft + parseInt(jQuery(overlayDiv).css('margin-left'))).toString() + 'px');
    }
    else if ( settings.hPos.toString().toLowerCase() == 'right' ) {
      jQuery(loadingDiv).css('left', (indicatorLeft + (jQuery(overlayDiv).width() - parseInt(jQuery(loadingDiv).width()))).toString()  + 'px');
    }
    else {
      jQuery(loadingDiv).css('left', (indicatorLeft + parseInt(settings.hPos)).toString() + 'px');
    }   

    //
    // set vertical position
    //
    if ( settings.vPos.toString().toLowerCase() == 'center' ) {
      jQuery(loadingDiv).css('top', (indicatorTop + ((jQuery(overlayDiv).height() - parseInt(jQuery(loadingDiv).height())) / 2)).toString()  + 'px');
    }
    else if ( settings.vPos.toString().toLowerCase() == 'top' ) {
      jQuery(loadingDiv).css('top', indicatorTop.toString() + 'px');
    }
    else if ( settings.vPos.toString().toLowerCase() == 'bottom' ) {
      jQuery(loadingDiv).css('top', (indicatorTop + (jQuery(overlayDiv).height() - parseInt(jQuery(loadingDiv).height()))).toString()  + 'px');
    }
    else {
      jQuery(loadingDiv).css('top', (indicatorTop + parseInt(settings.vPos)).toString() + 'px' );
    }   

    // Set any custom css for loading indicator
    //
          if ( settings.css ) {
            jQuery(loadingDiv).css ( settings.css );
          }

    //
    // Set up callback options
    //
    var callback_options = 
      {
        'overlay': overlayDiv,
        'indicator': loadingDiv,
        'element': this
      };
  
    //
    // beforeShow callback
    //
    if ( typeof(settings.beforeShow) == 'function' ) {
      settings.beforeShow( callback_options );
    }
    
    //
    // Show the overlay
    //
    jQuery(overlayDiv).show();
    
    //
    // Show the loading indicator
    //
    jQuery(loadingDiv).show();

    //
    // afterShow callback
    //
    if ( typeof(settings.afterShow) == 'function' ) {
      settings.afterShow( callback_options );
    }

    return this;
       };


  jQuery.fn.hideLoading = function(options) {
    
    
          var settings = {};
  
          jQuery.extend(settings, options);

    if ( settings.indicatorID ) {
      indicatorID = settings.indicatorID;
    }
    else {
      indicatorID = jQuery(this).attr('id');
    }
        
      jQuery(document.body).find('#loading-indicator-' + indicatorID ).remove();
    jQuery(document.body).find('#loading-indicator-' + indicatorID + '-overlay' ).remove();
    
    return this;
      };



/*!
 * jQuery UI 1.8.18
 *
 * Copyright 2011, AUTHORS.txt (http://jqueryui.com/about)
 * Dual licensed under the MIT or GPL Version 2 licenses.
 * http://jquery.org/license
 *
 * http://docs.jquery.com/UI
 */(function(a,b){function d(b){return!a(b).parents().andSelf().filter(function(){return a.curCSS(this,"visibility")==="hidden"||a.expr.filters.hidden(this)}).length}function c(b,c){var e=b.nodeName.toLowerCase();if("area"===e){var f=b.parentNode,g=f.name,h;if(!b.href||!g||f.nodeName.toLowerCase()!=="map")return!1;h=a("img[usemap=#"+g+"]")[0];return!!h&&d(h)}return(/input|select|textarea|button|object/.test(e)?!b.disabled:"a"==e?b.href||c:c)&&d(b)}a.ui=a.ui||{};a.ui.version||(a.extend(a.ui,{version:"1.8.18",keyCode:{ALT:18,BACKSPACE:8,CAPS_LOCK:20,COMMA:188,COMMAND:91,COMMAND_LEFT:91,COMMAND_RIGHT:93,CONTROL:17,DELETE:46,DOWN:40,END:35,ENTER:13,ESCAPE:27,HOME:36,INSERT:45,LEFT:37,MENU:93,NUMPAD_ADD:107,NUMPAD_DECIMAL:110,NUMPAD_DIVIDE:111,NUMPAD_ENTER:108,NUMPAD_MULTIPLY:106,NUMPAD_SUBTRACT:109,PAGE_DOWN:34,PAGE_UP:33,PERIOD:190,RIGHT:39,SHIFT:16,SPACE:32,TAB:9,UP:38,WINDOWS:91}}),a.fn.extend({propAttr:a.fn.prop||a.fn.attr,_focus:a.fn.focus,focus:function(b,c){return typeof b=="number"?this.each(function(){var d=this;setTimeout(function(){a(d).focus(),c&&c.call(d)},b)}):this._focus.apply(this,arguments)},scrollParent:function(){var b;a.browser.msie&&/(static|relative)/.test(this.css("position"))||/absolute/.test(this.css("position"))?b=this.parents().filter(function(){return/(relative|absolute|fixed)/.test(a.curCSS(this,"position",1))&&/(auto|scroll)/.test(a.curCSS(this,"overflow",1)+a.curCSS(this,"overflow-y",1)+a.curCSS(this,"overflow-x",1))}).eq(0):b=this.parents().filter(function(){return/(auto|scroll)/.test(a.curCSS(this,"overflow",1)+a.curCSS(this,"overflow-y",1)+a.curCSS(this,"overflow-x",1))}).eq(0);return/fixed/.test(this.css("position"))||!b.length?a(document):b},zIndex:function(c){if(c!==b)return this.css("zIndex",c);if(this.length){var d=a(this[0]),e,f;while(d.length&&d[0]!==document){e=d.css("position");if(e==="absolute"||e==="relative"||e==="fixed"){f=parseInt(d.css("zIndex"),10);if(!isNaN(f)&&f!==0)return f}d=d.parent()}}return 0},disableSelection:function(){return this.bind((a.support.selectstart?"selectstart":"mousedown")+".ui-disableSelection",function(a){a.preventDefault()})},enableSelection:function(){return this.unbind(".ui-disableSelection")}}),a.each(["Width","Height"],function(c,d){function h(b,c,d,f){a.each(e,function(){c-=parseFloat(a.curCSS(b,"padding"+this,!0))||0,d&&(c-=parseFloat(a.curCSS(b,"border"+this+"Width",!0))||0),f&&(c-=parseFloat(a.curCSS(b,"margin"+this,!0))||0)});return c}var e=d==="Width"?["Left","Right"]:["Top","Bottom"],f=d.toLowerCase(),g={innerWidth:a.fn.innerWidth,innerHeight:a.fn.innerHeight,outerWidth:a.fn.outerWidth,outerHeight:a.fn.outerHeight};a.fn["inner"+d]=function(c){if(c===b)return g["inner"+d].call(this);return this.each(function(){a(this).css(f,h(this,c)+"px")})},a.fn["outer"+d]=function(b,c){if(typeof b!="number")return g["outer"+d].call(this,b);return this.each(function(){a(this).css(f,h(this,b,!0,c)+"px")})}}),a.extend(a.expr[":"],{data:function(b,c,d){return!!a.data(b,d[3])},focusable:function(b){return c(b,!isNaN(a.attr(b,"tabindex")))},tabbable:function(b){var d=a.attr(b,"tabindex"),e=isNaN(d);return(e||d>=0)&&c(b,!e)}}),a(function(){var b=document.body,c=b.appendChild(c=document.createElement("div"));c.offsetHeight,a.extend(c.style,{minHeight:"100px",height:"auto",padding:0,borderWidth:0}),a.support.minHeight=c.offsetHeight===100,a.support.selectstart="onselectstart"in c,b.removeChild(c).style.display="none"}),a.extend(a.ui,{plugin:{add:function(b,c,d){var e=a.ui[b].prototype;for(var f in d)e.plugins[f]=e.plugins[f]||[],e.plugins[f].push([c,d[f]])},call:function(a,b,c){var d=a.plugins[b];if(!!d&&!!a.element[0].parentNode)for(var e=0;e<d.length;e++)a.options[d[e][0]]&&d[e][1].apply(a.element,c)}},contains:function(a,b){return document.compareDocumentPosition?a.compareDocumentPosition(b)&16:a!==b&&a.contains(b)},hasScroll:function(b,c){if(a(b).css("overflow")==="hidden")return!1;var d=c&&c==="left"?"scrollLeft":"scrollTop",e=!1;if(b[d]>0)return!0;b[d]=1,e=b[d]>0,b[d]=0;return e},isOverAxis:function(a,b,c){return a>b&&a<b+c},isOver:function(b,c,d,e,f,g){return a.ui.isOverAxis(b,d,f)&&a.ui.isOverAxis(c,e,g)}}))})(jQuery);/*
 * jQuery UI Effects 1.8.18
 *
 * Copyright 2011, AUTHORS.txt (http://jqueryui.com/about)
 * Dual licensed under the MIT or GPL Version 2 licenses.
 * http://jquery.org/license
 *
 * http://docs.jquery.com/UI/Effects/
 */jQuery.effects||function(a,b){function l(b){if(!b||typeof b=="number"||a.fx.speeds[b])return!0;if(typeof b=="string"&&!a.effects[b])return!0;return!1}function k(b,c,d,e){typeof b=="object"&&(e=c,d=null,c=b,b=c.effect),a.isFunction(c)&&(e=c,d=null,c={});if(typeof c=="number"||a.fx.speeds[c])e=d,d=c,c={};a.isFunction(d)&&(e=d,d=null),c=c||{},d=d||c.duration,d=a.fx.off?0:typeof d=="number"?d:d in a.fx.speeds?a.fx.speeds[d]:a.fx.speeds._default,e=e||c.complete;return[b,c,d,e]}function j(a,b){var c={_:0},d;for(d in b)a[d]!=b[d]&&(c[d]=b[d]);return c}function i(b){var c,d;for(c in b)d=b[c],(d==null||a.isFunction(d)||c in g||/scrollbar/.test(c)||!/color/i.test(c)&&isNaN(parseFloat(d)))&&delete b[c];return b}function h(){var a=document.defaultView?document.defaultView.getComputedStyle(this,null):this.currentStyle,b={},c,d;if(a&&a.length&&a[0]&&a[a[0]]){var e=a.length;while(e--)c=a[e],typeof a[c]=="string"&&(d=c.replace(/\-(\w)/g,function(a,b){return b.toUpperCase()}),b[d]=a[c])}else for(c in a)typeof a[c]=="string"&&(b[c]=a[c]);return b}function d(b,d){var e;do{e=a.curCSS(b,d);if(e!=""&&e!="transparent"||a.nodeName(b,"body"))break;d="backgroundColor"}while(b=b.parentNode);return c(e)}function c(b){var c;if(b&&b.constructor==Array&&b.length==3)return b;if(c=/rgb\(\s*([0-9]{1,3})\s*,\s*([0-9]{1,3})\s*,\s*([0-9]{1,3})\s*\)/.exec(b))return[parseInt(c[1],10),parseInt(c[2],10),parseInt(c[3],10)];if(c=/rgb\(\s*([0-9]+(?:\.[0-9]+)?)\%\s*,\s*([0-9]+(?:\.[0-9]+)?)\%\s*,\s*([0-9]+(?:\.[0-9]+)?)\%\s*\)/.exec(b))return[parseFloat(c[1])*2.55,parseFloat(c[2])*2.55,parseFloat(c[3])*2.55];if(c=/#([a-fA-F0-9]{2})([a-fA-F0-9]{2})([a-fA-F0-9]{2})/.exec(b))return[parseInt(c[1],16),parseInt(c[2],16),parseInt(c[3],16)];if(c=/#([a-fA-F0-9])([a-fA-F0-9])([a-fA-F0-9])/.exec(b))return[parseInt(c[1]+c[1],16),parseInt(c[2]+c[2],16),parseInt(c[3]+c[3],16)];if(c=/rgba\(0, 0, 0, 0\)/.exec(b))return e.transparent;return e[a.trim(b).toLowerCase()]}a.effects={},a.each(["backgroundColor","borderBottomColor","borderLeftColor","borderRightColor","borderTopColor","borderColor","color","outlineColor"],function(b,e){a.fx.step[e]=function(a){a.colorInit||(a.start=d(a.elem,e),a.end=c(a.end),a.colorInit=!0),a.elem.style[e]="rgb("+Math.max(Math.min(parseInt(a.pos*(a.end[0]-a.start[0])+a.start[0],10),255),0)+","+Math.max(Math.min(parseInt(a.pos*(a.end[1]-a.start[1])+a.start[1],10),255),0)+","+Math.max(Math.min(parseInt(a.pos*(a.end[2]-a.start[2])+a.start[2],10),255),0)+")"}});var e={aqua:[0,255,255],azure:[240,255,255],beige:[245,245,220],black:[0,0,0],blue:[0,0,255],brown:[165,42,42],cyan:[0,255,255],darkblue:[0,0,139],darkcyan:[0,139,139],darkgrey:[169,169,169],darkgreen:[0,100,0],darkkhaki:[189,183,107],darkmagenta:[139,0,139],darkolivegreen:[85,107,47],darkorange:[255,140,0],darkorchid:[153,50,204],darkred:[139,0,0],darksalmon:[233,150,122],darkviolet:[148,0,211],fuchsia:[255,0,255],gold:[255,215,0],green:[0,128,0],indigo:[75,0,130],khaki:[240,230,140],lightblue:[173,216,230],lightcyan:[224,255,255],lightgreen:[144,238,144],lightgrey:[211,211,211],lightpink:[255,182,193],lightyellow:[255,255,224],lime:[0,255,0],magenta:[255,0,255],maroon:[128,0,0],navy:[0,0,128],olive:[128,128,0],orange:[255,165,0],pink:[255,192,203],purple:[128,0,128],violet:[128,0,128],red:[255,0,0],silver:[192,192,192],white:[255,255,255],yellow:[255,255,0],transparent:[255,255,255]},f=["add","remove","toggle"],g={border:1,borderBottom:1,borderColor:1,borderLeft:1,borderRight:1,borderTop:1,borderWidth:1,margin:1,padding:1};a.effects.animateClass=function(b,c,d,e){a.isFunction(d)&&(e=d,d=null);return this.queue(function(){var g=a(this),k=g.attr("style")||" ",l=i(h.call(this)),m,n=g.attr("class");a.each(f,function(a,c){b[c]&&g[c+"Class"](b[c])}),m=i(h.call(this)),g.attr("class",n),g.animate(j(l,m),{queue:!1,duration:c,easing:d,complete:function(){a.each(f,function(a,c){b[c]&&g[c+"Class"](b[c])}),typeof g.attr("style")=="object"?(g.attr("style").cssText="",g.attr("style").cssText=k):g.attr("style",k),e&&e.apply(this,arguments),a.dequeue(this)}})})},a.fn.extend({_addClass:a.fn.addClass,addClass:function(b,c,d,e){return c?a.effects.animateClass.apply(this,[{add:b},c,d,e]):this._addClass(b)},_removeClass:a.fn.removeClass,removeClass:function(b,c,d,e){return c?a.effects.animateClass.apply(this,[{remove:b},c,d,e]):this._removeClass(b)},_toggleClass:a.fn.toggleClass,toggleClass:function(c,d,e,f,g){return typeof d=="boolean"||d===b?e?a.effects.animateClass.apply(this,[d?{add:c}:{remove:c},e,f,g]):this._toggleClass(c,d):a.effects.animateClass.apply(this,[{toggle:c},d,e,f])},switchClass:function(b,c,d,e,f){return a.effects.animateClass.apply(this,[{add:c,remove:b},d,e,f])}}),a.extend(a.effects,{version:"1.8.18",save:function(a,b){for(var c=0;c<b.length;c++)b[c]!==null&&a.data("ec.storage."+b[c],a[0].style[b[c]])},restore:function(a,b){for(var c=0;c<b.length;c++)b[c]!==null&&a.css(b[c],a.data("ec.storage."+b[c]))},setMode:function(a,b){b=="toggle"&&(b=a.is(":hidden")?"show":"hide");return b},getBaseline:function(a,b){var c,d;switch(a[0]){case"top":c=0;break;case"middle":c=.5;break;case"bottom":c=1;break;default:c=a[0]/b.height}switch(a[1]){case"left":d=0;break;case"center":d=.5;break;case"right":d=1;break;default:d=a[1]/b.width}return{x:d,y:c}},createWrapper:function(b){if(b.parent().is(".ui-effects-wrapper"))return b.parent();var c={width:b.outerWidth(!0),height:b.outerHeight(!0),"float":b.css("float")},d=a("<div></div>").addClass("ui-effects-wrapper").css({fontSize:"100%",background:"transparent",border:"none",margin:0,padding:0}),e=document.activeElement;b.wrap(d),(b[0]===e||a.contains(b[0],e))&&a(e).focus(),d=b.parent(),b.css("position")=="static"?(d.css({position:"relative"}),b.css({position:"relative"})):(a.extend(c,{position:b.css("position"),zIndex:b.css("z-index")}),a.each(["top","left","bottom","right"],function(a,d){c[d]=b.css(d),isNaN(parseInt(c[d],10))&&(c[d]="auto")}),b.css({position:"relative",top:0,left:0,right:"auto",bottom:"auto"}));return d.css(c).show()},removeWrapper:function(b){var c,d=document.activeElement;if(b.parent().is(".ui-effects-wrapper")){c=b.parent().replaceWith(b),(b[0]===d||a.contains(b[0],d))&&a(d).focus();return c}return b},setTransition:function(b,c,d,e){e=e||{},a.each(c,function(a,c){unit=b.cssUnit(c),unit[0]>0&&(e[c]=unit[0]*d+unit[1])});return e}}),a.fn.extend({effect:function(b,c,d,e){var f=k.apply(this,arguments),g={options:f[1],duration:f[2],callback:f[3]},h=g.options.mode,i=a.effects[b];if(a.fx.off||!i)return h?this[h](g.duration,g.callback):this.each(function(){g.callback&&g.callback.call(this)});return i.call(this,g)},_show:a.fn.show,show:function(a){if(l(a))return this._show.apply(this,arguments);var b=k.apply(this,arguments);b[1].mode="show";return this.effect.apply(this,b)},_hide:a.fn.hide,hide:function(a){if(l(a))return this._hide.apply(this,arguments);var b=k.apply(this,arguments);b[1].mode="hide";return this.effect.apply(this,b)},__toggle:a.fn.toggle,toggle:function(b){if(l(b)||typeof b=="boolean"||a.isFunction(b))return this.__toggle.apply(this,arguments);var c=k.apply(this,arguments);c[1].mode="toggle";return this.effect.apply(this,c)},cssUnit:function(b){var c=this.css(b),d=[];a.each(["em","px","%","pt"],function(a,b){c.indexOf(b)>0&&(d=[parseFloat(c),b])});return d}}),a.easing.jswing=a.easing.swing,a.extend(a.easing,{def:"easeOutQuad",swing:function(b,c,d,e,f){return a.easing[a.easing.def](b,c,d,e,f)},easeInQuad:function(a,b,c,d,e){return d*(b/=e)*b+c},easeOutQuad:function(a,b,c,d,e){return-d*(b/=e)*(b-2)+c},easeInOutQuad:function(a,b,c,d,e){if((b/=e/2)<1)return d/2*b*b+c;return-d/2*(--b*(b-2)-1)+c},easeInCubic:function(a,b,c,d,e){return d*(b/=e)*b*b+c},easeOutCubic:function(a,b,c,d,e){return d*((b=b/e-1)*b*b+1)+c},easeInOutCubic:function(a,b,c,d,e){if((b/=e/2)<1)return d/2*b*b*b+c;return d/2*((b-=2)*b*b+2)+c},easeInQuart:function(a,b,c,d,e){return d*(b/=e)*b*b*b+c},easeOutQuart:function(a,b,c,d,e){return-d*((b=b/e-1)*b*b*b-1)+c},easeInOutQuart:function(a,b,c,d,e){if((b/=e/2)<1)return d/2*b*b*b*b+c;return-d/2*((b-=2)*b*b*b-2)+c},easeInQuint:function(a,b,c,d,e){return d*(b/=e)*b*b*b*b+c},easeOutQuint:function(a,b,c,d,e){return d*((b=b/e-1)*b*b*b*b+1)+c},easeInOutQuint:function(a,b,c,d,e){if((b/=e/2)<1)return d/2*b*b*b*b*b+c;return d/2*((b-=2)*b*b*b*b+2)+c},easeInSine:function(a,b,c,d,e){return-d*Math.cos(b/e*(Math.PI/2))+d+c},easeOutSine:function(a,b,c,d,e){return d*Math.sin(b/e*(Math.PI/2))+c},easeInOutSine:function(a,b,c,d,e){return-d/2*(Math.cos(Math.PI*b/e)-1)+c},easeInExpo:function(a,b,c,d,e){return b==0?c:d*Math.pow(2,10*(b/e-1))+c},easeOutExpo:function(a,b,c,d,e){return b==e?c+d:d*(-Math.pow(2,-10*b/e)+1)+c},easeInOutExpo:function(a,b,c,d,e){if(b==0)return c;if(b==e)return c+d;if((b/=e/2)<1)return d/2*Math.pow(2,10*(b-1))+c;return d/2*(-Math.pow(2,-10*--b)+2)+c},easeInCirc:function(a,b,c,d,e){return-d*(Math.sqrt(1-(b/=e)*b)-1)+c},easeOutCirc:function(a,b,c,d,e){return d*Math.sqrt(1-(b=b/e-1)*b)+c},easeInOutCirc:function(a,b,c,d,e){if((b/=e/2)<1)return-d/2*(Math.sqrt(1-b*b)-1)+c;return d/2*(Math.sqrt(1-(b-=2)*b)+1)+c},easeInElastic:function(a,b,c,d,e){var f=1.70158,g=0,h=d;if(b==0)return c;if((b/=e)==1)return c+d;g||(g=e*.3);if(h<Math.abs(d)){h=d;var f=g/4}else var f=g/(2*Math.PI)*Math.asin(d/h);return-(h*Math.pow(2,10*(b-=1))*Math.sin((b*e-f)*2*Math.PI/g))+c},easeOutElastic:function(a,b,c,d,e){var f=1.70158,g=0,h=d;if(b==0)return c;if((b/=e)==1)return c+d;g||(g=e*.3);if(h<Math.abs(d)){h=d;var f=g/4}else var f=g/(2*Math.PI)*Math.asin(d/h);return h*Math.pow(2,-10*b)*Math.sin((b*e-f)*2*Math.PI/g)+d+c},easeInOutElastic:function(a,b,c,d,e){var f=1.70158,g=0,h=d;if(b==0)return c;if((b/=e/2)==2)return c+d;g||(g=e*.3*1.5);if(h<Math.abs(d)){h=d;var f=g/4}else var f=g/(2*Math.PI)*Math.asin(d/h);if(b<1)return-0.5*h*Math.pow(2,10*(b-=1))*Math.sin((b*e-f)*2*Math.PI/g)+c;return h*Math.pow(2,-10*(b-=1))*Math.sin((b*e-f)*2*Math.PI/g)*.5+d+c},easeInBack:function(a,c,d,e,f,g){g==b&&(g=1.70158);return e*(c/=f)*c*((g+1)*c-g)+d},easeOutBack:function(a,c,d,e,f,g){g==b&&(g=1.70158);return e*((c=c/f-1)*c*((g+1)*c+g)+1)+d},easeInOutBack:function(a,c,d,e,f,g){g==b&&(g=1.70158);if((c/=f/2)<1)return e/2*c*c*(((g*=1.525)+1)*c-g)+d;return e/2*((c-=2)*c*(((g*=1.525)+1)*c+g)+2)+d},easeInBounce:function(b,c,d,e,f){return e-a.easing.easeOutBounce(b,f-c,0,e,f)+d},easeOutBounce:function(a,b,c,d,e){return(b/=e)<1/2.75?d*7.5625*b*b+c:b<2/2.75?d*(7.5625*(b-=1.5/2.75)*b+.75)+c:b<2.5/2.75?d*(7.5625*(b-=2.25/2.75)*b+.9375)+c:d*(7.5625*(b-=2.625/2.75)*b+.984375)+c},easeInOutBounce:function(b,c,d,e,f){if(c<f/2)return a.easing.easeInBounce(b,c*2,0,e,f)*.5+d;return a.easing.easeOutBounce(b,c*2-f,0,e,f)*.5+e*.5+d}})}(jQuery);/*
 * jQuery UI Effects Highlight 1.8.18
 *
 * Copyright 2011, AUTHORS.txt (http://jqueryui.com/about)
 * Dual licensed under the MIT or GPL Version 2 licenses.
 * http://jquery.org/license
 *
 * http://docs.jquery.com/UI/Effects/Highlight
 *
 * Depends:
 *	jquery.effects.core.js
 */(function(a,b){a.effects.highlight=function(b){return this.queue(function(){var c=a(this),d=["backgroundImage","backgroundColor","opacity"],e=a.effects.setMode(c,b.options.mode||"show"),f={backgroundColor:c.css("backgroundColor")};e=="hide"&&(f.opacity=0),a.effects.save(c,d),c.show().css({backgroundImage:"none",backgroundColor:b.options.color||"#ffff99"}).animate(f,{queue:!1,duration:b.duration,easing:b.options.easing,complete:function(){e=="hide"&&c.hide(),a.effects.restore(c,d),e=="show"&&!a.support.opacity&&this.style.removeAttribute("filter"),b.callback&&b.callback.apply(this,arguments),c.dequeue()}})})}})(jQuery);



}});



