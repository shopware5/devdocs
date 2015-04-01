---
layout: default
title: Shopware 5 System Requirements
github_link: sysadmins-guide/system-requirements/index.md
indexed: true
---
## Server requirements

The required server hardware is mostly dependent on the number of articles available in your shop and on the expected traffic (visitors per day). Upon request, we'll gladly send you guidelines for suitable server equipment. These requirements are met by most modern hosting providers.

- Linux-based operating system with Apache 2.x web server
- PHP 5.4 or higher (PHP 5.5 or higher recommended)*
- MySQL 5.5 or higher
- Possibility to set up cron jobs
- Minimum 4 GB available hard disk space

 * The minimum required PHP version may be raised to 5.5 during Shopware 5 life cycle. We strongly recommend that you use PHP 5.5.

### PHP Extensions / Web server:

- Apache mod_rewrite
- GD Library version 2.0 or higher
- cURL Library installed
- IonCube Loader version 4.6 or higher is required when using commercial Shopware versions or plugins.
- When using Shopware ESD functionalities, it's highly recommend to use Apache X-Sendfile.


### Web server / PHP settings:

- memory_limit > 128M
- magic_quotes_gpc deactivated
- allow_url_fopen activated
- register_globals deactivated
- Possibility to modify the webserver settings via .htaccess
- PHP calendar extension
- PDO / PDO_Mysql

### Other requirements

The requirements specified above reflect only the minimum requirements of Shopware. Specific hardware requirements vary depending on the size and expected traffic of your shop. Additional server configuration may be required. Plugins installed on your shop may increase Shopware's resource needs or add additional system dependencies. Please refer to each plugin's documentation for more information.

### Alternative server setups

The above requirements reflect the officially supported and recommended system setup to run Shopware. However, you might be able to run Shopware on equivalent setups (Mac OS, nginx, MariaDB, etc). Please keep in mind that we are unable to provide official support on those setups.


## Shopware 5 System Requirements - Administration client

The administration of your shop can be done completely online via the web browser. The following requirements should be met by any client system that uses the administration backend. These requirement differ from the frontend user system requirements.

### Requirements:

- Firefox, Chrome, Safari or Internet Explorer version 9 or higher.
- JavaScript and Cookies enabled
- 4 GB RAM
- Dual-core CPU
- Minimum backend resolution:  1366 x 768 pixels

