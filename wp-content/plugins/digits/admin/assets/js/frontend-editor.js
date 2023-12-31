jQuery(function () {
    var body = jQuery('body');

    body.addClass('digits-editor-mode');
    var selectBox = jQuery('<div class="digits_editor-selector"><div class="digits_editor-selector_content"><span></span></div></div>');
    selectBox.appendTo(body);
    window.addEventListener("mousemove", editorSelector, true);
    window.addEventListener("mouseout", editorSelectorOut, true)

    document.addEventListener("click", clickHandler, true);

    var allowSelection = true;
    var isSelected = false;

    jQuery(window).on('resize', function (e) {
        removeSelect();
    });

    function clickHandler(e) {
        if (!allowSelection) {
            return true;
        }
        e.stopPropagation();
        e.preventDefault();
        var select = selectElem(e);
        isSelected = select && !isSelected;
        sendSelectedElement(e);
    }

    function editorSelectorOut(e) {
        if (!isSelected) {
            removeSelect();
        }
    }

    function removeSelect() {
        isSelected = false;
        selectBox.hide();
        sendSelectedElement(null);
    }

    function editorSelector(e) {
        if (!isSelected) {
            selectElem(e);
        }
    }

    function selectElem(e) {
        if (!allowSelection) {
            return true;
        }
        var target = e.target;

        if (target.parentNode.tagName === "BODY") {
            editorSelectorOut(e);
            return false;
        }

        var pos = target.getBoundingClientRect();

        var top = pos.top + document.documentElement.scrollTop;
        var left = pos.left + document.documentElement.scrollLeft;
        var width = target.offsetWidth;
        var height = target.offsetHeight;
        var elem_css = {};
        elem_css.left = left + 'px';
        elem_css.top = top + 'px'
        elem_css.height = height + 'px';
        elem_css.width = width + 'px';

        var elem_name = getElemName(target);
        var min_width = elem_name.length * 7;

        if (width > min_width) {
            min_width = 'unset';
        } else {
            min_width += 'px';
        }

        selectBox.show().css(elem_css).find('span').css('min-width', min_width).text(elem_name);

        return true;
    }

    function sendSelectedElement(e) {
        var selector = [];
        if (e !== null) {
            for (var elem = e.target; elem && elem.tagName !== 'BODY'; elem = elem.parentNode) {
                selector.push(getElemName(elem))
            }
            selector.push('body');
        }
        var message = {key: 'digits_editor_frame', value: 'editor_select', selector: selector};
        window.parent.postMessage(message, "*");
    }

    window.addEventListener('message', function (event) {
        if (event && event.data) {
            var data = event.data;
            if (data.key && data.key === 'digits_editor_mode') {
                process_message(data);
            }
        }
    })

    function process_message(data) {
        if (data.body.mode) {
            allowSelection = data.body.mode === 'selector';
            if (!allowSelection) {
                removeSelect();
                body.removeClass('digits-editor-mode');
            } else {
                body.addClass('digits-editor-mode');
            }
        } else if (data.body.script_type) {
            process_script(data.body);
        } else if (data.body.visibility) {
            jQuery(data.body.elem).hide();
            removeSelect();
        }
    }

    window.addEventListener('beforeunload', function (event) {
        removeSelect(null);
    });

    function getElemName(elem) {
        if (elem.id) {
            return '#' + elem.id;
        }
        if (elem.classList.length > 0) {
            return '.' + elem.classList.value.split(' ').join('.');
        }

        return elem.tagName.toLowerCase();
    }

    var custom_css = jQuery('#digits_custom_css');
    var custom_js = jQuery('#digits_custom_js');

    function process_script(obj) {
        var script_type = obj.script_type;
        if (script_type === 'css') {
            if (!custom_css.length) {
                body.append('<style id="digits_custom_css"></style>')
                custom_css = jQuery('#digits_custom_css');
            }
            custom_css.html(obj.script);
        } else if (script_type === 'js') {
            if (!custom_js.length) {
                body.append('<script id="digits_custom_js" type="text/javascript"></script>')
                custom_js = jQuery('#digits_custom_js');
            }
            custom_js.html(obj.script);
        }
    }

});