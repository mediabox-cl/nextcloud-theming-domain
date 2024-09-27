# Nextcloud - Domain Theming
Customizes the appearance of Nextcloud according to the trusted domain from which it is being accessed.

# Configuration

After the APP installation a new configuration file named `theming.config.php` is created inside the `config` directory. You can use this file to customize the trusted domains (`trusted_domains`) set in the Nextcloud `config.php` file.

### Configuration keys:
`version (string)` = CSS Version (Cache Buster)  
`variables (array)` = Theme variables. Can be used to override the Nextcloud Theme Variables (`:root {...}`).  
`scss (string)` = Path to a custom scss file relative to the `scss` folder inside the APP main folder (`theming_domain`).

_Note: None of these keys are mandatory, you can use them or not._

## Configuration example:

```
<?php

# Domain Theming APP Config file (theming.config.php).

$CONFIG = array(
    'theming_domain' => array( // APP Config Key
        'cloud.domain.tld' => array( // Trusted Domain
            'version' => '1', // CSS Version
            'variables' => array( // Variables to be overrided
                '--image-background-default' => "url('/apps/theming/img/background/tommy-chau-already.jpg')"
            ),
            'scss' => '/ispconfig/style.scss' // Custom SCSS file
        ),
        'cloud.domain2.tld' => array(
            'version' => '355',
            //...
        )
    )
);
```