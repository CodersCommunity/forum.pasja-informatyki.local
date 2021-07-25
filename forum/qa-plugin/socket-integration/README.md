# Socket integration plugin

For integration with [HTTP websocket server](https://github.com/CodersCommunity/http-websocket-server).

## Installation and configuration

Download plugin to your `qa-plugins` directory.

Add configuration values to your `qa-config.php`:

```php
define('QA_WS_TOKEN', 'secretToken'); // token to send with requests from Q2A to Node server
define('QA_WS_PORT', 3000); // port used by frontend script
define('QA_WS_URL', 'http://socket:' . QA_WS_PORT); // URL to Node server used by Q2A (only backend requests)
```

To disable frontend script, set `QA_WS_PORT` to `null`. If you want to disable sending events from Q2A to Node server, set `QA_WS_URL` to `null`.

## Available endpoints

- `GET /socket/user-id` - returns logged user id by cookie. Requires header `TOKEN` with token configured in `QA_WS_TOKEN`, so can be used only by backend.
- `GET /socket/comments` - returns comments HTML for selected question or answer. Parameters:
    - `post_id` - required, id of question or answer for which Q2A should return comments,
    - `last_id` - optional, id of the latest visible comment. When set, Q2A return only newest comments.
