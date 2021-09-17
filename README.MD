# [Gojek] GopayID Api PHP Class (Un-Official)
Repository Berikut Ini Merupakan Porting Dari namdevel (https://github.com/namdevel/ovoid-API) Untuk PHP 

[![CodeFactor](https://www.codefactor.io/repository/github/mugypleci/ovoid/badge)](https://www.codefactor.io/repository/github/mugypleci/ovoid)
[![MIT license](https://img.shields.io/badge/License-MIT-blue.svg)](https://lbesson.mit-license.org/)
[![PHP Libraries](https://badgen.net/badge/icon/libraries?icon=libraries&label)](https://github.com/mugypleci/gopay-api)
[![Open Source Love svg1](https://badges.frapsoft.com/os/v1/open-source.svg?v=103)](https://github.com/mugypleci/gopay-api)
[![HitCount](http://hits.dwyl.com/mugypleci/ovoid.svg)](http://hits.dwyl.com/mugypleci/ovoid)

Compliant with the Sep 17, 2020 OVO API update.

<b>[Fitur Akun Pengguna OVO](#fitur-akun-pengguna-ovo)</b>
------------
- [x] generateUUIDV4
- [x] getDeviceId
- [x] login2FA
- [x] login2FAVerify
- [x] loginSecurityCode
- [x] getWalletTransaction
- [x] isOVO

### Dokumentasi

#### Langkah Untuk Menjalankan OVOID API
##### Ambil Paket Pada Composer
```php
composer require gyugie/gopayid
```

##### Jika Di Jalankan Dengan Native
```php
require 'vendor/autoload.php';
use Gyugie\OVOID;

$gopay = new OVOID();
```

#### Fitur Akun Pengguna OVOID

##### Genereate Device ID
```php
$loginToken = $gopay->generateUUIDV4();
```

```php
$gopay = new GopayID('<device_id>');
```
##### Login Dengan Nomor Handphone
```php
$loginToken = $gopay->login2FA('<mobilePhone>');
```
##### Login Pada OVOID Untuk Mendapatkan OTP REF ID
```php
$authToken = $gopay->login2FAVerify('<otp_ref_id>', '<OTP>', '<phone number>');
```
##### Mendapatkan Acess Token Informasi Akun Pengguna
```php
$result = $gopay->loginSecurityCode('<secutrity_code>', '<phone number>', '<otp_token>');
```
##### Transfer Ke Sesama GOPAY
```php
$gopay = new GopayID('<access_token>');
$result = $gopay->getWalletTransaction('<page>', '<limit>');
```

Contributing
------------

All kinds of contributions are welcome - code, tests, documentation, bug reports, new features, etc...

* Send feedbacks.
* Submit bug reports.
* Write/Edit the documents.
* Fix bugs or add new features.
