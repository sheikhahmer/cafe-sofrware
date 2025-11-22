Set WshShell = CreateObject("WScript.Shell")

WshShell.Run "cmd /c C:\xampp\apache_start.bat", 0, False
WshShell.Run "cmd /c C:\xampp\mysql_start.bat", 0, False

WScript.Sleep 5000

WshShell.Run "cmd /c cd /d C:\xampp\htdocs\cafe-software && php artisan serve --port=8000", 0, False

WScript.Sleep 3000
WshShell.Run """C:\Program Files\Google\Chrome\Application\chrome.exe"" --app=http://127.0.0.1:8000 --window-size=1400,900", 0, False