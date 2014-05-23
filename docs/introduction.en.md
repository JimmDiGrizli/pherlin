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

Application settings
--------------------

By default, application settings are located in the ```app/config``` and uses the format ```ini```, but you can use any other:

```
app/
.   config/
.   .   config.ini
.   .   config_dev.ini
.   .   config_prod.ini
.   .   services.ini
```

Файлы шаблона ```config_%environment%.ini```, где ```%environment%``` - текщее окружение, являются основными файлами настройки приложения. Если вам потребуются, какие-либо, исключительные настройки для определенного окружения, то вы можете их задать именно в этих файлах.

Файл ```config.ini``` это файл с общими настройками приложения. Его мы рассмотри более подробно, так как по-умолчанию именно он содержит все настройки, а файл ```config_%environment%.ini``` лишь импортируют этой файл:  

```ini
dependencies = %res:../app/config/services.ini

[bootstrap]
config-name = 'config'
path = '../src/'
module = 'Module.php'

[namespaces]
App\Providers = "../app/Providers/"
App\Services = "../app/Services/"

[modules]
DemoModule.namespace = "GetSky\DemoModule"
DemoModule.services = false
DemoModule.config = false

[app]
def_module = 'DemoModule'
base_uri = '/'

[mail]
host = "smtp.localhost"
port = "25"
user = "post@localhost"
password = ""

[session]
cookie.name = sid
cookie.lifetime = 31104000
cookie.path = "/"
cookie.domain = ""
cookie.secure = 0
cookie.httponly = 1

[logger]
adapter = "\Phalcon\Logger\Adapter\File"
path = "/app/environment/{environment}/logs/error.log"
format = "[%date%][%type%] %message%"

[cache]
cache.cacheDir = "/app/environment/{environment}/cache/"
cache.lifetime = 86400

[errors]
e404.controller = "index"
e404.action = "error404"

```

Файл ```service.ini``` это файл с общими сервисами приложения, которые инициализируются до загрузки модуля. Данный файл экспортируется в ```config.ini``` в переменную ```dependencies```:

```ini
dependencies = %res:../app/config/services.ini

```

Категория настроек ```bootstrap``` служит для настройки загрузчика приложения. В нем мы указываем, какое имя у сервиса настроек будет в DI, папку где лежат наши модули и название файла, которое будет у главного класса модуля.

Категория ```namespace``` служит для подключения пространств имен. В базовой конфигурации у нас зарегистрировано два дополнительных пространства: ```App\Providers``` для провайдеров сервисов и ```App\Services``` для глобальных сервисов приложения.

Категория ```modules``` является одной из ключивых настроек: в ней мы указываем какие модули необходимо подключить в нашем приложении, а также, если необходимо, можем переопределить настройки модуля и запретить подгружать сервисы модуля. В базовой конфигурации загружаетя модуль ```GetSky\DemoModule``` c именем ```DemoModule```, который подгружается в приложение с помощью ```composer```.

```ini
DemoModule.services = false 
DemoModule.config = false
```

Если ```service``` установить в значение ```true```, то сервисы, которые определяются в модуле, подгружаться не будут.

Настройки ```config``` содержат настройки модуля, которыми после инициализации манипулирует модуль. Вы можете их подменить. Для этого необходимо вместо ```false``` вписать новые настройки модуля. Для удобства, чтобы не переписовать все настройки модуля, вы можете воспользоваться импортом настроек модуля таким образом:

```ini
DemoModule.config.%class% = GetSky\DemoModule::CONFIG
DemoModule.config.view.debug = 0
```

*В принципе, вы можете не указывать что ```service``` и ```config``` равны ```false```, так как это значение по-умолчанию и в базовой конфигурации они упомянуты для примера.*


Все последующие группы настроек используются стандартными провайдарами сервисов.


Config Loader
-------------
Pherlin use universal loader for configuration files, which allows:
- create a configuration of various formats (ini, yaml, JSON, or any other, for which you will add adapter) via a single method;
- merge configuration files;
- replace variable ```{environment}``` on application environment.

It should be noted that the use of yaml without installed extension PECL - [yaml](http://pecl.php.net/package/yaml), may significantly slow down the application due to the fact that in the absence of expansion package is used ```symfony/yaml` ``.

The loader is a individual package [ConfigLoader]((https://github.com/JimmDigrizli/phalcon-config-loader) (```getsky/phalcon-config-loader```), which is loaded with the help of composer. In order to start using it, you must create instance of the class ```ConfigLoader``` with transfer of current environment, or get it from the DI (service `` `config-loader ```).

```php
$configLoader = new ConfigLoader($this->environment);
// or
$configLoader = $di->get('config-loader');
$config = $configLoader->create('config.ini');
```

For more information, see the [README](https://github.com/JimmDiGrizli/phalcon-config-loader/blob/develop/README.md).


Configuring Services
--------------------
Pherlin uses a configuration file for registration services in the DI. Configuration services should be located in configuraion of application. That's how it is implemented by default:

```ini
# /app/config/config.ini
dependencies = %res:../app/config/services.ini
```

```ini
# /app/config/services.ini

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

The ability to initialize services through configuration files in Pherlin provided by means of component [AutoloadServices](https://github.com/JimmDiGrizli/phalcon-autoload-services).

There are three ways to register services:

1. By the class name. This method does not allow to pass arguments to a constructor or adjust parameters.
    
    ```ini
    ...
    [response]
    string = "Phalcon\Http\Response"
    ...  
    ```
    
2. Registering an instance directly. When using this method the container is placed dependency already finished object.
    ```php
    ...
    [request]
    object = "Phalcon\Http\Response"
    ...
    ```

3. Through the service provider. Which must implement the interface ```GetSky\Phalcon\AutoloadServices\Provider```. According to the plan, providers are intermediaries for registration of anonymous functions in the container dependency, but have the opportunity to realize any other way that supports Phalcon.
    ```ini
    ...
    [route]
    provider = "RouteProvider"
    ...    
    ```
    
For the second and third method possible to specify which arguments are passed to the constructor and invoke methods since its inception and prior to placement in the DI. Below is an example of how it can be implemented on the ini:

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

In the above example, we register the service ```SomeNamespace\FerstClass``` under the name ```fest-service ``` and pass 5 arguments: the service ```config```, variable ```24```, DI (object implements ```DiInterface```, which was passed to the constructor ```Botstrap ``` in file ```/public/index.php```), service ```shared-services``` caused by the method ``` getShared``` and an instance of ```SomeNamespace\SecondClass```, which was first created with transfer ```42``` and calling ```run```.

Running Codeception
-------------------
To run the tests necessary to go to ```public``` folder and run:

```bash
bash ../vendor/bin/codecept run --config ../tests/codeception
```
