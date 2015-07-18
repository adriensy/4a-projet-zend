<?php

namespace CountryReferential\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\ViewModel;
use CountryReferential\Model\Pays;

class ApiController extends AbstractRestfulController
{

    public function indexAction()
    {
        return new ViewModel();
    }
    
    /**
     * Méthode GET de l'API
     * @return \Zend\View\Model\JsonModel
     */
    public function getAction()
    {
        $code = $this->params('code');
        $fieldsString = $this->params()->fromQuery('fields');
        
        $paysTable = $this->getServiceLocator()->get('pays-table');
        $jsonView = new \Zend\View\Model\JsonModel(array(
            'success'=>true,
        ));
        
        $paysList = $paysTable->getPays($code, $fieldsString);
        
        $jsonView->setVariable('data', $paysList);
        
        if (true) {
            return $jsonView;
        } else {
            return $view;
        }
    }

}

