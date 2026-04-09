@echo off
echo Running Flutter Doctor... > flutter_output.txt
call C:\flutter\bin\flutter.bat doctor >> flutter_output.txt 2>&1
echo. >> flutter_output.txt
echo Running Flutter Pub Get... >> flutter_output.txt
call C:\flutter\bin\flutter.bat pub get >> flutter_output.txt 2>&1
echo. >> flutter_output.txt
echo Checking Devices... >> flutter_output.txt
call C:\flutter\bin\flutter.bat devices >> flutter_output.txt 2>&1
echo. >> flutter_output.txt
echo Attempting to Run App on Windows... >> flutter_output.txt
call C:\flutter\bin\flutter.bat run -d windows >> flutter_output.txt 2>&1
