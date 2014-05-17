**Pherlin**
===========

What is Pherlin?
----------------
Pherlin is an open source, which is a wrapper for a quick start developing applications on proper framework Phalcon. 
Pherlin is loosely coupled, allowing you to use its objects in your application based on Phalcon.

What gives Pherlin?
-------------------
Pherlin enables flexibly manage your application by means of configuration files: 
- possibility to organize different behavior of the program depending on the environment;
- organize initialization of Dependency Injection through configuration files with support for all native capabilities;
- organize uniform storage and connecting modules, including with their installation through the composer.

Installation
------------
To use Pherlin, you must have installed php 5.4 or later version, Phalcon 1.2.4 or later version, and must be installed composer to install dependencies.

Pherlin can be downloaded in two ways:
- use composer by running the command ```composer create-project getsky/pherlin myproject -s dev```;

- or by downloading the archive project from github and install the dependency packages by runniing the command ```composer update ```.

Acquaintance with the content
-----------------------------
As well as Phalcon, Pherlin does not require a certain directory structure, you can use any convenient structure for you. But for a quick start, you can use default structure:
```
app/
.   config/
.   .   config.ini
.   .   config_dev.ini
.   .   config_prod.ini
.   .   services.ini
.   environment/
.   .   dev/
.   .   .   cache/
.   .   .   .   frontend/
.   .   .   .   volt/
.   .   prod/
.   .   .   cache/
.   .   .   .   frontend/
.   .   .   .   volt/
.   Providers/
.   Services/
public/
.   img/
.   css/
.   js/
.   .htaccess
.   index.php  
src/
tests/
.   codecepton/
.   ...
.   phpunit/
.   ...
.   install-php-extension.sh
.gitignore
.htaccess
LICENSE
README.md
composer.json
```

Catalog ```app/``` serves us to store all configuration files (directory ```app/config/```), which relate to the entire application (all modules). Catalog ```app/environment ``` is used for various services cache our application. Also, by default, are already reserved two directories for user services and providers: ```app/Providers/``` and ```app/Services /``` respectively.

Catalog ```public/``` contains all application resources (images, css-styles, js-scripts, etc.), as well as php file that will redirect all requests - ```public/index.php```. Also present in the catalog file ```public/codeception.php``` required to run BDD-tests.

Directory `` `src ``` is used to store the modules that you create in your application.

Directory ```tests/``` contains catalogs for testing using Codeception and phpUnit and file with the commands to install the extension Phalcon, which may be necessary when using the CI.

Changing the environment
------------------------
Environment default is ```dev ```. To change the environment must be passed as the second argument the name of the desired environment:

```php
#/public/index.php
$app = new Bootstrap(new FactoryDefault(),'prod');

```

Creating the Module
-------------------
To create a module, you can use two different approaches: a module inside Pherlin (for this you need to create a directory with the name of the module ```ModuleNameModule``` in the directory ```src/```), or to create a separate project with the module in its IDE and connect the module after composer. The second method is more complicated, but it gives more flexibility to continue using this module. Below is a step by step guide to create your own module:

**Module inside Pherlin**

1. Create a directory with the name of the module ```ModuleNameModule``` listing ```src/```. And also create a directory for controllers, providers and resources module. As a result, should get such a structure:
    ```
    src/
    .   ModuleNameModule/
    .   .   Controllers/
    .   .   Providers/
    .   .   Resources/
    ```
    Directory ```src/``` contains files with the logic of your application, namely modules. Out of the box already has a     preset module - ```FrondendModule```. You can use it or delete and create new module. How to create new modules and     delete preset will be written later. Module directory contains a folder ```Controllers``` with controllers of module,     ```Providers``` with providers of services module, ```Resources``` with application resources - configuration files     and templates.


2. Now you need to create a master class of module (```Module.php ```) in the root directory ```ModuleNameModule``` with the following contents:
    ```php
    <?php
    namespace GetSky\ModuleNameModule;
    
    use GetSky\Phalcon\Bootstrap\Module as ModuleBootstrap;
        
    class Module extends ModuleBootstrap
    {   
        const DIR = __DIR__;
	const NAME = "ModuleName"
        //const CONFIG = '/Resources/options.ini';
        //const SERVICES = '/Resources/services.ini';
    }
    ```
    The class itself is very simple: it is necessary to override only two constants - ```DIR ``` and ```NAME``` (Name of you module), which indicates the path     to the directory module. Also you can set your path for storing module configuration (constant ```CONFIG ```) and the     list of connected services (constant ```SERVICES ```).

