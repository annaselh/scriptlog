# Scriptlog

### A Simple, Modular and lightweight PHP Blog System

[![GitHub license](https://img.shields.io/github/license/cakmoel/scriptlog.svg)](https://github.com/cakmoel/scriptlog/blob/master/LICENSE)
[![Twitter](https://img.shields.io/twitter/url/https/github.com/cakmoel/scriptlog.svg?style=social)](https://twitter.com/intent/tweet?text=Wow:&url=https%3A%2F%2Fgithub.com%2Fcakmoel%2Fscriptlog)

### Description

Scriptlog is Free and Open Source PHP blog system. We're refactoring our legacy blog code. Make it more simple, modular and lightweight blog system. Scriptlog runs on PHP 5.6 or higher, it uses MySQL.

Before you install scriptlog on your host, make sure scriptlog can work correctly in your environment. Scriptlog need the following **System Requirement:**

 - [MySQL](https://www.mysql.com) version 5.6 or greater or any version of [MariaDB](https://mariadb.org/).
 - [PHP](https://secure.php.net) version 5.6 or greater.
    - curl
    - gd
    - iconv
    - pdo_mysql
    - filter_list
    - HTTPS Support.

### Installation

 1. Download scriptlog [here](https://sourceforge.net/projects/scriptlog/).
 2. Ensure that you have the required system.
 3. Upload Scriptlog through FTP/SFTP or whatever upload method you prefer to the public-facing directory of your site.
 4. Ensure that the permissions for `install/index.php`, `public/log`, `public/cache`, `public/themes` and `library/plugins` folders are set to `writeable` and that all files belong to the web user or is a part of the same group as the web user.
 5. Create a database for Scriptlog to installs to. You may name it anything you like. The method for database creation varies depending on your webhost but may require using adminer, PHPMyAdmin or etc. If you are unsure of how to create this, ask your host.
 6. Follow the installer instructions.
 7. For security purposes, delete the `install` directory when you are finish.

### Usage

After you finish installation, you can populate your blog. To manage your blog content, go to administrator panel on web browser:
   `http://your-server-name/admin/`

### Contributing

