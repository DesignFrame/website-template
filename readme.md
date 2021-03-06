# Website Repository

This is a website repository. It houses all of the files and configuration necessary to create a duplicate of a site.

## Setup

1. Clone this repository
1. Run `docker-compose up -d && docker-compose run composer composer install`. This will setup the container, and install
   the libraries included in `composer.json`
1. Visit `localhost:3000` to set up and run this website.

## Set up Composer authentication

In-order to access private repositories on GitHub, you must add an auth token to `auth.json` first.

1. Copy `auth-template.json` as `auth.json`
1. visit [this page](https://github.com/settings/tokens/new?scopes=repo&description=composer+dfs-template) to create a new token
1. Copy the token set the value of `github.com` to your token.

## Composer

You can install Composer packages, as well as WordPress plugins and themes with Composer. All plugins and themes should
be represented in the Composer file, except for custom plugins and themes that are _specific to this site_.

Most plugins and themes can be fetched using [WPPackagist](https://wpackagist.org/), and custom plugins accessed via VCS.

For example, if you wanted to add Yoast SEO to this site, you could accomplish this by adding this to your `composer.json`
file's `require` object:

```JSON
"require":{
"wpackagist-plugin/wordpress-seo": "*"
}
```

Once the package is added to `composer.json`, install it by running `docker-compose run composer composer update`.

Private repositories require a little more. It will not work unless you have set up your `auth.json` file, detailed [here](#set-up-composer-authentication).

```json
  "repositories":[
    ...
    {
      "type": "vcs",
      "url": "git@github.com:DesignFrame-Registry/repository-name'",
      "reference": "master"
    }
    ...
  ],
```

Require would have something like:

```json
  "require": {
    ...
    "designframe/repository-name": "dev-master",
  }
```

The package name in require is always equal to the package name defined in the repository you're trying to pull's
`composer.json` file. Check out [Composer's Documentation](https://getcomposer.org/doc/05-repositories.md#vcs)
for more info on how to set up a VCS as a source for Composer.


## WP CLI

This site _does_ support WP CLI, however it works a little differently than how you may be adjusted. Instead of running
`wp <command>` directly, you must run your command against the Docker container, like so:

`docker-compose run cli <command>`

This _does_ work within a container like everything else, so if you want to actually use local files directly, you'll
first have to add the directory containing those files to your list of volumes, much like how [custom plugins or themes
are added](#adding-a-custom-plugin-or-theme)

## Adding a Custom Plugin or Theme

A common situation for a custom site is to have custom themes and plugins. You'll notice that there is no `wp-content`
directory in this repository, and that's because it's built directly into the WordPress container. This is done this way to
keep the files in this repository minimal. Only files that are _custom to this website_ or _configuration for this site_
should exist in this repository.

This plugin automatically includes the [Underpin](https://github.com/alexstandiford/underpin) library as a WordPress
Must-Use plugin. This makes it possible to use the [Underpin Plugin Boilerplate](https://github.com/alexstandiford/underpin-plugin-boilerplate) and
[Underpin Theme Boilerplate](https://github.com/alexstandiford/underpin-theme-boilerplate) to create custom themes and
plugins for this site.

To add a plugin to your site, follow these steps:

1. Create a directory for your custom plugin inside this repository, and set up your plugin just like you normally would
   if it were to be built directly in WordPress' `wp-content/plugins` directory.

Set up the Underpin plugin boilerplate, and remove the git history.
```bash
git clone git@github.com:alexstandiford/underpin-plugin-boilerplate ./plugin-name && cd ./plugin-name && rm -rf .git
```

2. Update `docker-compose.yml`, and add a new volume to your WordPress container, and link it to the directory
   in which the files should be placed.

For example, if your plugin directory is `./plugin-name`, the `volumes` in the `wordpress` container would look like this:

```yml
    volumes:
       - ./plugin-name:/var/www/html/wp-content/plugins/plugin-name
```

This works in the same fashion with themes, only instead of adding it to the `plugins`, you would add it to the `themes`
directory when adding the new image.

3. Restart your server with `docker-compose up -d`