3. Now create a module configuration file config.ini in the folder ```Resources/config/```:
    ```ini
    [view]
    path = "../app/environment/%environment%/cache/volt/"
    extension = ".volt"
    [viewCache]
    path = "../app/environment/%environment%/cache/frontend/" 
    ```
    In this file we are prescribed settings for view and cache.
    
4. In the same folder ```Resources/config/``` we create file ```services.ini```, which will contain information about which services are required for this module:
    ```ini
    [dispatcher]
    provider = "GetSky\Phalcon\Provider\DispatcherProvider"
    arg.0.service = "config"
    arg.1.var = "GetSky\ModuleName\Controllers"
    [view]
    provider = "GetSky\Phalcon\Provider\ViewProvider"
    arg.0.service = "config"
    arg.1.var = "ModuleName"
    [viewCache]
    provider = "GetSky\Phalcon\Provider\ViewCacheProvider"
    arg.0.service = "config"
    arg.1.var = "ModuleName"
    ```
    For more information about how to create services you can read in the chapter devoted to this issue. In this example,     we use the standard Pherlin providers are located in the repository phalcon-skeleton-provider.

5. Now we create directory ```Resources/views/``` for storing templates used by the service ```view ```.

6. Create the first controller ```IndexController.php``` in directory ```Controllers ```:
    ```php
    <?php
    namespace GetSky\ModuleNameModule\Controllers;
    
    use Phalcon\Mvc\Controller;
    
    class IndexController extends Controller
    {
        public function indexAction()
        {
        }

        public function aboutAction()
        {
        }

        public function error404Action()
        {
        }
    }
    ```
    And create one template ```Resources/views/index/index.volt ```:
    ```html
    <h1>Hi Phalcon!</h1>
    ```

8. After all these steps, we need to get a file structure for our module:
    ```
    src/
    .   ModuleNameModule/
    .   .   Controllers/
    .   .   .   IndexController.php
    .   .   Resources/
    .   .   .   config/
    .   .   .   .   config.ini
    .   .   .   .   services.ini
    .   .   .   views/
    .   .   .   .    index/
    .   .   .   .    .    index.volt
    Module.php
    ```

9. The final step is that we associate module with the application. To do this, we need to enter one entry in the application configuration file. By default, this file is ```app/config/config.ini```:
    ```ini
    [modules]
    ModuleName.namespace = "GetSky\ModuleNameModule"
    ```
    And to make our module for routing module by default:
    ```ini
    [app]
    def_module = "ModuleName"
    ```

Загрузчик конфигурационных файлов
---------------------------------
В Pherlin используется универсальный загрузчик конфигурационных файлов, который позволяет:
- загружать конфигурации в ini, json и yaml форматах;
- склеивать их между собой и подгружать их друг в друга;
- подменять переменную ```{environment}``` на текущее окружение приложения.

