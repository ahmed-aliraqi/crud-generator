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

### Account Type Cloner
```shell
php artisan account:clone customer merchant
```
> This command will clone `customer` account type to another type named `merchant`,

Some files should be modified manually like:
- add constant for the newly generated type in `app/Models/User.php`
```php
/**
 * The code of merchant type.
 *
 * @var string
 */
const MERCHANT_TYPE = 'merchant';
```
Then register the type in `childTypes` array:
```php
/**
 * @var array
 */
protected $childTypes = [
    // other types ...
    self::MERCHANT_TYPE => Merchant::class,
];
```
- Add check for type helper in `app/Models/Helpers/UserHelper.php`:
```php
/**
 * Determine whether the user type is merchant.
 *
 * @return bool
 */
public function isMerchant()
{
    return $this->type == User::MERCHANT_TYPE;
}
```
- Add seeders in `database/seeders/UserSeeder.php`:
```php
Merchant::factory()->count(10)->create();
```
- Add translation lang file name to `config/lang-generator.php`
```php
    'lang' => [
        // ...
        'merchants' => base_path('lang/{lang}/merchants.php'),
        /*  The lang of generated crud will set here: Don't remove this line  */
    ],
```
- Update arabic translations in lang file for generated type `lang/ar/merchant.php`
- Add type translated key into `lang/{lang}/users.php`:
```php
'types' => [
    // Other types ...
    'merchant' => 'Merchant',
],
```
- Clone view files in dashboard from `customer` directory to `merchant` and replace all `customer` word
 to `merchant`
  - `Customer` => `Merchant` 
  - `customers` => `merchants` 
  - `customer` => `merchant` 
- Add Sidebar link in `resources/views/dashboard/accounts/sidebar.blade.php`:
```php
[
    'name' => trans('merchants.plural'),
    'url' => route('dashboard.merchants.index'),
    'can' => ['ability' => 'viewAny', 'model' => \App\Models\Merchant::class],
    'active' => request()->routeIs('*merchants*'),
], 
```
- Add the routes for the newly generated type in `routes/dashboard.php` file:
```php
// Merchants Routes.
Route::get('trashed/merchants', 'MerchantController@trashed')->name('merchants.trashed');
Route::get('trashed/merchants/{trashed_merchant}', 'MerchantController@showTrashed')->name('merchants.trashed.show');
Route::post('merchants/{trashed_merchant}/restore', 'MerchantController@restore')->name('merchants.restore');
Route::delete('merchants/{trashed_merchant}/forceDelete', 'MerchantController@forceDelete')->name('merchants.forceDelete');
Route::resource('merchants', 'MerchantController');
```
- Add route binding in `storage/soft_deletes_route_binding.json`:
```json
{
 "trashed_merchant": "App\\Models\\Merchant"
}
```
- Add the permision in `storage/permissions.json`:
```json
[
    "manage.merchants"
]
```
- Add `actingAsMerchant` helper into `tests/TestCase.php`
```php
  /**
   * Set the currently logged in merchant for the application.
   *
   * @param null $driver
   * @return \App\Models\Merchant
   */
  public function actingAsMerchant($driver = null)
  {
      $merchant = Merchant::factory()->create();

      $this->be($merchant, $driver);

      return $merchant;
  }
```
