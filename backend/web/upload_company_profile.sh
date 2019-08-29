#!/bin/bash
id=$1
echo "Start upload profile"
/usr/bin/php /opt/code/motobike/yii company-profile/upload-profile $id
echo "end upload profile"
