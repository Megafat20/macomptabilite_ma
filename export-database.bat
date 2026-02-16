@echo off
REM Script pour exporter la base de données MySQL
REM Pour Windows avec Laragon

echo ================================================
echo   Export Base de Donnees - Gestion Facturation
echo ================================================
echo.

REM Configuration (ajustez si nécessaire)
set MYSQL_USER=root
set MYSQL_PASS=
set DB_NAME=gestion_facturation
set EXPORT_FILE=gestion_facturation_%date:~-4,4%%date:~-7,2%%date:~-10,2%_%time:~0,2%%time:~3,2%.sql
set LARAGON_MYSQL="C:\laragon\bin\mysql\mysql-8.0.30-winx64\bin\mysqldump.exe"

echo Exportation de la base de donnees : %DB_NAME%
echo Fichier de sortie : %EXPORT_FILE%
echo.

REM Exporter la base de données
%LARAGON_MYSQL% -u %MYSQL_USER% %DB_NAME% > %EXPORT_FILE%

if %errorlevel% equ 0 (
    echo.
    echo ================================================
    echo   SUCCES ! Base de donnees exportee
    echo ================================================
    echo.
    echo Fichier cree : %EXPORT_FILE%
    echo.
    echo Vous pouvez maintenant uploader ce fichier SQL
    echo sur votre hebergement Najahost via phpMyAdmin
    echo.
) else (
    echo.
    echo ================================================
    echo   ERREUR lors de l'export
    echo ================================================
    echo.
    echo Verifiez :
    echo - Le nom d'utilisateur MySQL : %MYSQL_USER%
    echo - Le nom de la base de donnees : %DB_NAME%
    echo - Le chemin vers mysqldump
    echo.
)

pause
