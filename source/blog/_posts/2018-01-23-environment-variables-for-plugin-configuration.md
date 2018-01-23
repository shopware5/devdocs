---
title: How to use Environment Variables for Plugin Configuration
tags:
- environment
- deployment
- configuration

categories:
- dev

authors: [tn]
github_link: blog/_posts/2018-01-23-environment-variables-for-plugin-configuration.md

---

<div class="alert alert-info">
Anyone who is just searching for the example source code and does not want to read the complete blog post, <a href="https://github.com/teiling88/shopware-environment-variables">here it is.</a>
</div>

This blog post describes a small proof of concept how we can handle different plugin configurations for multi stage environments. I use our Paypal plugin as an example and will overwrite the two config values `paypalUsername` and `paypalPassword`.

## Preparation
At first, we have to create our environment variables. For a quick test, I added the environment variables to my `.htaccess` file:

```
SetEnv paypalUsername EnvPayPalUsername
SetEnv paypalPassword EnvPaypalPassword
```

I recommend for a production environment to set this kind of environment variables in your vhosts configuration file to protect you for unintentionally changes. After creating our environment variables we should make them available via the dependency injection container. 
You have to add the following lines to your config.php:

```php
<?php return [
    'db' => [...],
    'custom' =>
        [
            'paypalUsername' => getenv('paypalUsername'),
            'paypalPassword' => getenv('paypalPassword'),
        ],
];
```

With this addition our environment variables are available as parameter in the %shopware.custom% array.  

## Plugin Installation
In the last step we can install the ShopwareEnvironmentVariables plugin and extend our mapping in: [Reader](https://github.com/teiling88/shopware-environment-variables/blob/master/Reader.php#L35) If you don't know the name of the configuration element, you can easily create a small debug statement in the Reader.php. 


# Conclusion

This is a very small proof of concept how to overwrite plugin configurations by environment variables. **Nothing more**. You can extends this proof of concept to overwrite other configurations as well. 
