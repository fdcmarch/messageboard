<?php echo $this->element('navbar'); ?>
<div class="row clearfix">
    <div class="col-lg-12">
        <a href="<?php echo $this->Html->url(array('controller' => 'message', 'action' => 'addNewMessage')); ?>" class="float-right mb-4 btn btn-info" type="button">
            <i class="fas fa-plus"></i> New Message
        </a>
        <div class="card chat-app">
            <div id="plist" class="people-list">
                <div class="text-center">
                    <h4 class="text-muted">Contacts</h4>
                </div>
                <ul class="list-unstyled chat-list mt-2 mb-0 chats">
                    <?php if(!empty($allUsers)):?>
                    <?php foreach($allUsers as $key => $value) : ?>
                    <li class="clearfix openMessage" data-id="<?php echo $value['fk_userid']; ?>">
                        <?php if (!empty($value['profile_picture'])): ?>
                        <img src="<?php echo $this->Html->url('/app/webroot/img/uploads/' . $value['profile_picture']); ?>" alt="avatar"/>
                        <?php else: ?>
                        <img src="<?php echo $this->Html->url('/app/webroot/img/default.jpg'); ?>" id="image-preview" alt="avatar" >
                        <?php endif; ?>
                        <div class="about">
                            <div class="name"><?php echo $value['name']; ?></div>
                            <div class="status">
                                <?php if($value['is_online'] != 0) :?>
                                    <i class="fa fa-circle online"></i> online 
                                <?php else : ?>
                                    <i class="fa fa-circle offline"></i> offline 
                                <?php endif ; ?>

                            </div>
                        </div>
                    </li>     
                    <?php endforeach ; ?>
                    <?php else : ?>
                        <div class="no-contacts text-center">
                           No Contacts Yet
                        </div>
                    <?php endif ; ?>
                </ul>
            </div>
            <div class="chat" id="chat-content">
                <div class="user-conversation ml-2">                
                    <div class="chat-header clearfix">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="chat-about">
                                    <h6 class="m-b-0">All Messages</h6>
                                    <h6 style="font-size:13px;"><i class="note"> <strong>Note:</strong> Click the contact's name to reply to their message. Below is only a summary of all the messages you have sent and received.</i></h6>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="chat-history">
                        <ul class="m-b-0 chat-body chats" id="messageContainer">
                            <?php if(!empty($messages)):?>
                            <?php foreach($messages as $key => $message):?>

                                <?php if($message['from_id'] == $this->Session->read('userdata.id')):?>
                                    <li class="clearfix">
                                        <div class="message-data text-right">
                                            <span class="sent-to">Sent to: <?php echo $message['to_name']; ?> on <?php echo date("M. j, Y \a\\t h:i A", strtotime($message['sent_date'])); ?></span>
                                            <?php if (!empty($message['profile_picture'])): ?>
                                                <img src="<?php echo $this->Html->url('/app/webroot/img/uploads/' . $message['profile_picture']); ?>" alt="avatar"/>
                                            <?php else: ?>
                                                <img src="<?php echo $this->Html->url('/app/webroot/img/default.jpg'); ?>"  alt="avatar"/>
                                            <?php endif; ?>
                                        </div>
                                        <div class="message other-message float-right"> <?php echo $message['content']?></div>
                                        <button type="button" class="btn waves-effect waves-light btn-danger float-right icon-right deleteMessage" data-id="<?php echo $message['id']; ?>"><i class="fa fa-trash"></i></button>
                                    </li>
                                <?php else : ?>
                                        <li class="clearfix">
                                            <div class="openMessage"  data-id="<?php echo $message['from_id']; ?>">
                                                <div class="message-data">
                                                    <span class="message-data-time"><?php echo $message['name']?></span>
                                                    <?php if (!empty($message['profile_picture'])): ?>
                                                        <img src="<?php echo $this->Html->url('/app/webroot/img/uploads/' . $message['profile_picture']); ?>" alt="avatar" class="float-left" />
                                                    <?php else: ?>
                                                        <img src="<?php echo $this->Html->url('/app/webroot/img/default.jpg'); ?>"  alt="avatar" class="float-left" />
                                                    <?php endif; ?>
                                                </div>
                                                <div class="message-data">
                                                    <div class="display-date"><?php echo date("M. j, Y h:i A", strtotime($message['sent_date'])); ?></div>
                                                </div>
                                            </div>
                                            <div class="message my-message"><?php echo $message['content']?></div>
                                            <button type="button" class="btn waves-effect waves-light btn-danger deleteMessage" data-id="<?php echo $message['id']; ?>"><i class="fa fa-trash"></i></button>                                      
                                        </li>        
                                <?php endif ; ?>
                            <?php endforeach ; ?>
                            <?php else : ?>
                                <div class="no-contacts">
                                    No Messages Yet
                                </div>
                            <?php endif ; ?>
                        </ul>
                    </div>
                    <div class="chat-pagination mb-4">
                        <div class="paginator">
                            <!-- <div class="pagination">
                                </?php echo $this->Paginator->prev(__('Show Previous'), array('class' => 'btn btn-info mr-3'), null, array('class' => 'd-none')); ?>
                                <br>
                                </?php echo $this->Paginator->next(__('Show More'), array('class' => 'btn btn-success'), null, array('class' => 'd-none')); ?>
                            </div> -->
                            <button id="showMoreButton" class="btn btn-info" data-total-pages="<?php echo $totalPages; ?>">Show More</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
