<?php 

App::uses('AppController', 'Controller');

class MessageController extends AppController {
    public $layout = 'ProfileLayout';
    public $components = array('Paginator');

    public function index() {
        $pageTitle = 'Message List';
        $this->set(compact('pageTitle'));


        $this->loadModel('Message');
        $this->loadModel('Profile');


        $perPage = 10;

         // Get the total count of messages
        $messageCount = $this->Message->find('count', array(
            'conditions' => array(
                'OR' => array(
                    'Message.from_id' => $this->Session->read('userdata.id'),
                    'Message.to_id' => $this->Session->read('userdata.id')
                )
            )
        ));

        // Calculate the total number of pages
        $totalPages = ceil($messageCount / $perPage);


        $page = $this->request->query('page');
        $this->Paginator->settings = array(
            'limit' => $perPage,
            'page' => $page,
            'conditions' => array(
                'OR' => array(
                    'Message.from_id' => $this->Session->read('userdata.id'),
                    'Message.to_id' => $this->Session->read('userdata.id')
                )
            ),
            'order' => array('Message.id' => 'desc'),
            'joins' => array(
                array(
                    'table' => 'tbl_userdata', 
                    'alias' => 'Profile',
                    'type' => 'INNER',
                    'conditions' => array(
                        'Message.from_id = Profile.fk_userid'
                    )
                ),
                array(
                    'table' => 'tbl_userdata',
                    'alias' => 'Recipient',
                    'type' => 'INNER',
                    'conditions' => array(
                        'Message.to_id = Recipient.fk_userid'
                    )
                )
            ),
            'fields' => array(
                'Message.id',
                'Message.from_id',
                'Message.to_id',
                'Message.content',
                'Message.sent_date',
                'Profile.fk_userid',
                'Profile.name',
                'Profile.profile_picture',
                'Recipient.name AS to_name'
            )
        );
        $messages = $this->Paginator->paginate('Message');
        $this->set(compact('messages', 'totalPages'));


        // Simplify the array structure
        $simplifiedMessages = array();
        foreach ($messages as $message) {
            $simplifiedMessages[] = array_merge($message['Message'], [
                'name' => $message['Profile']['name'],
                'profile_picture' => $message['Profile']['profile_picture'],
                'to_name' => $message['Recipient']['to_name']
            ]);
        }

        $this->set('messages', $simplifiedMessages);

        $originalUseTable = $this->Profile->useTable;
        $this->Profile->useTable = 'tbl_userdata';
        $userId = $this->Session->read('userdata.id');
        $allUsers = $this->Profile->find('all', array(
            'conditions' => array(
                'Profile.fk_userid !=' => $userId
            ),
            'fields' => array(
                'Profile.id',
                'Profile.fk_userid',
                'Profile.name',
                'Profile.profile_picture',
                'Users.is_online'
            ),
            'joins' => array(
                array(
                    'table' => 'tbl_users', // Specify the correct table name for the users
                    'alias' => 'Users',
                    'type' => 'INNER',
                    'conditions' => array(
                        'Profile.fk_userid = Users.id'
                    )
                )
            )
        ));
        $this->Profile->useTable = $originalUseTable;

        if (!empty($allUsers)) {
            foreach ($allUsers as &$user) {
                $user['Profile']['is_online'] = $user['Users']['is_online'];
                unset($user['Users']); 
            }
        }
        $flatUserData = array_map(function($data) {
            return $data['Profile'];
        }, $allUsers);        


        
        $this->set('allUsers', $flatUserData);
        $this->render('index');
    }

