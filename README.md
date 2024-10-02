# Nextcloud - Domain Theming
Customizes the appearance of **Nextcloud** according to the trusted domain from which it is being accessed.

_Note: This application is intended for use by a system administrator as it has no user interface._

## Installation

### Automatic installation (recommended)

Just install it from your **Nextcloud** application catalogue.

### Manual installation

Clone this repository into your **Nextcloud** apps directory:

```bash
cd /var/www/nextcloud/site/apps/
git clone https://github.com/mediabox-cl/nextcloud-theming-domain theming_domain
```
Install it as usual from admin app list or CLI with:

```bash
cd ..
sudo -u www-data php occ app:install theming_domain
sudo -u www-data php occ app:enable theming_domain
```

# Configuration

After installing the application, a new configuration file called `theming.domain.config.php` will be created inside the `config` directory. You can use this file to customize the trusted domains (`trusted_domains`) configured in **Nextcloud's** `config.php` file.

### Configuration keys:
`version (integer|string)` = CSS Version (Cache Buster)  
`variables (array)` = Theme variables. Can be used to override the **Nextcloud** Theme Variables (`:root {...}`).  
`scss (string)` = Path to a custom scss file relative to the `scss` folder inside the APP main folder (`theming_domain`).

_Note: None of these keys are mandatory, you can use them or not._

### Configuration example:

```
<?php

# Domain Theming APP Config file (theming.domain.config.php).

$CONFIG = array(
    'theming_domain' => array( // APP Config Key
        'cloud.domain.tld' => array( // Trusted Domain
            'version' => 1, // CSS Version
            'variables' => array( // Variables to be overrided
                '--image-background-default' => "url('/apps/theming/img/background/tommy-chau-already.jpg')"
            ),
            'scss' => '/default/style.scss' // Custom SCSS file
        ),
        'cloud.domain2.tld' => array(
            'version' => 355,
            //...
        )
    )
);
```

## Thanks to:

- The **Nextcloud** community and developers.
