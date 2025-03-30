Hej OnlinePOS

Jeg havde en del problemer med at få deployed min backend, så jeg håber det er okay at i kun kan køre den lokalt


For at køre løsningen lokalt:

Juster env fil til lokal mysql db

Terminal 1:

cd frontend 

npm i

npm run dev 

Terminal 2:

cd backend

composer install

php artisan migrate:fresh --seed

php artisan serve


Sig endelig til hvis der er nogen spørgsmål :))
