
#!/bin/bash
set -e


php -d error_reporting="E_ALL & ~E_DEPRECATED & ~E_NOTICE" artisan optimize:clear
php -d error_reporting="E_ALL & ~E_DEPRECATED & ~E_NOTICE" artisan optimize