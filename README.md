# CRUD. Generator

### Introduction
**This package is a useful tool to generate simple crud for [laravel-modules/scaffolding](https://github.com/laravel-modules/scaffolding)** 

the files that will be generated is:
- Lang Files (ar & en)
- Breadcrumb File
- View Files
- Api Resource Files
- Migration Files
- Factory File
- Policy Files
- Controller Files
- Model Files
- Request Files
- Filter Files
- Test Files

### Installation
```shell
composer require ahmed-aliraqi/crud-generator --dev
```
### Configuration
You should add config file using the following command to configure the supported resources.
```shell
php artisan vendor:publish --provider="AhmedAliraqi\CrudGenerator\CrudServiceProvider"
```

Then add the following comment line in the `routes/dashboard.php` and `routes/api.php` files:
```php
/*  The routes of generated crud will set here: Don't remove this line  */
```
And the follwing comment line in the `resources/views/layouts/sidebar.blade.php` file:
```blade
{{-- The sidebar of generated crud will set here: Don't remove this line --}}
```

### Usage
For example if you want to generate a new CRUD named `category`. make sure it's arabic words was defined in `arabicWords` of config file and then use the following `artisan` command:
```shell
php artisan make:crud category
```
Use `translatable` option if the CRUD is translatable:
```shell
php artisan make:crud category --translatable
```
Use `has-media` option if the CRUD has media:
```shell
php artisan make:crud category --has-media
```
Also you can use both options together to generate translatable and has media CRUD.
```shell
php artisan make:crud category --translatable --has-media
```
