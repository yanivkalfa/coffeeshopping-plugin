<?php

class Collection extends BasicCartObject{
    public $unique;
    public $prop;
    public $colName;

    public function __construct($col){

        $this->colName = isset($col['colName']) ? $col['colName'] : 'collection';
        $this->unique = isset($col['unique']) ? $col['unique'] : true;
        $this->prop = isset($col['prop']) ? $col['prop'] : 'ID';
        $this->{$this->colName} = isset($col['collection']) ? $col['collection'] : [];
    }

    public function generateRandomId($randId = ''){

        if($this->isUnique($randId) && !empty($randId)){
            return $randId;
        }
        $randId = '';
        for($i = 0; $i<10; $i++){
            $randId .= rand(1,100);
        }
        return $this->generateRandomId($randId);
    }

    public function isUnique ($uniqueProp){
        $items = $this->get();
        foreach($items as $item){
            $item = (array)$item;
            if($item[$this->prop] == $uniqueProp) return false;
        }
        return true;
    }

    public function add ($item, $prop = false){
        if($this->unique){
            $index = $this->indexOf($item, $prop? : $this->prop);
            if($index > -1) return false;
        }
        $this->{$this->colName}[] = $item;
        return true;
    }

    public function update ($item, $prop = false){
        $index = $this->indexOf($item, $prop? : $this->prop);
        if($index <= -1) return false;
        $this->{$this->colName}[$index] = $item;
        return true;
      }

    public function remove ($item, $prop = false){
        $index = $this->indexOf($item, $prop? : $this->prop);
        if($index <= -1) return false;
        array_splice($this->{$this->colName}, $index,1);
        return true;
    }

    public function clear (){
        $this->{$this->colName} = [];
        return true;
    }

    public function set ($newCollection){
        $this->{$this->colName} = $newCollection;
        return true;
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