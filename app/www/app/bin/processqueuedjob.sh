#!/bin/bash
while true
do
    /bin/sleep 10
    /usr/local/bin/php -f /var/www/vendor/silverstripe/framework/cli-script.php dev/tasks/ProcessJobQueueTask queue=immediate
done
