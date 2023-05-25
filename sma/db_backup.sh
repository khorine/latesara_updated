#!/bin/sh

DB_USER="root"
DB_PASS="Savanna#123$$"
DB_NAME="latesara"

BACKUP_DIR="/home/savanna/backup"
DATE=$(date +"%Y-%m-%d_%H-%M-%S")

mysqldump --user=$DB_USER --password=$DB_PASS $DB_NAME > $BACKUP_DIR/$DB_NAME-$DATE.sql
gzip $BACKUP_DIR/$DB_NAME-$DATE.sql

find $BACKUP_DIR -type f -name "*.gz" -mtime +3 -delete