    public function addNewMessage(){
        $pageTitle = 'New Message';
        $this->set(compact('pageTitle'));


        $this->loadModel('Profile');
        $originalUseTable = $this->Profile->useTable;
        $this->Profile->useTable = 'tbl_userdata';


        $userId = $this->Session->read('userdata.id');
        $allUsers = $this->Profile->find('all', array(
            'conditions' => array(
                'Profile.fk_userid !=' => $userId
            )
        ));
        $this->Profile->useTable = $originalUseTable;
        // Create a simplified structure with names and IDs
        $simplifiedUsers = array();
        foreach ($allUsers as $userData) {
            $profile = $userData['Profile'];
            $simplifiedUsers[] = array(
                'id' => $profile['fk_userid'],
                'name' => $profile['name']
            );
        }
        $this->set('allUsers', $simplifiedUsers);
        $this->render('newmessage');
    }

    
    public function view() {

        $this->loadModel('Profile');
        $this->loadModel('Message');
        $this->autoRender = false;
        $originalUseTable = $this->Profile->useTable;
        $this->Profile->useTable = 'tbl_userdata';

        $postId = $this->request->data('id');

        $allUsers = $this->Profile->find('first', array(
            'conditions' => array(
                'Profile.fk_userid' => $postId
            ),
            'fields' => array(
                'Profile.id',
                'Profile.fk_userid',
                'Profile.name',
                'Profile.profile_picture',
                'User.last_login_time',
                'User.is_online'
            ),
            'joins' => array(
                array(
                    'table' => 'tbl_users',
                    'alias' => 'User',
                    'type' => 'INNER',
                    'conditions' => array(
                        'User.id = Profile.fk_userid'
                    )
                )
            )
        ));
        $this->Profile->useTable = $originalUseTable;
        if (!empty($allUsers)) {
            $allUsers['Profile']['last_login_time'] = date("M. j, Y h:i A", strtotime($allUsers['User']['last_login_time']));
            $allUsers['Profile']['is_online'] = $allUsers['User']['is_online'];
            unset($allUsers['User']); // Remove the User array from the result
        }

        // Retrieve messages from tbl_messages where the sender is me
        $messagesFromMe = $this->Message->find('all', array(
            'conditions' => array(
                'Message.to_id' => $postId,
                'Message.from_id' => $this->Session->read('userdata.id')
            )
        ));
        
        // Extract inner arrays and create a simplified structure
        $messagesFromMeArray = array();
        foreach ($messagesFromMe as $message) {
            $messagesFromMeArray[] = $message['Message'];
        }

        // Retrieve messages from tbl_messages where the receiver is me
        $messagesToMe = $this->Message->find('all', array(
            'conditions' => array(
                'Message.to_id' => $this->Session->read('userdata.id'),
                'Message.from_id' => $postId 
            )
        ));

        // Extract inner arrays and create a simplified structure
        $messagesToMeArray = array();
        foreach ($messagesToMe as $message) {
            $messagesToMeArray[] = $message['Message'];
        }

         // Combine user data and messages
        $userWithMessages = array(
            'Profile' => $allUsers['Profile'],
            'MessagesFromMe' => $messagesFromMeArray,
            'MessagesToMe' => $messagesToMeArray
        );

        $formattedUserWithMessages = $this->formatDates($userWithMessages);

        echo json_encode($formattedUserWithMessages);
    }

    private function formatDates($data) {
        foreach ($data['MessagesFromMe'] as &$message) {
            $message['sent_date'] = date('M. d, Y h:i A', strtotime($message['sent_date']));
        }
    
        foreach ($data['MessagesToMe'] as &$message) {
            $message['sent_date'] = date('M. d, Y h:i A', strtotime($message['sent_date']));
        }
    
        return $data;
    }

    public function delete() {
        $this->loadModel('Message');
        $this->autoRender = false;

        $messageId = $this->request->data('id');

        if($this->Message->delete($messageId)){
            $response['message'] = "Message deleted.";
            $response['status'] = true;
        }else{
            $response['message'] = "Something went wrong.";
            $response['status'] = false;
        }

        echo json_encode($response);
    }

    public function sendMessage() {
        date_default_timezone_set('Asia/Manila');

        if ($this->request->is('ajax')) {
            $this->autoRender = false;
            $data = $this->request->data;
            if(!empty($data['recipient']) && !empty($data['message'])){
                $insertData = array(
                    'from_id' => $this->Session->read('userdata.id'),
                    'to_id' => $data['recipient'],
                    'content' => $data['message'],
                    'sent_date' => date('Y-m-d H:i:s')
                );
    
                if($this->Message->save($insertData)) {
                    $response['message'] = "Message sent.";
                    $response['status'] = true;
                } else {
                    $response['message'] = "Something went wrong, please try again.";
                    $response['status'] = false;
                }
            }else{
                $response['message'] = "Recipient and Message cannot be empty.";
                $response['status'] = false;
            }
        }
        echo json_encode($response);
    }


    public function replyMessage() {

        date_default_timezone_set('Asia/Manila');
        $this->autoRender = false;
        $recipientId = $this->request->data('id');
        
        if(!empty($this->request->data('reply'))) {
            $insertNewMessage = array(
                'from_id' => $this->Session->read('userdata.id'),
                'to_id' => $recipientId,
                'content' => $this->request->data('reply'),
                'sent_date' => date('Y-m-d H:i:s')
            );

            if($this->Message->save($insertNewMessage)) {
                $response['message'] = "Message sent.";
                $response['status'] = true;
            }else{
                $response['message'] = "Something went wrong, please try again.";
                $response['status'] = false;
            }
        }else{
                $response['message'] = "Message cannot be empty.";
                $response['status'] = false;
        }

        echo json_encode($response);
    }

    public function thankyou(){
        $this->render('thankyou');
    }

}