<?php

namespace CountryReferential\Form;

use Zend\Form\Form;
use Zend\Form\Element\Text;
use Zend\Form\Element\Submit;

class CountryUpdate extends Form
{
    public function __construct() {
        parent::__construct('CountryUpdate');
        
        $this->setAttribute('method', 'post');
        $this->setAttribute('enctype', 'application/x-www-form-urlencoded');
        
        $nomFrFr = new Text('nom_fr_fr');
        $nomFrFr->setLabel('Nom français');
        $nomFrFr->setAttribute('required', true);
        
        $submit = new Submit('send');
        $submit->setValue("Modifier");
        
        //$validator = new Zend\Validator\EmailAddress();
        
        //$result = $validator->isValid($email);
        
        // Ajout des champs
        $this->add($nomFrFr);
        $this->add($submit);
    }
}

