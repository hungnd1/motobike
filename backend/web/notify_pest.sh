#!/bin/bash
id=$1
echo "Start pest"
/usr/bin/php /opt/code/motobike/yii question/notify-pest $id
echo "Add pest"
