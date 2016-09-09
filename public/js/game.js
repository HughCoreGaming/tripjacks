$(document).ready(function(){    
    var type = $("#type").val();
    var times = $("#times").val();
    var bookingid = $("#bookingid").val();
    var venueid = $("#venueid").val();    
});


    var sec;   // set the seconds
    var min;   // set the minutes
    
    if (times == 'demo'){       
        sec = 40;
        min = 00;    
    }else{       
        sec = 00;
        min = 25;       
    }
    
    var count_timer = 1;
    var small = 25;
    var big  = 50;    
    var s = small;
    var b = big;
    var the_blinds = 1;
    var sep = "/";
    var vid = venueid;
    var table_count = 1;
    var results;
    var total_players;
    var tables;
    var split;
    var score = new Array();
    var up;
    
    
    
    //startup commands
     $(document).ready(function(){  
 
     $(".alertbox").hide();
     $("#hide_game_info").hide();
     $("#hidelate").hide();
     $("#latecomers").hide();
     $("#the_timer").hide();
     $("#timer").hide();
     $("#counters_3").hide();
     $("#counters_2").hide();
     
     $("#clock").hide();
     $("#clock2").hide();
     
     $("#roundstable").hide();
     $("#chipstable").hide();     
     $("#tablestable").hide();
     $("#eliminationstable").hide();
     
     
     var the_blinds = small+sep+big;
     $("#round").text("Round: "+count_timer);
     $("#blinds").text(the_blinds);  
     
     
     

    //click events

    $("#count_tables").click(function(){  
        $.count_tables(table_count);
    });

    $("#timer").click(function(){  
        $("#the_tables").hide();
        $("#the_timer").show();
    });
    $("#timer").click(function(){  
        $("#the_tables").hide();
        $("#the_timer").show();
    });

    $("#tables").click(function(){  
        $("#the_timer").hide();
        $("#the_tables").show();              
    });

    $("#assemble").click(function(){  
        $("#the_tables").show();
        $("#timer").show();
    });

    $("#late").click(function(){   
            $("#late").hide();
        $("#hidelate").show();
        $("#latecomers").fadeIn('slow');
    });

    $("#hidelate").click(function(){  
        $("#hidelate").hide();
            $("#late").show();
        $("#latecomers").fadeOut('slow');
    });
        
    $("#game_info").click(function(){ 
        $("#game_info").hide();
        $("#hide_game_info").show();
        $("#roundstable").fadeIn('slow'); 
        $("#chipstable").fadeIn('slow');    
        $("#tablestable").fadeIn('slow');
        $("#eliminationstable").fadeIn('slow');
    });

    $("#hide_game_info").click(function(){ 
        $("#hide_game_info").hide();
        $("#game_info").show();
        $("#roundstable").hide();
        $("#chipstable").hide();     
        $("#tablestable").hide();
        $("#eliminationstable").hide();
    });

    $("#timer_ini").click(function(){  
        $("#timer_ini").hide();
        $("#clock2").show();
        $("#counters_3").show();
        $("#the_timer").show();
        countDown(); 
    });   

    $('#assemble').click(function(){ 
        results = $.getPlayers(bookingid);

        total_players = results.length;
        tables = get_tables(total_players);
        split = Math.round(get_split(total_players));
        ini(bookingid,tables,split,results,vid);  
    });

    $("#txtHint").click(function(){  
        insert(bookingid,vid); 
    });
    
    
    
    
    //Ajax functions
    jQuery.extend({
        count_tables: function(table_count) {
            alert(table_count);
            alert( $(".table_count-1").length );

        }
    });

    jQuery.extend({
        playerOut: function(id) {

            $.ajax({
                url: '/includes/getplayers.php',                      
                type: "POST",
                data: {
                    h : id
                },                       
                dataType: 'json',   
                async: false
            });

        }
    });

    jQuery.extend({
        getpic: function(id) {
            var result = new Array();
            $.ajax({
                url: 'admin/games/game/getpic',                      
                type: "POST",
                data: {
                    q : id
                },                       
                dataType: 'json',   
                async: false,
                success: function(data)  {
                    result = data;
                }
            });

            return result;
        }
    });

    jQuery.extend({
        theScores: function(playerid,count,vid,ids) {

            $.ajax({
                url: 'admin/games/game/ajaxinsertresults',                      
                type: "POST",
                data: {
                    playerid : playerid,
                    count : count,
                    vid : vid,
                    ids : ids  
                },                       
                dataType: 'json',   
                async: false,
                success: function(data) {
                    if (type == 'venue'){
                        window.location = "/leagues/league/index/"+venueid;
                    }else{
                        window.location = '/home';
                    }
                }
            });
        }
    }); 

    jQuery.extend({
        getPlayers: function(bookingid) {
            var result = new Array();
            $.ajax({
                url: 'admin-games-playerdetails',                      
                type: "POST",
                data: {
                    bookingid : bookingid
                },                       
                dataType: 'json',   
                async: false,
                success: function(data)  {
                    result = data;
                }
            });
            return result;
        }
    });
});   


