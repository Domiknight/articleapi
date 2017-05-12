#!/usr/bin/env bash

DATE=`date +%Y%m%d%H%M%S`;
mysqldump -uroot -pPassword --all-databases --events --triggers --routines --add-drop-database --single-transaction | gzip > /opt/backups/database/$DATE.sql.gz