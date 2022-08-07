# Lap Times Saver

Symfony application that I created in order to store lap times. Has games CRUD, cars CRUD, tracks CRUD, lap times CRUD and lap times summary.

## Tech stack

1. Symfony 6

## Installation

### Via INSTALL.sh

1. Make sure INSTALL.sh has execute permissions.
1. Run `./INSTALL.sh` on the command line.
1. After running the script, you may need to edit the database credentials in the .env.local file.
    1. If you edited the database credentials in the .env.local file, you need to run `php bin/console doctrine:database:create` and `php bin/console doctrine:migrations:migrate` again in order to create the necessary tables and columns in the database.

### Manual installation

1. After cloning the repository, run composer install and yarn install

```
composer install
yarn install
```

2. Create .env.local. Edit the database credentials as you like

```
DATABASE_URL="mysql://root:samplepassword@localhost:3306/database?serverVersion=your-database-version"
```

3. Create the database and run the migrations

```
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
```

4. Add dummy data if you want

```
php bin/console doctine:fixtures:load
```

5. Run npm run dev to compile the necessary css and js files

```
npm run dev
```
