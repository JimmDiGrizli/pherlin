**Pherlin**
===========

Что такое Pherlin?
------------------
Pherlin это продукт с открытым исходным кодом, который является оберткой для быстрого старта разработки приложений на фрэймворке Phalcon. Части Pherlin слабо связаны между собой, что позволяет использовать их в качестве независимых компонентов для любого вашего приложения.


Что дает Pherlin?
-----------------
Pherlin дает возможность гибко управлять вашим приложением по средствам конфигурационных файлов: 
- возможность организовать разное поведение программы в зависимости от окружения;
- инициализация контейнера зависимостей через конфигурационные файлы с поддержкой всех нативных возможностей контейнера зависимостей из Phalcon;
- автоматическая интеграция одних конфигурационных файлов в другие;
- единообразное хранение и подключения модулей, в том числе и с подгрузкой их через composer.


Установка
---------
Для того, чтобы использовать Pherlin, у вас должен быть установлен php версии не ниже 5.4, расширение Phalcon версии не ниже 1.2.4, а также должен быть установлен composer, для установки зависимостей.

Сам Pherlin можно скачать двумя способами:
- используя composer, выполнив команду ```composer create-project getsky/pherlin myproject -s dev```;

- либо скачав архив проекта с сайта github и установить пакеты зависимостей, выполнив команду ```composer update ```.

Ознакомление с содержимым
-------------------------
Так же как и Phalcon, Pherlin не требует использовать определенную структуру каталогов, вы можете использовать любую удобную для вас структуру. Но для быстрого начала работы, вполне может сгодиться структура по умолчанию:
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

Давайте разберем подробнее всю структуру каталогов. 

Каталог ```app/``` служит нам для хранения всех конфигурационных файлов (каталог ```app/config/```), которые касаются всего приложения (всех модулей). Каталог ```app/environment ``` используется для хранения кэша различными сервисами нашего приложения. Также, по умолчанию, в системе зарезервированы два каталога для пользовательских сервисов и провайдеров, - ```app/Providers/``` и ```app/Services/``` соответственно. 

Каталог ```public/``` содержит все ресурсы приложения (картинки, css-стили, js-скрипты и др.), а также php файл, на который будут перенаправляться все запросы - ```public/index.php```. Кроме того в каталоге присутствует файл ```public/index_dev.php``` необходимый для запуска приложения в develop окружении с отключенным кэшированием настроек и дебаг панелью.

Каталог ```src/``` служит для хранения ваших модулей, которые вы будите создавать непосредственно в вашем приложении.

Каталог ```tests``` содержит каталоги для тестирования с помощью Codeception и phpUnit и файл с командами для установки расширения Phalcon, который может понадобится при использовании CI.

Смена окружения
---------------

Мы уже позаботились о вас и добавили два окружения и два файла ```index.php``` и ```index_dev.php``` с настройкой окружения. Но если вы хотите добавить еще одно окружение, то для этого вам потребуется создать новый файл ```index_{environment}.php```.

У компонетна phalcon-bootstrap окружением по умолчанию является ```dev```. Для смены окружения необходимо при создании объекта класса ```Bootstap``` вторым аргументом передавать название необходимого окружения:

```php
#/public/index.php
$app = new Bootstrap(new FactoryDefault(),'prod');

```

Создание и управление модулями
------------------------------
Для создания модуля можно использовать два разных подхода: создание модуля внутри Pherlin (для этого необходимо создать каталог с названием модуля ```ModuleNameModule``` в каталоге ```src/```), либо создать отдельный проект с модулем в своей IDE, и подключать этот модуль уже через composer. Второй метод более сложен, но при этом дает больше гибкости в дальнейшем использовании этого модуля, а также обновлении Pherlin. Ниже приведена пошаговая инструкция для создания своего модуля:

**Создание модуля внутри Pherlin**

1. Создаем каталог с названием модуля ```ModuleNameModule``` в каталоге ```src/```. А также создаем каталог для контроллеров, провайдеров и ресурсов модуля. В итоге должна получиться такая структура: 
    ```
    src/
    .   ModuleNameModule/
    .   .   Controllers/
    .   .   Providers/
    .   .   Resources/
    ```
    Сам каталог модуля содержит папку ```Controllers``` с контроллерами модуля, ```Providers``` с провайдерами сервисов     модуля, ```Resources``` с ресурсами приложения - конфигурационные файлы и шаблоны.

