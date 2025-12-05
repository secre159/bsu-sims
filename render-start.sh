#!/usr/bin/env bash

# Start PHP built-in server
php artisan serve --host=0.0.0.0 --port=${PORT:-8080}
