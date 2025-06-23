# Trackr API

[![Laravel](https://img.shields.io/badge/Laravel-10.x-FF2D20?style=for-the-badge&logo=laravel)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.1-777BB4?style=for-the-badge&logo=php)](https://www.php.net/)
[![Docker](https://img.shields.io/badge/Docker-20.10-2496ED?style=for-the-badge&logo=docker)](https://www.docker.com/)
[![GitHub Actions](https://img.shields.io/badge/GitHub%20Actions-CI/CD-2088FF?style=for-the-badge&logo=github-actions)](.github/workflows/docker-publish.yml)

Trackr is a headless backend API built with Laravel, designed to power a media tracking application. It provides a comprehensive set of endpoints to manage movies, TV shows, user watchlists, ratings, and reviews. The application is containerized with Docker and ready for deployment.

## Key Features

-   **User Authentication**: Secure user registration, login, and token-based authentication using Laravel Sanctum.
-   **Movie & TV Show Management**: Full CRUD (Create, Read, Update, Delete) functionality for movies and TV shows.
-   **Watchlist System**: Users can add or remove movies and TV shows from their personal watchlist.
-   **Watched History**: Track which items have been watched and when.
-   **Ratings & Reviews**: Users can rate and write reviews for movies and TV shows.
-   **Polymorphic Relationships**: Efficiently handles relationships for watchlists, ratings, and reviews across different media types (e.g., `Movie`, `TvShow`).
-   **API Documentation**: Auto-generated, interactive API documentation powered by [Scramble](https://github.com/dedoc/scramble).
-   **Containerized**: Includes a `Dockerfile` for easy and consistent deployment.
-   **Continuous Integration**: A GitHub Actions workflow automates building and pushing the Docker image to GitHub Container Registry (GHCR).

## API Documentation

This project uses `dedoc/scramble` to automatically generate API documentation from the source code. Once the application is running, you can access the interactive documentation by navigating to the `/docs/api` endpoint of your application URL.

Example: `http://localhost:8000/docs/api`

## Getting Started (Local Development)

Follow these steps to get the Trackr API running on your local machine.

### Prerequisites

-   PHP 8.1+
-   Composer
-   A local database server (e.g., MySQL, PostgreSQL) OR SQLite.

### Installation Steps

1.  **Clone the repository:**
    ```bash
    git clone https://github.com/your-username/trackr-api.git
    cd trackr-api
    ```

2.  **Install Composer dependencies:**
    ```bash
    composer install
    ```

3.  **Create your environment file:**
    ```bash
    cp .env.example .env
    ```

4.  **Generate an application key:**
    ```bash
    php artisan key:generate
    ```

5.  **Configure your `.env` file:**
    Update the `DB_*` variables to connect to your local database. For a quick start, you can use SQLite by modifying the `.env` file as follows:
    ```ini
    DB_CONNECTION=sqlite
    ```
    And then create the database file:
    ```bash
    touch database/database.sqlite
    ```

6.  **Run database migrations:**
    This will create all the necessary tables in your database.
    ```bash
    php artisan migrate
    ```

7.  **Start the development server:**
    ```bash
    php artisan serve
    ```
    The API will now be available at `http://localhost:8000`.

## Continuous Integration & Deployment

### GitHub Actions for Docker (GHCR)

The repository includes a GitHub Actions workflow defined in `.github/workflows/docker-publish.yml`. This workflow automates the process of building and publishing a Docker image to the GitHub Container Registry (GHCR).

**How it works:**
-   **Trigger**: The workflow runs automatically whenever code is pushed to the `main` branch.
-   **Process**:
    1.  It checks out the repository's code.
    2.  It logs into GHCR using a temporary `GITHUB_TOKEN`.
    3.  It builds the Docker image using the provided `Dockerfile`.
    4.  It tags the image with `latest` and the commit SHA.
    5.  Finally, it pushes the tagged image to your repository's package on GHCR.

### Deployment on Render

This application is configured for easy deployment on [Render](https://render.com/) using its Docker support and persistent disks.

1.  **Fork this repository** to your own GitHub account.

2.  On the Render Dashboard, click **New +** and select **Web Service**.

3.  Connect the GitHub repository you just forked.

4.  On the settings page, configure the following:
    -   **Name**: Give your service a name (e.g., `trackr-api`).
    -   **Environment**: Select **Docker**.
    -   **Instance Type**: The free plan is sufficient to get started.

5.  Add a **Persistent Disk** for the SQLite database to prevent data loss on deploys:
    -   Click **Add Disk**.
    -   **Name**: `trackr-database`
    -   **Mount Path**: `/var/www/html/database`
    -   **Size**: `1 GB` (or as needed)

6.  Add the following **Environment Variables** under the "Advanced" section:

| Key             | Value                                              | Notes                                                                                             |
| --------------- | -------------------------------------------------- | ------------------------------------------------------------------------------------------------- |
| `APP_KEY`       | `base64:YourGeneratedKey=`                         | **Required.** Run `php artisan key:generate --show` locally and paste the output here.              |
| `APP_URL`       | `${RENDER_EXTERNAL_URL}`                           | This uses Render's built-in variable for the public URL.                                          |
| `DB_CONNECTION` | `sqlite`                                           | Specifies that the app should use SQLite.                                                         |
| `DB_DATABASE`   | `/var/www/html/database/database.sqlite`           | The absolute path to the database file on the persistent disk.                                    |
| `APP_ENV`       | `production`                                       | Puts Laravel in production mode.                                                                  |
| `APP_DEBUG`     | `false`                                            | Disables debug mode for security.                                                                 |
| `LOG_CHANNEL`   | `stderr`                                           | Recommended for containerized environments to view logs in the Render log stream.                 |

7.  Click **Create Web Service**. Render will automatically pull your repository, build the Docker image, and deploy your application. The first deploy might take a few minutes.

## Environment Variables

The following environment variables are used by the application. See `.env.example` for a full list.

| Variable           | Description                                                | Default in `.env.example` |
| ------------------ | ---------------------------------------------------------- | ------------------------- |
| `APP_NAME`         | The name of your application.                              | `Laravel`                 |
| `APP_ENV`          | The application environment (e.g., `local`, `production`). | `local`                   |
| `APP_KEY`          | The application encryption key.                            |                           |
| `APP_DEBUG`        | Toggles debug mode.                                        | `true`                    |
| `APP_URL`          | The base URL of the application.                           | `http://localhost`        |
| `DB_CONNECTION`    | The database driver to use.                                | `mysql`                   |
| `DB_HOST`          | The database host.                                         | `127.0.0.1`               |
| `DB_PORT`          | The database port.                                         | `3306`                    |
| `DB_DATABASE`      | The database name.                                         | `laravel`                 |
| `DB_USERNAME`      | The database user.                                         | `root`                    |
| `DB_PASSWORD`      | The database user's password.                              |                           |