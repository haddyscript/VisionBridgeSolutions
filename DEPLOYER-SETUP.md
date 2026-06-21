# Deployer Setup Notes

How auto-deploy from GitHub to the Hostinger server was set up for this project.

## What it does

Pushing to `main` on GitHub now automatically updates the live site at
`https://maroon-magpie-775843.hostingersite.com` — no more manual FTP uploads.

A GitHub webhook hits a password-protected route (`/deployer`) on the live
site, which runs `git fetch` + `git reset --hard origin/main` on the server
and clears Laravel's caches.

## Server paths

- App root (the actual Laravel/git repo): `/home/u290597841/domains/maroon-magpie-775843.hostingersite.com/laravel-app`
- Web root (what the domain serves): `/home/u290597841/domains/maroon-magpie-775843.hostingersite.com/public_html`
  - This is now a **symlink** to `laravel-app/public`, not a separate copy.

## Code added to the repo

- `app/Http/Controllers/DeployerController.php` — checks `?password=` against `.env`, then runs:
  - `git fetch origin main`
  - `git reset --hard origin/main`
  - `php artisan config:clear` / `view:clear` / `route:clear`
  - optionally `composer install` and `php artisan migrate --force`, if `DEPLOYER_RUN_COMPOSER` / `DEPLOYER_RUN_MIGRATIONS` are set to `true` in `.env` (off by default — they're slow and can time out on shared hosting)
- `routes/web.php` — added `GET|POST /deployer` route
- `bootstrap/app.php` — exempted `deployer` from CSRF validation (same as the existing `stripe/webhook` exemption)
- `config/app.php` + `.env.example` — added `DEPLOYER_PASSWORD`, `DEPLOYER_RUN_COMPOSER`, `DEPLOYER_RUN_MIGRATIONS`

## One-time server setup (already done)

1. **Generate a GitHub deploy key on the server** (not on the local Mac — it has to live on the server that's doing the `git pull`):
   ```bash
   ssh -p 65002 u290597841@45.130.228.160
   cd ~/domains/maroon-magpie-775843.hostingersite.com
   ssh-keygen -t ed25519 -f ~/.ssh/vbs_deploy_key -N "" -C "hostinger-deploy"
   cat ~/.ssh/vbs_deploy_key.pub
   ```
2. **Add the printed public key to GitHub** as a read-only Deploy Key:
   Repo → Settings → Deploy keys → Add deploy key (leave "Allow write access" unchecked)
3. **Tell SSH on the server to use that key for GitHub:**
   ```bash
   cat >> ~/.ssh/config <<'EOF'
   Host github.com
     HostName github.com
     User git
     IdentityFile ~/.ssh/vbs_deploy_key
     IdentitiesOnly yes
   EOF
   chmod 600 ~/.ssh/config
   ssh -T git@github.com
   ```
   Should reply: `Hi haddyscript/VisionBridgeSolutions! You've successfully authenticated...`
4. **Turn the existing `laravel-app` folder into a git repo tracking GitHub:**
   ```bash
   cd ~/domains/maroon-magpie-775843.hostingersite.com/laravel-app
   git init
   git remote add origin git@github.com:haddyscript/VisionBridgeSolutions.git
   git fetch origin main
   git checkout -f main
   git branch --set-upstream-to=origin/main main
   git reset --hard origin/main
   ```
   `.env`, `vendor/`, `node_modules/`, `public/build`, `public/storage` are all gitignored, so this doesn't touch them.
5. **Set the deploy password** (edit `.env` directly with `nano`, don't use `echo` — special characters in the password can get mangled by the shell):
   ```bash
   nano .env
   ```
   ```
   DEPLOYER_PASSWORD='YOUR_DEPLOYER_PASSWORD'
   ```
   ```bash
   php artisan config:clear
   ```
6. **Swap `public_html` for a symlink** so every future deploy goes live instantly with no copy step:
   ```bash
   cd ~/domains/maroon-magpie-775843.hostingersite.com
   mv public_html public_html.bak
   ln -s laravel-app/public public_html
   ```
7. **Test the endpoint:**
   ```bash
   curl -i "https://maroon-magpie-775843.hostingersite.com/deployer?password=YOUR_DEPLOYER_PASSWORD"
   ```
   Should return `200` with the git/artisan output as plain text.
8. **Add the GitHub webhook:**
   Repo → Settings → Webhooks → Add webhook
   - Payload URL: `https://maroon-magpie-775843.hostingersite.com/deployer?password=YOUR_DEPLOYER_PASSWORD` (URL-encode any special characters in the real password)
   - Content type: `application/json`
   - Events: just the **push** event
   - Active: checked
   - Secret field: left blank (not used — our controller only checks the URL password, not HMAC signatures)

## Day-to-day usage

- **Normal deploy:** just `git push` to `main`. The webhook fires automatically and the site updates within a few seconds.
- **Manual deploy:** visit in browser:
  ```
  https://maroon-magpie-775843.hostingersite.com/deployer?password=YOUR_DEPLOYER_PASSWORD
  ```
- **Reverting a bad push:** use `git revert <commit>` (not `reset --hard` + force push) and push the revert commit — this triggers another auto-deploy that undoes the change. Don't rewrite already-pushed history.

## Notes / gotchas

- The deploy password lives only in the server's `.env`, never in git.
- If the password ever needs to change, update it in `.env` on the server, run `php artisan config:clear`, then update the webhook's Payload URL on GitHub to match.
- The `public_html.bak` folder (original FTP-deployed site) is still sitting on the server as a backup. Safe to delete once confident everything is stable: `rm -rf public_html.bak`.
- `DEPLOYER_RUN_COMPOSER` / `DEPLOYER_RUN_MIGRATIONS` are off by default since shared-hosting PHP requests have a short execution timeout. If a deploy needs a `composer install` or migration, run those manually over SSH instead of relying on the webhook.