2. Теперь необходимо создать главный класс модуля (```Module.php```) в корне каталога ```ModuleNameModule``` со следующим содержанием:
    ```php
    <?php
    namespace GetSky\ModuleNameModule;
    
    use GetSky\Phalcon\Bootstrap\Module as ModuleBootstrap;
    
    class Module extends ModuleBootstrap
    {   
        const DIR = __DIR__;
	const NAME = "ModuleName"
        //const CONFIG = '/Resources/options.yml';
        //const SERVICES = '/Resources/services.yml';
    }
    ```
Сам класс очень прост: в нем необходимо переопределить всего две константы - ```DIR``` и ```NAME``` (название вашего модуля), которая указывает на путь к каталогу модуля. Также вы можете задать свои пути для хранения конфигурации модуля (константа ```CONFIG```) и списка подключаемых сервисов (константа ```SERVICES```).

3. Теперь в папке ```Resources``` создадим каталог ```config``` и разместим в ней конфигурационный файл модуля ```config.yml``` следующего содержания:
    ```yml
    view:
        path: "../app/environment/%environment%/cache/volt/"
        extension: ".volt"
    viewCache:
        path: "../app/environment/%environment%/cache/frontend/" 
    ```
    В этом файле мы прописали настройки для шаблонизатора и его механизма кэширования.
    
4. В той же папке ```Resources/config/``` создаем файл ```services.yml```, который будет содержать информацию о том, какие сервисы требуются для данного модуля:
   ```yml
    dispatcher:
        provider: "GetSky\Phalcon\Provider\DispatcherProvider"
        arg:
            - service: "config"
            - var: "GetSky\ModuleNameModule\Controllers"
    view:
        provider: "GetSky\Phalcon\Provider\ViewProvider"
        arg:
            - service: "config"
            - var: "ModuleName"
    viewCache:
        provider: "GetSky\Phalcon\Provider\ViewCacheProvider"
        arg:
            - service: "config"
            - var: "ModuleName"
    ```
    Более подробно о том, как создавать сервисы вы сможете прочитать в главе посвященной данному вопросу. Здесь, для         примера, мы использовали стандартные провайдеры Pherlin, которые можно найти в репозитории phalcon-skeleton-provider.

5. Теперь создаем каталог ```Resources/views/``` для хранения шаблонов используемых сервисом ```view```.

7. Создаем первый контроллер, к примеру ```IndexController.php``` в каталоге ```Controllers```:
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
    И создадим, для примера, один шаблон ```Resources/views/index/index.volt```:
    ```html
    <h1>Hi Phalcon!</h1>
    ```

8. После всех этих действий мы должны получить такую структуру файлов в нашем модуле:
    ```
    src/
    .   ModuleNameModule/
    .   .   Controllers/
    .   .   .   IndexController.php
    .   .   Resources/
    .   .   .   config/
    .   .   .   .   config.yml
    .   .   .   .   services.yml
    .   .   .   views/
    .   .   .   .    index/
    .   .   .   .    .    index.volt
    Module.php
    ```

9. Последний шаг заключается в том, что мы свяжем модуль с приложением. Для этого нам необходимо внести одну запись в файл конфигурации приложения. По умолчанию это файл ```app/config/config.yml```:
    ```yml
    modules:
        ModuleName:
            namespace: "GetSky\ModuleNameModule"
    ```
    И чтобы сделать наш модуль для роутинга модулем по умочанию:
    ```yml
    app:
        def_module: "ModuleName"
    ```
    Без этой правки наш модуль доступен будет только по ссылке ```module/index/action```, если же мы сделаем его по умолчанию то по "index/action".
    
Настройки приложения в Pherlin
-----------------------------

По-умолчанию, настройки приложения находятся в папке ```app/config``` и используется формат ```yml```, но вы можете использовать любой другой:

```
app/
.   config/
.   .   config.yml
.   .   config_dev.yml
.   .   config_prod.yml
.   .   services.yml
.   .   services_dev.yml
```

Файлы шаблона ```config_%environment%.yml```, где ```%environment%``` - текущее окружение, являются основными файлами настройки приложения. Если вам потребуются, какие-либо, исключительные настройки для определенного окружения, то вы можете их задать именно в этих файлах.

