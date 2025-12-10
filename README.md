# PHP_Laravel12_Create_Custome_Log_File

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-12.x-f72c1f?style=for-the-badge&logo=laravel" />
  <img src="https://img.shields.io/badge/Custom-Logging-green?style=for-the-badge" />
  <img src="https://img.shields.io/badge/File-System-Logs-orange?style=for-the-badge" />
</p>

---

##  Overview  
This guide explains how to create a **custom log file** in Laravel 12 and store different log levels  
(info, warning, error) into a separate file for cleaner debugging.

---

##  Features  
- Custom log channel in `logging.php`  
- Single & daily rotating logs  
- Route-based log writing  
- Auto-creating custom log file  
- JSON-friendly structured logs  

---

##  Folder Structure  
```
config/
│── logging.php

routes/
│── web.php

storage/
└── logs/
    ├── laravel.log
    └── custom.log

.env
README.md
```

---

#  Step 1 — Install Laravel  
```bash
composer create-project laravel/laravel custom-log-demo
cd custom-log-demo
```

---

#  Step 2 — Setup .env File  
```
APP_NAME=CustomLogDemo
APP_ENV=local
APP_DEBUG=true

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=custom_log_demo
DB_USERNAME=root
DB_PASSWORD=

SESSION_DRIVER=file
```

✔ `SESSION_DRIVER=file` prevents session table errors.

---

#  Step 3 — Add Custom Log Channels  

 config/logging.php

```php
'custom' => [
    'driver' => 'single',
    'path' => storage_path('logs/custom.log'),
    'level' => 'debug',
],

'custom_daily' => [
    'driver' => 'daily',
    'path' => storage_path('logs/custom/custom.log'),
    'days' => 7,
    'level' => 'debug',
],
```

✔ `custom` → one file  
✔ `custom_daily` → new file every day  

---

#  Step 4 — Clear Cache  
```bash
php artisan config:clear
php artisan cache:clear
php artisan optimize:clear
php artisan serve
```

---

#  Step 5 — Create Route to Test Logging  

 routes/web.php

```php
<?php

use Illuminate\Support\Facades\Log;

Route::get('/test-log', function () {

    Log::channel('custom')->info('Custom Log: Info message');
    Log::channel('custom')->warning('Custom Log: Warning message');
    Log::channel('custom')->error('Custom Log: Error message');

    return "Custom logs written successfully!";
});
```

✔ Automatically creates:  
```
storage/logs/custom.log
```

---

#  Step 6 — Test in Browser  
Open:

```
http://localhost:8000/test-log
```

Check file:

```
storage/logs/custom.log
```

Expected:

```
[2025-12-10] local.INFO: Custom Log: Info message
[2025-12-10] local.WARNING: Custom Log: Warning message
[2025-12-10] local.ERROR: Custom Log: Error message
```

---

#  Optional — Manual File Write Test  

```php
Route::get('/file-test', function () {
    file_put_contents(
        storage_path('logs/custom.log'),
        "This is a manual test log.\n",
        FILE_APPEND
    );

    return "Manual LOG WRITTEN — check storage/logs/custom.log";
});
```

---


#  Log Anywhere in Laravel  

```php
Log::channel('custom')->info('User logged in');
```

---

#  Your Custom Logging System is Ready!  

✔ Custom log channel  
✔ Clean debugging  
✔ Daily rotating logs  
✔ Easy integration  

---


###SCREENSHOTS:-

Browser Side:-


<img width="409" height="124" alt="Screenshot 2025-12-10 122421" src="https://github.com/user-attachments/assets/09e0bd67-a1d3-4a0a-ba63-42a430bb1eae" />



Custom Log File:-


<img width="696" height="112" alt="Screenshot 2025-12-10 122448" src="https://github.com/user-attachments/assets/51745152-9862-4653-9037-d36f90bada25" />


