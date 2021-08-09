<?php
  // Error Class Extension
  class FileDBError extends Error {}

  // FileDB Class
  class FileDB {
    private $iter = false;
    private $iter_dat = [];
    private $last_iter = [];
    private $iter_ind = -1;

    // __construct() : Constructor
    public function __construct(string $file, array $fields = ['key', 'value']){
      // Filename
      if(!empty($file)){
        $this->file = $file;
        $ready = file_exists($this->file);

        if(!fopen($this->file, "a+"))
          throw new FileDBError('Unable to access file!');
        elseif(!$ready)
          $this->put([]);
      }
      else
        throw new FileDBError('FileName cannot be empty.');

      // Fields
      if(!empty($fields))
        $this->fields = $fields;
      else
        throw new FileDBError('Fields Cannot be empty');
    }

    // add() : add new record(s)
    public function add(array $data){
      if (gettype($data[array_keys($data)[0]]) == 'array')
        foreach ($data as $dat)
          $this->add($dat);

      elseif (count(array_values($data)) == count($this->fields)){
        $fdat = $this->get();
        $fdat []= array_values($data);
        $this->put($fdat);
        return $this;
      }

      else
        throw new FileDBError('Invalid Data to add!');
        
      return false;
    }

    // all() : Fetch All Records
    public function all(){
      $all = [];
      foreach($this->get() as $single){
        $unit = [];
        for($i=0; $i<count($this->fields); $i++)
          $unit[$this->fields[$i]] = $single[$i];
        $all []= $unit;
      }
      return $all;
    }

    // iter() : Start Iteration
    public function iter(){
      $this->iter_dat = $this->all();
      $this->iter=true;
      $this->iter_ind=-1;
      return $this;
    }

    // next() : Get Next Records (iteration)
    public function next(){
      if(!$this->iter)
        return false;
      
      if(count($this->iter_dat)<=1)
        $this->iter = false;
      
      $this->iter_ind++;
      return ($this->last_iter = array_shift($this->iter_dat));
    }

    // last() : Get last fetched Records by next() call (iteration)
    public function last(){
      return $this->last_iter;
    }

    // del() : delete most recent fetched record by next()
    public function del(int $ind=-1){
      if($ind=-1){
        if((!$this->iter) && ($this->iter_ind!=0))
          throw new FileDBError('Call iter() & next() before del()!');
        elseif($this->iter_ind<=-1)
          throw new FileDBError('Call next() before del()!');
        else
          $ind = $this->iter_ind;
      }
      elseif($ind<-1)
        throw new FileDBError("Invalid index $ind for del()!");

      $dat = $this->get();
      unset($dat[$ind]);
      $dat = array_values($dat);
      $this->put($dat);

      return $this;
    }

    // upd() : update most recent fetched record by next()
    public function upd(array $update, int $ind=-1){
      if($ind=-1){
        if(!$this->iter)
          throw new FileDBError('Call iter() & next() before upd()!');
        elseif($this->iter_ind<=-1)
          throw new FileDBError('Call next() before upd()!');
        else
          $ind = $this->iter_ind;
      }
      elseif($ind<-1)
        throw new FileDBError("Invalid index $ind for delete()!");
      
      $dat = $this->get();
      $last_iter = $this->last();
      foreach($update as $key => $value)
        if(in_array($key, $this->fields))
          $last_iter[$key] = $value;
      $dat[$ind] = array_values($last_iter);
      $this->put($dat);

      return $this;
    }

    // - Privates

    // invalid_fix() : Backup and Re-Start (from Empty) DB
    private function invalid_fix(bool $warn=true, array $new_content = [], string $postfix = '_bk') {
      trigger_error("Invalid Data in file! Backing up file (with '$postfix' postfix) and starting with blank data.", E_USER_WARNING);
      copy($this->file, $this->file.$postfix);
      file_put_contents($this->file, serialize($new_content));
    }

    // valid_fdb_dat() : Determines weather given DBFile is valid or not
    private function valid_fdb_dat($dat){
      if(gettype($dat) != 'array')
        return false;
      if(count($avals = array_values($dat))>0)
        foreach($avals as $val)
          if(
            (gettype($val)!='array') ||
            count($val) != count($this->fields)
          )
            return false;
      return true;
    }

    // get() : Get Data From DBFile
    private function get() {
      $data = [];

      try {
        $data = unserialize(file_get_contents($this->file));
      } catch (\Throwable $th) {
        $this->invalid_fix();
      }
      
      if(!$this->valid_fdb_dat($data)){
        $this->invalid_fix();
        $data = [];
      }

      return $data;
    }

    // put() : Put Data to DBFile
    private function put(array $data){
      file_put_contents($this->file, serialize($data));
      return $this;
    }
  }
?>
