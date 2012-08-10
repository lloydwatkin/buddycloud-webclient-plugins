# Scrobble to Buddycloud

This piece of code, whilst not strictly a buddycloud webclient plugin, takes your currently playing track from (last.fm)[http://last.fm] and posts to a (buddycloud)[http://buddycloud.com] channel.

## Usage

Copy __config.ini.example__ to __config.ini__ and edit the contents to match your settings.

Run __php scrobble-to-buddycloud.php__ if there are any errors you will be informed.

### Template

You can define a template for posts to buddycloud, these are of the form:

    ♫ Listening to: %artist% - %track% (%url%) ♫

In the template the values of %artist%, %track%, and %url% are replaced with the artist name, track name, and Last.fm URL respectively.

## Tips

* Set up on a cron to save you all the work!
* Requires PHP>=5.3.0

# Recent changes

* Stopped using 'nowplaying' attribute
  * Not reliable enough, especially for obscure tracks
  * Now checking time track started playing
* Allow user to provide a post template
