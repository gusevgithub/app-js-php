<h1>Таблица в формате Single Page Application (PHP + JS)</h1>

Таблица работает с помощью данных, которые запрашиваются, поступают и обрабатываются PHP-кодом. С помощью JavaScript данные проходят конечную обработку. Приложение работает без 
перезагрузки страниц.

Файловая структура частично имеет сходство с шаблоном MVC, 
но в данном шаблоне роль представления принадлежит JavaScript-у и функции fetch().

Фреймворки, библиотеки и плагины не применяются.

Структура приложения:

index.html <br>
api/Core/Router.php - маршрутизатор PHP. <br>
api/Core/Config.php - подключение к БД. <br>
api/Core/Model.php - подключаем БД с помощью PDO. <br>
api/PHP/Controllers - контроллеры PHP, принимают, обрабатывают и передают данные <br>
api/PHP/Models - модели, выполняют запросы к БД и передают данные непосредственно из БД. <br>
api/index.php - передача данных через этот файл  <br>
JS/app.js - основной файл JavaScript. <br>
JS/router.js - маршрутизатор JavaScript.  <br>
JS/pages/home.js - страница с контентом и конечной его обработкой. <br>

Установка: <br>
Загрузите папки и файлы на сервер. <br>
Создайте БД с именем table_data и загрузите туда файл: table_data.sql  <br>
В файле \Core\Config.php настройте подключение к БД (имя, пароль к БД).  <br>
