<?php

namespace Main\Entity\Mongo;

use Exception;
use Main\DI\Containerable;
use Main\Entity\D7\D7Query;
use MongoDB\BSON\ObjectID;
use MongoDB\BSON\Regex;
use MongoDB\Driver\Command;
use MongoDB\Driver\Query;

class DocumentQuery extends D7Query
{
    use Containerable;

    public function __construct($modelName, $collectionClass = null)
    {
        $this->modelName = $modelName;

        if ($collectionClass)
            $this->collectionClass = $collectionClass;
    }

    public static function getClientFiltersInfo()
    {
        return [
            '_id' => [
                'TYPE' => 'string',
            ],
            'nid' => [
                'TYPE' => 'number',
            ],
        ];
    }

    public function count()
    {
        $query = $this->filter ?? [];

        $command = new Command(["count" => "logger_event", 'query' => (object)$query]);

        $con = $this->container->getMongoService()->getConnection();

        try {
            $result = $con->executeCommand("sushi", $command);
            $res = current($result->toArray());
            $count = $res->n;
        } catch (\MongoDB\Driver\Exception\Exception $e) {
            echo $e->getMessage(), "\n";
        }

        return $count;
    }

    function buildClientFilterItem(&$result, $field, $cond, $filter)
    {
        if (!isset($filter['CALLBACK'])) {

            $field = isset($filter['FIELD']) ? $filter['FIELD'] : $field;

            if (is_array($cond)) {
                foreach ($cond as $op => $val) {
                    switch ($op) {
                        case 'lt':
                            $result[$field]['$lt'] = $cond['lt'];
                            break;
                        case 'gt':
                            $result[$field]['$gt'] = $cond['gt'];
                            break;
                        case 'eq':
                            $result[$field] = $cond['eq'];
                            break;
                        case 'ne':
                            $result[$field]['$ne'] = $cond['ne'];
                            break;
                        case 'in':
                            $result[$field]['$in'] = $cond['in'];
                            break;
                        case 'nin':
                            $result[$field]['$nin'] = $cond['nin'];
                            break;
                        case 'like':
                            $result[$field] = new Regex($cond['like']);
                            break;
                    }
                }
            } else {
                $result[$field] = $cond;
            }


        } else {
            $filter['CALLBACK']($result, $cond, $this);
        }
    }

    public function loadCollection($params)
    {
        $rows = [];


        if (!$this->setEmpty) {

            $options = [];

            $filter = $params['filter'] ?? [];

            if (!empty($params['order'])) {
                foreach ($params['order'] as $sortField => $sortDir) {
                    switch ($sortDir) {
                        case 'ASC':
                        case 'asc':
                            $sortDir = 1;
                            break;
                        case 'DESC':
                        case 'desc':
                            $sortDir = -1;
                            break;
                    }
                    $options['sort'][$sortField] = $sortDir;
                }
            }

            if ($this->limit) {
                $options['limit'] = $this->limit;
            }

            if (!empty($filter['_id'])) {
                try {
                    $ids = array_filter((array)$filter['_id']);
                    if ($ids[0]) {
                        foreach ($ids as &$id) {
                            if (!is_object($id))
                                $id = new ObjectID($id);
                        }
                        $filter['_id'] = ['$in' => $ids];
                    }
                } catch (Exception $e) {

                }
            }

            //die(\Safe\json_encode($filter));

            $db = $this->container->getMongoService()->getConnectionDb();
            $conn = $this->container->getMongoService()->getConnection();

            $query = new Query($filter, $options);

            $tableName = $this->modelName::tableName();

            $db = 'sushi';

            $rs = $conn->executeQuery($db . '.' . $tableName, $query);


            foreach ($rs as $document) {

                $doc = (array)$document;

                $doc['ID'] = $doc['_id'];

                $model = new $this->modelName($document->_id, $doc);

                $rows[] = $model;
            }
        }

        return new $this->collectionClass($rows);
    }
}
