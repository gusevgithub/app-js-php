<?php
namespace Core;

  class Router {
    public static function router() {
      $query_str = $_SERVER['QUERY_STRING'];
      $query = preg_split('/\//', $query_str);
     // print_r($query);
     // echo $query[0];
      // Определяем директорию где хранятся файлы контроллеров.
      $dir_controllers = $_SERVER['DOCUMENT_ROOT'] . '/api/PHP/Controllers/';
      // В директории контроллеров файлы контроллеров формируем в массив.
      $controllers_files = scandir($dir_controllers);
      // Удаляем из массива ключи 0,1 с элементами . и .. (точка и две точки).
      $controllers_files = array_slice($controllers_files, 2);
      // Удаляем (.php) из массива названий файлов (Было - Home.php, Users.php , стало - Home, Users).
      $classes_names = preg_replace('/([A-Z])([a-z]+)(\.php)/', "$1$2", $controllers_files);
     
      // Переводим в нижний регистр все названия (Было - Home, Users, стало - home, users).
      $class_strtolower = array_map('mb_strtolower', $classes_names); 
      // Автоматическое подключение типа файлов.
      spl_autoload_extensions('.php');
      // Автоматическая регистрация файлов.
      spl_autoload_register();
      //print_r($class_strtolower); // Массив с названиями файлов в нижнем регистре без расширения. 
      // Создаем одномерный массив, где будут храниться значения для сравнения с адресной строкой.
      $arr = [];
      //print_r($class_strtolower);
      //print_r($query[0]);
      //$class_name = substr($query[0], 4);
      //print_r($class_strtolower);
      if(in_array($query[0], $class_strtolower) !== false) {
        

        $controller_name = '\PHP\Controllers\\'.ucfirst($query[0]);
        $controller_object = new $controller_name();
        $class_methods = get_class_methods(get_class($controller_object));
      
        if(in_array($query[1], $class_methods) && $query[1] !== '') {
          $method = $query[1];
          $result = $controller_object->$method();
        } else {
          $result = $controller_object->indexAction();
        } 
        echo json_encode($result);
        //return $result;
      } else {
        $controller_object = new \PHP\Controllers\Home();
        $result = $controller_object->indexAction();
        echo json_encode($result);
        //return $result;
      }

    }

  }
?>