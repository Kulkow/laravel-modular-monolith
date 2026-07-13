workspace "Laravel Modular Monolith" "Архитектура модульного монолита (Core + Identity)" {

    model {
        # ====== 1. System Context ======
        user = person "Пользователь" "Конечный пользователь системы"
        admin = person "Администратор" "Управляет пользователями и ролями"

        system = softwareSystem "Модульный монолит" "Laravel-приложение с модулями Core и Identity" {
            # Связи контекста (будем добавлять позже)
        }

        # Связи на уровне контекста
        user -> system "Использует (API/Web)"
        admin -> system "Управляет (Web/API)"

        # ====== 2. Containers (Контейнеры) ======
        # Веб-сервер
        nginx = container "Веб-сервер - Nginx" "Отдача статики проксирование запросов" {
            technology "Nginx"
        }

        # PHP-приложение (основной контейнер)
        app = container "PHP-приложение" "Laravel (PHP 8.4)" "Содержит всю бизнес-логику и модули" {
            technology "Laravel + PHP 8.4"
        }

        # База данных
        db = container "База данных" "PostgreSQL 16" "Хранилище данных (пользователи, роли, etc.)"

        # Кэш / очереди
        redis = container "Redis" "Redis 7.4" "Кэширование, сессии, очереди"

        # Horizon
        horizon = container "Horizon" "Laravel Horizon" "Обработка фоновых задач"

        # Scheduler
        scheduler = container "Scheduler" "Laravel Scheduler" "Планировщик задач (cron)"

        # Связи между контейнерами
        user -> nginx "Запросы"
        admin -> nginx "Запросы"

        nginx -> app "Передача запросов (FastCGI)"
        app -> db "Чтение/запись (SQL)"
        app -> redis "Кэш, сессии"
        app -> horizon "Отправка задач"
        horizon -> redis "Очереди"
        scheduler -> app "Запуск команд"
        scheduler -> db "Выполнение миграций/данных"

        # ====== 3. Components (внутри PHP-приложения) ======
        # Модуль Core
        core = component "Core" "Общее ядро" "Общие утилиты, абстракции, интерфейсы" {
            technology "PHP"
        }

        # Модуль Identity
        identity = component "Identity" "Управление пользователями и ролями" "Domain, Application, Infrastructure" {
            technology "PHP"
            # Можно детализировать его внутренние компоненты, если нужно
            # domain = component "Domain" "Сущности, VO, события, интерфейсы репозиториев"
            # appLayer = component "Application" "Use Cases, DTO, обработчики"
            # infra = component "Infrastructure" "Eloquent-репозитории, контроллеры"
        }

        # Связи между компонентами
        identity -> core "Использует утилиты и абстракции"
        # Другие модули (если бы были) -> identity "Проверка прав"

        # Также можно показать внутреннюю структуру Identity
        identityDomain = component "Identity.Domain" "Доменный слой" "Сущности (User, Role), Value Objects, события"
        identityApp = component "Identity.Application" "Прикладной слой" "Use Cases (регистрация, выдача ролей)"
        identityInfra = component "Identity.Infrastructure" "Инфраструктурный слой" "Eloquent-репозитории, контроллеры"

        identityDomain -> identityApp "Модели"
        identityApp -> identityInfra "Интерфейсы репозиториев"
        identityInfra -> identityDomain "Реализации"

        # Можно также добавить зависимости инфраструктуры от Core
        identityInfra -> core "Использует общие компоненты"
    }

    # ====== 4. Views (Диаграммы) ======
    views {
        # Контекстная диаграмма
        systemContext "SystemContext" {
            include *
            autoLayout
        }

        # Контейнерная диаграмма
        container "Containers" {
            include *
            autoLayout
        }

        # Компонентная диаграмма для PHP-приложения
        component "Components" {
            include app
            include core
            include identity
            include identityDomain
            include identityApp
            include identityInfra
            autoLayout
        }

        # Можно сделать отдельные диаграммы для Identity
        component "IdentityComponents" "Компоненты модуля Identity" {
            include identityDomain
            include identityApp
            include identityInfra
            include core
            autoLayout
        }

        # Стилизация (опционально)
        styles {
            element "System" {
                background "#1168bd"
                color "#ffffff"
            }
            element "Person" {
                background "#08427b"
                color "#ffffff"
                shape "Person"
            }
            element "Container" {
                background "#438dd5"
                color "#ffffff"
            }
            element "Component" {
                background "#85bbf0"
                color "#000000"
            }
            element "Database" {
                background "#438dd5"
                color "#ffffff"
                shape "Cylinder"
            }
        }
    }
}
