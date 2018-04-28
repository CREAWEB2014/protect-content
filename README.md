# WordPress Protect Content

A simple WordPress plugin that allows to protect pages and posts and assign them to specific users.

## Installing

You can easily install this plugin in your WordPress site following these steps:

- Download the ZIP archive.
- Upload the protect-content folder into your WordPress plugins folder.
- Activate the plugin.
- Create a post or a page you want to protect
- In the "Protect Content" box, activate the "Protect" checkbox.
- Assign the users you want to be able to access the content.
- Optionally, specify a redirect url where send logged users that don't have access to the content.
- Enjoy!

## Programmatic use

This plugin exposes a series of APIs to interact with protected contents:

**Add an user to a protected content**
```
FTProtectContentAPI::add_user($post, $user)
```

**Remove an user from a protected content**
```
FTProtectContentAPI::remove_user($post, $user)
```

**Add multiple users from a protected content**
```
FTProtectContentAPI::add_users($post, $users)
```

**Remove multiple users from a protected content**
```
FTProtectContentAPI::remove_users($post, $users)
```

**Protecte a content**
```
FTProtectContentAPI::protect_content($post)
```

**Unprotect a content**
```
FTProtectContentAPI::unprotect_content($post)
```

**Set content redirect URL**
```
FTProtectContentAPI::set_redirect_url($post, $redirect_url)
```

**Get a post protection details**
```
FTProtectContentAPI::get_protection_details($post)
```
