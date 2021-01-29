# Wordpress Webpack

WordPress CMS running in Docker and using Webpack for theme development.    

## Setup

```
git clone git@github.com:notepadwebdev/wordpress-docker-webpack.git
```

### Install and set up WordPress

Install [Docker](https://www.docker.com/get-started) (if you don't already have it), and start the Docker app.

From the repo root do the following...

1. Start the Docker container
```bash
docker-compose up -d
```
2. When complete (can take a few of minutes - especially first time) go to [http://localhost:8888/](http://localhost:8888/) and complete the usual WordPress install.
3. Once installed, log into WordPress and [enable the included plugins](http://localhost:8888/wp-admin/plugins.php) 
   * [ACF Pro](https://www.advancedcustomfields.com/pro/)
4. [Switch theme](http://localhost:8888/wp-admin/themes.php) to the provided `Boilerplate Theme` theme.
5. Make sure that WP permalinks are setup correctly. [*Settings => Permalinks =>*](http://localhost:8888/wp-admin/options-permalink.php) choose **Post name** and save.

## Development

From the root directory, run the following command to start the Docker container

```bash
docker-compose up -d
```

Navigate to the Boilerplate theme root, gather dependencies, and then start the dev server...

```bash
cd wp-content/themes/boilerplate-theme
npm i
npm run start
```


### Boilerplate theme

- ACF Local JSON enabled.
- A reduced Admin UI.
- Docker volume for `./wp-content` for your plugins, themes, and uploads.

### Local Development URLs

WordPress (CMS only): http://localhost:8888/wp-admin/

phpMyAdmin: http://localhost:3333/   
***user: wordpress***    
***pass: wordpress***
