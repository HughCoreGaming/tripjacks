/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
$(document).ready(function(){
    
    
var x = $(window).width(); 
var y = $(window).height(); 

var startx = x/2-200;
var starty = y/2-130;

$(".regoption").hide();
$(".loginoption").hide();

    $(".regoption").css({
      "left":startx,
      "top":starty
    });
    
        $(".loginoption").css({
      "left":startx,
      "top":starty
    });

    
    $('.reg').click(function(){
        
        $(".loginoption").hide();
        $(".regoption").fadeIn('slow');
             
    });
    
        $('.login').click(function(){
        
        $(".regoption").hide();
        $(".loginoption").fadeIn('slow');
             
    });

});