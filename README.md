# Laravel Post CRUD with Docker

A simple Laravel 13 app where you can create, read, update and delete posts. It runs entirely in Docker.

You do **not** need PHP, Composer or MySQL on your computer. Docker installs and runs them for you.

| Container | What it runs |
|---|---|
| `laravel_app` | PHP 8.4 + Apache + Composer + the Laravel code |
| `laravel_db` | MySQL 8.4 database |

---

## 1. What you need

Install these two programs first:

- **Git**: https://git-scm.com/downloads
- **Docker Desktop**: https://www.docker.com/products/docker-desktop/

Before you continue, open Docker Desktop and wait until it says **Engine running**.

Check that both are installed:

```bash
git --version
docker --version
docker compose version
```

---

## 2. Clone and run the project

### Step 1: Clone the project

```bash
git clone https://github.com/yonyen249-ai/laravel-hosting-with-docker.git
cd laravel-hosting-with-docker
```

### Step 2: Create the `.env` file

The `.env` file is not uploaded to GitHub, so you create it from the example file.

```bash
# Git Bash / Mac / Linux
cp .env.example .env

# Windows CMD or PowerShell
copy .env.example .env
```

> **Important:** Do not skip this step. Without `.env`, the database will not start.

### Step 3: Build and start the containers

```bash
docker compose up -d --build
```

The first run takes a few minutes because Docker downloads PHP and MySQL. Later runs are much faster.

You do **not** need to run `composer install`, `php artisan key:generate` or `php artisan migrate`. The container does all of that automatically when it starts.

### Step 4: Open the app

Go to **http://localhost:8000**

If the page does not load yet, wait about 20 seconds and refresh. MySQL needs time to start the first time.

Check that both containers are running:

```bash
docker compose ps
```

Both `laravel_app` and `laravel_db` should show **Up**.

---

## 3. See the database

The database login comes from your `.env` file:

| Setting | Value |
|---|---|
| Database | `laravel` |
| Username | `laravel` |
| Password | `secret` |
| Root password | `rootsecret` |

### Option A: MySQL command line (recommended)

Open MySQL inside the database container:

```bash
docker exec -it laravel_db mysql -u laravel -psecret laravel
```

Then type SQL at the `mysql>` prompt:

```sql
SHOW TABLES;            -- list all tables
DESCRIBE posts;         -- show the columns of the posts table
SELECT * FROM posts;    -- show all posts
exit                    -- leave MySQL
```

> **Windows Git Bash:** if you see `the input device is not a TTY`, put `winpty` at the start of the command:
> `winpty docker exec -it laravel_db mysql -u laravel -psecret laravel`

### Option B: Run one query without going inside

```bash
docker exec laravel_db mysql -u laravel -psecret laravel -e "SELECT * FROM posts;"
```

### Option C: Use Laravel's database commands

```bash
docker compose exec app php artisan db:show           # database info and list of tables
docker compose exec app php artisan db:table posts    # columns of the posts table
```

### Option D: Use a GUI program (DBeaver, HeidiSQL, TablePlus, MySQL Workbench)

MySQL is not reachable from your computer by default. To open it, add `ports` under the `db:` service in `docker-compose.yml`:

```yaml
  db:
    image: mysql:8.4
    ports:
      - "3307:3306"
```

Restart the containers:

```bash
docker compose up -d
```

Then connect your GUI program with:

| Setting | Value |
|---|---|
| Host | `127.0.0.1` |
| Port | `3307` |
| Username | `laravel` |
| Password | `secret` |
| Database | `laravel` |

> Only do this on your own computer. Do **not** open the database port on a real server.

---

## 4. Useful commands

| What you want to do | Command |
|---|---|
| Start the app | `docker compose up -d` |
| Stop the app | `docker compose down` |
| Rebuild after changing code | `docker compose up -d --build` |
| See the app logs | `docker compose logs -f app` |
| See the database logs | `docker compose logs -f db` |
| Run an artisan command | `docker compose exec app php artisan <command>` |
| Open a shell inside the app container | `docker compose exec app bash` |
| Delete everything, **including the database data** | `docker compose down -v` |

> The code is copied into the image when it is built. After you edit code, run `docker compose up -d --build` to see your changes.

---

## 5. Common problems

| Problem | Fix |
|---|---|
| `port is already allocated` (port 8000 is in use) | In `.env`, change `APP_PORT=8000` to `APP_PORT=8080`, run `docker compose up -d`, then open http://localhost:8080 |
| A folder called `.env` was created | You started Docker before Step 2. Run `docker compose down`, delete the `.env` folder, then do Step 2 and Step 3 again |
| The database won't start after you changed passwords in `.env` | The old database still has the old passwords. Run `docker compose down -v` (this **deletes all data**), then `docker compose up -d` |
| `Cannot connect to the Docker daemon` | Docker Desktop is not running. Open it and wait for **Engine running** |
| The page shows an error | Run `docker compose logs app` to see what went wrong |

---

## 6. Project structure (CRUD files)

```
app/Http/Controllers/PostController.php    # index, create, store, show, edit, update, destroy
app/Models/Post.php                        # Post model
database/migrations/..._create_posts_table.php
resources/views/posts/                     # index, create, edit, show, _form
routes/web.php                             # Route::resource('posts', ...)

Dockerfile                                 # builds the PHP + Apache + Composer image
docker-compose.yml                         # runs the app and MySQL containers
docker/entrypoint.sh                       # runs on start: APP_KEY, wait for DB, migrate
```
