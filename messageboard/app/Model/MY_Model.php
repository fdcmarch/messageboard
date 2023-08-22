<?php
App::uses('Model', 'Model');

class MY_Model extends AppModel {

    public $useTable = false;

    public function insertData($tableName, $data){
        $this->setSource($tableName);
        $this->create();
        return $this->save($data);
    }

    public function updateData($tableName, $data, $conditions){
        $this->setSource($tableName);
        
        // Find the record based on conditions
        $record = $this->find('first', array('conditions' => $conditions));
        // debug($record);
        
        if ($record) {
            // Update the fields
            foreach ($data as $field => $value) {
                $record['MY_Model'][$field] = $value;
            }
            
            // Debug the changes before saving
            // debug($record);zz
    
            // Save the updated record
            $result = $this->save($record);
    
            // Debug the result of the save operation
            // debug($result);
    
            return $result;
        }
        
        return false;
    }
    

    public function getData($tableName, $conditions = array(), $options = 'array') {
        $this->setSource($tableName);

        $result = $this->find('first', array('conditions' => $conditions));
        
        switch ($options) {
            case 'row':
                return isset($result['MY_Model']) ? $result['MY_Model'] : array();
            case 'object':
                return isset($result['MY_Model']) ? (object)$result['MY_Model'] : new stdClass();
            case 'count':
                return $this->find('count', array('conditions' => $conditions));
            case 'array':
            default:
                if (isset($result['MY_Model'])) {
                    return array($result['MY_Model']);
                } else {
                    return array();
                }
        }
    }

    public function getDataWithJoin($tableName, $fields = array(), $conditions = array(), $joinOptions = array(), $options = 'array') {
        $this->setSource($tableName);
    
        $joins = array();
        if (!empty($joinOptions)) {
            $joins[] = $joinOptions;
        }
    
        $result = $this->find('first', array(
            'joins' => $joins,
            'fields' => $fields,
            'conditions' => $conditions
        ));
        
        switch ($options) {
            case 'row':
                return isset($result['MY_Model']) ? $result['MY_Model'] : array();
            case 'object':
                return isset($result['MY_Model']) ? (object)$result['MY_Model'] : new stdClass();
            case 'count':
                return $this->find('count', array('conditions' => $conditions));
            case 'array':
            default:
                if (isset($result['MY_Model'])) {
                    return array($result['MY_Model']);
                } else {
                    return array();
                }
        }
    }
    
}
