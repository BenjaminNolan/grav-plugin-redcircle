# Grav RedCircle Plugin

`RedCircle` is a simple [Grav][grav] Plugin that converts markdown links into responsive embeds for RedCircle content. It is based on the [grav-plugin-youtube][grav-plugin-youtube].

# Installation

Installing the RedCircle plugin can be done in one of two ways. Our GPM (Grav Package Manager) installation method enables you to quickly and easily install the plugin with a simple terminal command, while the manual method enables you to do so via a zip file.

## GPM Installation (Preferred)

The simplest way to install this plugin is via the [Grav Package Manager (GPM)](http://learn.getgrav.org/advanced/grav-gpm) through your system's Terminal (also called the command line).  From the root of your Grav install type:

    bin/gpm install redcircle

This will install the RedCircle plugin into your `/user/plugins` directory within Grav. Its files can be found under `/your/site/grav/user/plugins/redcircle`.

## Manual Installation

To install this plugin, just download the zip version of this repository and unzip it under `/your/site/grav/user/plugins`. Then, rename the folder to `redcircle`. You can find these files either on [GitHub](https://github.com/getgrav/grav-plugin-redcircle) or via [GetGrav.org](http://getgrav.org/downloads/plugins#extras).

You should now have all the plugin files under

    /your/site/grav/user/plugins/redcircle

# Config Defaults

```
enabled: true
built_in_css: true
```

If you need to change any value, then the best process is to copy the [redcircle.yaml](redcircle.yaml) file into your `users/config/plugins/` folder (create it if it doesn't exist), and then modify there.  This will override the default settings.

<!-- You can also set any of these settings on a per-page basis by adding them under a `redcircle:` setting in your page header.  For example:

    - - -
    title: RedCircle Podcast
    redcircle:
    - - -
    
    [plugin:redcircle](https://api.podcache.net/embedded-player/sh/272df5be-5896-44b8-9a73-159e42a6588c/ep/063d5bda-40b5-4453-88ed-2620522b1505)

This will display a podcast and auto-play it.

For more details on the `player_parameters`, please check out the [RedCircle official documentation](https://developers.google.com/redcircle/player_parameters) -->

# Usage

To use this plugin you simply need to include a redcircle URL in markdown link such as:

```
[plugin:redcircle](https://api.podcache.net/embedded-player/sh/272df5be-5896-44b8-9a73-159e42a6588c/ep/063d5bda-40b5-4453-88ed-2620522b1505)
```

Will be converted into the following embeded HTML:

```
<div class="grav-redcircle">
    <div class="redcirclePlayer-272df5be-5896-44b8-9a73-159e42a6588c"></div>
    <script async defer onload="redcircleIframe();" src="https://api.podcache.net/embedded-player/sh/272df5be-5896-44b8-9a73-159e42a6588c/ep/063d5bda-40b5-4453-88ed-2620522b1505"></script>
</div>
```

CSS is also loaded to provide the appropriate responsive layout.

# Shortcode Syntax

As of version `3.0` you can now use an alternative _shortcode_ syntax that supports passing in the player parameter values inline 

```
[redcircle]https://api.podcache.net/embedded-player/sh/272df5be-5896-44b8-9a73-159e42a6588c/ep/063d5bda-40b5-4453-88ed-2620522b1505[/redcircle]
```

[grav]: http://github.com/getgrav/grav
[grav-plugin-youtube]: https://github.com/getgrav/grav-plugin-youtube

