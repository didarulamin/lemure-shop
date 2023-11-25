!function(){var o=function(o,n){var e=void 0===o?{center:{lat:mapOptions.lpac_map_default_latitude,lng:mapOptions.lpac_map_default_longitude},zoom:mapOptions.lpac_map_zoom_level,streetViewControl:!1,clickableIcons:mapOptions.lpac_map_clickable_icons,backgroundColor:mapOptions.lpac_map_background_color}:o,a=void 0===n?"":n;a=a||"lpac-map";var t=document.querySelector(".".concat(a));if(t)return new google.maps.Map(t,e)}();function n(o){var n=prompt(drawingLocalizedStrings.regionName,"Region1");if(null!=n&&""!=n){var e=prompt(drawingLocalizedStrings.regionCost,"0.00");if(null!=e&&""!=e)!function(o,n){if(null==o)return void console.log("LPAC: Empty Overlay event object. Returning...");if(null==n)return void console.log("LPAC: Empty Region Details object. Returning...");var e=o;if("polygon"!==o.type)return;for(var a=e.overlay.getPath(),t=[],i=0;i<a.getLength();i++){var l=a.getAt(i);t.push({lat:parseFloat(l.lat()),lng:parseFloat(l.lng())})}if(0===t.length)return void console.log("LPAC: Empty Polygon. Returning...");n.polygon=t,n.type="polygon",function(o){if(void 0===wp.ajax||null===wp.ajax)return void console.log("LPAC: Cannot find WP Util library. wp.ajax cannot work.");wp.ajax.post("lpac_save_drawn_shipping_region",{regionDetails:o}).done((function(o){!0===o&&window.location.reload()})).fail((function(o){console.error(o.responseJSON.data)}))}(n)}(o,{name:n,cost:e,bgColor:prompt(drawingLocalizedStrings.regionColor,"#ff0000")});else window.location.reload()}else window.location.reload()}function e(o){for(var n=document.querySelector("#lpac_shipping_regions_updated_obj"),e=o.getPath(),a=[],t=0;t<e.getLength();t++){var i=e.getAt(t);a.push({lat:parseFloat(i.lat()),lng:parseFloat(i.lng())})}var l={};l.polygonCords=a,window.shippingRegions[o.id]=l,n.value=JSON.stringify(window.shippingRegions)}window.shippingRegions={},function(){o.setOptions({zoom:12,center:{lat:mapOptions.lpac_map_default_latitude,lng:mapOptions.lpac_map_default_longitude},clickableIcons:!1,backgroundColor:"#eee"});var e=new google.maps.drawing.DrawingManager({drawingControl:!0,drawingControlOptions:{position:google.maps.ControlPosition.TOP_CENTER,drawingModes:[google.maps.drawing.OverlayType.POLYGON]}});e.setMap(o),google.maps.event.addListener(e,"overlaycomplete",n)}(),wp.ajax.post("lpac_get_saved_shipping_regions",{}).done((function(n){var a;"string"==typeof n?console.log(n):null!=(a=n)?a.forEach((function(n){var a=new google.maps.Polygon({id:n.id,paths:n.polygon,strokeColor:n.bgColor,strokeOpacity:.8,strokeWeight:2,fillColor:n.bgColor,fillOpacity:.35,editable:!0});a.setMap(o);var t=new google.maps.LatLngBounds;if(n.polygon){for(var i=0;i<n.polygon.length;i++)t.extend(n.polygon[i]);var l,r,p=(r=void 0===l?{disableAutoPan:!0}:l,new google.maps.InfoWindow(r)),g="<span style='font-weight: 800;'>".concat(shopCurrency).concat(parseFloat(n.cost).toFixed(2),"</span>"),s=n.name+"<br/>";p.setContent('<p class="lpac-shipping-region-infowindow" style="text-align: center; margin-top: 0; font-size: 14px"> '.concat(s," ").concat(g," </p>")),p.id=n.id,p.setPosition(t.getCenter()),p.open(o),google.maps.event.addListener(p,"closeclick",(function(){var o;o=p.id,void 0!==wp.ajax&&null!==wp.ajax?wp.ajax.post("lpac_delete_drawn_shipping_region",{regionID:o}).done((function(o){!0===o&&window.location.reload()})).fail((function(o){console.error(o.responseJSON.data)})):console.log("LPAC: Cannot find WP Util library. wp.ajax cannot work.")})),google.maps.event.addListener(a,"click",(function(){if(confirm(drawingLocalizedStrings.regionCostUpdate)){var o=prompt(drawingLocalizedStrings.regionCost,"0.00");if(null==o||""==o)return void window.location.reload();var n={};n.id=a.id,n.cost=o,function(o){wp.ajax.post("lpac_update_drawn_shipping_region_cost",{regionDetails:o}).done((function(o){!0===o&&window.location.reload()})).fail((function(o){console.error(o.responseJSON.data)}))}(n)}})),google.maps.event.addListener(a.getPath(),"insert_at",(function(o,n){e(a)})),google.maps.event.addListener(a.getPath(),"set_at",(function(o,n){e(a)})),google.maps.event.addListener(a.getPath(),"remove_at",(function(o,n){e(a)})),o.setCenter(t.getCenter())}})):console.log("LPAC: No regions returned. Returning...")})).fail((function(o){console.log(o)}))}();