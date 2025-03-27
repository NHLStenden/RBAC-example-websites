#!/bin/bash
TIMESTAMP=$(date +"%Y-%m-%d_%H-%M-%S")

echo "Running User Provisioning"  > /app/logs/script_run_$TIMESTAMP.log 2>&1
python3 /app/src/sync.py > user-provisioning.log  >> /app/logs/script_run_$TIMESTAMP.log 2>&1