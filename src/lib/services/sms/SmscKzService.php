<?php

namespace lib\services\sms;

use SoapClient;

/* Функция отправки SMS https://smsc.kz/api/soap/balance/#menu
 *
 * обязательные параметры:
 *
 * $phones - список телефонов через запятую или точку с запятой
 * $message - отправляемое сообщение
 *
 * необязательные параметры:
 *
 * $translit - переводить или нет в транслит (1,2 или 0)
 * $time - необходимое время доставки в виде строки (DDMMYYhhmm, h1-h2, 0ts, +m)
 * $id - идентификатор сообщения. Представляет собой 32-битное число в диапазоне от 1 до 2147483647.
 * $format - формат сообщения (0 - обычное sms, 1 - flash-sms, 2 - wap-push, 3 - hlr, 4 - bin, 5 - bin-hex, 6 - ping-sms)
 * $sender - имя отправителя (Sender ID). Для отключения Sender ID по умолчанию необходимо в качестве имени
 * передать пустую строку или точку.
 * $query - строка дополнительных параметров, добавляемая в URL-запрос ("valid=01:00&maxsms=3&tz=2")
 *
 * возвращает массив (<id>, <количество sms>, <стоимость>, <баланс>) в случае успешной отправки
 * либо массив (<id>, -<код ошибки>) в случае ошибки
 */

/* Одно длинное сообщение может разбиваться на несколько SMS,
 * за каждое из которых снимается отдельная плата.
 * Одно SMS может содержать 70 символов с нелатинскими
 * буквами (например, на русском) или 160 символов только с латинскими буквами.
 * Для передачи более длинного сообщения оно разбивается на несколько SMS,
 * и в каждое SMS добавляется специальный заголовок (UDH),
 * позволяющий телефону объединить полученные части в одно длинное сообщение,
 * и максимальная длина каждой SMS в этом случае становится 67 символов для нелатинских и 153 для латинских букв.
 * Часть символов, например, "№`" не относятся к латинским, поэтому сообщения,
 * содержащие подобные символы, кодируются, как сообщения с нелатинскими символами.
 * Также существуют некоторые спецсимволы, для кодирования которых используются всегда 2 символа — это "{}[]^~\|€".
 * Проверить стоимость рассылки всегда можно в личном кабинете на странице отправки, нажав ссылку "Пересчитать".
 *
 * Код ошибки может принимать следующие значения:
 * 1 Ошибка в параметрах.
 * 2 Неверный логин или пароль.
 * 3 Недостаточно средств на счету Клиента.
 * 4 IP-адрес временно заблокирован из-за частых ошибок в запросах. Подробнее
 * 5 Неверный формат даты.
 * 6 Сообщение запрещено (по тексту или по имени отправителя).
 * 7 Неверный формат номера телефона.
 * 8 Сообщение на указанный номер не может быть доставлено.
 * 9 Отправка более одного одинакового запроса на передачу SMS-сообщения в течение минуты.
 */


class SmscKzService
{
    private $soapClient;

    private $client;

    private $phones;

    private $query;

    private $status;

    private $testMode;

    private $params = [
        'url'       => null,
        'login'     => null,
        'password'  => null,
        'sender'    => null,
        'test_mode' => false,
    ];

    public static $smsLengthNotice = 'Одно SMS может содержать 70 символов с нелатинскими' . PHP_EOL
    . ' буквами (например, на русском) или 160 символов только с латинскими буквами.' . PHP_EOL
    . ' Для передачи более длинного сообщения оно разбивается на несколько SMS.';

    private $errors = [
        '1' => 'Ошибка в параметрах.',
        '2' => 'Неверный логин или пароль.',
        '3' => 'Недостаточно средств на счету Клиента.',
        '4' => 'IP-адрес временно заблокирован из-за частых ошибок в запросах.',
        '5' => 'Неверный формат даты.',
        '6' => 'Сообщение запрещено (по тексту или по имени отправителя).',
        '7' => 'Неверный формат номера телефона.',
        '8' => 'Сообщение на указанный номер не может быть доставлено.',
        '9' => 'Отправка более одного одинакового запроса на передачу SMS-сообщения в течение минуты.',
    ];

    public function __construct($phones, array $params = [])
    {
        $this->params = $params;

        $this->soapClient = $this->params['url'];

        $this->testMode = $this->params['test_mode'];

        $this->client = new \StdClass();

        $this->client->login = $this->params['login'];

        $this->client->password = strtolower(md5($this->params['password']));

        $this->client->from = $this->params['sender'];

        $this->phones = join(',', (array) $phones);
    }

    public function from($name)
    {
        $this->client->from = $name;

        return $this;
    }

    public function setQuery($query)
    {
        $this->query = $query;

        return $this;
    }

    public function send($message, $translit = 0)
    {
        $client = new SoapClient($this->soapClient);

        $data['login'] = $this->client->login;
        $data['psw'] = $this->client->password;
        $data['phones'] = $this->phones;
        $data['mes'] = $message;
        $data['time'] = 0;
        $data['sender'] = $this->client->from;
        $data['id'] = '';
        $data['query'] = 'translit=' . $translit;

        $this->status = $this->testMode ? true : $client->send_sms2($data);

        return $this;
    }

    public function getStatus($name = null)
    {
        if ($this->status === true)

            return true;

        $this->status->sendresult = (array) $this->status->sendresult;

        return $this->status->sendresult[$name] ?? $this->status->sendresult;
    }

    public function getErrorCode()
    {
        return $this->status->sendresult->error ?? null;
    }

    public function getError()
    {
        if (empty($this->status->sendresult->error))

            return null;

        return $this->errors[$this->status->sendresult->error];
    }
}
