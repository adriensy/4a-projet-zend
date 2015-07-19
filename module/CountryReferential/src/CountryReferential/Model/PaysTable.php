<?php

namespace CountryReferential\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Where;

class PaysTable
{
    protected $tableGateway;
    
    /**
     * 
     * @param TableGateway $tableGateway
     */
    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }
    
    /**
     * Méthode get : récupère un ou plusieurs pays
     * 
     * @param string $code
     * @param string $fieldsString
     * @return array
     * @throws \Exception
     */
    public function getPays($code = "", $fieldsString = "")
    {
        $select = new Select();
        $where = null;
        $result = [];
        
        try {
            $select->from('pays');

            if ($code) {
                $where = $this->constructWhereFromCode($code);

                $select->where($where);

                if ($fieldsString) {
                    $columns = [];

                    $fieldsArray = explode(",", $fieldsString);

                    foreach($fieldsArray as $columnBdd) {
                        $columns[] = $columnBdd;
                    }

                    $select->columns($columns);
                }
            }
            
            $rowset = $this->tableGateway->selectWith($select);
            
            if ($rowset->count() > 1) {
                for ($i=0; ($row = $rowset->current()) && $i < 30; $rowset->next(), $i++) {
                    $result[] = $row->toArray();
                }
            } else if ($rowset->count() > 0) {
                $result[] = $rowset->current()->toArray();
            }
            
            if(empty($result)) {
                throw new \Exception("Could not find country with code, alpha2 or alpha3 equal to $code");
            }
        } catch (\Exception $e) {
            $result['error'] = $e->getMessage();
        }
        
        return $result;
    }
    
    /**
     * Retourne la liste des pays de la vue d'admin
     * 
     * @return type
     */
     public function getPaysAdmin()
    {
        return $this->getPays();
    }
    
    /**
     * Méthode get : retourne au format XML
     * 
     * @param type $code
     * @param type $fieldsString
     * @return type
     */
    public function getPaysXml($code = "", $fieldsString = "")
    {
        $paysXml = new \SimpleXMLElement("<?xml version=\"1.0\"?><country></country>");
        $paysListe = $this->getPays($code, $fieldsString);
        
        foreach($paysListe as $id => $pays) {
            if (is_string($pays)) {
                $paysXml->addChild("error", $pays);
            } else {
                $subnode = $paysXml->addChild("country-".($id+1));
                
                foreach($pays as $key => $value) {
                    $subnode->addChild($key, $value);
                }
            }
        }
        
        return $paysXml->asXML();
    }
    
    /**
     * Supprime un pays en base de données depuis son code, alpha2 ou alpha3
     * 
     * @param type $code
     */
    public function deleteCountry($code)
    {
        $delete = new \Zend\Db\Sql\Delete();
        $where = $this->constructWhereFromCode($code);
        
        $delete->from('pays')->where($where);
        
        $this->tableGateway->deleteWith($delete);
    }
    
    /**
     * Retourne une clause where sur le code fourni en paramètre
     * 
     * @param type $code
     * @return Where
     */
    private function constructWhereFromCode($code)
    {
        $where = new Where();
        
        $where->equalTo('code', $code);
        $where->OR->equalTo('alpha2', $code);
        $where->OR->equalTo('alpha3', $code);
        
        return $where;
    }
}