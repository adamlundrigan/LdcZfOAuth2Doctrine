LdcZfOAuth2Doctrine
=================

---
[![Latest Stable Version](https://poser.pugx.org/adamlundrigan/ldc-zf-oauth2-doctrine/v/stable.svg)](https://packagist.org/packages/adamlundrigan/ldc-zf-oauth2-doctrine) [![Total Downloads](https://poser.pugx.org/adamlundrigan/ldc-zf-oauth2-doctrine/downloads.svg)](https://packagist.org/packages/adamlundrigan/ldc-zf-oauth2-doctrine) [![Latest Unstable Version](https://poser.pugx.org/adamlundrigan/ldc-zf-oauth2-doctrine/v/unstable.svg)](https://packagist.org/packages/adamlundrigan/ldc-zf-oauth2-doctrine) [![License](https://poser.pugx.org/adamlundrigan/ldc-zf-oauth2-doctrine/license.svg)](https://packagist.org/packages/adamlundrigan/ldc-zf-oauth2-doctrine)
[![Build Status](https://travis-ci.org/adamlundrigan/LdcZfOAuth2Doctrine.svg?branch=master)](https://travis-ci.org/adamlundrigan/LdcZfOAuth2Doctrine)
[![Code Coverage](https://scrutinizer-ci.com/g/adamlundrigan/LdcZfOAuth2Doctrine/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/adamlundrigan/LdcZfOAuth2Doctrine/?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/adamlundrigan/LdcZfOAuth2Doctrine/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/adamlundrigan/LdcZfOAuth2Doctrine/?branch=master)

---

## What?


LdcZfOAuth2Doctrine implements `zf-oauth2` tables as Doctrine ORM entities.  Easily link up any ZfcUser-compatible account entity and bingo-bango they can now authenticate via OAuth2.

__WARNING__: This code is not yet tested, documented or been used in a live environment.  Approach with extreme caution.

## How?

1. Install the [Composer](https://getcomposer.org/) package:

    ```
    composer require adamlundrigan/ldc-zf-oauth2-doctrine:dev-master@dev
    ```

2. Copy the `config/ldc-zf-oauth2-doctrine.local.php.dist` file to you application's `config/autoload` folder and modify to suit. 

3. Enable the module (`LdcZfOAuth2Doctrine`) in your ZF2 application.

