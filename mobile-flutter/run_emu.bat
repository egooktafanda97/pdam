@echo off
echo === FLUTTER RUN === > flutter_run_output.txt
echo Starting at %date% %time% >> flutter_run_output.txt
echo. >> flutter_run_output.txt
call C:\flutter\bin\flutter.bat run -d emulator-5554 >> flutter_run_output.txt 2>&1
echo. >> flutter_run_output.txt
echo === DONE === >> flutter_run_output.txt
echo Finished at %date% %time% >> flutter_run_output.txt
