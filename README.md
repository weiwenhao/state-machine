
## 介绍

laravel state machine manager

## 安装

    composer require weiwenhao/

If you are using Laravel 5.5+, there is no need to manually register the service provider. However, if you are using an earlier version of Laravel, register the `StateMachineServiceProvider` in your `app.php` configuration file:

```php
'providers' => [
    // Other service providers...

    Weiwenhao\StateMachine\StateMachineServiceProvider::class,
],
```
