@echo off
cd /d "C:\xampp"

REM Start Apache & MySQL silently
start "" /min cmd /c "apache_start.bat >nul 2>&1"
start "" /min cmd /c "mysql_start.bat >nul 2>&1"
timeout /t 5 >nul

REM Start cafe Laravel project
cd /d "C:\xampp\htdocs\cafe-software"
start "" /min cmd /c "php artisan serve --port=8000 >nul 2>&1"

REM Open browser as desktop app
timeout /t 3 >nul
start "" "C:\Program Files\Google\Chrome\Application\chrome.exe" --app=http://127.0.0.1:8000 --window-size=1400,900
exit