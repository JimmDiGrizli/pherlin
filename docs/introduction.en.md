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
.   .   config.yml
.   .   config_dev.yml
.   .   config_prod.yml
.   .   services.yml
.   .   services_dev.yml
.   environment/
.   .   dev/
.   .   .   cache/
.   .   .   .   volt/
.   .   prod/
.   .   .   cache/
.   .   .   .   volt/
.   Providers/
.   Services/
public/
.   .htaccess
.   index.php  
.   index_dev.php
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

Catalog ```public/``` contains all application resources (images, css-styles, js-scripts, etc.), as well as php file that will redirect all requests - ```public/index.php```. Also present in the catalog file ```public/index_dev.php``` required to run to the application in the develop environment with disabled caching and debug panel.

Directory ```src ``` is used to store the modules that you create in your application.

Directory ```tests/``` contains catalogs for testing using Codeception and phpUnit and file with the commands to install the extension Phalcon, which may be necessary when using the CI.

Changing the environment
------------------------
We take care of you and added two environments and two files: ```index.php``` and ```index_dev.php``` with setting up the environment. But if you want to add another environment, then you need to create a new file ```index_{environment}.php```.

Environment is the default ```dev``` in phalcon-bootstrap. To change the environment must be passed as the second argument the name of the desired environment:

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

Files ```config_%environment%.Ini``` (where ```%environment%``` - the current environment) are the main application configuration file. If you require, any exceptional settings for a particular environment, then you can ask them in these files.

File ```config.ini``` a file with the general application settings. It will be described in more detail, as it is by default it contains all the settings, and file ```config_%environment%.ini``` just import this file:

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
DemoModule.global_services = false
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

File ```service.ini``` is a file with the general application resources that are initialized before the module. This file is exported to ```config.ini``` in a variable ```dependencies```:

```ini
dependencies = %res:../app/config/services.ini

```

Category ```bootstrap``` is used to configure loader of the application. In it, we have to specify the name service settings folder where to store our modules and file name, which will be the main module class.

Category ```namespace``` is used to connect namespaces. In it's basic configuration, we recorded two additional spaces: ```App\Providers``` for providers of services and ```App\Services``` for global application services.

Category ```modules``` is one of the key settings: here we specify which modules you need to connect to our application, and, if necessary, can override the module and deny services to load module. In the basic configuration should be loaded module ```GetSky\DemoModule``` with name ```DemoModule```, which is loaded into the application using ```composer```.

```ini
DemoModule.global_services = false 
DemoModule.config = false
```

If ```service``` be set to ```true```, then the services that are defined in the module will not be loaded.

Settings ```config``` contain module configuration, which manipulates after initialization module. You can replace them. To do this, instead of ```false ``` enter new module settings. For convenience, not to perepisovat all settings module, you can use the import module settings as follows:

```ini
DemoModule.config.%class% = GetSky\DemoModule::CONFIG
DemoModule.config.view.debug = 0
```

*In general, you can not specify that ```global_service``` and ```config``` equal ```false```, as it is the default value in the base configuration and they are mentioned for example.*

All of the following groups of settings used standard service providers.


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
