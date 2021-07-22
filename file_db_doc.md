# FileDB:

### Create DB-File
```php
  $users = new FileDB('./path/to/file.fdb', ['id', 'name']);
```

### Read Records
```php
  $users->all();
  - or -
  $users->iter();
  $record = $users->next();
  - or -
  $users->iter();
  $record1 = $users->next();
  $record2 = $users->next();
  - or -
  $users->iter();
  $record1 = $users->next();
  $record2 = $users->next();
  $record2_again = $users->last();
```

### Add Record
```php
  $users->add(['1', 'name']);
  - or -
  $users->add(['whatever' => '1', 'whatever2' => 'name']);
  - or -
  $users->add([['1', 'name'], ['2', 'name2'], ['3', 'name3'], ...]);
  - or -
  $users->add([['whatever' => '1', 'whatever2' => 'name'], ['whatever' => '2', 'whatever2' => 'name2'], ...]);
```

### Delete Record
```php
  $users->iter()->next();
  $users->del()
```

### Update Record
```php
  $users->iter()->next();
  $users->upd(['name'=>'newName']);
  - or -
  $users->iter()->next();
  $users->upd(['id'=>99, 'name'=>'newName']);
```