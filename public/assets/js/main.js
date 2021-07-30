"use strict";

var last_sec;
document.addEventListener('click', function (e) {

     if(e.target.id == 'addPlaylist'){
        e.preventDefault();

        if(document.getElementById('nombre').value ==''){
            document.getElementById('_error_').innerText ='El nombre es obligatorio';
            return;   
        }

        const data = new FormData();

        data.append('id', e.target.getAttribute('data-id'));
        data.append('nombre',document.getElementById('nombre').value);
        data.append('tipo','new_playlist');
        fetch('list', {
            method: 'POST',
            body: data
         })
         .then(function(response) {
            if(response.ok) {
                return response.json()
            } else {
                throw "Error en la llamada Ajax";
            }
         
         })
        
         .then(function(texto) {
             if(texto.error !== undefined){
                 document.getElementById('_error_').innerText = 'Ya existe esa Playlist';
                return;
             }
             const div = document.createElement('div');
             div.className = 'form-check';
             div.innerHTML = `
             
             <input class="form-check-input" type="checkbox"  id="${texto.name}" name="playlist_name" value="${texto.id}">
             <label class="form-check-label" for="${texto.name}">
                ${texto.name}
             </label>
           
             `;
             document.getElementById('checks_list').appendChild(div);
         })
         .catch(function(err) {
            console.log(err);
         });

     }

     if(e.target.name == 'playlist_name'){

         const data = new FormData();
         data.append('id_song',id_lyric);
         data.append('id',e.target.value);
        
         if(e.target.checked){
             data.append('tipo','addToPlaylist');
        }else{
            data.append('tipo','removeToPlaylist');
        }
        fetch('list',{method:'POST', body: data})
        .then(response => response.json())
        .then(data => console.log(data));

     }

    if(e.target.id == 'addfav'){
        e.preventDefault();
         
        const data = new FormData();
        data.append('id', e.target.getAttribute('data-id'));

        fetch('api', {
            method: 'POST',
            body: data
         })
         .then(function(response) {
            if(response.ok) {
                return response.text()
            } else {
                throw "Error en la llamada Ajax";
            }
         
         })
         .then(function(texto) {
             
           if(parseInt(texto) == 1){
               e.target.classList.add('active');     
           }else{
            let msg = document.getElementById('addmsg');
            msg.innerHTML = `<div class="alert alert-danger mt-4">Esta cancion ya esta en tu lista</div>`;
           }
         })
         .catch(function(err) {
            console.log(err);
         });
    }


    if(e.target.id == 'dismiss' || e.target.classList.contains('overlay') || e.target.parentElement.id == 'dismiss2' ){
        document.getElementById('sidebar2').classList.remove('active');
        document.querySelector('.overlay').classList.remove('active');
        document.querySelector('body').classList.remove('nonScroll');
     }

     if(e.target.id == 'sidebarCollapse2' || e.target.parentElement.id == 'sidebarCollapse2' ){
         console.log('hola')
        document.getElementById('sidebar2').classList.add('active');
        document.querySelector('.overlay').classList.add('active');
        document.querySelector('body').classList.add('nonScroll');
     }


  if ((e.target.id == 'dismiss' || e.target.classList.contains('overlay') || e.target.parentElement.id == 'dismiss') && (document.getElementById('sidebar').classList.remove('active'), document.querySelector('.overlay').classList.remove('active'), document.querySelector('body').classList.remove('nonScroll')), (e.target.id == 'sidebarCollapse' || e.target.parentElement.id == 'sidebarCollapse') && (document.getElementById('sidebar').classList.add('active'), document.querySelector('.overlay').classList.add('active'), document.querySelector('body').classList.add('nonScroll')), (e.target.classList.contains('btn-buscar') || e.target.classList.contains('fa-search')) && (document.getElementById('buscadorForm').classList.add('show'), document.getElementById('bsc').focus()), e.target.classList.contains('bst')) {
    var element = document.getElementById('opciones');
    last_sec = ['all', 'artist', 'song'][e.target.getAttribute('data-type')], element.querySelector('.active').classList.remove('active'), e.target.classList.add('active'), document.getElementById('bsc').placeholder = ['Buscar letras, artistas, música', 'Buscar artistas', 'Buscar canciones'][e.target.getAttribute('data-type')], console.log(e.target.getAttribute('data-type')), searchmus();
  }
}), document.getElementById("bsc").addEventListener("keyup", function () {
  searchmus();
});


