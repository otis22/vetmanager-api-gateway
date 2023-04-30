
# Vetmanager Api Gateway

Помощник для работы с АПИ Ветменеджера.

[Используется в основе библиотека](https://github.com/otis22/vetmanager-rest-api) -
тут же документация к использованию классов для запросов: Builder и PagedQuery

[Официальный сайт Vetmanager.ru](https://vetmanager.ru/)

[Vetmanager REST API Docs](https://help.vetmanager.cloud/article/3029)

[Vetmanager REST API Postman Collection](https://www.postman.com/vetmanager/workspace/vetmanager-api/collection/23836400-17133b76-0f52-4bb4-8b38-28a64781074e)

## Для чего?
С помощью этой библиотеки удобно получать данные с АПИ Ветменеджера. Данные приходят в виде DTO (Data Transfer Object).
Каждый DTO связан с одним или с несколькими другими DTO.
Пример кода:
```php
use VetmanagerApiGateway\ApiGateway;
use VetmanagerApiGateway\DO\DTO\DAO\Client;

$apiGateway = ApiGateway::fromDomainAndApiKey('subDomain', 'apiKey', true):
$client = Client::getById($apiGateway, 33);
$clientEmail = $client->email;
$clientCityId = $client->cityId; 
```

## Подробное использование:
### Начало работы/Конфигурация подключения
Простыми словами, объект ApiGateway - связующее звено с работой с АПИ Ветменеджера
#### С помощью домена и АПИ ключа
```php
$subDomain = 'kras-best';   // субдомен клиники в ветменеджер
$apiKey = 'xXdfxfsfsdffsf'; // АПИ ключ к домену в ветменеджер
$isProduction = true;       // рабочий или тестовый сервер будет использоваться

$apiGateway = ApiGateway::fromDomainAndApiKey($subDomain, $apiKey, $isProduction):
```
#### С помощью домена, имени АПИ-сервиса и АПИ ключа
Для специальных внутренних сервисов
```php
$apiGateway = ApiGateway::fromDomainAndServiceNameAndApiKey('subDomain', 'serviceName', 'apiKey', true):
```
### Первоначальное получение объектов
Первоначально по АПИ можно получить лишь обращаясь к **DAO** (Data Access Object), а не к **DTO** (Data Transfer Object).

То есть в этой библиотеке **DAO** - подвид **DTO**, но с возможностью получить эти объекты прямым АПИ-запросом (например,
получение по ID, или через более сложный запрос - например, через фильтры).

В качестве исключения есть DAO, которые могут быть получены лишь с помощью конкретного АПИ-запроса. Например,
AdmissionFromGetById можно получить лишь по ID. А у MedicalCardsByClient есть лишь один (уникальный) метод получения по
АПИ. Недоступные методы у DAO получения не высветятся (с помощью твоего IDE), и значит не поддерживаются. 
#### Получение объекта по ID
```php
$client = Client::getById($apiGateway, 33);
```
#### Получение объекта по Query запросу
По скольку все Query запросы всегда возвращают массив объектов: даже когда 1 объект получаем - обращаемся к нему через
массив.
```php
$comboManualItemTitle = $comboManualItems[0]->title;
```
Ниже перечислены 3 варианта одного и того же запроса
1) Query Builder

    [Ссылка на используемую библиотеку с большим количество примеров использования Builder](https://github.com/otis22/vetmanager-rest-api)
    ```php
    use Otis22\VetmanagerRestApi\Query\Builder;
    use VetmanagerApiGateway\DO\DTO\DAO\ComboManualItem;
    
    $comboManualItems = ComboManualItem::getByQueryBuilder(
            $apiGateway,
            (new Builder())
                ->where('value', '7')
                ->where('combo_manual_id', '1'),
            1
    );
    ```
2) PagedQuery

   [Ссылка на используемую библиотеку с большим количество примеров использования PagedQuery](https://github.com/otis22/vetmanager-rest-api)

    С помощью этого объекта удобнее работать с пагинацией.
    ```php
    use Otis22\VetmanagerRestApi\Query\Builder;
    use VetmanagerApiGateway\DO\DTO\DAO\ComboManualItem;
    
    $comboManualItems = ComboManualItem::getByPagedQuery(
            $apiGateway,
            (new Builder())
                ->where('value', '7')
                ->where('combo_manual_id', '1')
                ->top(1)
    );
    ```
3) Get Parameters As String
   Сюда можно передать все те же Get-параметры, используемые в коллекции Postman. Более подробно о фильтрах, сортировке
   и т.д. здесь - [Vetmanager REST API Docs](https://help.vetmanager.cloud/article/3029)
    ```php
    use VetmanagerApiGateway\DO\DTO\DAO\ComboManualItem;
    
    $comboManualItems = ComboManualItem::getByParametersAsString(
            $apiGateway,
            "filter=[{'property':'combo_manual_id', 'value':'1'},{'property':'value', 'value':'7'}]&limit=1"
    );
    ```
#### Альтернативные способы получения для конкретных DAO
```php
use VetmanagerApiGateway\DO\Enum\ComboManualName\Name;
use VetmanagerApiGateway\DO\DTO\DAO\ComboManualItem;
use VetmanagerApiGateway\DO\DTO\DAO\MedicalCardAsVaccination;
use VetmanagerApiGateway\DO\DTO\DAO\MedicalCardsByClient;
use VetmanagerApiGateway\DO\DTO\DAO\Property;

$clinicLanguage = Property::getByClinicIdAndPropertyName($apiGateway, $clinicId = 13, 'lang')->title;
$clientMedicalCards = MedicalCardsByClient::getByClientId($apiGateway, $clientId = 77);
$petVaccinations = MedicalCardAsVaccination::getByPetId($apiGateway, $petId = 11);
ComboManualItem::getByAdmissionTypeId($apiGateway, $id = 11);
ComboManualItem::getByAdmissionResultId($apiGateway, $id = 11);
ComboManualItem::getByPetColorId($apiGateway, $id = 11);
ComboManualItem::getByVaccineTypeId($apiGateway, $id = 11);
ComboManualItem::getOneByValueAndComboManualName($apiGateway, $id, Name::AdmissionResult);
$admissionResult = ComboManualItem::getByName($apiGateway, 'admission_result');
$admissionResultId = ComboManualItem::getIdByNameAsString($apiGateway, 'admission_result');
$admissionResultId = ComboManualItem::getIdByNameAsEnum($apiGateway, Name::AdmissionResult);
```
### Работа с DAO/DTO
#### Пример представления данных
Каждое свойство подсвечивается. Видно, что может вернуться
```php
use VetmanagerApiGateway\DO\DTO\DAO\Client;

$clientEmail = $client->email; // Объявлено, что только строка, возможно пустая
$clientCityId = $client->cityId; // Объявлено, что только int или null может прийти
$clientDateRegister = $client?->dateRegister->format('Y-m-d H:i:s'); //dateRegister содержит DateTime (или null, если отсутствует дата)
$clientName = $client->fullName->fullStartingWithFirst; // fullName - вспомогательный объект для удобного форматирования имени
$clientStatus = $client->status; // Возвращается одно из возможных значений соответствующего Enum
```
#### Пример связанных запросов
Есть свойства объекта, которые вместо скалярных данных, возвращают другие объекты или массив объектов.
```php
$clientStreet = $client->street; // В переменной будет null или DTO (объект данных со свойствами)
$streetName = !is_null($clientStreet) ? $clientStreet->title : ''; // Название улицы или пустая строка

$clientPets = $client->petsAlive; // Массив с DTO питомцев 
$firstPet = (!empty($client->petsAlive)) ? $clientPets[0] : null; // Будет DTO Pet или null
$firstPetName = !is_null($firstPet) ? $firstPet->color->title : ''; // Получение названия цвета питомца
```
Рассмотрим пример сложного обращения:
1) Вначале получаем все медкарты клиента (1-ый дополнительный АПИ-запрос)
2) В первой медкарте получаем объект доктора (происходит 2-ой АПИ-запрос - получение объекта по ID)
3) В объекте доктора уже берем имя (нет запроса)
```php
$firstPetsDoctorsFirstName = $client?->medcards[0]->user->firstName;
```
#### Получение данных присланных в виде раздекодированного JSON
Метод работает у всех DTO/DAO.

Возвращение данных в том же виде, в котором и были получены. Без валидатиции и т.д.
```php
$clientDataAsArray = $client->getOriginalObjectData();
```
#### Создание объекта из данных в виде первоначального раздекодированного JSON
Методы работают у всех DTO/DAO.

1. Получение одного объекта из раздекодированного JSON (в виде ['id' => '12', "address" => ........]).
   ```php
   $client = Client::fromSingleObjectContents($apiGateway, $clientDataAsArray);
   ```
2. Получение массива объектов из раздекодированных JSON
   ```php
   $clients = Client::fromMultipleObjectsContents($apiGateway, $decodedObjects);
   ```
Подобным образом несложно реализовать кеширование DAO/DTO (получить раздекодированный объект и в новой сессии создавать
объект заново из данных в виде массива в кеше).
#### Дополнительные возможности
#### Вспомогательный объект FullName
Например, у DAO и DTO User, Client есть свойство FullName. У объекта FullName есть свойства возвращающие полное имя в
разном формате:
   ```php
   $client = VetmanagerApiGateway\DO\DTO\DAO\Client::getById($apiGateway, 9);
   echo $client->fullName->fullStartingWithFirst; // Возвращает: "Имя Отчество Фамилия"
   echo $client->fullName->fullStartingWithLast;  // Возвращает: "Фамилия Имя Отчество"
   echo $client->fullName->initials;              // Возвращает: "Фамилия И. О."
   echo $client->fullName->lastPlusInitials;      // Возвращает: "Ф. И. О."
   ```
Если, предположим, отчества не будет, то каждый из методов просто пропустит слово без создания лишних пробелов, точек и т.д.
#### Вспомогательный объект FullPhone
Например, у DAO и DTO Clinic есть свойство fullPhone. Он возвращает Объект FullPhone, у которого есть метод __toString - 
возвращает телефон с кодом страны и выбранной маской номера +7(918)-277-21-21
   ```php
   $clinic = VetmanagerApiGateway\DO\DTO\DAO\Clinic::getById($apiGateway, 33);
   echo $clinic->fullPhone; // Выведет телефона в виде +7(918)-277-21-21
   echo $clinic->fullPhone->mask; // +7
   ```

##### Узнать возможность онлайн записи клиники
Несколько вариантов. Возвращается bool:
```php
Property::isOnlineSigningUpAvailableForClinic($this->apiGateway, $clinicId = 13);
```
```php
Clinic::getById($apiGateway, 13)->isOnlineSigningUpAvailable;
```

