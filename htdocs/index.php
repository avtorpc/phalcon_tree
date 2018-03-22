
<?php

use Phalcon\Loader;
use Phalcon\Mvc\View;
use Phalcon\Mvc\Application;
use Phalcon\Di\FactoryDefault;
use Phalcon\Mvc\Url as UrlProvider;
use Phalcon\Db\Adapter\Pdo\Mysql as DbAdapter;
use Phalcon\Config\Adapter\Ini as ConfigIni;


try {

	$config = new ConfigIni('../app/config/config.ini');

    // Регистрируем автозагрузчик
    $loader = new Loader();
    $loader->registerDirs(array(
        '../app/controllers/',
        '../app/models/'
    ))->register();

    // Создаем DI
    $di = new FactoryDefault();

    // Настраиваем компонент View
    $di->set('view', function () {
        $view = new View();
        $view->setViewsDir('../app/views/');
        return $view;
    });

    // Настраиваем базовый URI 
    $di->set('url', function () {
        $url = new UrlProvider();
        $url->setBaseUri('/');
        return $url;
    });

    // Обрабатываем запрос
    $application = new Application($di);

    echo $application->handle()->getContent();

} catch (\Exception $e) {
     echo "Exception: ", $e->getMessage();
}