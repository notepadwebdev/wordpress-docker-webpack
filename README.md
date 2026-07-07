# Wordpress Docker Webpack

WordPress CMS running in Docker and using Webpack for theme development.    

## 📋 Overview

This repository serves as a **entire WordPress stack for theme development** using:

- **WordPress** running in Docker containers
- **Composer** for managing WordPress plugins (free & premium)
- **Webpack** for modern asset compilation
- **WP-CLI** for command-line WordPress management

## 🔧 Requirements

- **Docker Desktop** - [Download here](https://www.docker.com/get-started)
- **Composer** (included as composer.phar, or [download here](https://getcomposer.org/download/))
- **Node.js & npm** (for theme development)

## 🚀 Quick Start

### 1. Clone the Repository

```bash
git clone git@github.com:notepadwebdev/wordpress-docker-webpack.git
```

### 2. Build and Start the Docker Container

**First time setup** (builds the custom WordPress image with WP-CLI):
```bash
docker compose up -d --build
```

**Subsequent starts**:
```bash
docker compose up -d
```

The custom Docker image includes:
- WordPress (latest version)
- WP-CLI (for plugin installation and user management)
- Optimized PHP settings for development

### 3. Install WordPress (initial set-up only)

Once the Docker container is up and running you should see the WordPress install screen at the following URL http://localhost:8888/wp-admin/install.php

If you see missing table warnings for the database, you can ignore these as these tables will be set up once you complete the installation and the WordPress database has been created.

Follow the steps to create a fresh install of WordPress, providing the following information;

- Site Title
- Username
- Password
- Your Email
- Search Engine Visibility (untick this for now)

Then click "Install WordPress".

#### 3b. Enable Permalinks (initial set-up only)

Make sure that permalinks are enabled [in the CMS](http://localhost:8888/wp-admin/options-permalink.php) as some block functionality relies upon permalinks.

### 4. Install Required Plugins

#### Composer & ACF Pro Setup

This project uses Composer to manage premium plugins like ACF Pro.

##### Install Composer (Windows)

Composer is already included in this repo as `composer.phar`. If you need to update or install globally, visit [getcomposer.org](https://getcomposer.org/download/).

##### Add Your ACF Pro License Key

ACF Pro requires a valid license key for installation. Set your license key as an environment variable before running Composer. Edit the .env.example file and save as .env with the actual licence key included.

```
# Copy the env.example file to .env and add the ACF Pro license key
ACF_PRO_KEY=your-acf-pro-license-key
```

Or alternatively set it in your shell profile for persistence.

```powershell
$env:ACF_PRO_KEY="your-acf-pro-license-key"
```

You also need to add the same ACF Pro licence the auth.json
Edit the .auth.json.example file, add the key and then save as auth.json

Both of these files will be excluded from the repo.

For more details, see [ACF Pro Composer instructions](https://www.advancedcustomfields.com/resources/installing-acf-pro-with-composer/).

##### Install ACF Pro

Once the ACF Pro licence has been  added to both your .env and your auth.json you are ready to run Composer to install all plugins:

```powershell
php composer.phar install
```

### 4b. Activate all plugins.

Once plugins have been installed they can all be activated with the following command

```powershell
docker compose exec wordpress wp plugin activate --all --allow-root
```

### Rename the theme

Rename the boilerplate theme directory and replace all references to "boilerplate-theme" and "Boilerplate Theme" within the theme files.

### Install Theme Dependencies and Start Development

Now that all required plugins are installed and activated we can now safely switch to your theme within the WordPress CMS. http://localhost:8889/wp-admin/themes.php

Next we can navigate to the child theme root and gather dependencies and start the local Webpack dev server.

```bash
cd wp-content/themes/your-theme-name
npm install
npm run start
```

This starts the Webpack dev server with hot reloading.

## 🌐 Local Development URLs

| Service | URL | Credentials |
|---------|-----|-------------|
| **WordPress Frontend (Webpack Dev Server)** | http://localhost:3000/ | - |
| **WordPress Frontend (Direct)** | http://localhost:8888/ | - |
| **WordPress Admin** | http://localhost:8888/wp-admin/ | *(set during install)* |
| **phpMyAdmin** | http://localhost:3333/ | user: `wordpress`<br>pass: `wordpress` |

## 🛠️ Development Workflow

### Theme Development

```bash
cd wp-content/themes/your-theme-name

# Install dependencies
npm install

# Start dev server (with hot reload)
npm run start

# Build for production
npm run build
```

### WP-CLI Usage

Execute WP-CLI commands in the Docker container:

```bash
# Example: List all users
docker compose exec wordpress wp user list --allow-root

# Example: Install a plugin
docker compose exec wordpress wp plugin install contact-form-7 --activate --allow-root

# Example: Clear cache
docker compose exec wordpress wp cache flush --allow-root

# Example: Update all plugins
docker compose exec wordpress wp plugin update --all --allow-root
```