var TB_blog = {
    post_id:false,
    init: function() {
        this._bind_actions();
    },
    _bind_actions: function() {
        var $this = this;
        $('.btn-add-post').on('click', function(e){
            e.preventDefault();
            $this._init_post_form($(this).attr('post_id'));
        });
        $('.btn-add-category').on('click', function(e){
            e.preventDefault();
            TB.alerts.jAlert('Error', 'Sorry "Add Category" function not working yet!');
        });
        $('#post_modal form').on('submit', function(){
            $this._submit_post_form();
            return false;
        });
        $('#post_modal .btn-save').on('click', function(){
            $this._submit_post_form();
        });
        $('.btn-delete-post').on('click', function(e){
            e.preventDefault();
            $this._delete_post($(this).attr('post_id'));
        });
    },
    _init_post_form: function(post_id){
        var data={};
        if(post_id>0){
            data.post_id = post_id;
            this.post_id = post_id;
        }else{
            this.post_id = false;
        }
        TB.transport.ajax({
            url:'ajax/post_form',
            data:data,
            success: function(response){
                if(response.status=='success'){
                    $('#post_modal').find('.modal-body').html(response.html);
                    $('#post_modal').modal('show');
                }else{
                    TB.alerts.jAlert('Error', response.error);
                }
            }
        });
    },
    _submit_post_form: function(){
        var post = $('#post_modal form').serialize();
        TB.transport.ajax({
            url: 'ajax/edit_post',
            data: post,
            dataType: 'json',
            success: function(response) {
                if (response.status == 'success') {
                    window.location = response.url;
                } else {
                    TB.alerts.jAlert("Errors", "The form could not be submitted because of the following errors: <br/><br/>" + response.errors);
                }
            },
            error: function() {
                TB.alerts.jAlert("Errors", 'Internal server error!');
            }
        });
    },
    _delete_post: function(post_id){
        TB.alerts.jConfirm('Attention', 'Are you sure you want to delete this post?', function(){
            TB.transport.ajax({
                url: 'ajax/delete_post',
                data: {post_id:post_id},
                dataType: 'json',
                success: function(response) {
                    if (response.status == 'success') {
                        window.location = response.url;
                    } else {
                        TB.alerts.jAlert("Errors", response.errors);
                    }
                },
                error: function() {
                    TB.alerts.jAlert("Errors", 'Internal server error!');
                }
            });
        });
    }
}

TB.feature('blog', TB_blog);