Файл ```config.yml``` это файл с общими настройками приложения. Его мы рассмотри более подробно, так как по-умолчанию именно он содержит все настройки, а файл ```config_%environment%.yml``` лишь импортируют этой файл:  

```yml
dependencies:
    %res%: ../app/config/services.yml

bootstrap:
    path: ../src/
    module: Module.php

namespaces:
    App\Providers: ../app/Providers/
    App\Services: ../app/Services/

modules:
    DemoModule:
        namespace: GetSky\DemoModule

app:
    def_module: DemoModule
    base_uri: /

mail:
    host: smtp.localhost
    port: 25
    user: post@localhost
    password: ""

session:
    cookie:
        name: sid
        lifetime: 31104000
        path: /
        domain: ""
        secure: 0
        httponly: 1

logger:
    adapter: \Phalcon\Logger\Adapter\File
    path: /app/environment/{environment}/logs/error.log
    format: "[%date%][%type%] %message%"

cache:
    cache:
        cacheDir: /app/environment/{environment}/cache/
        lifetime: 86400

errors:
    e404:
        controller: index
        action: error404
```

Файл ```service.yml``` это файл с общими сервисами приложения, которые инициализируются до загрузки модуля. Данный файл экспортируется в ```config.yml``` в переменную ```dependencies```:

```yml
dependencies:
    %res%: ../app/config/services.yml
```

Категория настроек ```bootstrap``` служит для настройки загрузчика приложения. В нем мы указываем, какое имя у сервиса настроек будет в DI, папку где лежат наши модули и название файла, которое будет у главного класса модуля.

Категория ```namespace``` служит для подключения пространств имен. В базовой конфигурации у нас зарегистрировано два дополнительных пространства: ```App\Providers``` для провайдеров сервисов и ```App\Services``` для глобальных сервисов приложения.

Категория ```modules``` является одной из ключевых настроек: в ней мы указываем какие модули необходимо подключить в нашем приложении, а также, если необходимо, можем переопределить настройки модуля и запретить подгружать сервисы модуля. В базовой конфигурации загружается модуль ```GetSky\DemoModule``` c именем ```DemoModule```, который подгружается в приложение с помощью ```composer```.

```yml
modules:
    DemoModule:
        namespace: GetSky\DemoModule
	    global_services: false 
	    config: false
```

Если ```global_service``` установить в значение ```true```, то сервисы, которые определяются в модуле, подгружаться не будут.

Настройки ```config``` содержат настройки модуля, которыми после инициализации манипулирует модуль. Вы можете их подменить. Для этого необходимо вместо ```false``` вписать новые настройки модуля. Для удобства, чтобы не переписовать все настройки модуля, вы можете воспользоваться импортом настроек модуля таким образом:

```yml
DemoModule:
    config
        %class%: GetSky\DemoModule::CONFIG
        view:
            debug = 0
```

*В принципе, вы можете не указывать что ```global_service``` и ```config``` равны ```false```, так как это значение по-умолчанию и в базовой конфигурации они упомянуты для примера.*


Все последующие группы настроек используются стандартными провайдерами сервисов.


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

Также, вы можете создвать объект настроек из строки:
```php
$string = "foo.bar = true";
$config = $configLoader->fromText($string, 'ini');
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

Pherlin использует конфигурационный файл для регистрации сервисов в контейнере зависимостей. Настройка сервисов должна находится в конфигурации приложения, в переменной ```dependencies```. Вот как это реализовано в Pherlin по-умолчанию:

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
Возможность инициализации сервисов через конфигурационные файлы в Pherlin обеспечивается по средствам компонента [AutoloadServices](https://github.com/JimmDiGrizli/phalcon-autoload-services).

Есть три способа регистрации сервисов:

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

3. Через провайдера сервисов. Который должен реализовывать интерфейс ```GetSky\Phalcon\AutoloadServices\Provider```. По замыслу, провайдеры являются посредниками для регистрации анонимных функций в контейнере зависимостей, но при этом имеют возможность реализовать любой другой способ, который поддерживает Phalcon. 
    
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

Запуск тестов Codeception
-------------------------
Для запуска тестов необходимо в консоли зайти в папку ```public``` и выполнить команду:

```bash
bash ../vendor/bin/codecept run --config ../tests/codeception
```
