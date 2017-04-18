### Remote Member Authentication plugin

## This is a work in progress.

This WordPress plugin will, when completed, allow authentication of users against a remote host so that they may gain access to restricted content. The plugin takes advantage of the WordPress HTTP API to verify member passwords. A cookie is created on successful verification that is used to signify permission to access additional content.

The particular use case prompting this plugin was a church WordPress website that used a third-party church management system (e.g., Realm) for maintaining a member database. As of this writing Realm does not maintain member email addresses or passwords. If and when they or other such systems do this plugin may be useful.

Once installed:

- Configure settings
	- Add URI for returning user data
- Create sign-in form
	- Add new page with title "Sign in"
	- Insert shortcode [member_sign_in]
	- Publish page
- Add restricted content
	- Set template to "Restricted member content" 
 