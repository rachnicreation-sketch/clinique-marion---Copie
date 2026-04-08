@echo off
echo ========================================
echo  Redemarrage des services WAMP
echo ========================================
echo.
echo Arret des services...
net stop wampapache64
net stop wampmysqld64
echo.
echo Demarrage des services...
net start wampapache64
net start wampmysqld64
echo.
echo ========================================
echo  Services redemarrés avec succès!
echo ========================================
echo.
echo Testez maintenant: http://localhost/clinique-marion%%20-%%20Copie/test_pdo.php
echo.
pause