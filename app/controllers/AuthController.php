<?php

namespace App\Controllers;

use App\Models\User;
use App\Helpers\Helpers;
use Sirius\Validation\Validator;

class AuthController  extends BaseController{


    public function getIndex(){

        return $this->view('auth/login.twig');
    }

    public function getLogin(){

        return $this->view('auth/login.twig');
    }

    public function postLogin(){

        $validator = new Validator();
        $validator->add('email','required', null,'El correo es obligatorio.');
        $validator->add('email','email', null,'El correo es obligatorio.');
        $validator->add('password','required', null,'El password es obligatorio.');

        if($validator->validate($_POST)){
            $user = User::where('email',$_POST['email'])->first();
            if($user){
                if(password_verify($_POST['password'],$user->password)){
                    $_SESSION['userId'] = $user->id;
                    $_SESSION['name'] = $user->name;
                    if(isset($_POST['ajax'])){
                        echo 1;
                        exit;
                    }else{

                   
                    if(!\App\Helpers\Helpers::isAdmin($user->id)){
                        header('Location:' . \App\Helpers\Helpers::get_home_url().'cuenta');   
                        exit;
                     }
                      header('Location:'. \App\Helpers\Helpers::get_home_url().'admin');
                    exit;
                    }

                }
            }
            //Not OK
            $validator->addMessage('email','Correo/Password invalido intente de nuevo');

        }
        $errors = $validator->getMessages();
       
        return $this->view('auth/login.twig',['errors'=>$errors]);
        
    }

    public function getLogout(){
        unset($_SESSION['userId'], $_SESSION['name']);
        header('Location:' .\App\Helpers\Helpers::home_url().'auth/login');
    }

    public function getSalir(){
        unset($_SESSION['userId'], $_SESSION['name']);
        header('Location:' .\App\Helpers\Helpers::getReferer());
    }
}