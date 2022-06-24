# JetEngine - Highlight post by date

Among all the query items, finds posts whose date in the meta field is greater than the current one and makes it possible to style. Something like this:



Plugin doesn't sort posts by date itself, it only adding class to Listing Grid item based on comparison of posts dates. So you need to sort post by your self with Query settings

## Setup
- Download and intall plugin,
- Define configuration constants in the end of functions.php file of your active theme,


Configuration example:

``` php
  define( 'JET_ENGINE_HIGHLIGHT_POST_SCHEDULED_DATE', 'my_date_field' );
```

**Allowed constants:**

- `JET_ENGINE_HIGHLIGHT_POST_SCHEDULED_DATE` - by default `scheduled_post_date` - defines Meta field name/key/ID of posts by scheduled date. You can set any meta field key you want instead to break by meta field,
- `JET_ENGINE_HIGHLIGHT_POST_CLASS` - by default `jet-listing-grid__item-highlight`. Defines the name of the class that is added to the Listing Grid element