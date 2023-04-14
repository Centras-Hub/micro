# Laravel-Centras

<hr/>

Laravel-centras - наша собственная библиотека, включающая в себя некоторые общие возможности, присущие всем микросервисам.

На данный момент, основной функционал представляет из себя: 

1. Фасады, для отправки ответов при обращении к API, по единому шаблону.
   - Включает в себя заготовленные константы для статус кодов HTTP ответов.
   - Конструктор для создания ответов. 
2. Провайдер для логирования входящих запросов, и подзапросов, а также ответов, и подответов.
   - Использует сервис [Graylog](https://www.graylog.org/) для логгирования.
3. Базовые классы, для формирования общего шаблона исключений.
   - В список порожденных исключений входят:
      - AcceessDeniedException
      - ApiException
      - CentrasException
      - ConnectionException
      - DatabaseException
      - FileException
      - InvalidOperationException
      - NotFoundException
      - PaymentException
      - TimeoutException
      - ValidationException

## Установка

<hr/>

1. Добавьте в конец корневого json-объекта, в файле composer.json, следующую запись:

```json
"repositories": {
  "git.cic.kz/86": {
    "type": "composer",
    "url": "https://git.cic.kz/api/v4/group/86/-/packages/composer/packages.json"
  }
}
```

[Для примера, можно взять composer.json файл одного из микросервисов.](https://git.cic.kz/micro/partner/-/blob/master/composer.json)

2. Создайте в КОРНЕВОМ каталоге проекта, файл с названием ``auth.json``. 
3. Скопируйте в только что созданный файл, содержимое из файла ``auth.json``, находящегося в проекте [Config, в папке .dev](https://git.cic.kz/micro/config/-/blob/master/.dev/auth.json)
4. Запустите комманду.
```bash
 composer require centras/laravel-centras:1.0.1 
```
5. После окончания выполнения предыдущей команды, запустите следующую.
```bash
composer update centras/laravel-centras 
```


## Использование

<hr/>

<h3>1. Ответы и исключения</h3>
   
<p>
   Для использования ответов, и исключений, заверните массив, представляющий результат выполнения программы в специальную функцию-обёртку.


   Для этого импортируйте следующие классы в ваш контроллер. 
   
   ```php
    use App\Api\Api;
    use Illuminate\Http\JsonResponse;
   ```

   В конце вашего метода, добавьте следующую конструцию.

   ```php
   return Api::response(
            {массив данных}, {код статуса}, {сообщение}
   );
   ```

   Укажите в качестве возвращаемых типов данных вашего метода ``JsonResponse``.

   После этого всё должно заработать.

   [Для примера, можно взять один из проектов](https://git.cic.kz/micro/partner/-/blob/master/app/Domain/Service/Partners/Kupipolis/Products/KS.php).
</p>

<h3>2. Логгирование</h3>

<p>

   В-первую очередь, проверьте актуальность пакета ``laravel-centras``.

   Подключите провайдер ``CentrasServiceProvider``, в файле ``config/app.php``, чтобы получилось следующее:

   ```json
    ...App\Providers\EventServiceProvider::class,
    App\Providers\RouteServiceProvider::class,
    App\Providers\CentrasServiceProvider::class,
   ],

   ```
   
   [Пример](https://git.cic.kz/micro/esbd/-/blob/master/config/app.php#L178)

   После этого вам необходимо добавить в папку ``config``, файл с названием ``centras.php``, с содержимым:

   ```php
    <?php
    
    return [
        'graylog_url' => 'http://logger:8000/api/write/log'
    ];

    
```
[Пример](https://git.cic.kz/micro/partner/-/blob/master/config/centras.php#L4)

Теперь же, всё что вам остаётся, использовать объект из провайдера, с названием ``IOLog``.

Запрос делится на ``request``(далее - запросы) 

```php
app('IOLog')->request($data);
```

и ``response``(далее - ответы).
```php
app('IOLog')->response($result->original);
```

- Вам необходимо следить чтобы количество ответов не превышало количество запросов.

- В идеале их количество должно совпадать.

- Следите чтобы данные, что вы посылаете в логгер, представляли из себя словарь.

Иногда, чтобы удовлетворить все эти условия, запрос даже может выглядеть так:

```php
$this->ioLog->response([
    'result' => (array)$this->response
]);
```
    
 </p>


