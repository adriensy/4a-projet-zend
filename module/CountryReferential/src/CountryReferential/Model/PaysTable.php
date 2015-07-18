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
                $where = new Where();
                $where->equalTo('code', $code);
                $where->OR->equalTo('alpha2', $code);
                $where->OR->equalTo('alpha3', $code);

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
                throw new \Exception("Could not find row $code");
            }
        } catch (\Exception $e) {
            $result['error'] = $e->getMessage();
        }
        
        return $result;
    }
}