#!/bin/bash

# List of domain paths
domains=(
    "/home/ditrpindia/public_html"
)


# Source directory
source="/home/ditrporg/"

# Files and folders to exclude
excludes=(
    "include/classes/connection.class.php"
    "exam/application/config/database.php"
    "admin/login.php"
    "admin/include/classes/config.php"
    "admin/include/plugins"
    "admin/resources"
    "admin/WebService/resources/studentDetailsQR"
    "admin/WebService/resources/studentFeesQR"
    "event/assets/img"
    "resources"
    "uploads"
    "exam"
)

# Build exclude options for rsync
exclude_opts=""
for exclude in "${excludes[@]}"; do
    exclude_opts+="--exclude='$exclude' "
done

# Sync to each domain
for domain in "${domains[@]}"; do
    echo "Syncing to $domain"
    eval rsync -av --progress $exclude_opts "$source" "$domain/"
done
