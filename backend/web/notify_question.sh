#!/bin/bash
id=$1
echo "Start answer"
/usr/bin/php /opt/code/motobike/yii question/notify-question $id
echo "Add answer"
