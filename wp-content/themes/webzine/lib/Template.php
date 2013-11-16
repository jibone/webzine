<?php

class Template {

  protected $layouts  = __DIR__."/views/layouts";
  protected $partials = __DIR__."/views/partials";
  protected $mustache = "";

  public function __construct() {
    $this->mustache = new Mustache_Engine(array(
      'loader'          => new Mustache_Loader_FilesystemLoader($this->layouts, array('extension' => '.html')),
      'partials_loader' => new Mustache_Loader_FilesystemLoader($this->partials, array('extension' => '.html'))
    ));
  }

  public function render($layout, $data) {
    $this->mustache->render($layout, $data);
  }

}
