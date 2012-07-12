buddycloud-plugin-show-client
=============================

Show client test plugin - proof of concept functionality

This plugin adds an additional piece of information to the stanzas sent to the XMPP server appending the additional fields:

```xml

<client>
    <interface>the web</interface>
</client>
```

Clients not providing the data do not cause issues.

Example:
![Demo of show client plugin in action](https://github.com/lloydwatkin/buddycloud-plugin-show-client/raw/master/docs/screen-shot.png)

How to use:

At present you'll need the version of buddycloud-ui from https://github.com/lloydwatkin/buddycloud-webclient/tree/plugins.

Checkout this plugin to /src/plugins/show-client-0.1.0

Update assets/config.js with the following entry:

```json

    plugins: {
        'show-client': '0.1.0'
    }
```

Then build the ui (./development). This will generate a file plugin-list.coffee which will allow init.coffee to load the required plugins in the browser.



Plugin for buddycloud UI see http://www.github.com/buddycloud/buddycloud-webclient
