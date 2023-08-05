## Контейнеризация
1. git clone https://github.com/laradock/laradock.git laradock
2. cp ./laradock/.env.example ./laradock/.env
3. change PHP_VERSION in ./laradock/.env to 8.1
4. cp ./.env.example to ./.env
5. docker-compose up workspace nginx php-fpm
6. docker exec -it laradock-workspace-1 bash
7. npm install
8. npm run dev
