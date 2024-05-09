## Installation

1. Clone the repository:
   ```bash
   git clone https://github.com/MiroErzrumyan/Survey-app.git
   cd Survey-app
   composer install
   cp .env.example .env
   php artisan key:generate
   php artisan migrate
   php artisan db:seed
   php artisan serve

