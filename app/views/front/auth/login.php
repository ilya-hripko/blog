<div class="row">
    <div class="span12">
        <h1 class="widget-header"><i class="fa fa-lock"></i> Signin to Test Blog</h1>
        <div class="widget-body">
            
                <?php echo generate_user_msg_containers($success, $error); ?>
                <form class="form-signin-signup" name="login_form" action="" method="POST" id="login_form">
                    <div class="form-group">
                        <label>Email</label>
                        <input type="text" class="required form-control email" placeholder="Email" name="email">
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" class="required form-control" placeholder="Password" name="password">
                    </div>
                    <div class="checkbox">
                        <label>
                            <input name="remember_me" type="checkbox"> Remember me | <a href="<?php echo $base_url?>auth/forgot">Forgot password?</a>
                        </label>
                    </div>
                    <input type="submit" class="btn btn-primary btn-large" value="Signin">
                </form>
            <?php /**/?>
                <h4><i class="fa fa-question-sign"></i> Don't have an account?</h4>
                <a class="btn btn-lg btn-default" href="<?php echo $base_url?>signup">Signup</a>
              
             
          
        </div>
    </div>
</div>