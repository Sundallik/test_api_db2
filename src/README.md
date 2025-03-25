### Команды:

- get:sales {dateFrom} {dateTo} {account_id}
- get:orders {dateFrom} {dateTo} {account_id}
- get:stocks {account_id}
- get:incomes {dateFrom} {dateTo} {account_id}

**create:company {name}** \
create:company "Example Inc."

**create:account {company_id} {name}** \
create:account 1 "Main Account"

**create:api-service {name} {base_url}** \
create:api-service "Weather API" "https://api.weather.com"

**create:token-type {type}** \
create:token-type "bearer"

**attach:token-type-to-api-service {api_service_id} {token_type_id}** \
attach:token-type-to-api-service 1 1

**create:api-token {account_id} {api_service_id} {token_type_id} {token}** \
create:api-token 1 1 1 "jaUIb2sbLpbZEQWZzIY9KHg"

Для запуска автообновления:

- php artisan schedule:list
- php artisan schedule:work
