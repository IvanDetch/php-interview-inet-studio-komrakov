<?php

// Определяем интерфейс ObjectHandler
interface ObjectHandler
{
    public function getHandlerName(): string;
}

// Класс SomeObject имплементирует интерфейс ObjectHandler
class SomeObject implements ObjectHandler
{
    public function __construct(
        private readonly string $name
    ){}

    // Метод возвращает имя объекта
    public function getObjectName(): string
    {
        return $this->name;
    }

    // Реализация метода из интерфейса ObjectHandler
    public function getHandlerName(): string
    {
        return 'handle_' . $this->name;
    }
}

// Класс SomeObjectsHandler отвечает за обработку объектов
class SomeObjectsHandler
{
    /**
     * @param SomeObject[] $objects
     * @return array
     */
    public function handleObjects(array $objects): array
    {
        $handlers = [];

        // Проходим по каждому объекту
        foreach ($objects as $object) {
            // Проверяем, что объект является экземпляром класса SomeObject
            if ($object instanceof SomeObject) {
                // Получаем имя обработчика объекта и добавляем его в массив
                $handlers[] = $object->getHandlerName();
            }
        }

        // Возвращаем массив имен обработчиков
        return $handlers;
    }
}

// Создаем несколько объектов SomeObject
$objects = [
    new SomeObject('object_1'),
    new SomeObject('object_2')
];

// Создаем экземпляр SomeObjectsHandler
$soh = new SomeObjectsHandler();
// Обрабатываем объекты и сохраняем результат в переменную
$result = $soh->handleObjects($objects);

// Выводим результат на экран
var_dump($result);