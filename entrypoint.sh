#!/bin/bash
# Sheznie  
# turn on bash's job control
set -m
  
# Start the primary process and put it in the background
apache2-foreground &
  
# Start the helper process
service supervisor start
  
# the my_helper_process might need to know how to wait on the
# primary process to start before it does its work and returns
  
cd /var/www/html && php artisan migrate
# now we bring the primary process back into the foreground
# and leave it there
fg %1
