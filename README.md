# Silk Way Travel

WordPress theme and site content for [silkwaytravel.asia](https://silkwaytravel.asia).

## Theme

Custom theme path:

```text
wp-content/themes/silkway
```

Structure follows visit-kg style (tours, about, blog, reviews, gallery, services) with Silk Way brand colors.

## Local setup

1. Install WordPress.
2. Copy `wp-content/themes/silkway` into your WordPress themes directory.
3. Activate **Silk Way Travel** in Appearance → Themes.
4. Optional: restore demo pages/tours/reviews with `setup-content.php` (place in WP root and run via CLI):

```bash
php setup-content.php
```

5. Set permalinks to Post name.

## Admin (local)

Created during local install:

- URL: `/wp-admin`
- User: set during your own install (do not commit credentials)

## Requirements

- WordPress 6+
- PHP 8.0+
