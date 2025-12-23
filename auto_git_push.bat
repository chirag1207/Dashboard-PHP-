@echo off
:: ============================================
::  Auto Git Commit & Push Script for Chirag
::  Repo: https://github.com/chirag1207/Dashboard-PHP-
::  Branch: main
::  Local path: C:\xampp\htdocs\PHP\PHP
:: ============================================

:: Navigate to your local project folder
cd /d "C:\xampp\htdocs\PHP\PHP"

echo ---------------------------------------
echo Working directory: %cd%
echo ---------------------------------------
echo.

:: Stage all changes
git add -A

:: Check if there are any changes to commit
git diff --cached --quiet
if %errorlevel% equ 0 (
    echo ✅ No changes to commit. Exiting...
    timeout /t 3 >nul
    exit /b
)

:: Generate a timestamp for the commit message
for /f "tokens=1-4 delims=/ " %%a in ('date /t') do (
    set month=%%a
    set day=%%b
    set year=%%c
)
for /f "tokens=1-2 delims=: " %%a in ('time /t') do (
    set hour=%%a
    set minute=%%b
)
set commitMsg=Auto Backup - %year%-%month%-%day%_%hour%-%minute%

:: Commit the changes
git commit -m "%commitMsg%"

:: Push to GitHub main branch
git push origin main

:: Success message
echo.
echo ✅ Files committed and pushed successfully to main branch.
echo ✅ Commit message: %commitMsg%
echo ---------------------------------------
timeout /t 5 >nul
exit