function searchmus(t) {
  t || (t = last_sec || "all"), setTimeout(function () {
    var str = document.getElementById("bsc").value;
    var res = document.getElementById("result");
    var getUrl = window.location;
    var baseUrl = getUrl .protocol + "//" + getUrl.host + "/";
    if (str.length >= 2) {
      var xmlhttp = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObjuect('Microsoft.XMLHTTP');
      xmlhttp.onreadystatechange = function () {
        xmlhttp.readyState == 4 && xmlhttp.status == 200 && (res.innerHTML = xmlhttp.responseText);
      }, xmlhttp.open("GET", baseUrl+"api/?q=" + str + "&t=" + t, true), xmlhttp.send();
    }
  }, 4);
}

document.addEventListener("DOMContentLoaded", function () {
  var lazyImages = [].slice.call(document.querySelectorAll("img.lazy"));
  var active = false;

  var lazyLoad = function () {
    active === false && (active = true, setTimeout(function () {
      lazyImages.forEach(function (lazyImage) {
        lazyImage.getBoundingClientRect().top <= window.innerHeight && lazyImage.getBoundingClientRect().bottom >= 0 && getComputedStyle(lazyImage).display !== "none" && (lazyImage.src = lazyImage.dataset.src, lazyImage.srcset = lazyImage.dataset.srcset, lazyImage.classList.remove("lazy"), lazyImages = lazyImages.filter(function (image) {
          return image !== lazyImage;
        }), lazyImages.length === 0 && (document.removeEventListener("scroll", lazyLoad), window.removeEventListener("resize", lazyLoad), window.removeEventListener("orientationchange", lazyLoad)));
      }), active = false;
    }, 200));
  };

  document.addEventListener("scroll", lazyLoad), 
  window.addEventListener("resize", lazyLoad), 
  window.addEventListener("orientationchange", lazyLoad),
  window.addEventListener("DOMContentLoaded", lazyLoad);

});


function scrolltoTop() {
    var o = document.body.scrollTop + document.documentElement.scrollTop,
        e = o / 50;
    if (100 < o) var l = setInterval(function () {
      (o -= e) < e && (o = 0, clearInterval(l)), document.body.scrollTop = o, document.documentElement.scrollTop = o;
    }, 1);
  }
  
  window.addEventListener("scroll", function () {
    var o = window.pageYOffset || document.body.scrollTop,
        e = document.getElementById("scrollTop"); 
        // j = document.getElementById("avisoinferior");
        e.style.display = 400 < o ? "block" : "none";
        // j.style.display = 1800 < o ? "block" : "none";
  });

