#!/bin/bash

CRON_CMD="*/5 * * * * php $(pwd)/cron.php"
CRON_FILE="mycron"

# Backup existing crontab
crontab -l > $CRON_FILE 2>/dev/null

# Add new cron line if not already present
grep -F "$CRON_CMD" $CRON_FILE || echo "$CRON_CMD" >> $CRON_FILE

# Install new crontab
crontab $CRON_FILE
rm $CRON_FILE

echo "CRON job set to run cron.php every 5 minutes."
