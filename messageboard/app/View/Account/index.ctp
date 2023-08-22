
<div class="login-box">
            <h1>Login</h1>
            <form id="login-form">
            <div id="error-message"></div>
                <div class="textbox">
                    <i class="fa fa-envelope" aria-hidden="true"></i>
                    <input type="text" placeholder="Username or Email" name="email" value="">
                </div>

                <div class="textbox">
                    <i class="fa fa-lock" aria-hidden="true"></i>
                    <input type="password" placeholder="Password" name="password" value="">
                </div>

                <input type="submit" class="btn" name="" value="Submit">
            </form>
            <h5>Don't have an account? <?php echo $this->Html->link('Register here', array('controller' => 'account', 'action' => 'register')); ?></h5>
        </div>