//Adaptive layout for different size screens
$(document).ready(function(){

var x = $(document).width(); 
var y = $(document).height(); 

var startx = x/2;
var starty = y-100;

var alertx = $(window).width(); 
var alerty = $(window).height(); 

var alertstartx = x/2-200;
var alertstarty = y/2-130;

$(".alertbox").css({
    "left":alertstartx,
    "top":alertstarty
});
        
$("#inner").width(x-300);
$("#inner").css({"min-height":y-200});

if (x < 1400){
    $("#round").css('font-size', '32px');
    $("#blinds").css('font-size', '32px');
    $("#counter").css('font-size', '150px');
}else{
    $("#round").css('font-size', '62px');
    $("#blinds").css('font-size', '62px');
    $("#counter").css('font-size', '220px');
    $("#table-1 td").css('font-size', '40px'); 
    $("#image").width(125);
    $("#image").height(125);
    $("#down").width(100);
    $("#down").height(50);
    $("#down").css('font-size', '16px'); 
}
});
    
    
    //Main game functions
    function countDown() {
      
        sec--;
        if (sec == -01) {
            sec = 59;
            min = min - 1;
        } else {
            min = min;
        }
        if (sec<=9) {sec = "0" + sec;}
        time = (min<=9 ? "0" + min : min) + " : " + sec + " ";
        document.getElementById('counter').innerHTML = time;
        SD=window.setTimeout("countDown();", 1000);
        if (min == '00' && sec == '27') {
            var audio = document.getElementById("blindsup");
            audio.play();
        }
        if (min == '00' && sec == '05') {              
            var audio = document.getElementById("warning");
            audio.play();    
        }
        if (min == '00' && sec == '00') {
            sec = "00";window.clearTimeout(SD);
            if (count_timer == 10)
            {
                alert('Game Finished!');
            }else if(count_timer==7)
            {
                alert('10 mins break. Chip Up!');
                
                count_timer++; 
                the_blinds = blinds(count_timer);
                document.getElementById('round').innerHTML = "Round: "+count_timer;
                document.getElementById('blinds').innerHTML = the_blinds; 
                min=00;
                sec = 40; 
                countDown();
                
            }else{

                count_timer++; 
                the_blinds = blinds(count_timer);
                document.getElementById('round').innerHTML = "Round: "+count_timer;
                document.getElementById('blinds').innerHTML = the_blinds; 
                min=00;
                sec = 40; 
                countDown();
            }
        }
    } 
    
    function ini(bookingid,tables,split,results,vid){

        $('#the_table').empty();
        $('#the_table').append('<table id ="table-1"><tbody></tbody></table>');
        var table = $('#the_table').children();
        $("#table-1 tbody").sortable({
            revert: true
        });

                table.append( '<tr><td></td><td></td><td></td></tr>' );
                table.append( '<tr><td></td><td id = "table_header">Table 1</td><td></td></tr>' );
                table.append( '<tr><td></td><td>--------------</td><td></td></tr>' );

        for (i = 0; i <= results.length-1; i++){
            var temp = results[i];

            if(i==split){    

                table.append( '<tr><td></td><td></td><td></td></tr>' );
                table.append( '<tr><td></td><td id = "table_header">Table 2</td><td></td></tr>' );
                table.append( '<tr><td></td><td>--------------</td><td></td></tr>' );
                table_count++;


            }

                    if(i==split+split){    

                table.append( '<tr><td></td><td></td><td></td></tr>' );
                table.append( '<tr><td></td><td id = "table_header">Table 3</td><td></td></tr>' );
                table.append( '<tr><td></td><td>--------------</td><td></td></tr>' );
                table_count++;
            }

                    if(i==split+split+split){    

                table.append( '<tr><td></td><td></td><td></td></tr>' );
                table.append( '<tr><td></td><td id = "table_header">Table 4</td><td></td></tr>' );
                table.append( '<tr><td></td><td>--------------</td><td></td></tr>' );
                table_count++;
            }


                    if(i==split+split+split+split){  

                table.append( '<tr><td></td><td></td><td></td></tr>' );
                table.append( '<tr><td></td><td id = "table_header">Table 5</td><td></td></tr>' );
                table.append( '<tr><td></td><td>--------------</td><td></td></tr>' );
                table_count++;
            }



            table.append( '<tr class="table_count-'+table_count+'" id ="'+i+'"><td><img id="image" src="/playerpro/'+results[i][0].pic+'" height="50px" width="50px"  border="1"></td><td style="cursor:pointer" >'+results[i][0].name+'</td><td><button id="down" onclick="alertbox('+i+','+results[i][0].pid+','+results.length+','+vid+','+bookingid+')">Player Out!</button></td></tr>' );  
        } 
    } 
   
    function remove(id,playerid,count,vid,bookingid){      
             
            up = count-score.length;
             
            $("#"+id).remove();
            score.push(playerid); 

            $("#late").hide();
            $("#assemble").hide();
            
           if (up-1 == 8 || up-1 == 16 || up-1 == 24 || up-1 == 32 || up-1 == 40){
                $("#inner").effect( "shake",{times:4}, 1000 );
                $("#inner").effect( "highlight", {color:"#fff"}, 3000 );
                alert('Please re-org tables if needed!');
            }
            
            if(score.length==count){
                $.theScores(score,count,vid,bookingid);
            } 
    }
    

