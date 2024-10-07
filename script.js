jQuery(document).ready(function($) {
   $("#cityID").change(function(){
        var cityID = $(this).val();
        var data = {
            'action':'load_area',
            'cityID': cityID
        };
        $.post(the_ajax_script.ajaxurl, data, function(response) {
            $("#areaID").html(response);
        }); 
   });
});