#!/bin/bash
if command -v ./node_modules/.bin/lessc > /dev/null 2>&1; then
	lessc="./node_modules/.bin/lessc";
elif command -v ./node_modules/less/bin/lessc > /dev/null 2>&1; then
	lessc="./node_modules/less/bin/lessc";
else
	echo >&2 "The lessc command is required, please run: 'npm install less' first"; exit 1;
fi

watch_dir="wordpress-core/wp-content/themes/dorcel/assets/less";
css_dir="wordpress-core/wp-content/themes/dorcel/style.css";
$lessc "$watch_dir/main.less" $css_dir
