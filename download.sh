#!/bin/bash

# Set craft cms version
CRAFT_VERSION=2.6 CRAFT_BUILD=3015

CRAFT_ZIP=Craft-$CRAFT_VERSION.$CRAFT_BUILD.zip

# Download the latest Craft (https://craftcms.com/support/download-previous-versions)
echo "Downloading $CRAFT_ZIP"
curl -L https://download.buildwithcraft.com/craft/$CRAFT_VERSION/$CRAFT_VERSION.$CRAFT_BUILD/$CRAFT_ZIP -o /tmp/$CRAFT_ZIP

# Extract craft
echo "Unzipping $CRAFT_ZIP"
unzip -qqo /tmp/$CRAFT_ZIP 'craft/*' && \
unzip -qqoj /tmp/$CRAFT_ZIP 'public/index.php' -d ./html

echo "Moving in config"
cp config/* ./site/craft/config/

echo "Cleaning up"
rm /tmp/$CRAFT_ZIP

echo "Finished!"
