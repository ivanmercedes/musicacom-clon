<?php


namespace App\Controllers\Admin;

use App\Models\User;
use App\Helpers\Helpers;
use App\Controllers\BaseController;
use Plasticbrain\FlashMessages\FlashMessages;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;

class UsersController extends BaseController {
 
    public function getIndex(){
        
        Helpers::CheckAdmin();
        $pages= (isset($_GET['page'])? $_GET['page'] : 1);

        Paginator::currentPageResolver(function () use ($pages) {
            return $pages;
        });
         
         $users = User::orderBy('id','ASC')->where('status','=','1')->paginate();
         $paginate = Helpers::paginate($users);

         return $this->view('admin/user/index.twig', compact('users','paginate'));
    }
  
    public function getEdit($id_user){
        
        Helpers::CheckAdmin();
        $users = User::find($id_user);
        return $this->view('admin/user/edit.twig',compact('users'));
    }

    public function getAgregar(){
        
         Helpers::CheckAdmin();
         return $this->view('admin/user/add.twig');
    }

    public function postAgregar(){
        
        Helpers::CheckAdmin();
        
        $user = new User();
        $user->name = $_POST['nombre'];
        $user->email = $_POST['correo'];
        $user->password = password_hash($_POST['password'],PASSWORD_DEFAULT);
        $user->role = $_POST['rol'];
        $result = $user->save();

        $this->setFlashMsg('success','Usuario agregado correctamente');
        header('location:'. \App\Helpers\Helpers::home_url().'admin/usuarios');
        
   }

   public function postEdit($id_user){
        
    Helpers::CheckAdmin();
    
    $user = User::find($id_user);
    $user->name = $_POST['nombre'];
    $user->email = $_POST['correo'];
    if(!empty($_POST['password'])){
       $user->password = password_hash($_POST['password'],PASSWORD_DEFAULT);
    }
    $user->role = $_POST['rol'];
    $result = $user->save();

    $this->setFlashMsg('success','Usuario actualizado correctamente');
    header('location:'. \App\Helpers\Helpers::home_url().'admin/usuarios');
    
   }

    public function getDelete($id){
        Helpers::CheckAdmin();
         $del= User::find($id);
         if($del){
            $del->delete();
            $this->setFlashMsg('success','Usuario Eliminado correctamente');
            header('location:'.Helpers::getReferer());
          exit;
        } 
        $this->setFlashMsg('error','Error al Eliminar Usuario');
        header('location:'.Helpers::getReferer());
        exit;         
    }
}