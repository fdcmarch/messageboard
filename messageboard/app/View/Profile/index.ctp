<?php echo $this->element('navbar'); ?>
<div class="container">
    <div class="row flex-lg-nowrap">
        <div class="col">
            <h1>Profile Information</h1>
            <div class="card">
                <div class="card-body">
                    <div class="e-profile">
                        <div class="row">
                            <div class="col-12 col-sm-auto mb-3">
                                <div class="mx-auto" style="width: 140px;">
                                    <?php if (!empty($profileData['profile_picture'])): ?>
                                    <img src="<?php echo $this->Html->url('/app/webroot/img/uploads/' . $profileData['profile_picture']); ?>" alt="<?php echo $profileData['name']; ?>" class="rounded-circle" width="150" id="image-preview" />
                                    <?php else: ?>
                                    <img src="https://bootdey.com/img/Content/avatar/avatar7.png" id="image-preview" alt="<?php echo $profileData['name']; ?>" class="rounded-circle" width="150" />
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="col d-flex flex-column flex-sm-row justify-content-between mb-3">
                                <div class="text-center text-sm-left mb-2 mb-sm-0">
                                    <h4 class="pt-sm-2 pb-1 mb-0 text-nowrap">
                                        <?php echo $profileData['name']; ?>,
                                        <?php echo $profileData['age']?>
                                    </h4>
                                    <div class="text-muted">
                                        <small>
                                            Gender:
                                            <?php echo $profileData['gender']?>
                                        </small>
                                    </div>
                                    <div class="text-muted">
                                        <small>
                                            Birthdate:
                                            <?php echo $profileData['birthday']?>
                                        </small>
                                    </div>
                                    <div class="text-muted">
                                        <small>
                                            Joined:
                                            <?php echo date("F j, Y", strtotime($profileData['date_joined'])); ?>
                                        </small>
                                    </div>
                                    <div class="text-muted">
                                        <small>
                                            Last login:
                                            <?php echo date("F j, Y h:i A", strtotime($profileData['last_login_time'])); ?>
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                          <div class="col-md-12">
                            <h5>Hobby:</h5>
                            <?php echo $profileData['hobby']?>
                          </div>
                          <div class="col-md-12 text-center mt-4">
                          <?php echo $this->Html->link('Update', array('controller' => 'profile', 'action' => 'update'), array('class' => 'btn btn-primary', 'type' => 'button')); ?>
                          </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