Стоит заметить, что использование yaml без установленного расширения PECL - yaml (http://pecl.php.net/package/yaml) может заметно замедлить приложение из-за того, что в случаи отсутствия расширения используется пакет ```symfony/yaml```.

Загрузчик представляет собой отдельный сервис (```getsky/phalcon-config-loader```), который подгружается с помощью composer. Для того, чтобы начать им пользоваться необходимо создать экземпляр класса ```ConfigLoader``` с передачей в его конструктор переменной с текущим окружением.

```php
$configLoader = new ConfigLoader($this->environment);
$config = $configLoader->create('config.ini');
```

Если вы не хотите, чтобы происходил импорт ресурсов (подгрузка других конфигурационных файлов в эту конфигурацию), то вторым параметром необходимо передать булево значение ```false```:

```php
$config = $configLoader->create('config.ini', false);
```

В Pherlin загрузчик конфигурационных файлов помещается в DI под названием ```config-loader``` после выполнения метода ```run()``` класса ```Bootstrap``` в файле ```/public/index.php```, что позволяет с лёгкостью воспользоваться им в любой части вашего приложения где есть доступ к DI.

**Склейка конфигурационных файлов выглядит следующим образом:**

```ini
#config.ini
[test]
test = true
%res% = import.ini

```
```ini
#import.ini
import = "test"
```


При загрузке конфигурационного файла ```config.ini```:
```php
[                               
    'test' => [                 
        'test' => true,                             
        'import' => true,       
        'env' => 'dev'          
    ]                           
]                               
```

**Подгрузка конфигурационных файлов выглядит следующим образом:**

```ini
#config.ini
[test]
test = true
exp = %res:import.ini

```
```ini
#import.ini
import = "test"
```

При загрузке конфигурационного файла ```config.ini```:
```php
[
    'test' => [
        'test' => true,
        'exp' => [
            'import' => true,
            'env' => 'dev'
        ]
    ]
]                                                        
```

**Подменять переменную {environment} на текущее окружение приложения:**

```ini
#config.ini
[test]
test = true
exp = "app/config/%environment%/cache/"
```

При загрузке конфигурационного файла ```config.ini```, если активно окружение dev:
```php
[                               
    'test' => [                 
        'test' => true,     
        'exp' = "app/config/dev/cache/"                                 
    ]                           
]
```

Вы также можете добавлять свои адаптеры конфигурационных файлов. Для этого необходимо вызвать метод ```add()``` с передачей расширения, которое будет обрабатывать данный адаптер и класс адаптера, который должен наследовать класс ```Phalcon\Config```.

```php
$config = $configLoader->add('xml', 'MyNamespace/XmlConfig');
```

Автозагрузчик сервисов
----------------------

Главной фишкой Pherlin является автозагрузка сервисов в DI. Для это используются конфигурационные файлы. Ниже приведен один из таких файлов в формате ini, но вы можете использовать также и yaml и json, либо любой другой, если для него вы задали адаптер в загрузчике конфигурационных файлов (сервис ```config-loader```):

```ini
[router]
provider = "GetSky\Phalcon\Provider\RouterProvider"
arg.0.service = "config"

[callsample]
object = "CallService"
call.0.method = "run"
call.0.arg.0.var = "24"

[session]
string = "GetSky\Phalcon\Provider\SessionProvider"
shared = true
```
Возможность инициализации сервисов через конфигурационные файлы в Pherlin обеспечивается по средствам компонента ```phalcon-autoload-services```. 

Зарегистрировать сервисы можно тремя способами:

1. По названию класса. Такой способ не позволяет передавать аргументы для конструктора класса или настраивать параметры.
    
    ```ini
    ...
    [response]
    string = "Phalcon\Http\Response"
    ...  
    ```
    
2. Регистрация экземпляра напрямую. При использовании этого способа в контейнер зависимостей помещается уже готовый объект.

    ```php
    ...
    [request]
    object = "Phalcon\Http\Response"
    ...
    ```

3. Через провайдера сервисов. Который должен реализовывать интерфейс ```GetSky\Phalcon\AutoloadServices```. По замыслу, провайдеры являются посредниками для регистрации анонимных функций в контейнере зависимостей, но при этом имеют возможность реализовать любой другой способ, который поддерживает Phalcon. 
    
    ```ini
    ...
    [route]
    provider = "RouteProvider"
    ...    
    ```
    
Для второго и третьего способа возможно указать какие аргументы будут переданы в конструктор и вызывать методы после его создания и до помещения в DI. Ниже приведен пример, как это можно реализовать на ini:

```ini
[ferst-service]
provider = "SomeNamespace\FerstClass"
arg.0.service = "config"
arg.1.var = "24"
arg.2.di = 1
arg.3.s-service = "shared-service"
arg.4.object.object = "SoeNamespace\SecondClass"
arg.4.object.arg.0.var = "42"
arg.4.object.call.0.method = "run"
```

В приведенном выше примере, мы регистрируем сервис ```SomeNamespace\FerstClass``` под именем ```fest-service``` и передаем 5 аргументов: сервис ```config```, переменную ```24```, контейнер зависимостей (объект реализующий интерфейс ```DiInterface```, который был передан в конструктор ```Botstrap``` в файле ```/public/index.php```), сервис ```shared-services``` вызванный через метод ```getShared``` и экземпляр класса ```SomeNamespace\SecondClass```, который сначала создается с передачей в конструктор аргумента ```42```, и вызовом метода ```run```, а уже затем передается в конструктор нашего сервиса пятым параметром.
