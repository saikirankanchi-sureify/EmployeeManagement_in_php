#!/bin/sh

echo "Waiting for PostgreSQL to be ready..."

until pg_isready -h db -p 5432 -U saikirankanchi; do
  sleep 1
done

echo "PostgreSQL is ready. Starting PHP..."

exec apache2-foreground
