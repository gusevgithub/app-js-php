<?php

namespace PHP\Controllers;

use \PHP\Models\HomeModel;
use \Core\View;

class Home {
  public function indexAction() {
   
   $result['head'] = self::getHead();
   if(!empty(self::getContent())) {
    $result['content'] = self::getContent();  
   } else {
     $result['message'] = 'По данному запросу нет данных!';
   }

   $result['pagination'] = self::getPagination($result['content'][0][0]);
   return $result;
  
  }

  private function getHead() {
    $head['h1'] = 'Таблица в формате Single Page Application';
    $head['title'] = 'JavaScript and forms, and PHP';
    return $head;
	}
  
private function getContent() {
    
  $input = file_get_contents('php://input');
  $input = json_decode($input);
  $a = filter_var($input->a, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
  $b = filter_var($input->b, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
  $c = filter_var($input->c, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
  $d = filter_var($input->d, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

  $content = self::getMethod($a, $b, $c, $d);
  return $content;

  }
  
  private function getPagination($pages) {
    $arr = [];
    $n = 0;
    while($n < $pages) {
      $arr[] += $n;
      $n++;
    }
    return $arr;
  }

  private function getFooter() {
    $footer['footer'] = '';	
    \Core\View::footerTpl(['footer' => $footer]);
    
  }
  
  // Вспомогательная функция для запросов в БД и вывода данных используя фильтры
  private function getSwitch($get_two, $name_parameter, $get_search, $page) {
    // Вывод количество записей за один запрос (на странице строки)
    $kol = 5;
    // Если номер страницы, то устанавливаем номер записи, с которой нужно выводить данные.
    if($page) {
      $art = ceil($kol * (int)$page);   
    } else {
      $art = 0;
    }
    // Если выбор select (выбор какого либо условия), то вставляем данные для вывода из БД.
    switch($get_two) {
      case 'equalTo':
        $result = \PHP\Models\HomeModel::getDataAll($name_parameter, '=', $get_search, $kol, $art);
        return $result;
      break;
      case 'contains':
        $result = \PHP\Models\HomeModel::getDataAll($name_parameter, 'LIKE', $get_search, $kol, $art); 
        return $result;
      break;
      case 'greaterThan':
        $result = \PHP\Models\HomeModel::getDataAll($name_parameter, '>', $get_search, $kol, $art); 
        return $result;
      break;
      case 'lessThan':
        $result = \PHP\Models\HomeModel::getDataAll($name_parameter, '<', $get_search, $kol, $art); 
        return $result;
      break;
      default:
        $result = \PHP\Models\HomeModel::getDataAll($name_parameter, 'LIKE', "", $kol, $art);       
        return $result;
      break;
    }
  }

  // Функция для запросов в БД и вывода данных используя фильтры
  private function getMethod($get_one, $get_two, $get_search, $page) {
    // Вывод количество записей за один запрос (на странице строки)
    $kol = 5;
    // Если номер страницы, то устанавливаем номер записи, с которой нужно выводить данные.
    if($page) {
      $art = ceil($kol * (int)$page);   
    } else {
      $art = 0;
    }
   // Если input type="text" не пустой и задан первый select (выбрать столбец),
   // то вызываем вспомогательную функцию getSwitch() для вывода результата.
   if($get_search !== '' && $get_search !== false) {
    switch($get_one) {
      case 'nameProduct':
        $result = self::getSwitch($get_two, 'name_product', $get_search, $page);
        return $result;
      break;
      case 'quantity':
        $result = self::getSwitch($get_two, 'quantity', $get_search, $page);
        return $result;
      break;
      case 'distance':
        $result = self::getSwitch($get_two, 'distance', $get_search, $page);
        return $result;
      break;
    }

   } else {
    // Запрос к БД и вывод данных по умолчанию.
    $result = \PHP\Models\HomeModel::getDataAll('name_product', 'LIKE', "", $kol, $art);       
    return $result;
   }
  }

}
?>