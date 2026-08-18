#!/bin/bash

set -e

REPO_DIR="$HOME/game-website"
WEB_DIR="$HOME/www"

cd "$REPO_DIR"

git pull origin main

rsync -av --delete public/ "$WEB_DIR/"