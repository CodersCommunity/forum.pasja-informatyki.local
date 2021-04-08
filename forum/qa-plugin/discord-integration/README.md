# Discord integration plugin to [Question2Answer](http://question2answer.org/)

Plugin offers logging with Discord account to existing Q2A account. It's useful when you want connect Discord server users to Q2A users - you will know who is who on Discord.

Tested on **Q2A version >= 1.7.0 and PHP >= 7.0**. Code style adjusted to Q2A style.

## Description

After login user in Discord server receive nick from Q2A. The user can join to server only one Discord account per Q2A user. The user can remove connection and connect other Discord account. Of course, when user disconnecting account, it's also remove from the server. When the user change nick, plugin automatically change this on Discord. When user account is blocking or deleting, plugin automatically remove linked account from Discord server. Blocked user can't join to Discord.

## Installation

Clone or download this repository or selected [release](https://github.com/awaluk/q2a-discord-integration/releases) to *qa-plugin* directory in your Q2A.

## Configuration

Prepare your Discord application. You might create it in [Discord Developer Portal](https://discord.com/developers/applications/).

Go to admin panel and `Plugins` tab (*/admin/plugins*). Q2A should automatically prompt for execute database changes, for example by red alert on page top: `The Discord integration admin page module requires some database initialization`. Click it. Next, search *Discord integration* and click *settings* link next to the plugin description.

| Option | Description | Required? / Default value |
| --- | --- | --- |
| Discord API client ID | Discord application client id | Yes |
| Discord API secret key | Secret client key for your Discord application | Yes |
| Discord server ID | ID of server to which users will be joined | Yes |
| Information in page top (HTML allowed) | Custom message shown in page top, over join button | No / No information |
| Information in page bottom (HTML allowed) | Custom message shown in page bottom, under join button | No / No information |

After setting, click *Save* button. Integration page is available on **/discord-integration**. You might create custom menu position linked to page etc. When required fields aren't filled, plugin doesn't work (integration page doesn't shown).

On Discord server you should disable creating invitations and changing nicks for normal users. Don't forget invite application bot to your server. Bot must have permissions: `Kick members`, `Create Instant Invite`, `Manage nicknames` to right working.
