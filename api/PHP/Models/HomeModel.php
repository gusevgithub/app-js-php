<?php
namespace PHP\Models;

use \Core\Model;
use PDO;

/**
 * Post model
 *
 * PHP version 7.1
 */
class HomeModel extends \Core\Model
{

    // Для вывода данных по условиям (параметрам)
    public function __construct() {}
    
    public static function getDataAll($parameter, $operator, $search, $kol, $art)
	{

    try {
        $db = static::getDB();
            if($operator === 'LIKE') {
                $search = "%$search%";
            }
            if($search !== '') {
                $where = "WHERE {$parameter} {$operator} ? ORDER BY `id` DESC LIMIT {$kol} OFFSET {$art}";
            } else {
                $where = "ORDER BY `id` DESC LIMIT {$kol} OFFSET {$art}";
            }
           
            $stmt = $db->prepare(
                "SELECT * FROM `products`
                {$where}");
            $stmt->execute([$search]);
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Вывод количества записей и добавление в массив данных
            $cnt = $db->prepare(
                "SELECT COUNT(*) FROM `products` 
                 WHERE {$parameter} {$operator} ?");
            $cnt->execute([$search]);
            $count = $cnt->fetchAll(PDO::FETCH_ASSOC);

             if($results[0] !== null) {
                array_unshift($results[0], $count[0], $kol, $art);
                $pages = ceil($results[0][0]['COUNT(*)'] / $results[0][1]);
                array_unshift($results[0], $pages);
            } 
            return $results;

        } catch(PDOExpetion $e) {
            echo $e->getMessage();
        }
	} 

}
