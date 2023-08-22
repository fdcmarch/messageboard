<div class="login-box">
            <h1>Register</h1>
            
            <form id="register-form" method="post" action="<?= $this->Html->url(array('controller' => 'account', 'action' => 'auth')) ?>">
            <div id="error-message"></div>
            <div class="textbox">
                <i class="fa fa-user" aria-hidden="true"></i>
                <input type="text" placeholder="Username" class="input-field" name="name" value="" id="name" >
            </div>
            <div class="textbox">
                <i class="fa fa-envelope" aria-hidden="true"></i>
                <input type="text" placeholder="Email" class="input-field" name="email" value="">
            </div>

            <div class="textbox">
                <i class="fa fa-lock" aria-hidden="true"></i>
                <input type="password" placeholder="Password" class="input-field" name="password" value="">
            </div>

            <div class="textbox">
                <i class="fa fa-lock" aria-hidden="true"></i>
                <input type="password" placeholder="Confirm Password" class="input-field" name="confirm_password" value="">
            </div>

            <input type="submit" class="btn" value="Submit">
            </form>
            <h5>Already have an account? <?php echo $this->Html->link('Login here', array('controller' => 'account', 'action' => 'index')); ?></h5>
        </div>
