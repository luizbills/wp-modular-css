#!/bin/bash

echo "Use git master branch"
git checkout master

if [[ -f "composer.json" ]];
then
	echo "Installing composer packages"
	composer install
fi

echo "Removing unwanted files"
rm -Rf .git
rm -Rf .github
rm -Rf tests
rm -Rf apigen
rm -Rf wp-assets # wordpress plugin banners and icons
rm -f .gitattributes
rm -f .gitignore
rm -f .gitmodules
rm -f .travis.yml
rm -f Gruntfile.js
rm -f package.json
rm -f .jscrsrc
rm -f .jshintrc
rm -f composer.json
rm -f phpunit.xml
rm -f phpunit.xml.dist
rm -f README.md
rm -f .coveralls.yml
rm -f .editorconfig
rm -f .scrutinizer.yml
rm -f apigen.neon
rm -f CONTRIBUTING.md
rm -f before-wp-publish.sh # remove itself
