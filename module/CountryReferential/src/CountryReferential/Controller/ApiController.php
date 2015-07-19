<?php

namespace CountryReferential\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;

class ApiController extends AbstractRestfulController
{
    protected $acceptCriteria = array(
      'Zend\View\Model\ViewModel' => array(
         'application/xml',
      ),
   );
    
    /**
     * Gère les requêtes entrantes
     * 
     * @return type
     */
    public function indexAction()
    {
        if ($this->getRequest()->getMethod() == 'GET') {
            $view = $this->getAction();
            
            $view->setTemplate("/country-referential/api/get.phtml");
            
            return $view;
        } else if ($this->getRequest()->getMethod() == 'DELETE') {
            $this->deleteAction();
        }
    }
    
    /**
     * Méthode GET de l'API
     * 
     * @return \Zend\View\Model\JsonModel
     */
    public function getAction()
    {
        $view = $this->acceptableViewModelSelector($this->acceptCriteria, false);
        
        if (!$view) {
            $view = new JsonModel();
        }
        
        $code = $this->params('code');
        $fieldsString = $this->params()->fromQuery('fields');
        
        $paysTable = $this->getServiceLocator()->get('pays-table');
        
        if (get_class($view) == 'Zend\View\Model\ViewModel') {
            $view->setTerminal(true);
            
            $this->response->getHeaders()->addHeaderLine('Content-Type', 'text/xml; charset=utf-8');
            
            $paysList = $paysTable->getPaysXml($code, $fieldsString);
        } else {
            $paysList = $paysTable->getPays($code, $fieldsString);
        }
        
        $view->setVariable('country', $paysList);
        
        return $view;
    }
    
    /**
     * Supprime un pays en BDD
     * 
     * @param type $code
     */
    public function deleteAction()
    {
        $code = $this->params('code');
        
        if ($code) {
            $paysTable = $this->getServiceLocator()->get('pays-table');

            $paysTable->deleteCountry($code);
        }
    }
}

