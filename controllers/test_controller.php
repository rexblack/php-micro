<?php return new class {
  protected $db;

  public function index() {
    print_r($this->db);
  }

  public function show() {
    echo "SHOW";
  }

  public function edit() {
    echo "EDIT";
  }
};
