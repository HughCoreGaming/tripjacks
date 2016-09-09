/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

$(document).ready(function(){
    $("#test").click(function(){ 
        
        var id = 23;
        var url = "<?php echo $this->url(array('module' => 'games', 'controller' => 'game', 'action' => 'ajaxgetplayers'), 'default', true); ?>";

        $.ajax({
            url: url,                      
            type: "POST",
            data: {
                q : id
            },                       
            dataType: 'json',   
            async: false,
            success: function(data)  {
                result = data;
                alert(result);
            }
        });         
    });        
});

