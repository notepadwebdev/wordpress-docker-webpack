#!/bin/sh
set -eu

cd "$(dirname "$0")/.."

developer_uid=$(id -u)
if [ "$developer_uid" -eq 0 ]; then
    echo "Run this script as your normal Linux/WSL user, not with sudo." >&2
    exit 1
fi

docker compose exec -T --user root wordpress sh -s -- "$developer_uid" <<'SH'
set -eu

if ! command -v setfacl >/dev/null 2>&1; then
    echo "ACL tools are missing. Run docker compose up -d --build wordpress first." >&2
    exit 1
fi

developer_uid=$1
set -- wp-content/plugins wp-content/uploads wp-content/themes wp-content/languages \
    wp-content/upgrade wp-content/upgrade-temp-backup

mkdir -p "$@"
setfacl -m "u:${developer_uid}:rwx,u:www-data:rwx,o::rx" wp-content
setfacl -R -P -m "u:${developer_uid}:rwX,u:www-data:rwX,o::rX" "$@"
find "$@" -type d -exec setfacl -m \
    "d:u::rwx,d:u:${developer_uid}:rwx,d:u:www-data:rwx,d:g::r-x,d:m::rwx,d:o::r-x" {} +
SH

echo "Permissions repaired for WordPress and Linux/WSL user ${developer_uid}."
