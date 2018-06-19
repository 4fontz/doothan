<?php
class UserResetPassword extends CFormModel {
	public $password;
	public $verifyPassword;
	
	public function rules() {
		return array(
			array('password, verifyPassword', 'required'),
			array('password, verifyPassword', 'length', 'max'=>128,'message' => "Incorrect password (minimal length 4 symbols)."),
			array('verifyPassword', 'compare', 'compareAttribute'=>'password', 'message' => "Retype Password is incorrect."),
		);
	}
	public function attributeLabels()
	{
		return array(
			
			'password'=>"password",
			'verifyPassword'=>"Retype Password",
		);
	}

}
?>
