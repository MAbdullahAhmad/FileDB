## FileDB

Store Data in File like DB CRUD. Useful in environments without DB.

    Note : Not Recommended for Large data storage. Use for data which have less record instances count.

Records for each entity are stored in a seperate file.

### How-to:

  1. **Extract** at any location in project. (Better be in ```lib``` directory).
  2. Include where needed.
  3. Create an entity and it's file with statement : 
    ```
    $users = new FileDB('./path/to/file.fdb', ['id', 'name']);
    ```
  3. **Enjoy** with CRUD with simple methods:

  - ```add(<array>)``` : **Add one** record.
  - ```all()``` : **Fetch All** Records
  - ```iter()```, ```next()```, ```last()``` : **Iteration** methods.
  - ```del()``` : **Delete Single** record. It works with iteration only.
  - ```upd(<array>)``` : **Update Single** record .works with iteration only.

For Futher Detais, check out ```file_db_doc.md```.

### It needs improvement

```FileDB``` was created to improve storing data in files a little. It is not much more efficient (it can be). A future update may be more useful.
