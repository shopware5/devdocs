---
layout: default
title: System Requirements
github_link: sysadmins-guide/system-requirements/index.md
indexed: true
group: System Guides
menu_title: System requirements
menu_order: 10
---
## Server requirements

### Webserver 

- Linux-based operating system with Apache 2.2 or 2.4 web server with enabled  `mod_rewrite` module and ability to override options in `.htaccess` files
- PHP 7.2.0 or higher
- MySQL 5.7 or higher
- Possibility to set up cron jobs
- Minimum 4 GB available hard disk space

### Required PHP extensions:

-   <a href="http://php.net/manual/en/book.ctype.php" target="_blank">ctype</a>
-   <a href="http://php.net/manual/en/book.curl.php" target="_blank">curl</a>
-   <a href="http://php.net/manual/en/book.dom.php" target="_blank">dom</a>
-   <a href="http://php.net/manual/en/book.hash.php" target="_blank">hash</a>
-   <a href="http://php.net/manual/en/book.iconv.php" target="_blank">iconv</a>
-   <a href="http://php.net/manual/en/book.image.php" target="_blank">gd</a> (Version >= 2.0 with freetype and libjpeg support)
-   <a href="http://php.net/manual/en/book.json.php" target="_blank">json</a>
-   <a href="http://php.net/manual/en/book.mbstring.php" target="_blank">mbstring</a>
-   <a href="http://php.net/manual/en/book.openssl.php" target="_blank">openssl</a>
-   <a href="http://php.net/manual/en/book.session.php" target="_blank">session</a>
-   <a href="http://php.net/manual/en/book.simplexml.php" target="_blank">SimpleXML</a>
-   <a href="http://php.net/manual/en/book.xml.php" target="_blank">xml</a>
-   <a href="http://php.net/manual/en/book.zip.php" target="_blank">zip</a>
-   <a href="http://php.net/manual/en/book.zlib.php" target="_blank">zlib</a>
-   <a href="http://php.net/manual/en/ref.pdo-mysql.php" target="_blank">PDO/MySQL</a>

### PHP OPcache

It's strongly recommended that you verify the <a href="https://secure.php.net/manual/en/book.opcache.php" target="_blank">PHP OPCache</a> is enabled for performance reasons.

### PHP settings:

- `memory_limit` > 256M
- `upload_max_filesize` > 6M
- `allow_url_fopen` activated

### Optional MySQL settings

- MySQL variable `group_concat_max_len` should be increased to 2048 (default: 1024)
- `innodb_buffer_pool_size` should be at least as large as the whole database
- `query_cache_size` should normally be between 100 - 200MB

### Recommended
 
- <a href="https://secure.php.net/manual/en/book.apcu.php" target="_blank">APCu</a> 
- When using Shopware ESD functionalities, it's highly recommended to use Apache `mod_xsendfile`
- For enabling the HSTS header, Apache's `header` module is necessary. Simply add the line `Header always set Strict-Transport-Security "max-age=31536000"` to your .htaccess

### Other requirements

The requirements specified above reflect only the minimum requirements of Shopware. Specific hardware requirements vary depending on the size and expected traffic of your shop. Additional server configuration may be required. Plugins installed on your shop may increase Shopware's resource needs or add additional system dependencies. Please refer to each plugin's documentation for more information.

### Alternative server setups

The above requirements reflect the officially supported and recommended system setup to run Shopware. However, you might be able to run Shopware on equivalent setups (Mac OS, nginx, MariaDB, etc). Please keep in mind that we are unable to provide official support on those setups.

## Elasticsearch

Shopware supports [Elasticsearch](https://www.elastic.co/products/elasticsearch) servers in versions 2.x, 5.x and 6.x out of the box. 
For more details see the <a href="{{ site.url }}/sysadmins-guide/elasticsearch-setup/">Elasticsearch setup</a>.

## Shopware 5 System Requirements - Administration client

The administration of your shop can be done completely online via the web browser. The following requirements should be met by any client system that uses the administration backend. These requirement differ from the frontend user system requirements.

### Requirements:

- Latest version of: Firefox, Chrome or Safari. We only support the latest released version of Internet Explorer.
- JavaScript and Cookies enabled
- 4 GB RAM
- Dual-core CPU
- Minimum backend resolution: 1366 x 768 pixels
