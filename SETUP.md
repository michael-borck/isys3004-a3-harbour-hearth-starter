# Setup

How to get the Harbour & Hearth site running on your own machine. Budget about
20 to 30 minutes the first time, mostly waiting on the WordPress restore.

If something here does not work, check `RECOVERY.md` before panicking.

## What you need installed

- **Node.js** 18 or newer: <https://nodejs.org/>
- **LocalWP**: <https://localwp.com/> for the WordPress part, or the Playground
  blueprint if you cannot install LocalWP
- A terminal
- A modern browser with DevTools

The static site has no build step. The mock API has no dependencies.

## The pieces

| Piece | What it is | How it runs |
|---|---|---|
| Static frontend | The HTML, CSS and JS pages | Any static file server |
| Mock menu API | A small Node server | `node mock-api/server.js` |
| WordPress site | News, team and bookings | LocalWP or Playground |
| Gift card widget | A React bundle | Already built in `gift-card-widget/dist/` |

You want all three running to see the whole site behave.

## 1. Restore the WordPress site

Follow `wp-backup/README.md`. Download the `.wpress` backup from Blackboard, or
use the Playground blueprint if you are on a lab machine. Start the site.

Write down the local URL LocalWP gives you, for example
`http://harbour-hearth.local`.

> **About the URL:** these instructions assume a site named `harbour-hearth`,
> which gives the domain `harbour-hearth.local`. If LocalWP assigned a different
> domain, check the site's **Overview** tab and use that URL wherever the docs
> say `harbour-hearth.local`.

To reach the WordPress dashboard, go to `/wp-admin` and log in with:

| Field | Value |
|---|---|
| Username | `admin` |
| Password | `harbour-hearth-2026` |

These are local-only credentials for a throwaway practice site. They are not
secret and not reused anywhere.

On a lab machine, LocalWP installs per-user. If it cannot update the hosts file,
cancel the warning, set the site URL to `127.0.0.1`, and run one site at a time.

## 2. Run the mock menu API

> You do not need to read or change anything in `mock-api/`. It is a small
> server so the menu page has data. Start it, leave it running, move on.

From the repository root:

```bash
node mock-api/server.js
```

You should see:

```
Harbour & Hearth mock menu API running at http://localhost:4000
```

Quick check:

```bash
curl http://localhost:4000/api/menu
```

That should return JSON with a list of menu items.

## 3. Serve the static frontend

**Option A, Python:**

```bash
python3 -m http.server 8765
```

**Option B, Node:**

```bash
npx serve -l 8765
```

Then open <http://localhost:8765/index.html>.

> Do not open the HTML files with `file://`. Several pages make network requests
> that browsers block from `file://`. Always go through the static server.

## 4. Optional: run the gift card widget dev server

You only need this if you want to modify the widget. A pre-built bundle is
committed at `gift-card-widget/dist/gift-card-widget.iife.js`, and the static
pages load that.

```bash
cd gift-card-widget
npm install
npm run dev
```

When you are done, rebuild the bundle:

```bash
npm run build
```

That overwrites `gift-card-widget/dist/gift-card-widget.iife.js`. Commit the
rebuilt bundle.

## What "working" looks like

With the mock API running and the static site served:

- The home page loads with the hero section and the gift card widget.
- The menu page shows a list of items.
- The news link goes to the WordPress site.
- The booking and team pages load.

If some of that does not behave the way a finished site should, that is the
point of this assessment. Find out why and write it up. Start with DevTools open.
