<?php  


namespace App\Helpers;
use App\Models\User;
use App\Models\Artist;
use Melbahja\Seo\Factory;


class Helpers {

 
    public static function isAdmin($id){
        self::isLogin();
        $check = User::find($_SESSION['userId']);
        if($check && $check->id === 1 && $check->role === 1){
            return true;
        }
        return false;
    }

    public static function isLogin(){
        if(!isset($_SESSION['userId'])){
            header('Location:' . self::home_url().'auth/login');
             exit;      
        }  
    }

    public static function CheckAdmin(){
        if(!self::isAdmin($_SESSION['userId'])){
            header('Location:' . self::home_url().'cuenta');
            exit;
         }
    }

    public function home_url(){
        $baseUrl = '';
        $baseDir = str_replace(basename($_SERVER['SCRIPT_NAME']), '',$_SERVER['SCRIPT_NAME']);
        $baseUrl = (isset($_SERVER['HTTPS']) ? "https://" : "https://") .$_SERVER['HTTP_HOST']. $baseDir;
        return $baseUrl;
    }

    
    public static function get_home_url(){
        $baseUrl = '';
        $baseDir = str_replace(basename($_SERVER['SCRIPT_NAME']), '',$_SERVER['SCRIPT_NAME']);
        $baseUrl = (isset($_SERVER['HTTPS']) ? "https://" : "https://") .$_SERVER['HTTP_HOST']. $baseDir;
        return $baseUrl;
    }

    public static function getFeatSeo($feats){

            if(empty($feats)){ return false; }

            $feats = unserialize($feats);
            $results['feats']  = Artist::query()->select('name')->find($feats);
            
            foreach ($results['feats'] as $key) {
                $n[] = $key->name;
            }

            $results = ' Ft '.join(' & ',
            array_filter(array_merge(array(join(', ', array_slice($n, 0, -1))),
            array_slice($n, -1)), 'strlen'));
            return $results;
        
    }

    
    public static function createSchema($arr){

        $schema = Factory::schema('WebSite')
        ->url('https://example.com')
        ->logo(BASE_URL.'img/logo.png')
            ->contactPoint
                ->telephone('+1-000-555-1212')
                ->contactType('customer service');
    }

    public static function getUserIpAddr(){
        if(!empty($_SERVER['HTTP_CLIENT_IP'])){
            //ip from share internet
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        }elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
            //ip pass from proxy
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }else{
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }
    public static function paginate($posts,$size ='',$adjacents = 3) {


        $page= (isset($_GET['page'])? $_GET['page'] : 1);
        $actual_link = strtok((isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]",'?');	
        $tpage = ceil ($posts->total() / $posts->perPage() );

        $prevlabel = " <i class=\"fas fa-angle-left text-danger\"></i>";
        $nextlabel = " <i class=\"fas fa-angle-right text-danger\"></i>";
        $out  ='<nav aria-label="Page navigation">';
        $out .='<ul class="pagination justify-content-center pagination-'.$size.'">';
        
        // previous label
        if($page==1) {
        //  $out.= "<li class='disabled'><a  class='page-link'>$prevlabel</a></li>";
        } else if($page==2) {
          $out.= "<li class='page-item'><a class='page-link text-danger' href='".$actual_link."?page=".($page-1)."'>$prevlabel</a></li>";
        }else {
          $out.= "<li class='page-item'><a class='page-link text-danger' href='".$actual_link."?page=".($page-1)."'>$prevlabel</a></li>";
      
        }
        
        // first label
        if($page>($adjacents+1)) {
          $out.= "<li class='page-item'><a  class='page-link' href='".$actual_link."?page=1' >1</a></li>";
        }
        // interval
        if($page>($adjacents+2)) {
          $out.= "<li class='page-item'><a  class='page-link'>...</a></li>";
        }
      
        // pagina
        $pmin = ($page>$adjacents) ? ($page-$adjacents) : 1;
        $pmax = ($page<($tpage-$adjacents)) ? ($page+$adjacents) : $tpage;
        for($i=$pmin; $i<=$pmax; $i++) {
          if($i==$page) {
            $out.= "<li class='page-item active bg-danger'><a  class='page-link'>$i</a></li>";
          }else if($i==1) {
            $out.= "<li class='page-item'><a class='page-link' href='".$actual_link."?page=".$i."'>$i</a></li>";
          }else {
            $out.= "<li class='page-item'><a  class='page-link'href='".$actual_link."?page=".$i."'>$i</a></li>";
          }
        }
      
        // interval
        if($page<($tpage-$adjacents-1)) {
          $out.= "<li class='page-item'><a class='page-link'>...</a></li>";
        }
      
        // ultima
       if($page<($tpage-$adjacents)) {
          $out.= "<li class='page-item'><a class='page-link' href='?page=".$tpage."' >$tpage</a></li>";
        }
      
        // Siguiente
       if($page<$tpage) {
          $out.= "<li class='page-item'><span><a  class='page-link' href='".$actual_link."?page=".($page+1)."'>$nextlabel</a></span></li>";
        }else {
         // $out.= "<li class='page-item disabled'><span><a  class='page-link'>$nextlabel</a></span></li>";
        }
       
        $out.= "</ul>";
         $out.= "</nav>";
        return $out;
      }
    
     public  function getReferer($path=''){
       return (empty($_SERVER['HTTP_REFERER']) ? self::home_url().$path : strtolower($_SERVER['HTTP_REFERER']));
     }


    // public static function csrf(){
    //     if(empty($_SESSION['token_csrf']))
    //       if(PHP_VERSION >= 7):  
    //     $_SESSION['token_csrf']  = bin2hex(random_bytes(32));
    //     else:
    //       $_SESSION['token_csrf']  =   bin2hex(substr(md5(uniqid(rand())),0,12));
    //      endif; 
    //        $csrf = hash_hmac('sha256', 'Seguridad de formularios', $_SESSION['token_csrf'] );
    //       return $csrf;
    // }
    // public static function verify_token($token){
    //     if(hash_equals($_SESSION['token_csrf'],$token)){

    //             header('Content-Type:application/json');
    //             return json_encode('token invalido');
    //             exit;
    //     }  
    // }

    
}

