var auth = { 
    map: {},
    
    init: function(){
        this._map();
        this._bind_action();
    },
    
    _map: function() {
        this.map = {
            signup_form: $('#signup_form'),
            login_form: $('#login_form')
        }
    },
    
    _bind_action: function () {
        var map = this.map;
        var $this = this;
        
        map.signup_form.submit(function(e) {
            e.preventDefault();
            var isValid = map.signup_form.validate({tooltype_position:'top'}).form();
            if (!isValid) {
                return;
            }
            
            var post = $(this).serialize();    
        
            TB.transport.ajax({
                url:'auth/signup',
                data:post,
                success: function(data){
                    if(data.status == 'success') {
                       window.location = data.redir;
                       return;
                    } else {
                        if(data.type == 'global') {
                            TB.common.show_error(data.error);
                        } else if (data.type == 'field') {
                            TB.common.show_field_error(data.errors, document, 'top');
                        }
                    }
                }
            });

        });
        
        map.login_form.submit(function(e) {
            e.preventDefault();
            var isValid = map.login_form.validate({tooltype_position:'top'}).form();
            if (!isValid) {
                return;
            }
            
            var post = $(this).serialize();    
        
            TB.transport.ajax({
                url:'auth/login',
                data:post,
                success: function(data){
                    if(data.status == 'success') {
                       window.location = data.redir;
                       return;
                    } else {
                        if(data.type == 'global') {
                            TB.common.show_error(data.error);
                        } else if (data.type == 'field') {
                            TB.common.show_field_error(data.errors, document, 'top');
                        }
                    }
                }
            });

        });
    }
}

TB.feature('auth', auth);
TB.auth.init();