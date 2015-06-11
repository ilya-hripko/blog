
<div class="page-header">
    <h1>Signup to TestBlog</h1>
</div>
<div class="row">
    <div class="span6 offset3">
        <h4 class="widget-header"><i class="icon-gift"></i> Be a part of Test Blog</h4>
        <div class="widget-body">
            <div class="center-align">
                <?php echo generate_user_msg_containers(); ?>
                <form class="form-signup" name="registration_form" action="<?php echo $base_url?>signup" method="POST" id="signup_form">
                    <div class="form-group">
                        <label>Email</label>
                        <input type="text" class="required email form-control" placeholder="Email" name="email" />
                    </div>
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" class="required form-control" placeholder="Name" name="username" />
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" class="required form-control" placeholder="Password" name="password" />
                    </div>
                    <div class="form-group">
                        <label>Password confirmation</label>
                        <input type="password" class="required form-control" placeholder="Password confirmation" name="password_confirmation" />
                    </div>
                    <input type="submit" class="btn btn-primary btn-lg" value="Sign Up">
                </form>
                <h4><i class="icon-question-sign"></i> Have an account?</h4>
                <a class="btn btn-lg btn-default" href="<?php echo $base_url?>login">Sign In</a>
            </div>
        </div>
    </div>
</div>
