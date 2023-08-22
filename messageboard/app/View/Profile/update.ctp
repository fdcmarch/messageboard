<?php echo $this->element('navbar'); ?>
<div class="container">
<div class="row flex-lg-nowrap">
  <div class="col">
  <h1>Profile Information</h1>
    <div class="row">
      <div class="col mb-3">
        <div class="card">
          <div class="card-body">
            <div class="e-profile">
              <div class="row">

              <!--  -->
              <div class="col-12 col-sm-auto mb-3">
    <div class="mx-auto" style="width: 140px;">
        <?php if (!empty($profileData['profile_picture'])): ?>
            <img src="<?php echo $this->Html->url('/app/webroot/img/uploads/' . $profileData['profile_picture']); ?>" alt="<?php echo $profileData['name']; ?>" class="rounded-circle" width="150" id="image-preview">
        <?php else: ?>
            <img src="https://bootdey.com/img/Content/avatar/avatar7.png" id="image-preview" alt="<?php echo $profileData['name']; ?>" class="rounded-circle" width="150">
        <?php endif; ?>
    </div>
</div>
<div class="col d-flex flex-column flex-sm-row justify-content-between mb-3">
    <div class="text-center text-sm-left mb-2 mb-sm-0">
        <h4 class="pt-sm-2 pb-1 mb-0 text-nowrap"><?php echo $profileData['name']; ?></h4>
        <div class="text-muted"><small>Joined: <?php echo date("F j, Y", strtotime($profileData['date_joined'])); ?></small></div>
        <div class="text-muted"><small>Last login: <?php echo date("F j, Y h:i A", strtotime($profileData['last_login_time'])); ?></small></div>
        <form class="form" id="update-form" enctype="multipart/form-data" method="post" >
        <div class="mt-2">
                <label for="profile-image" class="btn btn-primary">
                    <i class="fa fa-fw fa-camera"></i>
                    <span>Change Photo</span>
                </label>
        </div>
    </div>
</div>
<!--  -->
              </div>
              <div class="tab-content pt-3">
                <div class="tab-pane active">
                    <div class="row">
                      <div class="col">
                        <div class="row">
                          <div class="col">
                            <div class="form-group">
                              <input type="file" name="file" id="profile-image" style="display:none;">
                              <label>Full Name</label>
                              <input class="form-control" type="text" name="name" value="<?php echo $profileData['name']?>">
                            </div>
                          </div>
                          <div class="col">
                            <div class="form-group">
                              <label>Email</label>
                              <input class="form-control" type="email" name="email" value="<?php echo $profileData['email']?>">
                            </div>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col">
                            <div class="form-group">
                              <label>Age</label>
                              <input class="form-control" type="text"  name="age" value="<?php echo $profileData['age']?>">
                            </div>
                          </div>
                          <div class="col">
                            <div class="form-group">
                              <label>Birthday</label>
                              <input class="form-control" type="text"  name="birthday" value="<?php echo $profileData['birthday']?>" id="datepicker">
                            </div>
                          </div>
                          <div class="col">
                            <div class="form-group">
                              <label>Gender</label>
                                <select name="gender" class="form-control">
                                  <option value="Male" <?php if ($profileData['gender'] === 'Male') echo 'selected'; ?>>Male</option>
                                  <option value="Female" <?php if ($profileData['gender'] === 'Female') echo 'selected'; ?>>Female</option>
                                </select>
                            </div>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col mb-3">
                            <div class="form-group">
                              <label>Hobby</label>
                              <textarea class="form-control" rows="5"  name="hobby" ><?php echo $profileData['hobby']?></textarea>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col d-flex justify-content-end">
                        <button class="btn btn-primary" type="submit">Save Changes</button>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</div>