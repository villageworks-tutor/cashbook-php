@echo off
rem vendor\bin\phpunit --colors=always %*
vendor\bin\phpunit ^
  -c ./test/phpunit.xml ^
  --testdox ^
  --colors=always ^
  %*
pause
