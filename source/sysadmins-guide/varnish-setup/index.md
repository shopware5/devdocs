---
layout: default
title: Varnish Setup
github_link: sysadmins-guide/varnish-setup/index.md
shopware_version: 4.3.3
tags:
  - performance
  - varnish
indexed: true
group: System Guides
menu_title: Varnish setup
menu_order: 70
---

<div class="toc-list"></div>

## Support
Please note that shopware AG only supports Varnish cache configuration for customers with Shopware Enterprise licenses.

## Requirements
This configuration requires at least version 4.0 of Varnish and at least version 4.3.3 of Shopware.

## Shopware configuration

### Disable the inbuilt reverse proxy
The PHP based reverse proxy has to be disabled, which can be done by adding the following section to your `config.php`:

```
'httpcache' => array(
    'enabled' => false,
),
```

### Configure Trusted Proxies
If you have a reverse proxy in front of your Shopware installation, you have to set the IP of the proxy in the `trustedProxies` section in your `config.php`:

```
'trustedProxies' => array(
    '127.0.0.1'
)
```

### TLS Termination

Varnish does not support SSL/TLS ([Why no SSL?](https://www.varnish-cache.org/docs/trunk/phk/ssl.html#phk-ssl)).
To support TLS requests, a [TLS termination proxy](https://en.wikipedia.org/wiki/TLS_termination_proxy) like nginx or HAProxy has to handle incoming TLS connections and forward them to Varnish.

You can configure Varnish to use port 80 and handle unencrypted requests directly.


```
# /etc/default/varnish
DAEMON_OPTS="-a :80 \
             -T localhost:6082 \
             -f /etc/varnish/default.vcl \
             -S /etc/varnish/secret \
             -s malloc,256m"
```

**Traffic flow:**

```
Internet ▶ 0.0.0.0:443 (nginx/TLS Termination) ▶ 0.0.0.0:80 (Varnish Cache) ▶ 127.0.0.1:8080 (Apache/Shopware)
Internet ▶ 0.0.0.0:80 (Varnish/Caching) ▶ 127.0.0.1:8080 (Apache/Shopware)
```

Or you can forward unencrypted traffic to the secure port via HTTP 301. In this case, all incoming traffic is handled by the reverse proxy upfront and Varnish can run on port 6081 on localhost.


```
# /etc/default/varnish
DAEMON_OPTS="-a :6081 \
             -T localhost:6082 \
             -f /etc/varnish/default.vcl \
             -S /etc/varnish/secret \
             -s malloc,256m"
```

**Traffic flow:**

```
Internet ▶ 0.0.0.0:80 (nginx/forward to TLS) ▶ 0.0.0.0:443 via HTTP 301 (TLS Only)
Internet ▶ 0.0.0.0:443 (nginx/TLS Termination) ▶ 127.0.0.1:6081 (Varnish Cache) ▶ 127.0.0.1:8080 (Apache/Shopware)
```

#### Forward HTTP Headers
The reverse proxy has to forward headers to to Varnish:

```nginx
server {
   listen         80;
   server_name    example.com www.example.com;
   return         301 https://$server_name$request_uri;
}

server {
    listen 443 ssl;
    server_name example.com;

    # Server certificate and key.
    ssl_certificate /etc/nginx/ssl/example.com.crt;
    ssl_certificate_key /etc/nginx/ssl/example.com.crt;

    location / {
        # Forward request to Varnish.
        proxy_pass  http://127.0.0.1:6081; // change to port 80 if varnish is running upfront
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto https;

        proxy_redirect off;
    }
}
```

For a secure TLS (SSL) you can use the [Mozilla SSL Configuration Generator](https://mozilla.github.io/server-side-tls/ssl-config-generator/).


### Enable cache plugin
The Shopware HTTP Cache Plugin has to be activated, to activate follow the these steps in your Shopware Backend:

`Configuration -> Caches / Performance -> Settings -> HTTP Cache -> Activate HTTP cache`

## Varnish configuration (vcl)

```
# Shopware Varnish Configuration
# Copyright © shopware AG

vcl 4.0;

import std;

backend default {
    .host = "127.0.0.1";
    .port = "8080";
}

# ACL for purgers IP.
# Provide here IP addresses that are allowed to send PURGE requests.
# PURGE requests will be sent by the backend.
acl purgers {
    "127.0.0.1";
    "localhost";
    "::1";
}

sub vcl_recv {
    # Mitigate httpoxy application vulnerability, see: https://httpoxy.org/
    unset req.http.Proxy;

    # Strip query strings only needed by browser javascript. Customize to used tags.
    if (req.url ~ "(\?|&)(pk_campaign|piwik_campaign|pk_kwd|piwik_kwd|pk_keyword|pixelId|kwid|kw|adid|chl|dv|nk|pa|camid|adgid|cx|ie|cof|siteurl|utm_[a-z]+|_ga|gclid)=") {
        # see rfc3986#section-2.3 "Unreserved Characters" for regex
        set req.url = regsuball(req.url, "(pk_campaign|piwik_campaign|pk_kwd|piwik_kwd|pk_keyword|pixelId|kwid|kw|adid|chl|dv|nk|pa|camid|adgid|cx|ie|cof|siteurl|utm_[a-z]+|_ga|gclid)=[A-Za-z0-9\-\_\.\~]+&?", "");
    }
    set req.url = regsub(req.url, "(\?|\?&|&)$", "");

    # Normalize query arguments
    set req.url = std.querysort(req.url);

    # Set a header announcing Surrogate Capability to the origin
    set req.http.Surrogate-Capability = "shopware=ESI/1.0";

    # Make sure that the client ip is forward to the client.
    if (req.http.x-forwarded-for) {
        set req.http.X-Forwarded-For = req.http.X-Forwarded-For + ", " + client.ip;
    } else {
        set req.http.X-Forwarded-For = client.ip;
    }

    # Handle PURGE
    if (req.method == "PURGE") {
        if (!client.ip ~ purgers) {
            return (synth(405, "Method not allowed"));
        }

        return (purge);
    }

    # Handle BAN
    if (req.method == "BAN") {
        if (!client.ip ~ purgers) {
            return (synth(405, "Method not allowed"));
        }

        if (req.http.X-Shopware-Invalidates) {
            ban("obj.http.X-Shopware-Cache-Id ~ " + ";" + req.http.X-Shopware-Invalidates + ";");
            return (synth(200, "BAN of content connected to the X-Shopware-Cache-Id (" + req.http.X-Shopware-Invalidates + ") done."));
        } else {
            ban("req.url ~ "+req.url);
            return (synth(200, "BAN URLs containing (" + req.url + ") done."));
        }
    }

    # Normalize Accept-Encoding header
    # straight from the manual: https://www.varnish-cache.org/docs/3.0/tutorial/vary.html
    if (req.http.Accept-Encoding) {
        if (req.url ~ "\.(jpg|png|gif|gz|tgz|bz2|tbz|mp3|ogg)$") {
            # No point in compressing these
            unset req.http.Accept-Encoding;
        } elsif (req.http.Accept-Encoding ~ "gzip") {
            set req.http.Accept-Encoding = "gzip";
        } elsif (req.http.Accept-Encoding ~ "deflate") {
            set req.http.Accept-Encoding = "deflate";
        } else {
            # unkown algorithm
            unset req.http.Accept-Encoding;
        }
    }

    # Fix ConflictingHeadersException with opera mini
    # https://github.com/contao/standard-edition/issues/45
    if (req.http.Forwarded) {
        unset req.http.Forwarded;
    }

    if (req.method != "GET" &&
        req.method != "HEAD" &&
        req.method != "PUT" &&
        req.method != "POST" &&
        req.method != "TRACE" &&
        req.method != "OPTIONS" &&
        req.method != "DELETE") {
        /* Non-RFC2616 or CONNECT which is weird. */
        return (pipe);
    }

    # We only deal with GET and HEAD by default
    if (req.method != "GET" && req.method != "HEAD") {
        return (pass);
    }

    # Don't cache Authenticate & Authorization
    if (req.http.Authenticate || req.http.Authorization) {
        return (pass);
    }

    # Don't cache selfhealing-redirect
    if (req.http.Cookie ~ "ShopwarePluginsCoreSelfHealingRedirect") {
        return (pass);
    }

    # Always pass these paths directly to php without caching
    # Note: virtual URLs might bypass this rule (e.g. /en/checkout)
    if (req.url ~ "^/(checkout|account|backend)(/.*)?$") {
        return (pass);
    }
    
    # Workaround for Basket Widget Caching and Compare. Will be fixed with https://issues.shopware.com/issues/SW-23673
    if (req.url ~ "^/\?module=widgets&controller=checkout&action=info$" || req.url ~ "^/\?module=widgets&controller=compare$") {
        return (pass);
    }

    return (hash);
}

sub vcl_hash {
    ## normalize shop and currency cookie in hash to improve hitrate
    if (req.http.cookie ~ "shop=") {
        hash_data("+shop=" + regsub(req.http.cookie, "^.*?shop=([^;]*);*.*$", "\1"));
    } else {
        hash_data("+shop=1");
    }

    if (req.http.cookie ~ "currency=") {
        hash_data("+currency=" + regsub(req.http.cookie, "^.*?currency=([^;]*);*.*$", "\1"));
    } else {
        hash_data("+currency=1");
    }

    if (req.http.cookie ~ "x-cache-context-hash=") {
        hash_data("+context=" + regsub(req.http.cookie, "^.*?x-cache-context-hash=([^;]*);*.*$", "\1"));
    }
}

sub vcl_hit {
    if (obj.http.X-Shopware-Allow-Nocache && req.http.cookie ~ "nocache=") {
        if (obj.http.X-Shopware-Allow-Nocache && req.http.cookie ~ "slt=") {
            set req.http.X-Cookie-Nocache = regsub(req.http.Cookie, "^.*?nocache=([^;]*);*.*$", "\1, slt");
        } else {
            set req.http.X-Cookie-Nocache = regsub(req.http.Cookie, "^.*?nocache=([^;]*);*.*$", "\1");
        }
        if (std.strstr(req.http.X-Cookie-Nocache, obj.http.X-Shopware-Allow-Nocache)) {
            return (pass);
        }
    }
}

sub vcl_backend_response {
    # Fix Vary Header in some cases
    # https://www.varnish-cache.org/trac/wiki/VCLExampleFixupVary
    if (beresp.http.Vary ~ "User-Agent") {
        set beresp.http.Vary = regsub(beresp.http.Vary, ",? *User-Agent *", "");
        set beresp.http.Vary = regsub(beresp.http.Vary, "^, *", "");
        if (beresp.http.Vary == "") {
            unset beresp.http.Vary;
        }
    }

    # Enable ESI only if the backend responds with an ESI header
    # Unset the Surrogate Control header and do ESI
    if (beresp.http.Surrogate-Control ~ "ESI/1.0") {
        unset beresp.http.Surrogate-Control;
        set beresp.do_esi = true;
        return (deliver);
    }

    # Respect the Cache-Control=private header from the backend
    if (
        beresp.http.Pragma        ~ "no-cache" ||
        beresp.http.Cache-Control ~ "no-cache" ||
        beresp.http.Cache-Control ~ "private"
    ) {
        set beresp.ttl = 0s;
        set beresp.http.X-Cacheable = "NO:Cache-Control=private";
        # set beresp.ttl = 120s;
        set beresp.uncacheable = true;
        return (deliver);
    }

    # strip the cookie before the image is inserted into cache.
    if (bereq.url ~ "\.(png|gif|jpg|swf|css|js)$") {
        unset beresp.http.set-cookie;
    }

    # Allow items to be stale if needed.
    set beresp.grace = 6h;

    # Save the bereq.url so bans work efficiently
    set beresp.http.x-url = bereq.url;
    set beresp.http.X-Cacheable = "YES";

    return (deliver);
}

sub vcl_deliver {
    ## we don't want the client to cache
    set resp.http.Cache-Control = "max-age=0, private";

    ## unset the headers, thus remove them from the response the client sees
    unset resp.http.X-Shopware-Allow-Nocache;
    unset resp.http.X-Shopware-Cache-Id;
    
    # remove link header, if session is already started to save client resources
    if (req.http.cookie ~ "session-") {
    	unset resp.http.Link;
    }

    # Set a cache header to allow us to inspect the response headers during testing
    if (obj.hits > 0) {
        unset resp.http.set-cookie;
        set resp.http.X-Cache = "HIT";
    }  else {
        set resp.http.X-Cache = "MISS";
    }

    set resp.http.X-Cache-Hits = obj.hits;
}
```

## Common issues
### Images are not loaded via SSL or the IP address of the customer is not correct.
The proxy is not recognized as a "[trusted proxy](https://developers.shopware.com/sysadmins-guide/varnish-setup/#configure-trusted-proxies)". More information about debugging is available here:
[Symfony and a Reverse Proxy](http://symfony.com/doc/current/request/load_balancer_reverse_proxy.html)

### Error message "Reverse proxy returned invalid status code"
This message appears when automatic cache invalidation fails. A proxy (mostly the SSL Proxy) didn't forward the BAN or PURGE request to the cache. Storing the cache proxy's IP (e.g. http://127.0.01/) should solve the problem. [Backend configuration](/developers-guide/http-cache/#backend)
If the problem still persists, investigate on the actual status code the proxy returns. Code 405 indicates, that the appserver is not permitted to purge the cache, code 404 indicates, that the proxy's IP is wrong or not accessible by the appserver.

### Varnish has no hits when using HTTP Authenticate or Authorization

If you are using any kind of HTTP authentication or authorisation please be aware, that by default our Varnish configuration ignores these request and does not cache them! If you want to use Varnish combined with HTTP authentication, you can use a webserver which handles the authentication beforehand and unsets the corresponding headers ("Authenticate" and "Authorization").
