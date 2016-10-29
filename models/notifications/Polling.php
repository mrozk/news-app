<?php

namespace app\models\notifications;

interface Polling
{
    /**
     * Генерация события
     * @param string $event Имя события
     * @param array $data Параметры события
     */
    public function push($event, $data = null);

    /**
     * "Слежение"за возникновением событий. Время работы зависит от опции max_execution_time
     * @param array $events
     * @param int $lastQueryTime Время завершения последнего запроса на "прослушивание".
     * Используется для того ,чтобы при медленном соединении не терялись события, возникшие
     * между запросами. Если не указан, то события отслеживаются с момента начала запроса
     * @return array Массив с результатами выполнения обработчиков для возникших событий
     */
    public function listen($events, $lastQueryTime = null);

    /**
     * Регистрация события и назначение обработчика. Поддерживается по одному обработчику на событие.
     * @param string $event Имя события
     * @param callback $callback Функция-обработчик
     */
    public function registerEvent($event, $callback);
}
