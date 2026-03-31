# Getting started

## Installation

Clone the repository
  git clone git@github.com:fbenmadani/sfsaas.git

 Switch to the repo folder
  cd sfsaas

 Install dependencies
  composer install

 Install frontend dependencies
  npm install

 Build frontend assets
  npm run build 

 Copy .env.example to .env
  cp .env.example .env

 Generate application key
  php artisan key:generate

 Run database migrations
  php artisan migrate

 Run database seeders
  php artisan db:seed

 Run the development server
  php artisan serve --host sfsaas.test --port 8000
  
  