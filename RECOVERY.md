# Recovery

Common setup problems and how to get unstuck. None of this is part of the
assessment. It is plumbing. If you are still stuck, ask on the unit discussion
board.

## The `.wpress` backup will not restore in LocalWP

LocalWP changed its restore flow between versions, so drag and drop does not
always work.

1. In LocalWP, create a new blank site (any name, PHP 8.1+, WordPress latest).
2. Start it and open **WP Admin**.
3. Install and activate **All-in-One WP Migration**.
4. Go to **All-in-One WP Migration > Import**, choose **Import from file**, and
   select the `.wpress` file.
5. Confirm the overwrite, then log back in.

If the file looks corrupt, re-download it. Partial downloads are the usual cause.

## LocalWP will not start, or the site URL is wrong

- Stop other LocalWP sites. Run one at a time.
- On a lab machine, LocalWP cannot update the hosts file. Cancel the warning,
  open the site settings, and set the URL to `127.0.0.1`.
- If the WordPress URL differs from the examples, use the URL LocalWP shows in
  the **Overview** tab.

## `node mock-api/server.js` says port 4000 is in use

Free port 4000, or run on another port:

```bash
PORT=4001 node mock-api/server.js
```

The front end talks to port 4000. If you move the API, update the URL in
`assets/js/menu.js` to match.

## The menu page is empty

- Is the mock API running? Look for the "running at http://localhost:4000" line.
- Test it: `curl http://localhost:4000/api/menu`.
- Are you using the static server (`http://localhost:8765/...`) rather than
  `file://`?
- Open DevTools, Console and Network. The page will tell you what went wrong.

## The gift card widget does not appear

The pages load `gift-card-widget/dist/gift-card-widget.iife.js`. If it is
missing or stale, rebuild it:

```bash
cd gift-card-widget
npm install
npm run build
```

If `npm install` fails, check your network and try again.

## `node` or `npm` is missing or old

`node --version` should be 18 or newer. Install the current LTS from
<https://nodejs.org/> and open a new terminal.

## Static server port 8765 is in use

Pick another free port:

```bash
python3 -m http.server 8080
```

The static server port does not matter to anything else.

## Nuclear option

If your working copy feels broken, clone a fresh copy into a separate folder and
diff against it. Do not delete your working copy. Your git history is part of
what you submit.
