@echo off
title Khoi dong Web Doan Thanh Nien
echo ===================================================
echo     DANG KHOI DONG SERVER LARAVEL (YOUTH UNION)
echo ===================================================
echo.
echo May chu dang chay tai: http://127.0.0.1:8888
echo Dang mo trinh duyet...
echo.
start http://127.0.0.1:8888/hoat-dong
php -S 127.0.0.1:8888 -t public
pause
