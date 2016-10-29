<?php

namespace app\models\notifications;

class PoolingImpl implements Polling
{

    public $filePath = '@app/runtime/';

    /**
     * Генерация события
     * @param string $event Имя события
     * @param array $data Параметры события
     */
    public function push($event, $data = null)
    {
        $eventFile = \Yii::getAlias($this->filePath) . $event;
        $last_change_in_data_file = file_exists($eventFile) ? filemtime($eventFile) : 0;
        if($last_change_in_data_file > (time() + 15)){
            file_put_contents($eventFile, $data, FILE_APPEND);
        }else{
            file_put_contents($eventFile, $data);
        }
    }

    /**
     * "Слежение"за возникновением событий. Время работы зависит от опции max_execution_time
     * @param array $events
     * @param int $lastQueryTime Время завершения последнего запроса на "прослушивание".
     * Используется для того ,чтобы при медленном соединении не терялись события, возникшие
     * между запросами. Если не указан, то события отслеживаются с момента начала запроса
     * @return array Массив с результатами выполнения обработчиков для возникших событий
     */
    public function listen($events, $lastQueryTime = null)
    {
        while (true) {

            if (!$lastQueryTime) {
                return [
                    'time' => time() - 15
                ];
            }

            clearstatcache();

            foreach ($events as $item) {
                $eventFile = \Yii::getAlias($this->filePath) . $item;
                if(!file_exists($eventFile)){
                    continue;
                }
                $last_change_in_data_file = filemtime($eventFile);
                if ($last_change_in_data_file > $lastQueryTime) {
                    $data = file_get_contents($eventFile);
                    return [
                        'time' => time(),
                        'data' => $data
                    ];
                }
            }

            sleep(3);
        }


        /*set_time_limit(0);
        $data_source_file = 'data.txt';
        while (true) {

            // if ajax request has send a timestamp, then $last_ajax_call = timestamp, else $last_ajax_call = null
            $last_ajax_call = isset($_GET['timestamp']) ? (int)$_GET['timestamp'] : null;

            // PHP caches file data, like requesting the size of a file, by default. clearstatcache() clears that cache
            clearstatcache();
            // get timestamp of when file has been changed the last time
            $last_change_in_data_file = filemtime($data_source_file);

            // if no timestamp delivered via ajax or data.txt has been changed SINCE last ajax timestamp
            if ($last_ajax_call == null || $last_change_in_data_file > $last_ajax_call) {

                // get content of data.txt
                $data = file_get_contents($data_source_file);

                // put data.txt's content and timestamp of last data.txt change into array
                $result = array(
                    'data_from_file' => $data,
                    'timestamp' => $last_change_in_data_file
                );

                // encode to JSON, render the result (for AJAX)
                $json = json_encode($result);
                echo $json;

                // leave this loop step
                break;

            } else {
                // wait for 1 sec (not very sexy as this blocks the PHP/Apache process, but that's how it goes)
                sleep( 1 );
                continue;
            }
        }*/
    }

    /**
     * Регистрация события и назначение обработчика. Поддерживается по одному обработчику на событие.
     * @param string $event Имя события
     * @param callback $callback Функция-обработчик
     */
    public function registerEvent($event, $callback)
    {
        // TODO: Implement registerEvent() method.
    }
}