# README Honeypot project group 15

## Prerequisites for the environments
- 2 VMs with Debian installed (1 webserver, 1 ELK server for logging)
    - ELK server specs: 8 GB ram and 25 GB HDD
- Your host machine (laptop)
- SSH enabled to access VMs

## Setting up the web environment

### Install NGINX Webserver
Install the necessary prerequisites:
> `sudo apt install curl gnupg2 ca-certificates lsb-release debian-archive-keyring git`
>
Import an official nginx signing key with fetch:
> `curl https://nginx.org/keys/nginx_signing.key | gpg --dearmor | sudo tee /usr/share/keyrings/nginx-archive-keyring.gpg >/dev/null`

Verify that the downloaded file contains the proper key
> `gpg --dry-run --quiet --no-keyring --import --import-options import-show /usr/share/keyrings/nginx-archive-keyring.gpg`

Set up the apt repository for stable nginx packages:
> `echo "deb [signed-by=/usr/share/keyrings/nginx-archive-keyring.gpg] http://nginx.org/packages/debian `lsb_release -cs` nginx" | sudo tee /etc/apt/sources.list.d/nginx.list`

Run update and upgrade first before installing the webserver:
> `sudo apt update`
> `sudo apt upgrade`

Run the following command to install NGINX:
> `sudo apt install nginx`

Check the version of NGINX:
> `nginx -v`

Add user nginx to the www-data-group:
>`sudo usermod -aG www-data nginx`

Configuration of the NGINX web server is described further in the document

### Install PHP
Run the following command to install PHP:
> `sudo apt install php8.2-fpm -y`

Other PHP modules to download will be described later. This is necessary for deploying a Laravel project

### Install MariaDB

Before installing the MariaDB, run update & upgrade:
>`sudo apt update`  
>`sudo apt upgrade`

Install the MariaDB package:
>`sudo apt install mariadb-server`

After the installation, configure the mysql security with the following command:
>`sudo mysql_secure_installation`

This will take you through a series of prompts where you can make some changes to your MariaDB installation's security options

1. For the **root** password, just press **enter** to leave it blank
2. Switch to Unix: **no**
3. Change the root password: **yes**
4. Use the password **honeypot**
5. Remove anonymous users: **yes**
6. Disallow root login remotely: **yes**
7. Remove test database: **yes**
8. Reload privileged tables: **yes**

Log in to the MariaDB server:
>`sudo mysql -u root -p`

After executing, It will prompt for your password

After successfully login in, the MariaDB prompt should be seen. Create a new database named 'honeypot' with the following statement:
>`CREATE DATABASE honeypot;`

Verify if the database is created with:
>`SHOW DATABASES`

It is recommended to create a seperate user for the database

### Deploy Laravel application on the NGINX webserver

Laravel framework needs a few requirements. The necessary PHP version and extensions can be found in the following link under 'Server Requirements': https://laravel.com/docs/10.x/deployment

Check the current PHP version and verify:
>`php -v`

Check the currently installed PHP extensions and verify:
>`php -m`

If there are missing extensions, install them with apt command

Install extra PHP extensions and git:
>`sudo apt-get install nginx php8.2-fpm php8.2-cli php8.2-mcrypt git`

Download the web application in zip from: https://gitlab.ti.howest.be/ti/2023-2024/s3/websecurityandhoneypot/honeypotproject/group-15/code/honeypot

Extract the zip and transfer the webapp folder to the webserver Debian VM. An option to do this is the **scp** command from the host machine.

Move the folder from the home directory to the /usr/share/nginx directory:

>`sudo mv ~/honeypot-main /usr/share/nginx/`

In the webapp folder, copy the .env.example file to **.env**:

>`sudo cp .env.example .env`

Edit the .env file and update the following settings :

1. APP_KEY (generated with '**php artisan key:generate**' command)
2. DB-DATABASE value to the database created in the 'Install MariaDB' section
3. DB_USERNAME value to your database username
4. DB_PASSWORD value to the password entered during the mysql security configuration
```shell

APP_NAME=Honeypot
APP_ENV=local
APP_KEY=[YOUR_GENERATED_key] 
APP_DEBUG=true
APP_URL=http://localhost

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=[YOUR_DATABASE_NAME]
DB_USERNAME= [YOUR_DATABASE_USERNAME]
DB_PASSWORD=[YOUR_DATABASE_PASSWORD]

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=database
SESSION_LIFETIME=120

MEMCACHED_HOST=127.0.0.1

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
PUSHER_HOST=
PUSHER_PORT=443
PUSHER_SCHEME=https
PUSHER_APP_CLUSTER=mt1

VITE_APP_NAME="${APP_NAME}"
VITE_PUSHER_APP_KEY="${PUSHER_APP_KEY}"
VITE_PUSHER_HOST="${PUSHER_HOST}"
VITE_PUSHER_PORT="${PUSHER_PORT}"
VITE_PUSHER_SCHEME="${PUSHER_SCHEME}"
VITE_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"
```