// functions inside main game functions
    
    function blinds(round){

        var blind;

        if (round == 2)
        {
            blind = "50/100";
        }
        else if (round == 3)
        {
            blind = "100/200";
        }
        else if (round == 4)
        {
            blind = "250/500";
        }
        else if (round == 5)
        {
            blind = "400/800";
        }
        else if (round == 6)
        {
            blind = "500/1000";
        }
        else if (round == 7)
        {
            blind = "750/1500";
        }
        else if (round == 8)
        {
            blind = "1000/2000";
        }
        else if (round == 9)
        {
            blind = "2000/4000";
        }
        else if (round == 10)
        {
            blind = "4000/8000";
        }
        return blind; 
    }



    function get_split(total_players){

        var tables=0;

        if (total_players >= 1 && total_players <= 8)
        {
            tables=1;
            split = total_players / tables;
        }
        else if (total_players >= 9 && total_players <= 16)
        {
            tables=2;
            split = total_players / tables;
        }
        else if (total_players >= 17 && total_players <= 24)
        {
            tables=3;
            split = total_players / tables;
        }
        else if (total_players >= 25 && total_players <= 32)
        {
            tables=4;
            split = total_players / tables;
        }
        else if (total_players >= 33 && total_players <= 40)
        {
            tables=5;
            split = total_players / tables;
        }
        return split; 
    }

    function get_tables(total_players){

        var tables=0;

        if (total_players >= 1 && total_players <= 8)
        {
            tables=1;
            split = total_players / tables;
        }
        else if (total_players >= 9 && total_players <= 16)
        {
            tables=2;
            split = total_players / tables;
        }
        else if (total_players >= 17 && total_players <= 24)
        {
            tables=3;
            split = total_players / tables;
        }
        else if (total_players >= 25 && total_players <= 32)
        {
            tables=4;
            split = total_players / tables;
        }
        else if (total_players >= 33 && total_players <= 40)
        {
            tables=5;
            split = total_players / tables;
        }
        return tables; 
    }


    // search hint ajax
    function insert(bookingid,venueid){
        
        var result = new Array();
        
        var user = document.getElementById('txtHint').innerHTML;
        var option = $('input:radio[name=option]:checked').val();
        
        $.ajax({
            url: 'admin/bookings/booking/insert/',                      
            type: "POST",
            data: {   
                user : user, 
                bookingid : bookingid,
                venueid : venueid,
                option : option
            },                     
            dataType: 'json',   
            async: false
        });   
    }
    
    
    function showHint(id){
        
        var result = new Array();
        var option = $('input:radio[name=option]:checked').val();
        
        $.ajax({
            url: 'admin/bookings/booking/search/',                      
            type: "POST",
            data: {
                q : id,
                option : option
            },                       
            dataType: 'json',   
            async: false,
            success: function(data)  {
                result = data;
                
                $("#txtHint").html(result);
            }
        });   
    }
    
    function alertbox(id,playerid,count,vid,bookingid) {

        $('<div></div>')
        .html('You are sure this player is out?<br><br>')
        .dialog({
            autoOpen: true,
            title: 'Player Out!?',
            buttons: {
                "No": function() { 
                    $(this).dialog("close"); 
                }, 
                "Yes": function() {
                    remove(id,playerid,count,vid,bookingid);
                    $(this).dialog("close"); 
                }
            }
        });  
    }



