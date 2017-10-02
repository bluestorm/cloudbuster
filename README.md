# CloudBuster plugin for Craft CMS

Busts caching on Cloudflare when entries or assets are updated/uploaded.

![CloudBuster Icon](https://cdn.rawgit.com/bluestorm/craft-cloudbuster/master/resources/icon.svg)

## Installation

To install CloudBuster, follow these steps:

1. Download & unzip the file and place the `cloudbuster` directory into your `craft/plugins` directory.
2.  -OR- do a `git clone git@github.com:bluestorm/cloudbuster.git cloudbuster` directly into your `craft/plugins` folder.  You can then update it with `git pull`.
4. Install plugin in the Craft Control Panel under Settings > Plugins.
5. The plugin folder should be named `cloudbuster` for Craft to see it.

CloudBuster works on Craft 2.4.x and Craft 2.5.x.

## Configuring CloudBuster

Click the cog icon next to the plugin under Settings > Plugins in the Craft Control Panel and enter your Cloudflare API key, email and Zone ID.

## Using CloudBuster

You don't need to do a thing! The plugin automatically flushes entry, category and asset urls from Cloudflare when they're created, updated or deleted.