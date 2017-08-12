# githook-auto-update-file
Auto download GitHub repository changed file(s) to WebHosting (Based PHP)


# Usage

- Download file "remote_update.php" and edit it, type in your $token, $github_user and $github_repo
- Upload file "remote_update.php" to your site (php) directory where the repository is located
- Turn to your repository's page
- Follow the menu to open Settings—>Webhooks—>Add webhook, Then, fill in the following

| Option       | Value                                    |
| ------------ | ---------------------------------------- |
| Payload URL  | https://yourdomain.com/remote_update.php?token=yourtokenstring |
| Content type | application/json                         |
| events       | Just the push event.                     |

- [x] Active, make sure this webhook is actived
- Update webhook
- Test, Find a file to try to write some change, push it, turn to your site directory,
