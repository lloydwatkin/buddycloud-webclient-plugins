buddycloud-plugin-show-client
=============================

Show client test plugin - proof of concept functionality

This plugin adds an additional piece of information to the stanzas sent to the XMPP 
server appending the additional fields:

```xml

<client>
    <interface>the web</interface>
</client>
```

Clients not providing the data do not cause issues.

Example:
![Demo of show client plugin in action](../../raw/master/show-client-0.1.0/docs/screen-shot.png)

To activate, ensure this is checked out into the correct location 
(see https://github.com/lloydwatkin/buddycloud-webclient-plugins/blob/master/README.md) 
and add the following to your config.js 'plugin' key:

```javascript

{
    'show-client': '0.1.0'
}
```