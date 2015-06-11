var TB_front = {
    init: function() {
        this._bind_actions();
    },
    _bind_actions: function() {
        var $this = this;
        $('.social-block a').on('click', function(e){
            e.preventDefault();
            var link = $(this).attr('href');
            window.open(link, "Share", "width=420,height=230,status=no");
        });
        
        $('.select-menu').on('change', function(){
            window.location = $(this).val();
        });
        
        
        $('.posts-rating').rating({
            fx: 'half',
            image: '/assets/rating/images/stars-16.png',
            loader: '/assets/rating/images/ajax-loader.gif',
            url: '/ajax/post_rating',
            width:16,
            callback: function(responce) {
                this.vote_success.fadeOut(4000);
            }
        });
        
    }
}

TB.feature('front', TB_front);