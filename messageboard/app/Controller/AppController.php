<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

App::uses('Controller', 'Controller');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		app.Controller
 * @link		https://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {
    
    public function beforeFilter() {
        parent::beforeFilter();
        
        // Check if session userdata exists and is not empty
        if (!$this->Session->check('userdata') || empty($this->Session->read('userdata'))) {
            // Redirect only if not already on the account controller's index action
            if($this->params['controller'] !== 'account'){
                $this->redirect(array('controller' => 'account', 'action' => 'index', 'index'));
            }
        }else{
            if($this->params['controller'] === 'account'){
                $this->redirect(array('controller' => 'message', 'action' => 'index'));
            }
        }
    }
    
    public function logout() {
        $this->loadModel('Account');
        $this->autoRender = false;
        $fetchedData = $this->Account->findById($this->Session->read('userdata.id'));
        if($fetchedData) {
            // $fetchedData['Account']['last_login_time'] = date('Y-m-d H:i:s');
            $fetchedData['Account']['is_online'] = 0;
            $this->Account->save($fetchedData);
        }
        $this->Session->delete('userdata');
        $this->redirect(array('controller' => 'account', 'action' => 'index'));
    }
}
