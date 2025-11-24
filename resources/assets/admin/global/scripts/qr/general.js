(function() {
 var gad = document.createElement('script'); gad.type = 'text/javascript'; gad.async = true;
 gad.src = '//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js';
 var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(gad, s);
})();

function AD_300_600(withTextAD,repeat){
if(screen.width>500){
if (typeof repeat=='undefined'){repeat=1;}
document.write('<div style="width:300px;">');
for (i=0;i<repeat;i++){
document.write("\n"+'<ins class="adsbygoogle" style="display:inline-block;width:300px;height:600px" data-ad-client="ca-pub-7451234849948311" data-ad-slot="1336184065"></ins>');
(adsbygoogle = window.adsbygoogle || []).push({});
if (withTextAD){
document.write('<br><br>');
document.write("\n"+'<ins class="adsbygoogle" style="display:block" data-ad-client="ca-pub-7451234849948311" data-ad-slot="1092299662" data-ad-format="link"></ins>');
(adsbygoogle = window.adsbygoogle || []).push({});
}
document.write('<br><br>');
}
document.write('</div>');
}}

function Auto_AD(){
document.write("\n"+'<ins class="adsbygoogle" style="display:block" data-ad-client="ca-pub-7451234849948311" data-ad-slot="8735835266" data-ad-format="auto"></ins>');
(adsbygoogle = window.adsbygoogle || []).push({});
}

function Auto_TextAD(){
document.write("\n"+'<ins class="adsbygoogle" style="display:block" data-ad-client="ca-pub-7451234849948311" data-ad-slot="1092299662" data-ad-format="link"></ins>');
(adsbygoogle = window.adsbygoogle || []).push({});
}

function Auto_MatchedContenAD(){
document.write("\n"+'<ins class="adsbygoogle" style="display:block" data-ad-format="autorelaxed" data-ad-client="ca-pub-7451234849948311" data-ad-slot="6117928467"></ins>'+"\n");
(adsbygoogle = window.adsbygoogle || []).push({});
}

function ConvertMenu2Mobile(id){
if (screen.width<500){
var t=document.getElementById(id);
var links=t.getElementsByTagName('a');
var s='';
for(i=0;i<links.length;i++){
  s+='<li><a href="'+links[i].href+'" target="_blank">'+links[i].innerHTML+'</a></li>';
}
t.outerHTML='<ul>'+s+'</ul>';
}}