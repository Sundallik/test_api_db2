### Для создания компании и т.д.:

**create:company {name}** \
create:company "Example Inc."

**create:account {company_id} {name}** \
create:account 1 "Admin"

**create:api-service {name} {base_url}** \
create:api-service "Test API" http://109.73.206.144:6969

**create:token-type {name}** \
create:token-type "api_key"

**attach:token-type-to-api-service {api_service_id} {token_type_id}** \
attach:token-type-to-api-service 1 1

**create:api-token {account_id} {api_service_id} {token_type_id} {token}** \
create:api-token 1 1 1 "E6kUTYrYwZq2tN4QEtyzsbEBk3ie"

### Выгрузка инфы из API:

- get:sales {dateFrom} {dateTo} {api_service_id} {account_id}
- get:orders {dateFrom} {dateTo} {api_service_id} {account_id}
- get:stocks {api_service_id} {account_id}
- get:incomes {dateFrom} {dateTo} {api_service_id} {account_id}

### Для запуска автообновления:

- php artisan schedule:run

Вместе с docker-container запускается контейнер "cron" с командой.
