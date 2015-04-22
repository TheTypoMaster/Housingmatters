<?php
App::import('Controller', 'Hms');
class ClassifiedsController extends HmsController {
var $helpers = array('Html', 'Form','Js');
public $components = array(
'Paginator',
'Session','Cookie','RequestHandler'
);


var $name = 'Classifieds';




}
?>