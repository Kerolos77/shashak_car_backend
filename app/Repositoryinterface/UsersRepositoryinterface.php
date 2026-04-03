<?php
namespace App\Repositoryinterface;

interface UsersRepositoryinterface{



    public function signup();

    public function verify_otp();
    public function send_otp();
    public function profile();
    public function profile_update();
 
    public function city($id);
    public function country();



    // public function profile_update($request);
    // public function profile_details();
    // public function verificationcode($request);
    // public function forgotpassword($request);
    // public function change_password($request);
    // public function resend_code($request);


}

