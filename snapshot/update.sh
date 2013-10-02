#!/bin/bash

rm -Rf localhost\:8080/* localhost\:8080/.htaccess
wget -mpc --no-cache --user-agent="" --content-disposition -e robots=off http://localhost:8080/ --base=http://localhost:8080/ > wget.log 2>&1
cp -Rfp *.png 404.html .htaccess php lib img localhost\:8080/

grep -B 2 '404 Not Found' wget.log
