var Position = (function(){
    var Position = {
        replaceDataSplit : '&&',
        selector: '.toggle-position',
        andToggleClass: 'notoggled',
        setClass: 'position-set',
        animation: 'fast',
        init: function() {
            var Position = this;
            $(this.selector).each(function(){
                var $this = $(this);
                $($this.attr('data-container')).height(0);
            });
            $(document).on('click', this.selector, function(event){
                var $currentTarget = $(event.currentTarget);
                var $container = $($currentTarget.attr('data-container'));
                var $target = $($currentTarget.attr('href'));
                var $positionSet = $('.' + Position.setClass);
                if ($positionSet.length > 0 && $positionSet.is($currentTarget) && $currentTarget.hasClass(Position.setClass)) {
                    $target.hide(Position.animation);
                    $container.height(0);
                }
                else {
                    if ($positionSet.length > 0) {
                        $($positionSet.attr('data-container')).height(0);
                        $container.height($target.height());
                        Position.setPosition($currentTarget, true);
                    }
                    else {
                        Position.setPosition($currentTarget, false);
                        $target.trigger('position.show');
                        $target.show(Position.animation, function(){
                            $container.height($target.height());
                            $target.trigger('position.shown');
                        });
                    }
                    var replacesData = $currentTarget.attr('data-replace')
                    if (replacesData) {
                        Position.setReplaces(replacesData);
                    }
                    var replacesAttrData = $currentTarget.attr('data-replace-attr')
                    if (replacesAttrData) {
                        Position.setReplacesAttr(replacesAttrData);
                    }
                }
                if ($positionSet.length > 0) {
                    Position.toggleClass($positionSet);
                    if (!$positionSet.is($currentTarget)) {
                        Position.toggleClass($currentTarget);
                    }
                }
                else {
                    Position.toggleClass($currentTarget);
                }
                return false;
            });
        },
        toggleClass: function($tag) {
            $tag.toggleClass(this.setClass).toggleClass(this.andToggleClass);
        },
        setPosition : function($element, animate) {
            var pos = $($element.attr('data-container')).position();
            var $target = $($element.attr('href'));
            if (animate) {
                $target.animate({'top': pos.top, 'left' : pos.left}, this.animation, function() {
                    $(this).trigger('position.set');
                });
            }
            else {
                $target.css({'top': pos.top, 'left' : pos.left});
                $target.trigger('position.set');
            }

        },
        setReplaces : function(replacesData) {
            var replaces = replacesData.split(this.replaceDataSplit);
            for (var i = 0; i < replaces.length; i++) {
                replace = replaces[i].split('=');
                if (replace[0] && replace[1]) {
                    $(replace[0]).html(replace[1]);
                }
            }
        },
        setReplacesAttr : function(replacesAttrData) {
            var replaces = replacesAttrData.split(this.replaceDataSplit);
            var attr = '', selector = '', indexLeft = 0;
            for (var i = 0; i < replaces.length; i++) {
                replace = replaces[i].split('=');
                indexLeft = replace[0].indexOf('[');
                selector = replace[0].substring(0, indexLeft)
                attr = replace[0].substring(indexLeft + 1, replace[0].indexOf(']'));
                if (selector && attr) {
                    $(replace[0]).attr(attr, replace[1]);
                }
            }
        }
    };
    return Position;
})(jQuery);