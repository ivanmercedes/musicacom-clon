var tag = document.createElement('script');
tag.src = "https://www.youtube.com/iframe_api";

var firstScriptTag = document.getElementsByTagName('script')[0];
firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

var player;
var show;
var duracion;
var musPlaying = null;
var subsPlaying = null;
var subsExist =  0;
var lastIni = 0;
var done = false;
var autoplay =  0;


function onYouTubeIframeAPIReady(){

player = new YT.Player('player',{

    width: '100%',
    height: '100%',
    videoId: '2qn1c5E6dSI',
    playerVars: {
        'autoplay':autoplay, 
        'modestbranding':1,
        'rel':0,
        'playsinline':1,
        'enablejsapi':1,
        'origin':'https://musicaclone.herokuapp.com/',
        'host': 'https://www.youtube.com/'
        
                    },

    events: {
        'onReady': onPlayerReady,
        //'onError': onPlayerError,
        'onStateChange': onPlayerStateChange
    }

});

}



function onPlayerReady(event) {

if(subsExist==1){
   // subtitles = parseSubtitle();
}

duracion = player.getDuration();

done = true;

if(autoplay==1){
    player.playVideo();
    window.parent.postMessage('videoPlay', "*");
}	

}



function onPlayerStateChange(event) {
if(event.data>=0){
    if (event.data == YT.PlayerState.PLAYING && done || event.data==YT.PlayerState.BUFFERING && done) {
        window.parent.postMessage('videoPlay', "*");
        if((parseInt(duracion)-parseInt(player.getCurrentTime()))<=2){
                        player.seekTo(0, true);
                        window.parent.postMessage('videoRestart', "*");
        }
        if(subsExist==1){ 
                        subtitulos('start');
        }
        played();
    }else{
        window.parent.postMessage('videoStop', "*");
        clearInterval(musPlaying);
        musPlaying = null;
        if(subsExist==1){
                        subtitulos('stop'); 
        }
    }
  }
}




function onPlayerError(event){

window.parent.postMessage('videoError', "*");

setTimeout(function(){

    if(event.data>=100){

        player.stopVideo();

        if(autoplay==1 && nextVideo>0){

//             window.top.location="" + nextVideo + "&ref=playlist2";
            nextVideo=0;

        }

    }

},1000);

}



function played(){
    if (!musPlaying) {
        musPlaying = setInterval(function(){

            curtime = player.getCurrentTime();

            if((parseInt(duracion)-parseInt(curtime))<=2){
                clearInterval(musPlaying);
                musPlaying = null;
                player.pauseVideo();
                setTimeout(function(){
                                window.parent.postMessage('videoEnd', "*");
                }, 1000);
            }
        
        }, 400);
    }              
}

// function parseSubtitle(){
// var xhr = new XMLHttpRequest();
// xhr.open('GET', 'subtitulos.asp?idletra=2506462&youtube=2qn1c5E6dSI', true);
// xhr.responseType = 'json';
// xhr.onload = function(){
//     var status = xhr.status;
//     if(status === 200){
//         subtitles = xhr.response;
//     }
// };
// xhr.send();
// }

function subtitulos(e){

    if(e=='stop'){
        clearInterval(subsPlaying);
        subsPlaying = null;
    }else{

        if (!subsPlaying) {
            subsPlaying = setInterval(function(){
                time = player.getCurrentTime();
                txt = searchSubtitle(time);
                if(lastIni==0 || lastIni!=txt){        
                    if(txt!='none'){
                        document.querySelector('#subtitulos .text').innerHTML = subtitles[txt][0];
                        lastIni = txt;
                    }else{                                                   
                        document.querySelector('#subtitulos .text').innerHTML = '';
                    }
                }
            }, 200);
        }

    }
}



function searchSubtitle(time){
var show=0;
var text = document.querySelector('#subtitulos .text').innerHTML;
for(var i=lastIni; i<=subtitles.length-1; i++){
    subs = subtitles[i];
    txt = subs[0];
    ini = subs[1];
    fin = subs[2];
    if(time>ini && time<=fin){				
        return i;
    }
}
return 'none';
}



function videoInit(){
if (window.addEventListener){
    window.addEventListener("message", OnMessage, false);
}else{
    if(window.attachEvent){
        window.attachEvent("onmessage", OnMessage);
    }
}
}



function OnMessage(event){

if (event.data == "playVideo") {

    if(player.getPlayerState()!=1){

        player.playVideo();

    }else{

        player.pauseVideo();

    }

}

}



function goTo(sec){

var newTime = player.getCurrentTime()+sec;
if(newTime<0){ newTime=0; }
if(newTime>duracion){ newTime=duracion-2; }
player.seekTo(newTime, true);

}



window.onload = function(){

videoInit();

};
