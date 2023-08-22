<?php 

App::uses('AppController', 'Controller');

class ProfileController extends AppController {
    public $layout = 'ProfileLayout';

    public function index(){
        $pageTitle = 'My Profile';
        $this->set(compact('pageTitle'));


        $username = $this->Session->read('userdata.name');
        $this->set('username', $username);
    
        $userData = $this->Profile->find('first', array(
            'joins' => array(
                array(
                    'table' => 'tbl_userdata',
                    'alias' => 'UserData',
                    'type' => 'INNER',
                    'conditions' => array(
                        'UserData.fk_userid = Profile.id'
                    )
                )
            ),
            'conditions' => array(
                'Profile.id' => $this->Session->read('userdata.id')
            ),
            'fields' => array(
                'Profile.email',
                'Profile.last_login_time',   // All columns from tbl_users
                'UserData.*'     // All columns from tbl_userdata
            )
        ));
        $this->set('profileData', array_merge($userData['Profile'], $userData['UserData']));
        $this->Session->write('profileData',array_merge($userData['Profile'], $userData['UserData']));

        
        $this->render('index');
    }
    
    public function update(){
        $pageTitle = 'My Profile';
        $this->set(compact('pageTitle'));

        $profileData = $this->Session->read('profileData');
        $this->set('profileData', $profileData);
        $this->render('update');
    }

    public function updateProfileData() {
        $this->loadModel('MY_Model');
        $this->loadModel('Account');

        $this->autoRender = false;
        $userId = $this->Session->read('userdata.id'); 
        $result = $this->MY_Model->getData('tbl_userdata', array('fk_userid' => $userId), 'row');
        
        if(!empty($result)){
            
            $file = $_FILES['file'];
            $allowedFormats = array('jpg', 'jpeg', 'gif', 'png');
            $fileExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            
            if (!empty($_FILES['file']['name']) && !in_array($fileExtension, $allowedFormats)) {
                $response['status'] = false;
                $response['message'] = "Invalid file format. Only JPG, GIF, and PNG files are allowed.";
            }else{
                $email_exist = $this->Account->find('first', array(
                    'conditions' => array(
                        'id !=' => $this->Session->read('userdata.id'),
                        'email' => $this->request->data('email'),
                    )
                ));
                if(empty($email_exist)){
                    if (!empty($_FILES['file']['name'])) {
                    
                    // Process and save the file
                    $uploadDir = WWW_ROOT . 'img' . DS . 'uploads' . DS;
                    $filename = $file['name'];
                    $uploadPath = $uploadDir . $filename;
                    
                    if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
                        
                        $fields = array('profile_picture' => $filename);
                        $conditions = array('id' => $result['id']);
                        $this->MY_Model->updateData('tbl_userdata', $fields, $conditions);
                        $response['status'] = true;
                        $response['message'] = "Profile Update Successfully.";
                    } else {
                        $response['status'] = false;
                        $response['message'] = "Error uploading file.";
                    }
                }
                    $fields = array(
                        'name' => $this->request->data('name'),
                        'age' => $this->request->data('age'),
                        'gender' => $this->request->data('gender'),
                        'birthday' => $this->request->data('birthday'),
                        'hobby' => $this->request->data('hobby'),
                    );
                    $conditions = array('id' => $result['id']);
                    $this->MY_Model->updateData('tbl_userdata', $fields, $conditions);
                    $updatedFirstTable = true;
                    if($updatedFirstTable){
                        $fields = array('email' => $this->request->data('email'));
                        $conditions = array('id' => $result['fk_userid']);
                        $this->MY_Model->updateData('tbl_users', $fields, $conditions);
                        $response['status'] = true;
                        $response['message'] = "Profile Update Successfully";
                    }
                }else{
                    $response['status'] = false;
                    $response['message'] = "Email already exist";
                }
            }
            
        }
        
        echo json_encode($response);
    }
    
    
}