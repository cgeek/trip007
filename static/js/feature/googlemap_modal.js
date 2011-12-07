
function get_address_info() {
    var c = '<div class="google-maps-modal"><input type="text" name="address" value=""><input type="submit" name="get_address"><div id="google-map-box"></div></div>';

    $(c).modal({
        onShow: function(g) {
            var latlng = new google.maps.LatLng(34.397, 150.644);
            var myOptions = {
                zoom:8,
                center:latlng,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            }
            //var map = google.maps.Map(document.getElementById("google-map-box"),myOptions);
            var gecoder = new google.maps.Geocoder();
            $('input[name=get_address]').click(function(){
                alert('a');
            });

        }  
    });
    alert('get_address_info');
};
