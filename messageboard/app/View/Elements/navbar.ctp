<?php App::uses('Router', 'Routing');?>
<nav class="navbar navbar-expand-md navbar-light bg-light mb-4">
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
    </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
            <li class="nav-item dropdown u-pro">
                    <a class="nav-link dropdown-toggle waves-effect waves-dark profile-pic" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-user"></i>
                    <span class="hidden-md-down"><?php echo $this->Session->read('userdata.username')?></span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right animated flipInY">
                    <ul class="dropdown-user">
                        <li>
                            <a href="<?php echo Router::url(array('controller' => 'profile', 'action' => 'index')); ?>"><i class="fa fa-user"></i> Profile</a>
                        </li>
                        <li role="separator" class="divider"></li>
                        <li>
                            <a href="<?php echo Router::url(array('controller' => 'app', 'action' => 'logout')); ?>"><i class="fa fa-power-off"></i> Logout</a>
                        </li>
                    </ul>
                    </div>
                </li>
            </ul>
        </div>
</nav>
<div class="container">
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-themecolor text-capitalize"><?php echo $this->params['controller'] ?> </h3>
            <ol class="breadcrumb bg-white">
                <li class="breadcrumb-item">
                    <a href="<?php echo Router::url(array('controller' => 'message', 'action' => 'index')); ?>">Home</a>
                </li>
                <li class="breadcrumb-item active text-capitalize"><?php echo ($pageTitle); ?></li>
            </ol>
        </div>
    </div>