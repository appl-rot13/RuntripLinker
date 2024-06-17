[English](README.md) | [日本語](README.ja.md)

# Runtrip Linker

An application that posts on Runtrip to social media(X/Twitter).

## Overview

Periodically check your Runtrip, and post any new journals to X/Twitter.  
The posted text will be as follows. If the character limit is exceeded, parts of the journal text will be omitted.

```
[Journal text]
[Hashtags]
[Journal URL]
```

## Requirements

- PHP >= 8.0
- [vlucas/phpdotenv](https://github.com/vlucas/phpdotenv)
- [abraham/twitteroauth](https://github.com/abraham/twitteroauth)
  - ext-curl
  - ext-openssl
- [nojimage/twitter-text-php](https://github.com/nojimage/twitter-text-php)
  - ext-mbstring
  - ext-intl

## Usage

### 1. Install Dependencies

Download and install [Composer](https://getcomposer.org/).
Then, run the following command to install the dependencies.

```sh
$ composer install
```

> [!IMPORTANT]
> Do **NOT** install via `composer require` because some libraries have been modified.

### 2. Setting Up `.env` File

Create a `.env` file based on the `.env.example` file and set it up to match your environment.

> [!IMPORTANT]
> Ensure that the `.env` file is protected from external visibility as it contains confidential information.

#### Runtrip Settings

Set your Runtrip user ID.

```env
RUNTRIP_USER_ID=
```

#### X/Twitter Settings

Obtain and set the following credentials from [X/Twitter Developer Portal](https://developer.twitter.com/en/portal/petition/essential/basic-info).

```env
TWITTER_API_KEY=""
TWITTER_API_KEY_SECRET=""
TWITTER_ACCESS_TOKEN=""
TWITTER_ACCESS_TOKEN_SECRET=""
```

#### Other Settings

Set the interval to check for new posts as needed. The default is 600 seconds.

```env
CHECK_INTERVAL=600
```

### 3. Configuring Cron

Configure the `index.php` file to execute at system startup using cron.

```cron
@reboot /[absolute path]/php /[absolute path]/index.php
```

## License

This software is licensed under the [Unlicense](LICENSE).
