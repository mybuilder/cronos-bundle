# Cronos Bundle

[![Build Status](https://travis-ci.org/mybuilder/cronos-bundle.svg?branch=master)](https://travis-ci.org/mybuilder/cronos-bundle)

A bundle for Symfony that allows you to use `@Cron` annotations to configure when cron should run your console commands.

Uses the [Cronos](https://github.com/mybuilder/cronos) library to do the actual output and updating.

## Installation

### Install with composer

Run the composer require command:

``` bash
$ php composer.phar require mybuilder/cronos-bundle
```

### Enable the bundle

Enable the bundle in the `app/AppKernel.php`:

``` php
public function registerBundles() {
    $bundles = array(
        new MyBuilder\Bundle\CronosBundle\MyBuilderCronosBundle(),
    );
}
```

### Configure the bundle

You can add the following to your `config.yml` to specify

```yaml
my_builder_cronos:
    exporter:
        key: unique-key
        mailto: cron@example.com
        path: /bin:/home/gavin/bin
        executor: php
        console: app/console
        shell: /bin/bash
```

option   | description
---------|-----------------------------------------
key      | Unique key that wraps all the cron configured for the current application
mailto   | Sets the default email address for all cron output to go to.
path     | Sets the path for all commands in the crontab it works just like the shell PATH, but it does not inherit from your environment. That means you cannot use ~ or other shell expansions.
executor | Allows you to specify a program that all commands should be passed to such as `/usr/local/bin/php`
console  | Allows you to specify the console that all commands should be passed to such as `app/console`
shell    | Allows you to specify which shell each program should be run with.

## Usage

The first step is to add the use case for the annotation to the top of the command you want to use the @Cron annotations in.

```php
use MyBuilder\Bundle\CronosBundle\Annotation\Cron;
```

Then add to the phpdoc for the command class the '@Cron' annotation which tells cron when you want it to run
This example says it should be run on the web server, every 5 minutes and we don't want to log any output.

```php
/**
 * Command for sending our email messages from the database.
 *
 * @Cron(minute="/5", noLogs=true, server="web")
 */
class SendQueuedEmailsCommand extends Command {}
```

### Specifying when to run
The whole point of cron is being able to specify when a script is run therefore there are a lot of options.

You should read the [general cron info](http://en.wikipedia.org/wiki/Cron) for a general idea of
cron and what you can use in these time fields.

**Please note** You CANNOT use `*/` in the annotations, if you want `*/5` just put `/5` and [Cronos](https://github.com/mybuilder/cronos)
will automatically change it to `*/5`.

### Annotation examples

annotation                                               | description
---------------------------------------------------------|------------------------------------------
`@Cron(minute="/5")`                                     | Every 5 minutes
`@Cron(minute="5")`                                      | At the 5th minute of each hour
`@Cron(minute="5", hour="8")`                            | 5 minutes past 8am every day
`@Cron(minute="5", hour="8", dayOfWeek="0")`             | 5 minutes past 8am every Sunday
`@Cron(minute="5", hour="8", dayOfMonth="1")`            | 5 minutes past 8am on first of each month
`@Cron(minute="5", hour="8", dayOfMonth="1", month="1")` | 5 minutes past 8am on first of of January
`@Cron(minute="/5", params="--user=barman")`             | Every 5 minutes, with a custom param

## Building the cron

You should run `app/console cronos:dump` and review what the cron file would look after it has been updated.
If everything looks ok you can replace your crontab by running the command below.

`app/console cronos:replace`

You can also limit which commands are included in the cron file by specifying a server and it will then only show
commands which are specified for that server.

### Exporting the cron

    app/console cronos:dump --server=web
    app/console cronos:replace --server=web

### Environment

You can choose which environment you want to run the commands in cron under like this

`app/console cronos:replace --server=web --env=prod`

## Troubleshooting

* When a cron line is executed it is executed with the user that owns the crontab, but it will not execute any of the users default shell files so all paths etc need to be specified in the command called from the cron line.
* Your crontab will not be executed if you do not have useable shell in /etc/passwd
* If your jobs don't seem to be running check that the cron deamon is running, also check your username is in /etc/cron.allow and not in /etc/cron.deny.
* Environmental substitutions do not work, you can not use things like $PATH, $HOME, or ~/sbin.
 

---

Created by [MyBuilder](http://www.mybuilder.com/) - Check out our [blog](http://tech.mybuilder.com/) for more insight into this and other open-source projects we release.
