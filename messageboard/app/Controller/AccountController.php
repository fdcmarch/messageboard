<?php 

App::uses('AppController', 'Controller');

class AccountController extends AppController {

    public $layout = 'login_layout';

    public function __construct($request = null, $response = null) {
        parent::__construct($request, $response);
        $this->autoRender = false;
        $this->loadModel('MY_Model');
    }

    public function index() {
        $this->render('index');
    }
    
    public function register() {
        // $this->layout = 'login_layout';
        $this->render('register');
    }

    public function auth() {
        date_default_timezone_set('Asia/Manila');
        $this->loadModel('Account');
        $this->loadModel('Userdata');

        $result = $this->Account->find('first', array(
            'conditions' => array(
                'email' => $this->request->data('email')
            )
        ));

        if(empty($result)) {
            if($this->request->data('password') === $this->request->data('confirm_password')){

                 // Start: Insert to main table (Account)
                $userData = array(
                    'username' => $this->request->data('name'),
                    'email' => $this->request->data('email'),
                    'password' => password_hash($this->request->data('password'), PASSWORD_BCRYPT),
                    'last_login_time' => date('Y-m-d H:i:s'),
                    'is_online' => 1,
                    'date_added' => date('Y-m-d H:i:s')
                );

                if ($this->Account->save($userData)) {
                    // Fetch the newly inserted user data
                    $new_result = $this->Account->find('first', array(
                        'conditions' => array(
                            'email' => $this->request->data('email')
                        )
                    ));

                    // Set session data
                    $sessionData = array(
                        'id' => $new_result['Account']['id'],
                        'username' => $new_result['Account']['username'],
                        'logged_in' => 1
                    );
                    $this->Session->write('userdata', $sessionData);
                    // End: Insert to main table (Account)

                    // Start: Insert to tbl_userdata
                    $userProfile = array(
                        'fk_userid' => $new_result['Account']['id'],
                        'name' => $this->request->data('name'),
                        'date_joined' => date('Y-m-d H:i:s')
                    );

                    if($this->Userdata->save($userProfile)) {
                        $data['status'] = true;
                        $data['message'] = 'Data was inserted';
                    } else {
                        $data['status'] = false;
                        $data['message'] = 'Data was not inserted 1';
                    }
                    // End: Insert to tbl_userdata
                } else {
                    $data['status'] = false;
                    $data['message'] = 'Data was not inserted';
                }
            
            }else{
                $data['status'] = false;
                $data['message'] = 'Password does not matched';
            }
        } else {
            $data['status'] = false;
            $data['message'] = 'Email already exists';
        }
        echo json_encode($data);
    }

    public function login() {
        date_default_timezone_set('Asia/Manila');
        $this->loadModel('Account');
        $email = $this->request->data('email');
        $password = $this->request->data('password');

        $run_query = $this->Account->find('first', array(
            'conditions' => array(
                'OR' => array(
                    'Account.email' => $email,
                    'Account.username' => $email
                )
            ),
            'fields' => array(
                'Account.*',
                'User.name'
            ),
            'joins' => array(
                array(
                    'table' => 'tbl_userdata',
                    'alias' => 'User',
                    'type' => 'INNER',
                    'conditions' => array(
                        'User.fk_userid = Account.id'
                    )
                )
            )
        ));
        if(!empty($run_query)){
            if (password_verify($password, $run_query['Account']['password'])) {
                $userData = array(
                    'id' => $run_query['Account']['id'],
                    'username' => $run_query['Account']['username'],
                    'logged_in' => 1
                );
                $this->Session->write('userdata', $userData);

                $account = $this->Account->findById($run_query['Account']['id']);
                if($account) {
                    $account['Account']['last_login_time'] = date('Y-m-d H:i:s');
                    $account['Account']['is_online'] = 1;
                    $this->Account->save($account);

                    $data['status'] = true;
                }
            }else{
                $data['status'] = false;
                $data['message'] = 'Incorrect Email or Password';
            }
        }else{
            $data['status'] = false;
            $data['message'] = 'User does not exists';
        }
        echo json_encode($data);
    }
}