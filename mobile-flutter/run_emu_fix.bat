@echo off
set SDK_PATH=C:\Users\s\AppData\Local\Android\Sdk
set EMULATOR_EXE=%SDK_PATH%\emulator\emulator.exe
set ADB_EXE=%SDK_PATH%\platform-tools\adb.exe
set AVD_NAME=Pixel_2_API_24

:MENU
cls
echo ======================================================
echo    ANDROID EMULATOR RUNNER (FIX STUCK/MACET)
echo ======================================================
echo  [1] Cold Boot (Fix Ringan - Hindari Quick Boot)
echo  [2] Wipe Data + Cold Boot (Fix Berat - Reset Emulator)
echo  [3] Jalankan Biasa (Cepat - Resiko Macet)
echo  [4] Keluar
echo ======================================================
set /p choice="Pilih menu (1-4): "

if "%choice%"=="1" goto COLD_BOOT
if "%choice%"=="2" goto WIPE_DATA
if "%choice%"=="3" goto QUICK_BOOT
if "%choice%"=="4" exit
goto MENU

:COLD_BOOT
echo.
echo [1/3] Menutup emulator lama...
taskkill /F /IM "qemu-system-x86_64.exe" /T >nul 2>&1
taskkill /F /IM "emulator.exe" /T >nul 2>&1
echo [2/3] Jalankan Cold Boot %AVD_NAME%...
start /B "Android Emulator" "%EMULATOR_EXE%" -avd %AVD_NAME% -no-snapshot-load -gpu host -no-audio
goto WAIT_READY

:WIPE_DATA
echo.
echo PERINGATAN: Semua data aplikasi di emulator akan dihapus!
set /p confirm="Lanjutkan? (y/n): "
if /i "%confirm%" neq "y" goto MENU
echo [1/3] Menutup emulator lama...
taskkill /F /IM "qemu-system-x86_64.exe" /T >nul 2>&1
taskkill /F /IM "emulator.exe" /T >nul 2>&1
echo [2/3] Jalankan Wipe Data + Cold Boot...
start /B "Android Emulator" "%EMULATOR_EXE%" -avd %AVD_NAME% -wipe-data -no-snapshot-load -gpu host -no-audio
goto WAIT_READY

:QUICK_BOOT
echo.
echo [1/2] Menjalankan emulator biasa...
start /B "Android Emulator" "%EMULATOR_EXE%" -avd %AVD_NAME% -gpu host
goto WAIT_READY

:WAIT_READY
echo [3/3] Menunggu koneksi ADB...
%ADB_EXE% wait-for-device
echo.
echo Emulator sedang proses booting.
echo Jika sudah menyala, Anda bisa menjalankan: flutter run
echo.
pause
goto MENU
