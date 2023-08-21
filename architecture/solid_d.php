<?php

// Определение перечисления HttpMethod
enum HttpMethod: string
{
    case GET = 'GET';
    case POST = 'POST';
    case PUT = 'PUT';
    case DELETE = 'DELETE';
    // TODO
}

// Определение интерфейса HttpServiceInterface
interface HttpServiceInterface
{
    // Метод для выполнения HTTP-запросов
    public function request(string $url, HttpMethod $method, array $options = []);
}

// Класс XMLHttpService, который реализует интерфейс HttpServiceInterface
class XMLHttpService implements HttpServiceInterface
{
    public function request(string $url, HttpMethod $method, array $options = [])
    {
        // Инициализация сеанса cURL
        $curl = curl_init($url);
        
        switch ($method) {
            case HttpMethod::GET:
                // Установить параметры для GET-запроса
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
                break;
            case HttpMethod::POST:
                // Установить параметры для POST-запроса
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
                curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($options));
                break;
            case HttpMethod::PUT:
                // Установить параметры для PUT-запроса
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
                curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($options));
                break;
            case HttpMethod::DELETE:
                // Установить параметры для DELETE-запроса
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "DELETE");
                break;
        }
        
        // Выполнить HTTP-запрос
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        //curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // Используется только для тестовых целей
        
        // Выполнение запроса
        $response = curl_exec($curl);
        
        // Закрыть соединение и вернуть ответ
        curl_close($curl);
        
        // Возвращение ответа
        return $response;
    }
}

class Http
{
    public function __construct(
        private readonly HttpServiceInterface $service
    ){}

    public function get(string $url, array $options): void
    {
        // Вызываем метод request() объекта сервиса с передачей GET-запроса и опций
        $this->service->request($url, HttpMethod::GET, $options);
    }

    public function post(string $url): void
    {
        // Вызываем метод request() объекта сервиса с передачей POST-запроса
        $this->service->request($url, HttpMethod::POST);
    }
}
