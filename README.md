# Shortest.top

An URL shorter created for a school project created by [Synctrex](https://github.com/Synctrex), [PalOne](https://github.com/p410n3) and me. It's UI is in German.
Note that this is developed with an development environment in mind, you shouldn't install it on your webserver without securing the code against both; the stupidness of the average user as well as spam-attacks.

## Requirements
* PHP with PDO
* MySQL Database

## Some core features

* Short URLs can be categorized; categories are created by the admin
* Users can create accounts
* Admin Panel allows the admin to view and edit all links, filter them by user/url/description/category
* Create Short URLs using either a random string or 3 words string-concatenation.
* Trying to create duplicate sites from the same user will simply return the existing short url instead of creating a new one

## Screenshots

[Index](http://shortest.top/img/overview.png) 
[URL List](http://shortest.top/img/urllist.png)
[Admin Panel](http://shortest.top/img/admin.png)
[Edit](http://shortest.top/img/edit.png)

## Some notes

* Deleting an user also deletes all his URLs. That's by design and not a bug; you can assume that this user was deleted for a reason.
* Deleting a category will move all URLs in said categorie in the "Andere" ("others") category.
* You can edit users, categories and URLs. Users can edit themselves and their own URLs.