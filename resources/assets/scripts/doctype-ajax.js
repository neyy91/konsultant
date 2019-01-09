/**
 * Doctype AJAX
 * Examples:
 * {
 *     "$": [
 *         { "#selector1" : "insert Html" },
 *         { "#selector2" : { "text" : "insert text" } },
 *         { "#selector2_1" : { "text" : ["insert text"] } },
 *         { "#selector2_2" : [
 *             {
 *                 "text" : ["insert text"] }
 *             },
 *             {
 *                 'attr' : {"title" : "insert title"}
 *             }
 *         ],
 *         { "#selector3" : [
 *             {
 *                 "attr" : {"title" : "set title"},
 *             },
 *             {
 *                 "attr" : {
 *                     "data-text" : "text",
 *                     "data-value" : 123,
 *                     "data-more" : "more values"
 *                 }
 *             },
 *         ] },
            {
                "context" : {
                    "attr": {"title" : "Success set contxt title"},
                    "html" : "Success set context html"
                }
            }
 *     ],
 * }
 */
var DoctypeAJAX = (function($, window) {
    var Ajax = {
        settings : {
            defaultCommand: 'html'
        }
    };

    // Define roots.
    // Funcions: init, ajaxSuccess.
    Ajax.roots = {};

    // convert to commands
    Ajax.toCommands = function(value) {
        if ($.isArray(value)) {
            return value;
        }
        if ($.isPlainObject(value)) {
            return [value];
        }
        var command = {};
        command[this.settings.defaultCommand] = value;
        return [command]
    };
    // run hook in containers
    Ajax.runHook = function(hook, container, args) {
        if (container in this) {
            for(var k in this[container]) {
                if (hook in this[container][k] && $.isFunction(this[container][k][hook])) {
                    this[container][k][hook].apply(this, args);
                }
            }
        }
    };

    // Init Doctype AJAX
    Ajax.init = function(settings) {
        // init roots
        var settings = $.extend(this.settings, settings);
        this.runHook('init', 'roots', [settings]);
        // set callback
        $(function(){
            $(document).ajaxSuccess(function(event, xhr, settings) {
                if ('responseJSON' in xhr && 'doctype' in xhr.responseJSON && xhr.responseJSON.doctype == 'AJAX') {
                    DoctypeAJAX.runHook('ajaxSuccess', 'roots', [xhr.responseJSON, event, xhr, settings]);
                }
            });
            // $(document).ajaxError(function(event, jqxhr, settings, thrownError) {
            //     console.error('Doctype AJAX error: ' + thrownError.message);
            // })
        });
    }

    // jQuery root element - $
    Ajax.roots.$ = {
        selectors: {},
        init: function(settings) {
            // run jquery commands
            this.jQueryRunAllCommands = function(resItems, context, selectors) {
                var element, selector;
                var items = this.toCommands(resItems);
                for (var i = 0; i < items.length; i++) {
                    for(selector in items[i]) {
                        element = selector in selectors ? $(selectors[selector]) : $(selector, context).get();
                        this.jQueryRunCommands(items[i][selector], element);
                    }
                }
            };
            // run commands(array)
            this.jQueryRunCommands = function(commandsToRun, context) {
                var attr, command, $element;
                var commands = this.toCommands(commandsToRun);
                var $context = $(context);
                for (var c = 0; c < commands.length; c++) {
                    $element = $context;
                    for(command in commands[c]) {
                        if ($element[command]) {
                            if ($.isFunction($element[command])) {
                                attr = $.isArray(commands[c][command] ) ? commands[c][command] : [commands[c][command]];
                                $element = $element[command].apply($element, attr);
                            } else {
                                // variable ?
                                $element = $element[command];
                            }
                            
                        }
                    }
                }
            };
            this.jQueryGetSelectors = function(event, xhr, settings) {
                var selectors = {
                    'document' : document,
                }
                if (settings) {
                    selectors.context = 'context' in settings && settings.context ? settings.context : document;
                }
                if (event) {
                    selectors.target = event.currentTarget.activeElement;
                }
                return selectors;
            }
        },
        ajaxSuccess: function(response, event, xhr, settings) {
            if ('$' in response && response.$) {
                this.jQueryRunAllCommands(response.$, document, this.jQueryGetSelectors(event, xhr, settings));
            };
        }
    };

    Ajax.roots.run = {
        init: function(settings) {
            this.toFunction = function(func) {
                var props = func.split('.');
                var obj = window;
                for (var i = 0; i < props.length; i++) {
                    if (props[i] && props[i] in obj) {
                        obj = obj[props[i]];
                    }
                    else {
                        obj = null;
                        break;
                    }
                }
                return obj && $.isFunction(obj) ? obj : null;
            };
        },
        ajaxSuccess: function(response, event, xhr, settings) {
            if ('run' in response && $.isPlainObject(response.run)) {
                var name, func, attr;
                for(name in response.run) {
                    attr = $.isArray(response.run[name]) ? response.run[name] : [response.run[name]];
                    attr.push({event : event, xhr: xhr, settings: settings});
                    if (func = DoctypeAJAX.toFunction(name)) {
                        func.apply(null, attr);
                    }
                }
            }
        }
    };

    return Ajax;
})(jQuery, window);