The web application needs to be added to the web server. Edit the /etc/nginx/conf.d/default.conf configuration file and add the following:

```bash
  server {
      listen 80;
      listen [::]:80;
      server_name localhost;
      root /usr/share/nginx/honeypot-main/public;
  
      index index.php;
  
      charset utf-8;
  
      location / {
          try_files $uri $uri/ /index.php?$query_string;
      }
  
      location = /favicon.ico { access_log off; log_not_found off; }
      location = /robots.txt  { access_log off; log_not_found off; }
  
      error_page 404 /index.php;
  
      location ~ \.php$ {
          fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
          fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
          include fastcgi_params;
      }
  
      location ~ /\.(?!well-known).* {
          deny all;
      }
  } 
```

#### Configure PHP

We need to make a small change in the PHP configuration. Open the `php.ini` file:

```shell
sudo nano /etc/php/8.2/fpm/php.ini
```

Find the line, `cgi.fix_pathinfo=1`, and change the `1` to `0`:

```shell
cgi.fix_pathinfo=0
```

> [!NOTE] What is fix_pathinfo ?
> If this number is kept as 1, the PHP interpreter will do its best to process the file that is as near to the requested file as possible. This is a possible security risk. If this number is set to 0, conversely, the interpreter will only process the exact file path — a much safer alternative. Now save it and exit nano.

Install the following extra tools:
>`apt install wget php-zip unzip`

Download the composer installer with the following command:
>`wget -O composer-setup.php https://getcomposer.org/installer`

Install the composer:
>`php composer-setup.php --install-dir=/usr/local/bin --filename=composer`

The output should state that it has succesfully installed to /usr/local/bin/composer

To verify if composer succesfully installed, run the following command:
>`composer`

Inside the webapp folder, run the following command:
>`composer update`

This command will update your depencencies as they are specified in composer.json

Install the npm tool:
>`sudo apt install npm`

Still in the webapp folder, run the following npm command:
>`npm install
>`npm run dev


