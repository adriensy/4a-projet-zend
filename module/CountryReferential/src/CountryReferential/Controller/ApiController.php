<?php

namespace CountryReferential\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\ViewModel;

class ApiController extends AbstractRestfulController
{

    public function indexAction()
    {
        return new ViewModel();
    }
    
    public function getAction()
    {
        $code = $this->params('code');
        
        if ($code) {
            $fieldsString = $this->params()->fromQuery('fields');
            $querySql = "SELECT ";
            
            if ($fieldsString) {
                $fieldsArray = explode(",", $fieldsString);
                
                foreach($fieldsArray as $columnBdd) {
                    $querySql .= $columnBdd.',';
                }
                $querySql = substr($querySql, 0, strlen($querySql)-1).' ';
                
                // Rechercher partiellement
            }
            
            $querySql .= "FROM pays WHERE code = \"".$code."\" OR alpha2 = \"".$code."\" OR alpha3 = \"".$code."\";";
            die($querySql);
            // Executer la requête
        }
        
        die();
        
        $json = new \Zend\View\Model\JsonModel(array(
            'success'=>true,
        ));
        
        $data = [];
        
        $data['test'] = "ee";
        
        $json->setVariable('data', $data);
        
        if (true) {
            return $json;
        } else {
            return $view;
        }
    }

}

