#!/usr/bin/env bash

# Laravel Queue Monitor Script (Fixed)
LARAVEL_PATH="/home/nidccglo/aces-portal.com"
QUEUE_CONNECTION="database"
LOG_FILE="$LARAVEL_PATH/storage/logs/queue-monitor.log"
PID_FILE="$LARAVEL_PATH/storage/logs/queue-worker.pid"

# Define absolute paths (adjust to your system)
PHP_BIN="/usr/local/bin/php"
NOHUP_BIN="/usr/bin/nohup"
PS_BIN="/bin/ps"

log_message() {
    echo "$(date '+%Y-%m-%d %H:%M:%S') - $1" >> "$LOG_FILE"
}

is_queue_running() {
    if [ -f "$PID_FILE" ]; then
        PID=$(cat "$PID_FILE")
        if $PS_BIN -p "$PID" > /dev/null 2>&1; then
            return 0
        else
            log_message "Process with PID $PID not running, removing stale PID file"
            rm -f "$PID_FILE"
            return 1
        fi
    else
        return 1
    fi
}

start_queue() {
    cd "$LARAVEL_PATH" || {
        log_message "ERROR: Cannot change to Laravel directory: $LARAVEL_PATH"
        exit 1
    }

    if ! $PHP_BIN artisan --version > /dev/null 2>&1; then
        log_message "ERROR: Artisan not found or Laravel not initialized in $LARAVEL_PATH"
        exit 1
    fi

    $NOHUP_BIN $PHP_BIN artisan queue:work \
        --stop-when-empty \
        --tries=3 \
        --timeout=90 \
        >> "$LOG_FILE" 2>&1 &

    QUEUE_PID=$!
    echo "$QUEUE_PID" > "$PID_FILE"
    sleep 2

    if $PS_BIN -p "$QUEUE_PID" > /dev/null 2>&1; then
        log_message "Queue worker started successfully (PID: $QUEUE_PID)"
    else
        log_message "ERROR: Failed to start queue worker"
        rm -f "$PID_FILE"
    fi
}

mkdir -p "$LARAVEL_PATH/storage/logs"

if is_queue_running; then
    exit 0
else
    log_message "Queue worker not running, starting new worker"
    start_queue
    exit 0
fi
