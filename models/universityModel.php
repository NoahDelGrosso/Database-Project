<?php
/* models/universityModel.php */

class University extends Model {

function __construct() {
  $this->tablename = 'universities';
  $this->id_name = 'university_id';
  $this->title_name = 'university_name';
  $this->form_fields = array('university_name', 'university_description', 'university_enrollment',
    'university_picture' => array('name' => 'university_picture', 'type' => 'standard', 'description' => 'URL of image'),
    'university_zipcode',
    'university_point' => array('name' => 'university_point', 'type' => 'point', 'description' => 'Format: XX.X, XX.X [ like 28.5,-72.1]'),
  );
  $this->config = array();
  $this->config['main']['contentFuncs']['university_picture'] = array('function' => 'return_picture', 'argument' => NULL);
}


}
