<?php

class Concept
{
    private $client;

    public function __construct()
    {
        $this->client = new \GuzzleHttp\Client(); // Создаем клиента Guzzle работать с HTTP
    }

    public function getUserData()
    {
        // Формируем параметры запроса с авторизацией и токеном
        $params = [
            'auth' => ['user', 'pass'],
            'token' => $this->getSecretKey()
        ];

        // Создаем HTTP-запрос к внешнему API
        $request = new \Request('GET', 'https://api.method', $params);
        $promise = $this->client->sendAsync($request)->then(function ($response) {
            $result = $response->getBody(); // Получаем тело ответа и обрабатываем его, если это необходимо
        });

        $promise->wait(); // Ожидаем завершения асинхронного запроса
    }

    public function getSecretKey(): string
    {
        /** @var SecretKeyProviderType $provider */
        $provider = Config::getInstance()->get('secret_key_provider');

        return (match ($provider) {
            SecretKeyProviderType::DB => new SecretKeyDBProvider(),
            SecretKeyProviderType::FILE => new SecretKeyFileProvider(),
            SecretKeyProviderType::REDIS => new SecretKeyRedisProvider(),
        })->getSecretKey();
    }
}

enum SecretKeyProviderType
{
    case FILE;
    case DB;
    case REDIS;
}

interface SecretKeyProvider
{
    public function getSecretKey(): string;
}

class SecretKeyFileProvider implements SecretKeyProvider
{
    public function getSecretKey(): string
    {
        $secretKey = ''; // Здесь должен быть код для чтения ключа из файла
                
        // Например, можно использовать функцию file_get_contents() для чтения ключа из файла
        // Необходимо заменить 'path/to/secret-key-file' на действительный путь к файлу
        /*$filePath = '/path/to/secret/key/file';
        if (file_exists($filePath)) {
            $secretKey = trim(file_get_contents($filePath));
        }*/
        
        // Возвращаем секретный ключ из файла
        return $secretKey;
    }
}

class SecretKeyDBProvider implements SecretKeyProvider
{
    public function getSecretKey(): string
    {
        $secretKey = ''; // Здесь должен быть код для чтения ключа из базы данных
        
        // Возвращаем секретный ключ из базы данных
        return $secretKey;
        
        // Прмер реализации для работы с БД
        /*
        // Настройка параметров подключения к вашей базе данных
        $dsn = 'mysql:host=localhost;dbname=mydb;charset=utf8mb4';
        $username = 'username';
        $password = 'password';
                
        try {
            // Создание нового подключения к базе данных с использованием PDO
            $pdo = new PDO($dsn, $username, $password);
            // Выполняем запрос на выборку секретного ключа (замени '`api_keys`' и '`key`' на действительные названия таблицы и поля в базе данных)
            $query = $pdo->prepare("SELECT `key` FROM `api_keys` WHERE `id` = :id");
            $query->execute([':id' => 1]); // Необходимо заменить '1' на идентификатор записи с секретным ключом
            $secretKey = $query->fetchColumn();
               
            if (!$secretKey) {
                throw new Exception('Секретный ключ не найден в базе данных!');
            }
                
            return $secretKey;
        } catch (PDOException $e) {
            // Обработка ошибок подключения или запросов к базе данных
            throw new Exception('Ошибка при получении секретного ключа из базы данных: ' . $e->getMessage());
        }  
        */
    }
}

class SecretKeyRedisProvider implements SecretKeyProvider
{
    public function getSecretKey(): string
    {
        // Здесь необходимо заменить значение 127.0.0.1 и 6379 информацией для подключения к вашему серверу Redis
        $redis = new Redis();
        $redis->connect('127.0.0.1', 6379);
        $secretKey = $redis->get('secret_key'); // Здесь должен быть код для чтения ключа из Redis
        return $secretKey;
        
        // Прмер реализации для работы с Redis
        /*--------
        
         // Необходимо заменить значения 'redis_host' и 6379 информацией для подключения к серверу Redis
         $redisHost = 'redis_host';
         $redisPort = 6379;
        
         try {
            // Создаем объект Redis и подключаемся к серверу
            $redis = new Redis();
            $redis->connect($redisHost, $redisPort);
        
            // Необходимо заменить 'secret-key' на действительный ключ хранения токена в Redis
            $secretKey = $redis->get('secret-key');
        
            if (!$secretKey) {
                throw new Exception('Секретный ключ не найден в базе данных Redis!');
            }
        
            return $secretKey;
         } catch (RedisException $e) {
            // Обработка ошибки при подключении к серверу Redis или выполнении команд
            throw new Exception('Ошибка при получении секретного ключа из Redis: ' . $e->getMessage());
         }
       --------*/         
    }
}


class Config
{
    private static ?Config $instance = null;

    private array $config;

    private function __construct()
    {
        $this->set('secret_key_provider', SecretKeyProviderType::DB);
    }

    protected function __clone()
    {
    }

    public static function getInstance(): Config
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function set(string $key, $value): void
    {
        $this->config[$key] = $value;
    }

    public function get(string $key)
    {
        return $this->config[$key] ?? null;
    }
}