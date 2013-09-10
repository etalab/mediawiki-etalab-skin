Mediawiki ETALAB
================

MediaWiki skin for ETALAB

## Requirements

* Webserver with PHP (needed for MediaWiki)
* MediaWiki 1.18.1 or higher (1.18.1 was the first release with jQuery 1.7+, needed for Bootstrap)


## Installation

1. Change to the "skins" subdirectory of your MediaWiki installation:

   ```
   cd skins
   ```

2. Clone the repository:

   ```
   git clone https://github.com/etalab/mediawiki-etalab-skin etalab
   ```

3. Add the following to `LocalSettings.php`:

   ```php
   require_once( "$IP/skins/etalab/etalab.php" );
   $wgDefaultSkin = "etalab";
   ```

   (You may safely remove or comment out other mentions of
   `$wgDefaultSkin`.)

4. Customize behavior with variables. (See below)


## Custom options

You can customize the skin behavior with the following options

### ETALAB Data site url

Customize the base URL used to feed the sidebar links and search
by setting the ``$wgEtalabDataUrl`` in your ``LocalSettings.php``.

Default value: 'http://data.gouv.fr'


```php
$wgEtalabDataUrl = 'http://www.etalab2.fr';
```


## Hacking

We use bower, grunt, uglify and less to build assets so yee need to have them installed:

```
sudo npm install -g bower less uglifyjs grunt-cli
```


Install needed dependencies:

```
npm install && bower install
```

Build the assets:

```
grunt
```

