var TB = {
    feature: function(name, namespaceObj) {
        TB[name] = namespaceObj;
    }
}

TB.feature('common', {
    hide: function(elObj) {
        if(elObj.length > 1) {
            elObj.each(function( index ) {
                if(!$(this).hasClass('hide')) {
                    $(this).addClass('hide');
                }
            });
        } else {
            if(!elObj.hasClass('hide')) {
                elObj.addClass('hide');
            }
        }
    },
    
    show: function(elObj) {
        if(elObj.length > 1) {
            elObj.each(function( index ) {
                if($(this).hasClass('hide')) {
                    $(this).removeClass('hide');
                }
            });
        } else {
            if(elObj.hasClass('hide')) {
                elObj.removeClass('hide');
            }
        }
        
    },
    
    prepare_tpl: function(tpl, data) {
        $.each(data, function(place, content) {
            var re = new RegExp("\{" + place + "\}","g");
            tpl = tpl.replace(re, content)
        });  
        return tpl;
    },
    
    show_success: function(messages, obj_map) {
        
        if(typeof (obj_map) == 'undefined') {
            obj_map = document;
        }
        
        this.hide($('#errorMessage', obj_map));
        this.hide($('#infoMessage', obj_map));
        $('#infoMessage', obj_map).text(messages);
        $('#infoMessage', obj_map).alert();
        this.show($('#infoMessage', obj_map));
        //$(window).scrollTo($('.alert-success', obj_map).scrollTop() + 50, 800);
    },
    
    show_error: function(messages, obj_map) {
        
        if(typeof (obj_map) == 'undefined') {
            obj_map = document;
        }
        
        this.hide($('#errorMessage', obj_map));
        this.hide($('#infoMessage', obj_map));
        $('#errorMessage', obj_map).text(messages);
        $('#errorMessage', obj_map).alert();
        this.show($('#errorMessage', obj_map));
        //$(window).scrollTo($('.alert-error', obj_map).scrollTop() + 50, 800);
    },
    
    show_field_error: function(errors, obj_map, pos) {
        
        if(typeof (pos) == 'undefined') {
            pos = 'right';
        }
        
        if(typeof (obj_map) == 'undefined') {
            obj_map = document;
        }
        var validator = $(obj_map).validate({tooltype_position:pos});
        validator.showErrors(errors);
        
        if (validator.errorList[0]) {
            var first_error_element = validator.errorList[0]['element'];
            if ($(first_error_element).is(":hidden")) {
                var closest_tab_pane = $(first_error_element).closest('.tab-pane');

                if (typeof(closest_tab_pane) != 'undefined') {
                    var selector_tab = '.nav-tabs a[href="#' + closest_tab_pane[0].id + '"]';
                    $(selector_tab).tab('show');
                }
            }
            var show_field_error_time = setTimeout(function() {
                //$(window).scrollTo($(first_error_element).parent().offset().top-50, 800);
                clearTimeout(show_field_error_time);
            }, 250);
        }
    }
    
});

