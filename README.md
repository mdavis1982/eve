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

- PHP 7.0 or greater
- A Slack Bot API token which can be generated in the admin interface of your Slack account

Once you have the API token, copy the `.env.example` file to `.env` and paste in your bot token.

To run Eve, first install the composer dependencies:

```
composer install
```

And then run the `eve.php` file:

```
php eve.php
```

Eve will connect to your Slack account and will then respond to messages mentioning her and DMs.

## Roadmap
Eve is currently in a very early alpha stage, and as such things are changing quickly. Currently, there are a number of commands:

- Hello (`Hi, @eve`)
- Thanks (`Thanks, @eve`)
- Ping (`@eve ping`)
- Pun (`@eve pun`)
- Sandwich (`@eve make me a sandwich`)
- Slap (`@eve slap @someone`)

The roadmap is as follows:

1. Refactor and make adding commands even easier
2. Add throttling:
    - Per-user per-command throttling
    - Per-command global throttling
3. Make it easier to interact with users
4. Persistent caching / state
5. User moderation
6. Tests!

As always, the roadmap is open to suggestions from the community and code contributions are welcomed and encouraged!

## Contributing
Contribute to Eve by forking the repository, making your changes in your fork and submitting a PR. The code follows some simple coding standards:

- PSR-2 (Mostly :wink:)
- [Object Calisthenics](http://williamdurand.fr/2013/06/03/object-calisthenics/)

These are not 100% strict requirements - if the code looks good, is easy to understand and is focussed and testable then it will be okay.

When contributing code, testing is strongly encouraged: the more we can test now, the less maintainenance problems we will have further down the line!

## Have fun!
The goal of Eve is to make it easy and accessible for members of the community to contribute to a project that is actively used by the community.

If there are features you would like to see, submit an issue (after searching first!). Even better is to open an issue and then send a PR.

Above all though, have fun with her.
