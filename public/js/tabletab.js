 $(document).ready(function(){
 // League tabs
    
    $(".tabContents").hide(); // Hide all tab conten divs by default
    $(".tabContents:first").show(); // Show the first div of tab content by default
    
    $("#tabContaier ul li a").click(function(e){ //Fire the click event
         e.preventDefault();

        var activeTab = $(this).attr("href"); // Catch the click link
        $("#tabContaier ul li a").removeClass("active"); // Remove pre-highlighted link
        $(this).addClass("active"); // set clicked link to highlight state
        $(".tabContents").hide(); // hide currently visible tab content div
        $(activeTab).fadeIn(); // show the target tab content div by matching clicked link.
        
            });
    
    
    
});
