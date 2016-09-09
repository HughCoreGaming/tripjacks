/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */



$(document).ready(function(){
    
    var x = $(window).width(); 
var y = $(window).height(); 

var startx = x/2;
var starty = y-100;


$(document).ready(function(){
    


    $("#card").css({
      "left":startx,
      "top":starty-400
    });
    
    $(document).mousemove(function(e){
  $("#follower").css({
      'top': e.pageY + 'px',
      'left': e.pageX + 'px'
  });
});


var mouseX = 0, mouseY = 0;
$(document).mousemove(function(e){
   mouseX = e.pageX;

});

// cache the selector
var follower = $("#card");
var xp = 0, yp = 0;
var loop = setInterval(function(){
    // change 12 to alter damping, higher is slower
    xp += (mouseX - xp) / 12;


    follower.css({left:xp});

}, 60);
    

});

});
