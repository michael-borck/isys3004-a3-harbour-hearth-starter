# Harbour & Hearth website

Hi, and sorry.

If you're reading this you've probably picked up the Harbour & Hearth site after
me. I did the web stuff for the group for about a year, part time, and I was
part way through redoing the site when life got busy and I had to step back. I
feel bad leaving it half done, so I've tried to write this so you're not
completely lost.

I am not a professional developer. I did a bit of coding and I was the only one
around who could, so I picked up the website. Please don't assume everything in
here is done "the right way". It mostly works, but there are rough edges.

## What this is

A small website for Harbour & Hearth, a Perth cafe and bakery group. Static
pages for the public side, a WordPress site behind it for news, team and
bookings, a gift card widget, and a tiny mock server I used for the menu page
while there was no real menu service.

## How it's laid out

```
index.html, menu.html, booking.html, team.html, gift-card.html
                       the actual pages
assets/css/            styles (one main.css, I kept it simple)
assets/js/             the page scripts
assets/img/            images
gift-card-widget/      the React gift card widget (built file is in dist/)
mock-api/              little Node server for the menu list
wp-backup/             how to get the WordPress part (hosted elsewhere)
```

There is a `SETUP.md` with step-by-step instructions for getting it all running
locally, and a `RECOVERY.md` for when something won't cooperate. Start with
`SETUP.md`.

## Stuff I know isn't great (sorry)

I'd rather be honest than have you find these the hard way:

- **The hero image on the home page is way too big.** It's a massive photo
  straight off someone's phone. I meant to resize and compress it and never did.
  It makes the home page slow.
- **The mobile menu never worked properly.** On a narrow screen the navigation
  is a bit broken. I started on a hamburger menu and didn't finish it. On
  desktop it is fine.
- **There is an "API key" sitting in the menu page JavaScript.** I know that is
  not how keys are supposed to work. Anyone can read it in the browser. The plan
  was to move it to the server once we had a proper menu service.
- **The booking form points at a WordPress endpoint that may not be where you
  think it is.** I copied the action path from the old site and never tested it
  from the static server.
- **The gift card widget was adapted from a donation widget.** It still has a
  "monthly" option, which does not make sense for a gift card. I never finished
  the cleanup.

There are almost certainly other things that are not right. I was learning as I
went. If something looks off, it probably is. Trust your eye and dig in.

## The gift card widget

It is a small React thing built with Vite. The built bundle is committed in
`gift-card-widget/dist/` so the pages work without you building anything. If you
want to change the widget itself, there are notes in `SETUP.md`.

## The WordPress part

That is not in this repo because the backup file is too big for git. See
`wp-backup/README.md` for the download link, the restore steps, and the
Playground fallback. It is the same LocalWP process you may have done before.

## Last thing

Thank you for taking this on. The site matters to the business more than you
might think. It is how people find the menu, book the private room, and buy gift
cards. Do what you can. It does not have to be perfect.

— the previous developer
