<?php

namespace CountryReferential\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Adapter\Http;
use Zend\Authentication\Adapter\Http\FileResolver;
use CountryReferential\Form\Country;

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
    
    /**
     * Create country
     */
    public function countryCreateAction()
    {
        $view = new ViewModel();
        $form = new Country(true);
        
        if ($this->request->isPost()) {
            $post = $this->request->getPost();

            $form->setData($post);

            if (false === $form->isValid()) {
                return $view;
            }

            $cleanedData = $form->getData();

            $this->saveCountry($cleanedData);
            
            return $this->redirect()->toRoute('api_admin');
        }
        
        $view->setVariable("form", $form);
        
        return $view;
    }
    
    /**
     * Update country
     * @return ViewModel
     */
    public function countryUpdateAction()
    {
        $view = new ViewModel();
        $form = new Country();
        $code = $this->params('code');
        
        if ($code) {
            $paysTable = $this->getServiceLocator()->get('pays-table');
            $paysArray = $paysTable->getPaysAdmin($code);
            
            if (isset($paysArray["error"])) {
                return $this->redirect()->toRoute('api_admin');
            }
            
            $form->bind($paysArray[0]);
            
            if ($this->request->isPost()) {
                $post = $this->request->getPost();
                
                $form->setData($post);
                
                if (false === $form->isValid()) {
                    return $view;
                }
                
                $cleanedData = $form->getData();
                
                $this->saveCountry($cleanedData);
            }

            $view->setVariable("form", $form);
            $view->setVariable("code", $code);
        }
        
        return $view;
    }
    
    public function saveCountry($data)
    {
        if (is_array($data)) {
            $country = new \CountryReferential\Model\Pays();
            $country->exchangeArray($data);
        } else {
            $country = $data;
        }
        
        $paysTable = $this->getServiceLocator()->get('pays-table');
        $paysTable->saveCountry($country);
        
        return true;
    }
    
    // FONCTION A VIRER
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
