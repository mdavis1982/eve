```
  _____           
 | ____|_   _____ 
 |  _| \ \ / / _ \
 | |___ \ V /  __/
 |_____| \_/ \___|
                  
# A Slack bot
```
Eve is a PHP Slack bot for the Larachat community, powered by community contributions.

## To Get Started
In order to get Eve up and running you need the following:

- A Slack Bot API token which can be generated in the admin interface of your Slack account

Once you have the API token, copy the `.env.example` file to `.env` and paste in your bot token.

To run Eve, first install the composer dependencies:

```
composer install
```

And then run the `eve:run` artisan command:

```
php artisan eve:run
```

Eve will connect to your Slack account and will then respond to messages mentioning her and DMs.

## Roadmap
Eve is currently in an early stage, and as such things are changing quickly. Currently, there are a number of commands:

- Hello (`Hi, @eve`)
- Thanks (`Thanks, @eve`)
- Ping (`@eve ping`)
- Pun (`@eve pun`)
- Sandwich (`@eve make me a sandwich`)
- Slap (`@eve slap @someone`, `@eve slap @someone @someonelse`)
- Eight Ball (`@eve 8-ball`)
- Calculate (`@eve calculate 2x + 3y --x=2 --y=4`)
- Giphy (`@eve giphy test`)

The roadmap is as follows:

1. Add throttling:
    - Per-user per-command throttling
    - Per-command global throttling
2. Persistent caching / state
3. User moderation
4. Tests!

As always, the roadmap is open to suggestions from the community and code contributions are welcomed and encouraged! In addition to this, improvements to the code quality and structure / architecture are always welcome additions.

## Contributing
Contribute to Eve by forking the repository, making your changes in your fork and submitting a PR. The code follows some simple coding standards:

- PSR-2 (Mostly :wink:)
- [Object Calisthenics](http://williamdurand.fr/2013/06/03/object-calisthenics/)

These are not 100% strict requirements - if the code looks good, is easy to understand and is focussed and testable then it will be okay.

When contributing code, testing is strongly encouraged: the more we can test now, the less maintainenance problems we will have further down the line!

### Contributors
A number of people have helped Eve to come this far. In no particular order:

- [Alexander Hjorth](https://github.com/ahjorth) (`@pistachio` on Larachat)
- [Andreas Elia](https://github.com/andreaselia) (`@andreaselia` on Larachat)
- [Apostolos Spanos](https://github.com/apspan)
- [Brett Taylor](https://github.com/glutnix) (`@glutnix` on Larachat)
- [Cedric van Putten](https://github.com/bycedric) (`@bycedric` on Larachat)
- [Damon Jones](https://github.com/damonjones)
- [Dan Rovito](https://github.com/danrovito) (`@dan` on Larachat)
- [Jens Eeckhout](https://github.com/jenseeckhout) (`@jense` on Larachat)
- [Rizqi Djamaluddin](https://github.com/rizqidjamaluddin) (`@rizqi` on Larachat)
- [Shawn Mayzes](https://github.com/smayzes) (`@smayzes` on Larachat)

## Have fun!
The goal of Eve is to make it easy and accessible for members of the community to contribute to a project that is actively used by the community.

If there are features you would like to see, submit an issue (after searching first!). Even better is to open an issue and then send a PR.

Above all though, have fun with her.

