[English](README.md) | [日本語](README.ja.md)

# Runtrip Linker

Runtripでの投稿をSNS(X/Twitter)にも反映するアプリケーション。

## 概要

定期的に自身のRuntripをチェックし、新しい投稿があればその内容をX/Twitterにも投稿します。  
投稿されるテキストは以下の通りです。文字数が上限を超える場合、ジャーナル本文が一部省略されます。

```
[ジャーナル本文]
[ハッシュタグ]
[ジャーナルへのURL]
```

## 依存環境

- PHP >= 8.0
- [vlucas/phpdotenv](https://github.com/vlucas/phpdotenv)
- [abraham/twitteroauth](https://github.com/abraham/twitteroauth)
  - ext-curl
  - ext-openssl
- [nojimage/twitter-text-php](https://github.com/nojimage/twitter-text-php)
  - ext-mbstring
  - ext-intl

## 使い方

### 1. 依存関係のインストール

[Composer](https://getcomposer.org/)をダウンロードし、インストールします。
その後、以下のコマンドを実行し、依存関係をインストールします。

```sh
$ composer install
```

> [!IMPORTANT]
> 一部のライブラリを改変しているため、`composer require` でインストールしないでください。

### 2. `.env` ファイルの設定

`.env.example` ファイルを参考に `.env` ファイルを作成し、環境に合わせて設定します。

> [!IMPORTANT]
> `.env` ファイルには機密情報を含めるため、必ず外部から見えないようにしてください。

#### Runtrip関係の設定

RuntripのユーザーIDを設定します。

```env
RUNTRIP_USER_ID=
```

#### X/Twitter関係の設定

[X/Twitter Developer Portal](https://developer.twitter.com/en/portal/petition/essential/basic-info)から以下の認証情報を取得し、設定します。

```env
TWITTER_API_KEY=""
TWITTER_API_KEY_SECRET=""
TWITTER_ACCESS_TOKEN=""
TWITTER_ACCESS_TOKEN_SECRET=""
```

#### その他の設定

必要に応じて、新着投稿の確認周期を設定します。デフォルトは600秒です。

```env
CHECK_INTERVAL=600
```

### 3. cronの設定

cronによってシステム起動時に `index.php` ファイルが実行されるよう設定します。

```cron
@reboot /[absolute path]/php /[absolute path]/index.php
```

## ライセンス

このソフトウェアは[Unlicense](LICENSE)に基づいてライセンスされています。
