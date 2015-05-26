<?php

class Collection {
    public $unique;
    public $prop;
    public $colName;

    public function __construct($col){

        $this->colName = isset($col['colName']) ? $col['colName'] : 'collection';
        $this->unique = isset($col['unique']) ? $col['unique'] : true;
        $this->prop = isset($col['prop']) ? $col['prop'] : 'ID';
        $this->{$this->colName} = isset($col['collection']) ? $col['collection'] : [];
    }

    public function add ($item, $prop = false){
        if($this->unique){
            $index = $this->indexOf($item, $prop? : $this->prop);
            if($index > -1) return false;
        }
        return $this->{$this->colName}[] = $item;
    }

    public function update ($item, $prop = false){
        $index = $this->indexOf($item, $prop? : $this->prop);
        if($index <= -1) return false;
        return $this->{$this->colName}[$index] = $item;
      }

    public function remove ($item, $prop = false){
        $index = $this->indexOf($item, $prop? : $this->prop);
        if($index <= -1) return false;
        unset($this->{$this->colName}[$index]);
        return $this->{$this->colName};
    }

    public function clear (){
        $this->{$this->colName} = [];
    }

    public function set ($newCollection){
        $this->{$this->colName} = $newCollection;
    }

    public function get ($item = false, $prop = false){
        if(!$item) return $this->{$this->colName};
        $index = $this->indexOf($item, $prop? : $this->prop);
        if($index <= -1) return false;
        return $this->{$this->colName}[$index];
      }

    public function indexOf ($item = false, $prop = false){
        if(!$item) return false;
        if($prop) return Utils::indexOf($this->{$this->colName}, $item, $prop);
        $index = array_search( $this->{$this->colName}, $item );
        return $index === false ? -1 : $index;
    }
}