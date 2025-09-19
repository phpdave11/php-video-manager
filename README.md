# php-video-manager

*php-video-manager* is a PHP web application that lets you upload and watch videos.  The videos can be tagged and comments can be added to each video.  The videos are stored on Amazon S3 or a compatible service such as MinIO.  The database is SQLite but it also supports MySQL and PostgreSQL.  The application is written with Laravel, Livewire, and Tailwind CSS.

![screenshot of home](https://raw.github.com/phpdave11/php-video-manager/master/screenshots/home.png)

![screenshot of comments](https://raw.github.com/phpdave11/php-video-manager/master/screenshots/comments.png)

![screenshot of search](https://raw.github.com/phpdave11/php-video-manager/master/screenshots/search.png)

## Requirements

- PHP 8.4+
- Node
- ffmpeg
- S3 or comptaible service such as MinIO

## Quick Start

```sh
composer install
composer run dev
```
