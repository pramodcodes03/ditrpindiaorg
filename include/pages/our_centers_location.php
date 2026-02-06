<?php
    include_once('include/classes/account.class.php');
    $account = new account();
    $res = $account->list_institute('', ' AND A.SHOW_ON_WEBSITE = 1');           
    if($res!='')
    {
        while($data = $res->fetch_assoc())
        {
            extract($data);
            //print_r($data);
            $inst_name = "";
            
            if($INSTITUTE_ID == '1'){
                $inst_name = "Main Center - ".$INSTITUTE_NAME;
            }else{
                $inst_name = "ATC Center - ".$INSTITUTE_NAME;
            }
            if($latitude!='' && $longitude!=''){
                $name ='';
                $name = $INSTITUTE_NAME;
                 $website ='';
                $website = $WEBSITE;
            $locations[]=array( 'name'=>$name, 'lat'=>$latitude, 'lng'=>$longitude, 'lnk'=>$website );
            }
            
        }
    }
?>
    
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDvyRXDlH8lyIFaFMPldx_hK2Nfh-hduDE"></script> 
    
    <script type="text/javascript">
    var map;
    var Markers = {};
    var infowindow;
    var locations = [
        <?php 
        //print_r($locations); exit();
        for($i=0;$i<sizeof($locations);$i++){ $j=$i+1;?>
        [
            'AMC Service',
            '<p style="color:#000;font-weight:900;"><a href="<?php echo $locations[$i]['lnk'];?>"><?= $locations[$i]['name'] ?></a></p>',
            <?php echo $locations[$i]['lat'];?>,
            <?php echo $locations[$i]['lng'];?>,
            0
        ]<?php if($j!=sizeof($locations))echo ","; }?>
    ];
    var origin = new google.maps.LatLng(locations[0][2], locations[0][3]);

    function initialize() {
      var mapOptions = {
        zoom: 6,
        center: origin
      };

      map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);

        infowindow = new google.maps.InfoWindow();

        for(i=0; i<locations.length; i++) {
            var position = new google.maps.LatLng(locations[i][2], locations[i][3]);
            var marker = new google.maps.Marker({
                position: position,
                map: map,
            });
            google.maps.event.addListener(marker, 'click', (function(marker, i) {
                return function() {
                    infowindow.setContent(locations[i][1]);
                    infowindow.setOptions({maxWidth: 200});
                    infowindow.open(map, marker);
                }
            }) (marker, i));
            Markers[locations[i][4]] = marker;
        }

        locate(0);

    }

    function locate(marker_id) {
        var myMarker = Markers[marker_id];
        var markerPosition = myMarker.getPosition();
        map.setCenter(markerPosition);
        google.maps.event.trigger(myMarker, 'click');
    }

    google.maps.event.addDomListener(window, 'load', initialize);

    </script>
    <body id="map-canvas">