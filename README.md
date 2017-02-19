Github Workflow ([Download v1.7](https://raw.github.com/willfarrell/alfred-github-workflow/master/Github.alfredworkflow))
=====================

Github on Alfred

## Requirements
1. [Alfred App v2](http://www.alfredapp.com/#download)
1. [Alfred Powerpack](https://buy.alfredapp.com/)

## Installing
1. Click the download buttons below
2. Double-click to import into Alfred 2
3. Review the workflow to add custom Hotkeys

## About
Searching Github for gists, repos, user repos, or repos starred by a user

![alt text][my]

## Setup
Github allows 60 request per hour. This isn't very high and can be used up very quickly when doing searches. To get 5000 request per hour, enter in your github credentials.

1. `github proxy` - Set proxy server if you need it for Internet connectivity
1. `github username {github_username}` - Required to use `git my`, `git star`, & `gist`
1. `github password {github_passowrd}` - A [Github Application Token](https://help.github.com/articles/creating-an-access-token-for-command-line-use#creating-a-token) can be used in replace of (Recommended)
1. `github test` - Test Github credentials

## Commands
- `git search {query}` - Search all of Github repos ([Documentation](https://help.github.com/articles/searching-repositories))
- `git my {query}` - Search user Github repos
- `git star {query}` - Search user starred Github repos
- `git limit` - Get remaining requests allowed count
- `gist {query}` - Search user gists
- `gist create` - Create new gist

## Security
API calls are made using [Githubs Basic Authentication](http://developer.github.com/guides/getting-started/#authentication). The requests use `-u <your_username>`, **not** `-u <your_username>:<your_password>` which leaves your password in shell history and isn’t recommended (See `src/auth.sh` for implementation). Github credentials are stored in `/Library/Application Support/Alfred 2/Workflow Data/com.farrell.github.alfredworkflow/settings.plist`.

## Contributors
- [@willfarrell](https://github.com/willfarrell)

## Featured
- [Alfred Workflows for Beginners](http://computers.tutsplus.com/tutorials/alfred-workflows-beginner--mac-55446)

[my]: ./screenshots/my.png "Github Workflow"