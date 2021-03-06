## Remote Member Authentication plugin


This WordPress plugin enables authentication of members against data provided by a remote RESTful API host so that they may gain access to restricted content. The RMA plugin takes advantage of the WordPress HTTP API to set, reset, and verify member passwords. Members must, of course, already exist in the remote host's database. Access to restricted content is only granted to existing members with active status. The RMA plugin can use either API key or HTTP Basic authentication.

The RMA plugin may create an additional database table. Member data is not contained in the `wp_users` table. If the remote host only allows GET of data a member table is created and maintained locally.

The particular use case prompting this plugin was a church WordPress website that uses a third-party church management system for maintaining a member database. As of this writing no known ChMS maintains member email addresses or passwords. If and when they do this plugin may be useful.

### Installation:

Use your favorite method for installing the RMA plugin. Since it is currently only available here at github.com you'll probably want to download the zip to your system. Unzip the file and copy or cut & paste the _rma-remote-auth_ directory into your `../wp-content/plugins` directory. Activate the plugin.

### Setup:

On activation, the RMA plugin creates pages for member sign-in and password maintenance. It also creates a template to be used for adding restricted content pages.

The settings will appear under the admin's Settings menu with the name `Remote Member Authentication`.

- Configure settings

	- **Authentication type**:

		- For API key, enter your **API key** and the **API key field name** of the key (e.g., *Api-key*)

		- for HTTP Basic, enter the **Username** and **Password** for a user with search and update privileges.

	- **Remote host URIs**:

		- **Get member data only**: Check this box if the RESTful API you're using does not provide POST privileges. When checked the plugin will create a member table in the WordPress database for maintaining member data. The member table is updated daily.

			- If **Get member data only** checked:

				- **Get all members data URI**: This option appears only when the *Get member data only* option IS checked. When the plugin is activated all active members are downloaded from the remote source at the URI specified here to a local member database table maintained by the plugin. Once a day (using a WordPress cron job) local data is updated.

			- If **Get member data only** NOT checked:

				- **Get member data URI**: This option appears only when the *Get member data only* option IS NOT checked. Returns password hash for a member email address.   Example: _https://www.example.com/api/get_user_

					The RMA plugin assumes that the member's email address is appended to the URI. The request sent will be _https://www.example.com/get_user/someuser@yoursite.org_

				- **Set member password URI**: This option appears only when the *Get member data only* option IS NOT checked. The RMA plugin assumes that active members without a password hash in the member database have never registered. When signing in without a password the member is directed to a link to register. Upon clicking that link a random password is created and emailed to the member. The plugin also updates the member database with a password hash. Example: _https://www.example.com/api/set_password_

					For setting and resetting passwords, the member email address and password hash are sent in the request header.

	- **Remote host field names**:

		- **Status field name**: A field name used by the remote host for the member's status, e.g., _enabled_ or _status_.

		- **Active status value**: the value used to indicate active status, e.g., _true_ or _active_

- **Add restricted content**

	- For each page of restricted content set its template to "Restricted member content"

		When anyone not already signed in clicks on a menu item for a page of restricted content they will be directed to the member sign in form. Sign in is with an email address and a password. The form includes a link for cases of  forgotten password.

### Testing:

For the technically advanced or bold there is a simple RESTful API that can be used to test your installation of this plugin.  See the github repository [truckee\wprest](https://github.com/truckee/wprest).

It is highly recommended that email testing be done within the test environment.

### Deactivation:

When the plugin is deactivated it deletes pages and templates it has created. Member content pages remain with the *Restricted member content* template removed. Any cron job created is deleted as is the member table.
