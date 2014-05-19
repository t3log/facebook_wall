facebook_wall
=============
Last week I decided to create a new TYPO3 extension – facebook_wall, which allows you to display facebook page posts on your website.

To use this extension you first need to create new facebook application <https://developers.facebook.com/apps> and then add its 'App ID' and 'App Secret' to plugin configuration:

example configuration:
-----------
```
plugin.tx_facebookwall {
   settings {
      limit = 3
      profileId =
      appId =
      appSecret =
      graphUrl = https://graph.facebook.com
      facebookProfile = https://www.facebook.com/myPofile
   }
}


```

