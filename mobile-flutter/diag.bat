@echo off
echo --- Flutter Config --- > diag.txt
call C:\flutter\bin\flutter.bat config >> diag.txt 2>&1
echo. >> diag.txt
echo --- Flutter Emulators --- >> diag.txt
call C:\flutter\bin\flutter.bat emulators >> diag.txt 2>&1
echo. >> diag.txt
echo --- Flutter Devices --- >> diag.txt
call C:\flutter\bin\flutter.bat devices >> diag.txt 2>&1
echo. >> diag.txt
echo --- ADB Devices --- >> diag.txt
adb devices >> diag.txt 2>&1
