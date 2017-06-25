<?php

class Item {
  protected $item_id;
  protected $name;
  protected $price;
  protected $image;
  protected $desc;
  private $amount = 0;
  protected static $count = 0;

  public function __construct($item_id, $name, $price, $image, $desc, $desc_long = null) {
    $this->item_id = $item_id;
    $this->name = $name;
    $this->price = $price;
    $this->image = $image;
    $this->desc = $desc;
    $this->desc_long = $desc_long;
  }

  public function getItemId() {
    return $this->item_id;
  }

  public function getName() {
    return $this->name;
  }

  public function getPrice() {
    return $this->price;
  }

  public function getImage() {
    return $this->image;
  }

  public function getDesc() {
    return $this->desc;
  }

  public function getAmount() {
    return $this->amount;
  }

  public function setAmount($amount) {
    $this->amount = $amount;
  }

  public function getTotalPrice() {
    return $this->getPrice() * $this->amount;
  }

  public static function getCount() {
    return self::$count;
  }

}