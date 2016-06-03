Pagination Plugin
=============
This library is a simple pagination plugin for your website using Bootstrap CSS

Version 1
-------
Version 1 is a simple version of the Plugin, easy to use.

Version 2
-------
Version 2 is a class that containing the most part of the code present in the version 1 of this plugin, you can easly use it.

Usage
-------
Initialize Pagination with `PDO Database Connection` - `Rows per Page` - `Database Table`
```php
$pagination = new \Cownnect\Framework\Plugins\Pagination($pdo,10,"table");
```
Run pagination
```php
$pagination->Paginate();
```
Add SQL limit to your query
```php
$db->query("SELECT * FROM table " . $pagination->Limit());
```
Show pagination in HTML (It will return in LI Format)
```php
<ul class="pagination">
  <?php echo $pagination->View(); ?>
</ul>
```
