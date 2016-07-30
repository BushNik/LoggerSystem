<?php

namespace ru\f_technology\logger;

/**
 * Класс реализует логирование сообщений в консоль
 *
 * @author Bushuev Nikita
 */
class File extends Logger {

    /**
     * Строка, содержащая имя файла с логами.
     * @var string
     */
    private $filePath = 'test.log';

    /**
     * Указатель на файл с логами, содержит resource, либо false в случае ошибки
     * @var resource 
     */
    private $fileHandle = false;

    /**
     * Запись в конец файла, если true и запись в новый файл, если false.
     * @var boolean
     */
    private $append = true;

    /**
     * Если true блокируем файл для записи LOCK_EX
     * @var boolean
     */
    private $locking = false;

    /**
     * Целое число в восьмеричной системе счисления, определяющее права доступа
     * к файлу с логами
     * @var integer
     */
    var $mode = 0644;

    /**
     * Целое число в восьмеричной системе счисления, определяющее права доступа
     * к директориям, которые будут созданы, если они не существуют
     * @var integer
     */
    private $dirmode = 0755;

    /**
     * Формат сообщения лога
     * @var string
     */
    private $lineFormat = '%1$s [%2$s] %3$s';

    /**
     * Формат даты и времени лога: YYYY-MM-DD HH:MM:SS
     * @var string
     */
    private $timeFormat = '%Y-%m-%d %H:%M:%S';

    /**
     * Символ перевода на новую строку
     * @var string
     */
    private $eol = "\n";

    /**
     * Создаёт новый логгер с возможностью записывать логи в файл.
     *
     * @param string $name         Имя файла.
     * @param string $label        Уникальный идентификатор логгера.
     * @param array $params        Массив настроек.
     */
    function __construct($name, $label = '', $params = []) {
        $this->label = $label;
        $this->filePath = $name;
        if (isset($params['append'])) {
            $this->append = $params['append'];
        }

        if (isset($params['locking'])) {
            $this->locking = $params['locking'];
        }

        if (!empty($params['mode'])) {
            if (is_string($params['mode'])) {
                $this->mode = octdec($params['mode']);
            } else {
                $this->mode = $params['mode'];
            }
        }

        if (!empty($params['dirmode'])) {
            if (is_string($params['dirmode'])) {
                $this->dirmode = octdec($params['dirmode']);
            } else {
                $this->dirmode = $params['dirmode'];
            }
        }

        if (!empty($params['lineFormat'])) {
            $this->lineFormat = str_replace(array_keys($this->formatMap), array_values($this->formatMap), $params['lineFormat']);
        }

        if (!empty($params['timeFormat'])) {
            $this->timeFormat = $params['timeFormat'];
        }

        if (!empty($conf['eol'])) {
            $this->eol = $conf['eol'];
        } else {
            $this->eol = (strstr(PHP_OS, 'WIN')) ? "\r\n" : "\n";
        }
    }

    /**
     * Создаёт структуру директорий. Если родительские директории 
     * не существуют, они также будут созданы.
     *
     * @param   string  $path       Полный путь директорий.
     * @param   integer $mode       Права доступа на директории
     *                              которые будут установлены при их создании.
     *
     * @return  True если все директории были успешно созданы или
     *          данный путь уже существует.
     *
     */
    function makePath($path, $mode = 0700) {
        // Выделяем последнюю директорию и остальную часть
        $head = dirname($path);
        $tail = basename($path); // последняя директория
        // Рекурсивно вызываем создание директории
        if (!empty($head) && !empty($tail) && !is_dir($head)) {
            $this->makePath($head, $mode);
        }

        // Создать директорию с правами доступа
        return @mkdir($head, $mode);
    }

    /**
     * Закрывает файл с логами.
     * 
     * @return boolean   True в случае успеха и false в обратном случае.
     */
    function close() {
        // Если файл с логами открыт, то закрываем его
        if ($this->opened && fclose($this->fileHandle)) {
            $this->opened = false;
        }

        return ($this->opened === false);
    }

    /**
     * Открывает файл по заданному пути для записи.
     * Если директории по заданному пути не существуют, то они создаются
     *
     * @return boolean   True в случае успеха и false в обратном случае.
     */
    function open() {
        if (!$this->opened) {
            // Если директория файла с логами не существует, то создаем её
            if (!is_dir(dirname($this->filePath))) {
                $this->makePath($this->filePath, $this->dirmode);
            }

            // Записываем в переменную, нужно ли создавать файл для логов
            $newFile = !file_exists($this->filePath);

            // Записываем указатель на файл в переменную
            $this->fileHandle = fopen($this->filePath, ($this->append) ? 'a' : 'w');

            // Файл открыт, если указатель на файл валидный, т.е. не равен false
            $this->opened = ($this->fileHandle !== false);

            // Если создан новый файл, устанавливаем права доступа на него
            if ($newFile && $this->opened) {
                chmod($this->filePath, $this->mode);
            }
        }

        return $this->opened;
    }

    /**
     * Сбрасывает все данные в поток
     *
     */
    function flush() {
        if (is_resource($this->fileHandle)) {
            return fflush($this->fileHandle);
        }

        return false;
    }

    /**
     * Вставляет сообщение лога $message в файл.
     *
     * @param mixed $message    Строка, массив, объект или исключение
     * @return boolean          True в случае успеха и false в обратном случае.
     */
    function log($message) {
        $message = $this->getMessage($message);
        $line = $this->formatMessage($this->lineFormat, strftime($this->timeFormat), $message) . $this->eol;

        // Если файл для логирования не открыт, открываем его
        if (!$this->opened && !$this->open()) {
            return false;
        }

        // Ставим блокировку файла, если параметр истина
        if ($this->locking) {
            flock($this->fileHandle, LOCK_EX);
        }

        // пишем сообщение лога в файл
        $success = (fwrite($this->fileHandle, $line) !== false);

        // разблокировка файла
        if ($this->locking) {
            flock($this->fileHandle, LOCK_UN);
        }
        return $success;
    }

}
