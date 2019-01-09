var DataAJAX = (function($, window) {
    var Ajax = {
        configs : {
            selector : '.ajax' ,
            attribute: 'data-ajax', // attribute(prefix) for configs
            eventAttribute: 'data-on',
            events: 'click submit',
            defaultCommand: 'parents'
        },
        getObject : {}
    };

    // Convert to object
    var toFunction = function(func) {
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
    var toObjectOfFunctions = function(value) {
        var func, newValue = {};
        for(var key in value) {
            func = toFunction(value[key]);
            if (func) {
                newValue[key] = func;
            }
        }
        return newValue;
    };

    // set convert config
    var functions = ['beforeSend', 'complete', 'dataFilter', 'error', 'jsonpCallback', 'success', 'xhr'];
    for (var i = 0; i < functions.length; i++) {
        Ajax.getObject[functions[i]] = toFunction;
    }
    Ajax.getObject['statusCode'] = toObjectOfFunctions;
    Ajax.getObject['context'] = function(value, element) {
        var contextFunc, toContext;
        if (contextFunc = toFunction(value)) {
            return contextFunc(element);
        }
        if (value == 'this') {
            return element;
        }
        try {
            toContext = JSON.parse(value);
            
        } catch(e) {
            toContext = {};
            toContext[this.configs.defaultCommand] = value;
        }
        var $context = $(element), args;
        for (var key in toContext) {
            if (key in $.fn) {
                args = $.isArray(toContext[key]) ? toContext[key] : [toContext[key]];
                $context = $context[key].apply($context, args);
            }
        }
        return $context.length > 0 ? $context.get() : null;
    };
    Ajax.getObject['data'] = function(value, element) {
        if (dataFunc = toFunction(value)) {
            return dataFunc(element);
        }
        var parse = null;
        try {
            parse = JSON.parse(value);
        } catch(error) {
            console.error('Data AJAX error: ' + error.message);
        }
        return parse;
    };

    // get element settings for $.ajax
    Ajax.getElementSettings = function(element) {
        var settings = {}, Ajax = this;
        $.each(element.attributes, function() {
            if (this.name.indexOf(Ajax.configs.attribute) === 0) {
                var name = this.name.replace(Ajax.configs.attribute + '-', '').replace(/-([a-z])/g, function (m) { return m[1].toUpperCase(); });
                settings[name] = name in Ajax.getObject ? Ajax.getObject[name].apply(Ajax, [this.value, element]) : this.value;
            }
        });
        return settings;
    };

    // init
    Ajax.init = function(configs) {
        this.configs = $.extend(this.configs, configs);
        // set events
        $(function(){
            $(document).on(DataAJAX.configs.events, DataAJAX.configs.selector, function(event) {
                if (event.type !== event.currentTarget.getAttribute(DataAJAX.configs.eventAttribute)) {
                    return true;
                }
                var settings = DataAJAX.getElementSettings(event.currentTarget);
                $.ajax(settings);
                return false;
            });
        });
    };

    return Ajax;
})(jQuery, window);