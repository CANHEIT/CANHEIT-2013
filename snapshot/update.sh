#!/bin/bash

rm -Rf localhost\:8080/* localhost\:8080/.htaccess
wget -mpc --no-cache --user-agent="" -e robots=off http://localhost:8080/ > wget.log 2>&1
cp -Rfp *.png 404.html .htaccess php lib render-page.php localhost\:8080/
