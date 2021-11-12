<?php
namespace ganbatter\Controller;
class Thread extends \ganbatter\Controller {
  public function run() {
    if($_SERVER['REQUEST_METHOD'] === 'POST') {
      if ($_POST['type'] === 'createthread') {
        $this->createThread();
      }
    }
  }
  private function createThread() {
    try {
      $this->validate();
    } catch ()
  }
}