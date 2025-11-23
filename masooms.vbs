Set WshShell = CreateObject("WScript.Shell")

' Start Apache
WshShell.Run "cmd /c C:\xampp\apache_start.bat", 0, False
WScript.Sleep 3000

' Start MySQL
WshShell.Run "cmd /c C:\xampp\mysql_start.bat", 0, False
WScript.Sleep 5000

' Start Laravel development server
WshShell.Run "cmd /c cd /d C:\xampp\htdocs\cafe-software && php artisan serve --port=8000", 0, False
WScript.Sleep 8000

' Open Chrome in app mode
WshShell.Run """C:\Program Files\Google\Chrome\Application\chrome.exe"" --app=http://127.0.0.1:8000 --window-size=1400,900", 1, False
