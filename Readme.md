##Remote Member Authentication plugin

###This is a work in progress.

This WordPress plugin will, when completed, allow authentication of users against a remote host. Authenticated users will then gain access to restricted content. That remote host must present a RESTful API that allows querying of member data. The plugin takes advantage of the [WordPress HTTP API](https://codex.wordpress.org/HTTP_API) to verify member passwords.

The particular use case prompting this plugin was a church WordPress website that used a church management system (e.g., [Realm](www.acstechnologies.com)) for maintaining a member database. The objective of the plugin is to restrict certain content in the website to church member. As of this writing Realm does not maintain member email addresses or passwords. If and when they or other such systems do this plugin may be useful.