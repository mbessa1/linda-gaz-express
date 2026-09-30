@echo off
echo Demarrage de Gaz Express...
cd /d C:\Users\gisela\Desktop\gaz_express
"C:\Users\gisela\.config\herd\bin\php84\php.exe" -S 0.0.0.0:8080 -t public
pause