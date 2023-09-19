#!/bin/bash
if command -v ./node_modules/.bin/lessc > /dev/null 2>&1; then
    lessc="./node_modules/.bin/lessc";
elif command -v ./node_modules/less/bin/lessc > /dev/null 2>&1; then
    lessc="./node_modules/less/bin/lessc";
else
    echo >&2 "The lessc command is required, please run: 'npm install less' first"; exit 1;
fi

project="dorcel"
watch_dir="wordpress-core/wp-content/themes/$project/assets/less";
css_dir="wordpress-core/wp-content/themes/$project/style.css";

echo "Compiling CSS at $css_dir from $watch_dir/main.less"
$lessc "$watch_dir/main.less" $css_dir
echo "Done!"
echo "Starting watch..."

inotifywait -m -r -e modify "$watch_dir" --format "%f" | while read f

do
    echo "Detected changes on: $f, recompiling..."
    $lessc "$watch_dir/main.less" $css_dir
done
