@echo off
echo Running Flutter on Chrome... > flutter_chrome_output.txt
call C:\flutter\bin\flutter.bat run -d chrome >> flutter_chrome_output.txt 2>&1
