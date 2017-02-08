Product Image Fix
===================

### Fixes Magento 2 GitHub issue #6803

This module is meant as temporary fix for [the issue #6803](https://github.com/magento/magento2/issues/6803).
So after installing this module the method Product::addImageToMediaGallery should work as expected.

**Please note:**
When Magento Inc. will have fixed this issue then this module should be uninstalled!

Requirements
------------
- PHP >= 5.5.0

Installation
------------

### Via composer (recommended)

Please go to the Magento2 root directory and run the following commands in the shell:

```
composer config repositories.diekavallerie_product-image-fix vcs git@github.com:DIE-KAVALLERIE/magento2-product-image-fix.git
composer require DIE-KAVALLERIE/magento2-product-image-fix:dev-master
bin/magento module:enable DieKavallerie_ProductImageFix
bin/magento setup:upgrade
bin/magento magesetup:setup:run <countrycode>
```

### Manually

Please create the directory *app/code/DieKavallerie/ProductImageFix* and copy the files from this repositories *src* folder to the created directory. Then run the following commands in the shell:

```
bin/magento module:enable DieKavallerie_ProductImageFix
bin/magento setup:upgrade
bin/magento magesetup:setup:run <countrycode>
```


Support
-------
If you encounter any problems or bugs, please create an issue on [GitHub](https://github.com/DIE-KAVALLERIE/magento2-product-image-fix/issues).

Contribution
------------
Any contribution to the development of MageSetup is highly welcome. The best possibility to provide any code is to open a [pull request on GitHub](https://help.github.com/articles/using-pull-requests).

Developer
---------
DIE KAVALLERIE GmbH - Team Digital
* Website: [http://diekavallerie.de](http://diekavallerie.de)
* Twitter: [@diekavallerie](https://twitter.com/diekavallerie)

Licence
-------
[Open Software License ("OSL") v. 3.0](http://opensource.org/licenses/osl-3.0)

Copyright
---------
(c) 2017 DIE KAVALLERIE GmbH
