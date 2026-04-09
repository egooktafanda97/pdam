@echo off
echo === DIAGNOSTIC CHECK === > emu_check.txt
echo. >> emu_check.txt

echo --- WHERE ADB --- >> emu_check.txt
where adb >> emu_check.txt 2>&1
echo. >> emu_check.txt

echo --- ANDROID_HOME --- >> emu_check.txt
echo %ANDROID_HOME% >> emu_check.txt
echo %ANDROID_SDK_ROOT% >> emu_check.txt
echo %LOCALAPPDATA%\Android\Sdk >> emu_check.txt
echo. >> emu_check.txt

echo --- ADB KILL SERVER --- >> emu_check.txt
adb kill-server >> emu_check.txt 2>&1
echo. >> emu_check.txt

echo --- ADB START SERVER --- >> emu_check.txt
adb start-server >> emu_check.txt 2>&1
echo. >> emu_check.txt

echo --- ADB DEVICES --- >> emu_check.txt
adb devices >> emu_check.txt 2>&1
echo. >> emu_check.txt

echo --- TASKLIST EMULATOR --- >> emu_check.txt
tasklist /FI "IMAGENAME eq qemu-system-x86_64.exe" >> emu_check.txt 2>&1
echo. >> emu_check.txt

echo --- TASKLIST EMULATOR2 --- >> emu_check.txt
tasklist /FI "IMAGENAME eq emulator.exe" >> emu_check.txt 2>&1
echo. >> emu_check.txt

echo --- FLUTTER DEVICES --- >> emu_check.txt
call C:\flutter\bin\flutter.bat devices >> emu_check.txt 2>&1
echo. >> emu_check.txt

echo Done! Check emu_check.txt
