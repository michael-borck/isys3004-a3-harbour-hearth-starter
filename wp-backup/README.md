# WordPress backup

The Harbour & Hearth WordPress site is not stored in this repository. The backup
file is too large for git and is hosted externally.

## Download

> **Download link:** TBC on Blackboard, in the Assessment 3 area.
>
> The same file will be linked from the Assessment 3 instructions.

You are after a LocalWP export file with a `.wpress` extension.

## What is in it

The WordPress site provides the parts of Harbour & Hearth that are not plain
static HTML:

- the **News** section (a normal WordPress blog with a few posts)
- the **team** data used by `team.html`
- the **booking** handling behind `booking.html`
- an older administrator account and a plugin list the previous developer left

## Playground fallback

If you cannot install LocalWP, or you are on a lab machine, use
`blueprint.json`:

1. Open <https://playground.wordpress.net/>.
2. Import `blueprint.json` from this folder.
3. Wait for the plugins and content to install.
4. Copy the Playground site URL from the browser.

The blueprint creates the news posts, pages, sample bookings, the `olddev`
administrator and the `/wp-json/hh/v1/team` and `/wp-json/hh/v1/bookings`
endpoints.

The static pages use relative paths for those endpoints. If the WordPress site
is on a different origin (LocalWP or Playground), you will need to point
`assets/js/team.js` and the booking form action at the WordPress URL. That is one
of the things you may need to fix.

## Build the `.wpress` from the starter (coordinator)

Use a LocalWP site named `harbour-hearth-a3`. In the LocalWP site shell:

1. Install the plugins:

   ```bash
   wp plugin install wp-super-cache ml-slider classic-editor
   wp plugin activate wp-super-cache
   ```

2. Seed the content:

   ```bash
   wp eval-file /path/to/provided/a3-harbour-hearth-starter/wp-backup/seed-localwp.php
   ```

   Use the real path to this repository.

3. Install the exporter:

   ```bash
   wp plugin install all-in-one-wp-migration --activate
   ```

4. In WP Admin, go to **All-in-One WP Migration > Export > Export to > File**
   and download the `.wpress`.

5. Upload it to Blackboard and replace the TBC link at the top of this file.

6. Test the restore in a fresh LocalWP site.

The LocalWP site URL becomes the restored site's URL. If you use
`harbour-hearth.local`, the docs match. On a lab machine, use `127.0.0.1` and
run one site at a time.

## Restore steps (LocalWP)

1. Install **LocalWP**: <https://localwp.com/>.
2. Open LocalWP.
3. Drag the `.wpress` file onto the LocalWP window, or use **Add Local Site >
   Restore from backup**.
4. Keep the suggested site name, or use `harbour-hearth`.
5. Start the site and open **WP Admin**.

On a lab machine, LocalWP installs per-user. If it cannot update the hosts file,
cancel the warning, set the site URL to `127.0.0.1`, and run one site at a time.

## Dashboard login

- **Username:** `admin`
- **Password:** `harbour-hearth-2026`

Local-only credentials for a throwaway practice site. They are not secret and
not reused anywhere.

## Files in this folder

`mu-plugin.php` and `build-blueprint.py` are the source for the Playground
backend. `blueprint.json` is generated. The `.wpress` file and extracted
WordPress files are git-ignored. Do not commit the backup.