//usefull functions not used in application

    function dump(obj) {
        var out = '';
        for (var i in obj) {
            out += i + ": " + obj[i] + "\n";
        }
        alert(out);
    }

    function chunk(a, s){
        for(var x, i = 0, c = -1, l = a.length, n = []; i < l; i++)
            (x = i % s) ? n[c][x] = a[i] : n[++c] = [a[i]];
        return n;
    }

    function removeItem(array, item){
        for(var i in array){
            if(array[i]==item){
                array.splice(i,1);
                break;
            }
        }
    }

    function arrayShuffle(theArray) {
        var len = theArray.length;
        var i = len;
        while (i--) {
            var p = parseInt(Math.random()*len);
            var t = theArray[i];
            theArray[i] = theArray[p];
            theArray[p] = t;
        }
    };



    // game class (not used)

    function MyClass() {
        
        var results = new Array();
        var split;
        var tables;
        var total_players;
        
        //Setters and Getters Results
        
        this.getResults = function(){
            return results;
        };
        
        this.setResults = function(val){
            results = val;
        };
        
        //Setters and Getters Split
        
        this.getSplit = function(){
            return split;
        };
        
        this.setSplit = function(val){
            split = val;
        };
        
        //Setters and Getters tables
        
        this.getTables = function(){
            return tables;
        };
        
        this.setTables = function(val){
            tables = val;
        };
        
        //Setters and Getters Total Players
        
        this.getTotal_players = function(){
            return total_players;
        };
        
        this.setTotal_players = function(val){
            total_players = val;
        };
        
        this.displayTable = function(bookingid,tables,split,results,vid){
            $('#the_table').empty();
            $('#the_table').append('<table id ="table-1"><tbody></tbody></table>');
            var table = $('#the_table').children();
            $("#table-1 tbody").sortable({
                revert: true
            });

            for (i = 0; i <= results.length-1; i++){
                var temp = results[i];
                if(i==split){    
                    table.append( '<tr><td>------</td></tr>' );
                    table.append( '<tr><td>Table 2</td></tr>' );
                    table.append( '<tr><td>-------</td></tr>' );
                }
                table.append( '<tr id ="'+i+'"><td>'+results[i].player+'</td><td><button onclick="remove('+i+','+results[i].pid+','+results.length+','+vid+','+bookingid+')">Player Down!</button></td></tr>' );  
            } 
        } 
        
        this.removeItem = function(item){
            for(var i in results){
                if(results[i]==item){
                    results.splice(i,1);
                    break;
                }
            }
        }
    }
     


    
