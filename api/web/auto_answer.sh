#!/bin/bash
id=$1
echo "Start auto answer"
/usr/bin/php /opt/code/motobike/yii question/auto-answer $id
echo "send auto answer"
