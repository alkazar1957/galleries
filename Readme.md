# Gallery
[![License](http://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](http://www.opensource.org/licenses/MIT)
> ### PLEASE NOTE: THIS PACKAGE IS NOT INTENDED FOR USE.
> It is here as a demonstration of a basic gallery package, uses other non-published packages for user/user-roles, here for testing the usage of github and will contains errors as it is UNFINISHED. This example will simply allow an admin user to create/delete galleries but is missing the form to upload images.

## Install

This example package is installed to packages/Alkazar/Gallery

 
In the header add calls to Dropzone and Lightbox:
``` html
    <!-- Lightbox -->
    <link href="{{ asset('/lightbox/css/lightbox.css') }}" rel="stylesheet">
    <script src="{{ asset('/lightbox/js/lightbox.js') }}"></script>

    <!-- DropZone -->
    <link href="{{ asset('/css/dropzone.css') }}" rel="stylesheet">
    <script src="{{ asset('/js/dropzone.js') }}"></script>
```
These are published to the /public directory using:
``` bash
php artisan vendor:publish --tag=gallery-assets
```

To override the package blades use:

```
php artisan vendor:publish --tag=gallery-views 
```

Migrations are run from the packages folder but can be published to the root database/migrations folder using:

```
php artisan vendor:publish --tag=gallery-migrations
```

If any changes are made to the migrations, use:

```
php artisan vendor:publish --tag=(migrations or assets) --force
```

config/app.php 
```
        Intervention\Image\ImageServiceProvider::class,
        'Image' => Intervention\Image\Facades\Image::class,
```

In root composer.json 
```
    "autoload": {
        "classmap": [
            "database"
        ],
        "psr-4": {
            "App\\": "app/",
            "Alkazar\\Gallery\\": "packages/Alkazar/Gallery/src/"
        }
    },
```
Finall in config/app add the Service Provider:
```
 Alkazar\Gallery\GalleryServiceProvider::class,
```
