<?php

namespace CountryReferential\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Adapter\Http;
use Zend\Authentication\Adapter\Http\FileResolver;

class AdminController extends AbstractActionController
{
    
    public function indexAction()
    {
        //$this->getAuthService();
        
        $view = new ViewModel();
        
        $paysTable = $this->getServiceLocator()->get('pays-table');
        $paysList = $paysTable->getPaysAdmin("", "nom_fr_fr", true);
        
        $view->setVariable('country', $paysList);
        
        return $view;
    }
    
    /**
     * Supprime un pays dans la liste admin
     * @return ViewModel
     */
    public function deleteAction()
    {
        $code = $this->params('code');
        
        if ($code) {
            $paysTable = $this->getServiceLocator()->get('pays-table');

            $paysTable->deleteCountry($code);
        }
        
        return $this->redirect()->toRoute('api_admin');
    }
    
    protected function getAuthService()
    {
        $config = array(
            'accept_schemes' => 'basic',
            'realm'          => 'country-referential-admin',
            //'digest_domains' => '/admin',
            //'nonce_timeout'  => 3600,
        );
        
//        if (null == $this->authService){
            $httpAuthAdapter = new Http($config);
            $authService = new AuthenticationService();
            $basicResolver = new FileResolver();
            
            $basicResolver->setFile(dirname(dirname(dirname(dirname(dirname(__DIR__))))).'\public\files\basicPasswd.txt');
            
            $httpAuthAdapter->setBasicResolver($basicResolver);
            
            $httpAuthAdapter->setRequest($this->getRequest());
            $httpAuthAdapter->setResponse($this->getResponse());

            
            $result = $httpAuthAdapter->authenticate();
            
            
            if (!$result->isValid()) {
                die(var_dump($result->getMessages()));
            }
            
            die('654645');
            
            $authService->setAdapter($httpAuthAdapter);
            $this->authService = $authService;
//        }
        
        return $this->authService;
    }
}
