---
title: On interfaces traits decorators
tags:
    - interface
    - trait
    - decorator
    - extensibility

categories:
- dev



authors: [bc]
---

- Nginx swiss army knive
- Github
- SSL critical
    - Protect privacy
    - SEO / Google
    - Perfect Forward Secreaty

SSL Offloading
SSL Configuration nginx
karma-jasmine
https://mozilla.github.io/server-side-tls/ssl-config-generator/
https://github.com/bcremer/shopware-with-nginx
https://github.com/cloudflare/sslconfig/blob/master/conf

perfect forward secrecy
https://en.wikipedia.org/wiki/Forward_secrecy

http://en.wikipedia.org/wiki/HTTP_Strict_Transport_Security

https://www.ssllabs.com/ssltest/

## Redirect HTTP to HTTPS and subdomain normalisation

server {
       listen         80;
       server_name    example.com www.example.com;
       return         301 https://$server_name$request_uri;
}

# Enable this if your want HSTS (recommended)
    # add_header Strict-Transport-Security max-age=15768000;


https://wiki.mozilla.org/Security/Server_Side_TLS

http://example.com -> https://example.com
http://www.example.com -> https://example.com


Mozilla Developer Network again:
https://developer.mozilla.org/en-US/docs/Web/HTTP/X-Frame-Options
openssl dhparam -rand - 2048

openssl dhparam -out /etc/ssl/certs/dhparam.pem 4096


# Add HSTS header. This must be sent via HTTPS.
# http://en.wikipedia.org/wiki/HTTP_Strict_Transport_Security
add_header Strict-Transport-Security max-age=31536000;

Strict-Transport-Security "max-age=31536000; includeSubDomains"
X-Frame-Options SAMEORIGIN

# Disable framing
add_header X-Frame-Options DENY;

