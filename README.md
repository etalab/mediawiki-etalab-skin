Mediawiki ETALAB Skin
=====================

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


## Customizations

You can customize the skin behavior with the following options

### ETALAB Home site url

Customize the base URL used to feed the sidebar links and search
by setting the ``$wgEtalabHomeUrl`` in your ``LocalSettings.php``.

Default value: 'http://data.gouv.fr'

### ETALAB Wiki site url

Customize the base URL used to feed the sidebar links and search
by setting the ``$wgEtalabWikiUrl`` in your ``LocalSettings.php``.

Default value: 'http://wiki.data.gouv.fr/wiki'

### ETALAB Wiki API url

Customize the URL used by the search autocomplete
by setting the ``$wgEtalabWikiAPIUrl`` in your ``LocalSettings.php``.

Default value: 'http://wiki.data.gouv.fr/api.php'

### ETALAB Questions site url

Customize the base URL used to feed the sidebar links and search
by setting the ``$wgEtalabQuestionsUrl`` in your ``LocalSettings.php``.

Default value: 'http://questions.data.gouv.fr'

### Favicon

Customize the favicon by setting the ``$wgFavicon`` variable:

```php
// If etalab is your default skin
$wgFavicon = "$wgStylePath/$wgDefaultSkin/img/favicon.png";
```


### Settings the theme to existing users

```console
$ cd maintenance
$ php userOptions.php skin --old "vector" --new "etalab"
```


## Hacking

We use bower, grunt, uglify and less to build assets so you need to have them installed:

```console
$ sudo npm install -g bower less uglifyjs grunt-cli
```


Install needed dependencies:

```console
$ npm install && bower install
```

Build the assets:

```console
$ grunt
```

