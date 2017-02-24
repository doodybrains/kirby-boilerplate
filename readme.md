# Kirby Boilerplate

Kirby CMS Boilerplate I've put together using my preferred technologies. Expect it to be opinionated!

## Technologies
- [Kirby CMS](https://getkirby.com)
- [Laravel Mix](https://github.com/JeffreyWay/laravel-mix/tree/master/docs#readme)
- [Yarn](https://yarnpkg.com)
- [Webpack](https://webpack.js.org)
- [PostCSS](http://postcss.org)
- [Tachyons CSS](http://tachyons.io)

## Folder Structure
For improved security, I've opted to change the folder structure to keep only public assets on the web root, called `/public`. All other important files such as `/kirby`, `/content`, `/site`, `.env`, source and build files, etc, remain a outside the web root.

<details>
    <summary><strong>Show folder structure</strong> 👁</summary><p>

    ├── content
    ├── kirby
    ├── package.json
    ├── panel
    ├── public
    │   ├── avatars
    │   ├── css
    │   ├── fonts
    │   ├── images
    │   ├── index.php
    │   ├── js
    │   ├── robots.txt
    │   └── thumbs
    ├── resources
    │   ├── js
    │   └── sass
    ├── site
    │   ├── accounts
    │   ├── blueprints
    │   ├── cache
    │   ├── config
    │   ├── plugins
    │   ├── snippets
    │   └── templates
    ├── site.php
    ├── webpack.mix.js
    └── yarn.lock

</p></details>

The recommended Nginx configuration _(see below)_ ensures that user uploaded files to the `/content` folder publicly accessible as `/uploads`: `https://example.com/uploads/home/welcome.jpg` looks for the file `home/welcome.jpg` inside the `/content` directory.

## Installation
Use Kirby's [command line interface](https://github.com/getkirby/cli) to install Kirby and the Panel:

    $ kirby install:core

    $ kirby install:panel

Use [Yarn](https://yarnpkg.com) to install the Javascript dependencies:

    $ yarn

> Alternatively you can run `npm install`.

Copy the `.env.example` file to `.env` and adjust the settings to your needs.

> The `.env` file should remain out of version control as it may contain sensitive data such as API keys.

Next up, change your Nginx configuration to accomodate the new structure:

<details>
    <summary><strong>Show Nginx configuration</strong> 👁</summary><p>

    server {
        listen 80;
        listen [::]:80;
        server_name example.com;
        root /var/www/example.com/public;

        add_header X-Frame-Options "SAMEORIGIN";
        add_header X-XSS-Protection "1; mode=block";
        add_header X-Content-Type-Options "nosniff";

        index.html index.php;

        charset utf-8;
        #client_max_body_size 20M;

        # Enable cache busting
        location ~* (.+)\.(?:\d+)\.(js|css)$ {
            try_files $uri $1.$2;
        }

        # Expire rules for static content

        # Feed
        location ~* \.(?:atom|rss)$ {
            expires 1h;
        }

        # Media: images, icons, video, audio, HTC
        location ~* \.(?:jpe?g|gif|png|ico|cur|gz|svg|svgz|mp4|ogg|ogv|webm|htc)$ {
            expires 1M;
            access_log off;
            add_header Cache-Control "public";
        }

        # CSS and Javascript
        location ~* \.(?:css|js)$ {
            expires 1y;
            access_log off;
        }

        # Rewrite user uploaded content
        location ~ ^/uploads(/.*\.(jpe?g|gif|png|svg|pdf|mp3))$ {
            root /var/www/example.com/content/;
            try_files $1 =404;
        }

        location / {
            try_files $uri $uri/ /index.php?$query_string;
        }

        location /panel {
            root /var/www/example.com/;
            try_files $uri $uri/ /panel/index.php?$query_string;

            location ~ \.php$ {
                fastcgi_split_path_info ^(.+\.php)(/.+)$;
                fastcgi_pass unix:/var/run/php/php7.1-fpm.sock;
                fastcgi_index index.php;
                include fastcgi_params;
            }
        }

        location = /favicon.ico { access_log off; log_not_found off; }
        location = /robots.txt  { access_log off; log_not_found off; }
        location = /sitemap.xml { access_log off; log_not_found off; }

        access_log off;
        error_log  /var/log/nginx/example.com-error.log error;

        error_page 404 /index.php;

        location ~ \.php$ {
            fastcgi_split_path_info ^(.+\.php)(/.+)$;
            fastcgi_pass unix:/var/run/php/php7.1-fpm.sock;
            fastcgi_index index.php;
            include fastcgi_params;
        }

        # Prevent clients from accessing hidden files (starting with a dot)
        # Access to `/.well-known/` is allowed.
        # https://www.mnot.net/blog/2010/04/07/well-known
        # https://tools.ietf.org/html/rfc5785
        location ~* /\.(?!well-known\/) {
            deny all;
        }

        # Prevent clients from accessing to backup/config/source files
        location ~* (?:\.(?:bak|conf|dist|fla|in[ci]|log|psd|sh|sql|sw[op])|~)$ {
            deny all;
        }
    }

</p></details>

The `^/uploads(/.*\.(jpe?g|gif|png|svg|pdf|mp3))$` rule includes only the most common file types on purpose. If your site requires other file types, just add them there to make them publicly available.

## Usage
Laravel Mix is a configuration layer on top of Webpack, so to run your Mix tasks you only need to execute one of the NPM scripts that is included on the package.json file.

Run all Mix tasks once:

    $ yarn run dev

Run all Mix tasks and watch all relevant files for changes:

    $ yarn run watch

Run all Mix tasks and minify output:

    $ yarn run production

## Recommended Plugins

### Auto Git

    $ kirby plugin:install pedroborges/kirby-autogit

### Cachebuster

    $ kirby plugin:install cachebuster-plugin

### Google Analytics

    $ kirby plugin:install pedroborges/kirby-google-analytics

### Sitemap

    $ kirby plugin:install pedroborges/kirby-xml-sitemap

## License
Kirby Boilerplate is open-sourced software licensed under the [MIT license](http://www.opensource.org/licenses/mit-license.php).

Copyright © 2017 Pedro Borges <oi@pedroborg.es>
