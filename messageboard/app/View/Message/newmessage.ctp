<?php echo $this->element('navbar'); ?>
<div class="container mt-5">
  <div class="row">
    <div class="col-md-6 offset-md-3">
      <h2>New Message</h2>
      <form id="send-message-form">
        <div class="form-group">
          <label for="recipient">Recipient</label>
          <select id="recipient" class="form-control select2" name="recipient">
            <option value="" class="hidden-placeholder" selected>Please select a recipient</option>
            <?php foreach($allUsers as $key => $value) :?>
                <option value="<?php echo $value['id']?>"><?php echo $value['name']?></option>
            <?php endforeach ;?>
          </select>
        </div>
        <div class="form-group">
          <label for="message">Message</label>
          <textarea id="message" class="form-control" rows="4" name="message"></textarea>
        </div>
        <?php echo $this->Html->link('Back', array('controller' => 'message', 'action' => 'index'), array('class' => 'btn btn-danger', 'type' => 'button')); ?>
        <button type="submit" class="btn btn-primary">Send</button>
      </form>
    </div>
  </div>
</div>