function votar(t, e, n) {
    document.querySelector(".icon").innerHTML = "";
    var a = new XMLHttpRequest();
    (a.onreadystatechange = function () {
        4 == this.readyState && 200 == this.status && (document.querySelector(".votar").innerHTML = this.responseText);
    }),
        a.open("GET", "incsmas/valorar_cancion.asp?nota=" + t + "&letraid=" + e + "&grupoid=" + n, !0),
        a.send();
}
function megusta(l, g) {
    var a = new XMLHttpRequest();
    (a.onreadystatechange = function () {
        4 == this.readyState && 200 == this.status && (document.querySelector(".megusta").innerHTML = this.responseText);
    }),
        a.open("GET", "incsmas/megusta_cancion.asp?letraid=" + l + "&grupoid=" + g, !0),
        a.send();
}
function opciones() {
    "none" == document.querySelector("#opciones").style.display ? (document.querySelector("#opciones").style.display = "block") : (document.querySelector("#opciones").style.display = "none");
}
function letra_editor_centro() {
    document.querySelector("#letra").style.textAlign = "center";
    document.querySelector("#boton-video").style.marginLeft = "30px";
    document.cookie = "alin=center;max-age=31536000;path=/";
}
function letra_editor_left() {
    document.querySelector("#letra").style.textAlign = "left";
    document.querySelector("#boton-video").style.marginLeft = "0px";
    document.cookie = "alin=left;max-age=31536000;path=/";
}
function fuente_mayor() {
    nsize = document.querySelector("#letra").style.fontSize.replace("px", "");
    nsize = parseInt(nsize) + 2;
    if (nsize <= 24) {
        document.querySelector("#letra").style.fontSize = nsize + "px";
        document.cookie = "fuen=" + nsize + ";max-age=31536000;path=/";
    }
}
function fuente_menor() {
    nsize = document.querySelector("#letra").style.fontSize.replace("px", "");
    nsize = parseInt(nsize) - 2;
    if (nsize >= 12) {
        document.querySelector("#letra").style.fontSize = nsize + "px";
        document.cookie = "fuen=" + nsize + ";max-age=31536000;path=/";
    }
}
var musRedir = 0;
function videoInit() {
    window.addEventListener ? window.addEventListener("message", OnMessage, !1) : window.attachEvent && window.attachEvent("onmessage", OnMessage);
}
function OnMessage(e) {

    var getUrl = window.location;
    var baseUrl = getUrl .protocol + "//" + getUrl.host + "/letra/";
    if (e.data)
        if ("videoStop" == e.data) {
            plays = document.querySelectorAll(".buttonPlay");
            for (var t = 0; t < plays.length; t++)
                (plays[t].querySelector("img").src = "https://i.musicaimg.com/im/play.svg"), plays[t].querySelector("img").setAttribute("onerror", "this.onerror=null;this.src='https://i.musicaimg.com/im/play.png'");
        } else if ("videoPlay" == e.data) {
            document.body.classList.add("play"), (plays = document.querySelectorAll(".buttonPlay"));
            for (t = 0; t < plays.length; t++)
                (plays[t].querySelector("img").src = "https://i.musicaimg.com/im/pause.svg"), plays[t].querySelector("img").setAttribute("onerror", "this.onerror=null;this.src='https://i.musicaimg.com/im/pause.png'");
        } else if( "videoEnd" == e.data){
            window.location = baseUrl + next
        }   
        
}
function gotoVideo() {
    document.querySelector(".videoclip").contentWindow.postMessage("playVideo", "*");
}
videoInit();
function autoplay() {
    setTimeout(function () {
        0 == document.getElementById("ap").checked
            ? ((document.cookie = "autoplay=;max-age=0;path=/;domain=.musica.com"), document.querySelector(".videoclip").contentWindow.postMessage("autoplay:0", "*"))
            : ((document.cookie = "autoplay=ok;max-age=2592000;path=/;domain=.musica.com"), document.querySelector(".videoclip").contentWindow.postMessage("autoplay:1", "*"));
    }, 300);
}


document.addEventListener('submit', function (e){
    e.preventDefault();
    const inputs = e.target.querySelectorAll('input');
    
    if(inputs[1].value.trim() !== ''  && inputs[0].value.trim() !== '' ){

    let form = `${inputs[1].name}=${inputs[1].value}&email=${inputs[0].value}&ajax=ajax_login`;

      const  connect = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObjuect('Microsoft.XMLHTTP');
     connect.onreadystatechange = function (){ 
       if(connect.readyState == 4 &&  connect.status == 200){
            if(parseInt(connect.responseText) ==1){
                      location.reload();
                }else{
                  //ERROR: los datos son incorrectos
                 document.getElementById('_ajax_').innerHTML = `<div class="alert alert-danger">Error verifica tu Ccorreo / Contraseña</div>  `;
              }
            }
     }
     var getUrl = window.location;
     var baseUrl = getUrl .protocol + "//" + getUrl.host + "/";
     connect.open('POST',`${baseUrl}auth/login`,true);
     connect.setRequestHeader('Content-type','application/x-www-form-urlencoded');
     connect.send(form);
    }
     
});

function embedMoved(){
    if(window.innerWidth < 992 && window.innerWidth > 700 ){
         document.getElementById('embed').appendChild(document.querySelector('.embed-responsive'))
    }  

}


document.addEventListener('DOMContentLoaded',function(){
    embedMoved();
});

window.addEventListener('resize',function(){
    embedMoved();
});

 

 
window.addEventListener("scroll", function () {
    if(window.innerWidth < 600  ){
        if(100 <= window.scrollY){ 
            document.querySelector("#embed-video-player").style.position = "fixed"
            document.querySelector("#embed-video-player").style.left = "0"
            document.querySelector("#embed-video-player").style.top = "3rem"
        } else if(30 <=  window.scrollY){
            document.querySelector("#embed-video-player").style.position = ""
            document.querySelector("#embed-video-player").style.left = ""
            document.querySelector("#embed-video-player").style.top = ""
        }else{
            document.querySelector("#embed-video-player").style.position = ""
            document.querySelector("#embed-video-player").style.left = ""
            document.querySelector("#embed-video-player").style.top = ""
        }
    }
});