Modify the permissions of the **storage/** and **bootstrap/cache** directories inside the webapp folder:
>`sudo chmod -R 775 /honeypot-main/storage/`  
>`sudo chmod -R 775 /honeypot-main/bootstrap/cache`

Change the group for the **storage/** and **bootstrap/cache** directories inside the webapp folder to **www-data**:
>`sudo chgrp -R www-data /honeypot-main/storage/`  
>`sudo chgrp -R www-data /honeypot-main/bootstrap/cache`

Inside the webapp folder, run the database migration:
>`php artisan migrate:fresh --seed`

Link the private image folder to the project public folder:
>`php artisan storage:link`

## Setting up the logging environment

### Prerequisites
Set the correct timezone to 'Brussels' with the following command:
>`sudo timedatectl set-ntp true`  
>`sudo timedatectl set-timezone "Europe/Brussels"`

Verify with the following command:
>`timedatectl`

Install extra tools:
>`sudo apt install curl gnupg`  
>`sudo apt install -y build-essential git`  
>`sudo apt -y install curl software-properties-common`   
>`sudo apt -y install apt-transport-https ca-certificates`

Add the elasticsearch GPG key and add it's repo URL the Debian repo list:
>`wget -qO - https://artifacts.elastic.co/GPG-KEY-elasticsearch | sudo gpg --dearmor -o /usr/share/keyrings/elasticsearch-keyring.gpg`
>
>`echo "deb [signed-by=/usr/share/keyrings/elasticsearch-keyring.gpg] https://artifacts.elastic.co/packages/8.x/apt stable main" | sudo tee /etc/apt/sources.list.d/elastic-8.x.list`

Run update to update the repositories:
> `sudo apt update -y`

Check if the apt cache is correctly updated with the following command:
>`sudo apt-cache policy`

The output should be similar to the following:
> 500 https://artifacts.elastic.co/packages/8.x/apt stable/main amd64 Packages elease o=elastic,a=stable,n=stable,l=. stable,c=main,b=amd64 origin artifacts.elastic.co

### Install Elasticsearch
Install elasticsearch with:
>`sudo apt install elasticsearch -y`

During the installation, a password will be generated for the buit-in elastic user. Save the password! The output:
>The generated password for the elastic built-in superuser is : [PASSWORD]

### Configuring Elasticsearch
Edit the /etc/elasticsearch/elasticsearch.yml file with the properties:

```bash
sudo nano /etc/elasticsearch/elasticsearch.yml

# CHANGE network.host TO 
network.host: 0.0.0.0
# CHANGE http.port TO
http.port: 9200
```
### Start and enable the Elasticsearch service
>`sudo systemctl start elasticsearch`  
>`sudo systemctl enable elasticsearch`

Check the status of the Elasticsearch service with:
>`sudo systemctl status elasticsearch`

### Testing Elasticsearch setup
Execute the next two curl commands on your host machine (laptop) and in the actual elasticstack VM

Make sure you mapped the IP of your elastic stack VM to a hostname of your choice in the **hosts file of your host machine (laptop)**.

```bash
# Command in elastic stack VM
sudo curl --cacert /etc/elasticsearch/certs/http_ca.crt -u elastic:YOUR_GENERATED_PASSWORD https://localhost:9200

# Command in host machine
curl -u "elastic:YOUR_GENERATED_PASSWORD" https://hostnameOfVM:9200 -k

# Output of both commands should look similar to:
{
  "name" : "elastic-stack-part-I",
  "cluster_name" : "elasticsearch",
  "cluster_uuid" : "UH0uIoTCQD-0OxEc7W83Lw",
  "version" : {
    "number" : "8.10.0",
    "build_flavor" : "default",
    "build_type" : "deb",
    "build_hash" : "f56126089ca4db89b631901ad7cce0a8e10e2fe5",
    "build_date" : "2022-08-19T19:23:42.954591481Z",
    "build_snapshot" : false,
    "lucene_version" : "9.3.0",
    "minimum_wire_compatibility_version" : "7.17.0",
    "minimum_index_compatibility_version" : "7.0.0"
  },
  "tagline" : "You Know, for Search"
}
```

### Install Kibana
Install Kibana with:
>`sudo apt install kibana`

Edit the contents of the /etc/kibana/kibana.yml file:
```bash
sudo nano /etc/kibana/kibana.yml 

server host: "::"
server port: 5601
```
## Start and enable the Kibana service
>`sudo systemctl start kibana`  
>`sudo systemctl enable kibana`

Check the status of the Kibana service with:
>`sudo systemctl status kibana`

### Setup and test the Kibana site
Create an installation token for Kibana:

>`sudo /usr/share/elasticsearch/bin/elasticsearch-create-enrollment-token -s kibana`

The output should look like:
>`#eyJ2ZXIiOiI4LjQuMCIsImFkciI6WyIxOTIuMTY4LjEwMC4xMDA6OTIwMCJdLCJmZ3IiOiJlZDAyNjQ5MWU4ZmU4ODljOGJkMDc0MGU4YzVkYmNjZjBiNDc1MzhmNzk2MGViNDlkZmZkNDBiZDdmZTdlYWQzIiwia2V5Ijoic2ZvZDBZSUJRUGpYZU14WUVhU0o6MHVnUldUTlFSZlNRZGhPcHI5cWNodyJ9`

Surf to your site to test if it works: **http://your-hostname:5601**

Kibana will ask to insert the created installation token. Paste the installation code

After that, Kibana will ask for a verification token. The token can be found by checking the status of the Kibana service:

```bash
  sudo systemctl status kibana
  
  Oct 30 18:41:22 elastic-stack: Your verification code is:  242 771
```
Login on Kibana with
- user: elastic
- password: ELASTIC_GENERATED_PASSWORD

## Overview secure part of web environment

Implemented security methods for the web server and the web application:
- Enabled HTTPS (SSL/TLS)
- Hidden NGINX web server version
- Unused HTTP methods
- Security headers
    - X-Frame-Options (XFO)
    - X-Content-Type-Options (XCTO)
    - HTTP Strict-Transport-Security (HSTS)


```bash
server {
      listen 80;
      server_name localhost;

      return 301 https://$host$request_uri;
}

server {
      listen 443 ssl;
      server_name localhost;

      ssl_certificate     /etc/ssl/certs/nginx-selfsigned.crt;
      ssl_certificate_key /etc/ssl/private/nginx-selfsigned.key;
      ssl_protocols       TLSv1.3;
      ssl_ciphers         HIGH:!aNULL:!MD5;

      root /usr/share/nginx/honeypot-main/public;
      index index.php;
  
      server_tokens off;

      add_header  X-Frame-Options "deny"; # Add XFO security header
      add_header  X-Content-Type-Options "nosniff"; # Add XCTO security header  
      add_header  Strict-Transport-Security: "max-age=31536000; includeSubDomains" always; # Add HSTS security header

      charset utf-8;
  
      location / {
        limit_except GET POST { deny all; } # Allow GET and POST methods only
          try_files $uri $uri/ /index.php?$query_string;
      }
  
      location = /favicon.ico { access_log off; log_not_found off; }
      location = /robots.txt  { access_log off; log_not_found off; }
  
      error_page 404 /index.php;
  
      location ~ \.php$ {
          fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
          fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
          include fastcgi_params;
      }
  
      location ~ /\.(?!well-known).* {
          deny all;
      }
  } 
```




 



