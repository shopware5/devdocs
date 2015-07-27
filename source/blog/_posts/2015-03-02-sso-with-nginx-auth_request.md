---
title: SSO with Nginx auth_request module
description: Recently we had the challenge to connect a static website with our existing Single Sign-on (SSO) infrastructure
tags:
- nginx
- development
- SSO

categories:
- dev

authors: [bc]
indexed: true
---

Recently we had the challenge to connect a static website with our existing Single Sign-on (SSO) infrastructure.

## Initial Situation

The following components are involved

- `api.example.com`: The SSO API endpoint
- `login.example.com`: User facing UI for the SSO API; Provides registration and login forms, etc.
- `staticpage.example.com`: Static website content that should be secured/connected to the SSO.

The authentication on the SSO API is done with a token that can be provided via the `X-SHOPWARE-SSO-Token` HTTP header or via the `shopware_sso_token` cookie.

## Challenge

Our task was to ensure that all requests to `staticpage.example.com` are authorized by `api.example.com`. Unauthenticated requests must be redirected to `login.example.com`.

To intercept every request we could have used a PHP based proxy like the Guzzle/Symfony based [jenssegers/php-proxy](https://github.com/jenssegers/php-proxy)...

## nginx to the rescue

Fortunately [nginx](http://nginx.org/) is also able to solve this problem for us.

All we need is the [auth_request](http://nginx.org/en/docs/http/ngx_http_auth_request_module.html) module.

> The ngx_http_auth_request_module module implements client authorization based on the result of a subrequest.
> If the subrequest returns a 2xx response code, the access is allowed.
> If it returns 401 or 403, the access is denied with the corresponding error code.


### Installation

The module is available in nginx since version 1.5.4 but is not compiled by default.

You can check if your installed version of nginx was compiled with auth_request support using the following command:

```bash
nginx -V 2>&1 | grep -qF -- --with-http_auth_request_module && echo ":)" || echo ":("
```

#### Debian Wheezy

There is a precompiled package available in the Debian Wheezy backports: [nginx-extra](https://packages.debian.org/wheezy-backports/nginx-extras).

```bash
echo "deb http://ftp.de.debian.org/debian/ wheezy-backports main contrib non-free" > /etc/apt/sources.list.d/backports.list
aptitude update
aptitude -t wheezy-backports install nginx-extras
```

#### Debian Jessie

On Debian Jessie the [nginx-extra](https://packages.debian.org/jessie/nginx-extras) package already includes the auth_request module.

```bash
aptitude install nginx-extras
```


#### Compile

[Compile nginx](http://wiki.nginx.org/Install#Building_Nginx_From_Source) with the auth_request module:

```bash
./configure --with-http_auth_request_module
```

### Configuration

Inside the vhost for `staticpage.example.com` we have to add the [auth_request](http://nginx.org/en/docs/http/ngx_http_auth_request_module.html#auth_request) directive:

```
server {
    server_name staticpage.example.com;
    auth_request /auth;
    ...
}
```

For every request to `http://staticpage.example.com/`, an internal subrequest to `http://staticpage.example.com/auth` is made.

Let's add this as a location:


```
server {
    ...
    location = /auth {
        internal;
        proxy_pass https://api.example.com;
    }
}
```

Now the request is forwarded to our SSO endpoint (`proxy_pass`). Please note that the path of the location is included in this request, so the request URL becomes `https://api.example.com/auth`.

At this point `api.example.com` is responsible for the authorization. If the request returns a 2xx response code the request is allowed. If it returns 401 or 403, the access is denied.

Let's handle the redirect in case the the SSO API returns http code 401.
With the [error_page](http://nginx.org/en/docs/http/ngx_http_core_module.html#error_page) directive:

```
server {
    ...
    error_page 401 = @error401;
    location @error401 {
        return 302 https://login.example.com;
    }
}
```

If the request is not authorized, we will redirect the user to `https://login.example.com` using status code 302. Here the user gets a proper error message and the chance to authorize.

Now we have to somehow transport the client's authorization token from one system to another.
After being authorized at `login.example.com`, the user gets a cookie containing the auth token. The cookie is set to `.example.com'` so `staticpage.example.com` can also access the token.
All we have to do now it to pass the token from the cookie to the auth backend.

We use `$http_cookie ~* "shopware_sso_token=([^;]+)(?:;|$)"` to match the token from the users cookie, followed by a `proxy_set_header` to pass the token to the backend.

```
location = /auth {
    ...

    if ($http_cookie ~* "shopware_sso_token=([^;]+)(?:;|$)") {
        set $token "$1";
    }
    proxy_set_header X-SHOPWARE-SSO-Token $token;
}
```

## Mission accomplished
Now `api.example.com` is able to decide if the request needs authentication (missing or expired token) and respond with 401 status code. For *authenticated* but not *authorized* users, it responds with a 403 code.
If the user is authenticated and authorized it responds with a 200 code.

## Appendix

```
server {
    server_name staticpage.example.com;

    root /var/www/staticpage.example.com/;

    error_page 401 = @error401;
    location @error401 {
        return 302 https://login.example.com;
    }

    auth_request /auth;

    location = /auth {
        internal;

        proxy_pass https://api.example.com;

        proxy_pass_request_body     off;

        proxy_set_header Content-Length "";
        proxy_set_header X-Original-URI $request_uri;
        proxy_set_header Host $http_host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;

        if ($http_cookie ~* "shopware_sso_token=([^;]+)(?:;|$)") {
            set $token "$1";
        }
        proxy_set_header X-SHOPWARE-SSO-Token $token;
    }
}
```

