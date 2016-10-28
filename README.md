Разворачивание 
============================
Редактируем доступы к БД
./yii migrate
./yii rbac/init
./yii rbac/admin
Создается админ с емейлом mrozk2012@gmail.com q1w2e3r4

Добавление обработчиков событий
==========================================
Заходим под админом notifications/index. Настройка всех диапазонов выполнения базируется 
на побитовом сравнении чисел степени 2. Доступные списки для добавлений находятся в классе app\models\Notifications

Events List - события модели
User Groups - групы пользователей
Level - тип обработчика

Добавление Events List app\models\Notifications 
~~~
public static $modelEventsList = [
          ActiveRecord::EVENT_AFTER_INSERT => 2,
          ActiveRecord::EVENT_AFTER_UPDATE => 4,
          ActiveRecord::EVENT_AFTER_SOME_ACTION => 8,
];
~~~


Добавление User Groups app\models\Notifications 
~~~
 public static $userGroups = [
        User::ROLE_READER => 2,
        User::ROLE_MODERATOR => 4,
        User::ROLE_ADMIN => 8,
        User::ROLE_USER_NEW_SOME_GROUP => 16,
    ];
];
~~~

Добавление Level app\models\Notifications 
~~~
 public static $notificationLevel = [
         'email' => 2,
         'browser' => 4,
         // 'telegram' => 8
     ];
];
~~~

В классе app\models\Notifications  есть фабричный метод notificationFactory, 
который создает объекты обработчиков которые реализуют интерфейс app\models\notifications\NotificationInterface;
Новыйм обработчикам нужно присваивать ключ, например
~~~
 public static $notificationLevel = [
         'email' => 2,
         'browser' => 4,
         // 'telegram' => 8
     ];
];
~~~
и по этому ключу создаем объкт в фабричном методе
Текущие обработчики хранятся в папке models/notifications
В обработчик передается набор данных для выполнения различных операций:
1. объект пользователя для котрого вызвался обработчик
2. объект шаблона
3. ссылка на объект сущности из которой вызвалось событие

Шаблоны
==========================================
В обработчик шаблонов передается 2 объекта:
1. Объект пользователя для которого вызвалось событие
2. Обїект модели для которой візвалось событие
с помощью спецсимолов можно вставлять переменные в шаблоны
Например:
Dear !#user.username#!, news was added !#entity.getUrl#!
В данном случае идет обращение к объекту пользователя user и к его полю username, и и объекту сущности 
для которой вызвали событие. Вначале обработчик ищет метод с именем которое хранится после entity.
Если метод не найдет ищет поле. Так образом идет автоматическое формирование контента сообщения.