TB.feature('alerts', {
    jConfirm: function(title, message, callback) {

        var $div = '<div class="modal fade" id="jConfirm" tabindex="-1" role="dialog">' +
                ' <div class="modal-dialog">' +
                ' <div class="modal-content">' +
                ' <div class="modal-header">' +
                '  <button class="close"><i class="icon-remove"> </i></button>' +
                '<h3>' + title + '</h3>' +
                '</div>' +
                ' <div class="modal-body">' +
                message +
                '</div>' +
                '<div class="modal-footer" style="text-align:center;">' +
                '      <a href="#" class="btn btn-primary confirm">Yes</a>' +
                '     <a href="#" class="btn cancel">No</a>' +
                '   </div>' +
                '   </div>' +
                '   </div>' +
                '</div>';
        $('body').append($div);
        $('#jConfirm .close').on('click', function(e) {
            e.preventDefault();
            $('#jConfirm').modal('hide');
            return false;
        });
        $('#jConfirm .cancel').on('click', function(e) {
            e.preventDefault();
            $('#jConfirm').modal('hide');
            return false;
        });
        $('#jConfirm .confirm').on('click', function(e) {
            e.preventDefault();
            $('#jConfirm').modal('hide');
            if (callback)
                callback(e);
            return false;
        });
        $('#jConfirm').modal('show');
        $('#jConfirm').on('hidden.bs.modal', function() {
            $('#jConfirm').remove();
        });
    },
    jAlert: function(title, message, callback) {
        var $div = '<div class="modal fade" id="jAlert" tabindex="-1" role="dialog"><div class="modal-dialog"><div class="modal-content">' +
                ' <div class="modal-header">' +
                '  <button class="close" data-dismiss="modal">&times;</button>' +
                '<h4 class="modal-title">' + title + '</h4>' +
                '</div>' +
                ' <div class="modal-body">' +
                message +
                '</div>' +
                '<div class="modal-footer text-center">' +
                '      <a href="#" class="btn btn-primary confirm">Ok</a>' +
                '   </div>' +
                '</div></div></div>';
        $('body').append($div);
        $('#jAlert .close').on('click', function(e) {
            e.preventDefault();
            $('#jAlert').modal('hide');
            if (callback)
                callback();
            return false;
        });
        $('#jAlert .confirm').on('click', function(e) {
            e.preventDefault();
            $('#jAlert').modal('hide');
            if (callback)
                callback();
            return false;
        });
        $('#jAlert').modal('show');
        $('#jAlert').on('hidden.bs.modal', function() {
            $('#jAlert').remove();
        });
    }

});

TB.feature('transport', {
    ajax: function(options) {
        var defaults = {
            dataType: 'json',
            type: 'POST',
            url: null,
            data: {},
            headers: {},
            success: function() {
            },
            error: function() {
            },
            sameOrigin: true,
            preloader: 'show'
        };
        
       
        var settings = $.extend({}, defaults, options);
        var preloader = settings.preloader;
        
        delete settings['preloader'];
                
        // use global base_url setting here
        settings.url = (settings.sameOrigin) ? TB.config.base_url + settings.url : settings.url;
        //settings.url += '?xhr=1';
        
        if(typeof TB.config.key_url != 'undefined') {
            settings.url += '&key=' + TB.config.key_url;
        }

        settings.success = function(data, status, xhr) {

            if(typeof data == 'string') {
                try {
                   var json = JSON.parse(data);
                   if(json.status == 'session_expired') {
                        window.location = TB.config.base_url + (typeof json.redir != 'undefined' ? json.redir : '/auth/login/?location='+window.location);
                        return;
                   }
                } catch(e) {
                   
                }
            }
            
            if(data.status == 'session_expired') {
                window.location = TB.config.base_url + (typeof data.redir != 'undefined' ? data.redir : '/authlogin/?location='+window.location);
                return;
            }
            
            if(data.status == 'error_plan') {
                var msg = $('#error_plan').html();
                TB.alerts.jAlert(attention, msg);
                $('#preloader-body').hide();
            }
            
            if (typeof options.success === 'function') {
                options.success(data, status, xhr);
            }
            $('#preloader-body').hide();
        };
                
        if ((typeof options.success === 'function') && (preloader == 'show')) {
            settings.beforeSend = function(){
                $('#preloader-body').show();
            };
        }
        
        $.ajax(settings);
    },
    
    require: function(url, scriptId, callback, item_callback, tab_id) {
        // no script loading needed - return
        if (!scriptId)
            return;

        // check if script is already loaded
        if (document.getElementById(scriptId)) {
            // if so, just execute callback and exit to avoid multiple file inclusion
            callback(item_callback, tab_id);
            return;
        }

        var script = document.createElement("script");
        script.type = "text/javascript";
        if (script.readyState) { //IE
            script.onreadystatechange = function() {
                if (script.readyState === "loaded"
                        || script.readyState === "complete") {
                    script.onreadystatechange = null;
                    callback(item_callback, tab_id);
                }
            };
        } else { //Others
            script.onload = function() {
                callback(item_callback, tab_id);
            };
        }

        // use global base_url here
        script.src = (url.indexOf('http') === -1) ? TB.config.base_url + url : url;
        script.id = scriptId;
        document.getElementsByTagName("head")[0].appendChild(script);
    }
});

// array . remove element
Array.prototype.remove = function(from, to) {
    var rest = this.slice((to || from) + 1 || this.length);
    this.length = from < 0 ? this.length + from : from;
    return this.push.apply(this, rest);
};