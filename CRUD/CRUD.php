<?php
require_once("crud.class.php");

$form = new TypeCrud();
$form->renderCreate();
echo $form->formCreate;

