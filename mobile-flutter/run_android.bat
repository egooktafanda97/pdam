@echo off
echo Running Android Run Script... > android_output.txt
echo Checking for devices... >> android_output.txt
call adb devices >> android_output.txt 2>&1
echo. >> android_output.txt
echo Attempting to launch app on Android... >> android_output.txt
call C:\flutter\bin\flutter.bat run -d emulator-5554 >> android_output.txt 2>&1
echo. >> android_output.txt
if %ERRORLEVEL% NEQ 0 (
    echo Run on emulator-5554 failed, trying generic run... >> android_output.txt
    call C:\flutter\bin\flutter.bat run >> android_output.txt 2>&1
)
