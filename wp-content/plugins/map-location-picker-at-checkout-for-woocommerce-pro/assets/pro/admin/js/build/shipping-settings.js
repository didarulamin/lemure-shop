!function(_){function e(_,e){"hide"===e?_.forEach((function(_){_.closest("tr").hide()})):_.forEach((function(_){_.closest("tr").show()}))}function c(){var e=_(".lpac-map"),c=_("#lpac_cost_by_region_taxable");return[e,_("#lpac_shipping_regions_shipping_methods"),c,_("#lpac_ship_only_to_drawn_regions"),_("#lpac_no_shipping_method_available_text"),_("#lpac_no_shipping_method_selected_error"),_("#lpac_shipping_regions_default_background_color"),_("#lpac_show_shipping_regions_on_checkout_map"),_("#lpac_show_shipping_regions_name_on_checkout_map"),_("#lpac_show_shipping_regions_cost_on_checkout_map"),_("#lpac_enable_shipping_restrictions"),_("#lpac_enable_free_shipping_for_regions")]}function i(){var e=_("#lpac_enable_shipping_restrictions_local_pickup");return[_("#lpac_regions_min_max_order_total_row_id"),e,_("#lpac_order_total_insufficient_text"),_("#lpac_order_total_limit_passed_text")]}function s(){return[_("#lpac_regions_free_shipping_row_id")]}function t(){return[_("#lpac_distance_matrix_cost_per_unit"),_("#lpac_limit_shipping_distance"),_("#lpac_max_free_shipping_distance"),_("#lpac_max_shipping_distance"),_("#lpac_distance_cost_no_shipping_method_available_text"),_("#lpac_distance_cost_no_shipping_method_selected_error"),_("#lpac_subtract_free_shipping_distance")]}function o(){return[_("#lpac_cost_by_distance_range_row_id")]}function n(){return[_("#lpac_cost_by_store_distance_delivery_pricing_row_id")]}var a,h,l,p,r,d,g,u,b,k,f,m,w,y;a=_("#lpac_shipping_cost_by_region_enabled"),h=_("#lpac_enable_shipping_restrictions"),l=_("#lpac_enable_free_shipping_for_regions"),a.is(":checked")||(e(c(),"hide"),e(i(),"hide"),e(s(),"hide")),a.on("click",(function(){a.is(":checked")?(e(c(),"show"),h.is(":checked")&&e(i(),"show"),l.is(":checked")&&e(s(),"show")):(e(c(),"hide"),e(i(),"hide"),e(s(),"hide"))})),p=_("#lpac_show_shipping_regions_on_checkout_map"),r=p.is(":checked"),d=_("#lpac_show_shipping_regions_name_on_checkout_map"),g=_("#lpac_show_shipping_regions_cost_on_checkout_map"),r||(d.closest("tr").hide(),g.closest("tr").hide()),p.on("click",(function(){p.is(":checked")?(d.closest("tr").show(),g.closest("tr").show()):(d.closest("tr").hide(),g.closest("tr").hide())})),u=_("#lpac_ship_only_to_drawn_regions"),b=u.is(":checked"),k=_("#lpac_no_shipping_method_available_text"),f=_("#lpac_no_shipping_method_selected_error"),b||(k.closest("tr").hide(),f.closest("tr").hide()),u.on("click",(function(){u.is(":checked")?(k.closest("tr").show(),f.closest("tr").show()):(k.closest("tr").hide(),f.closest("tr").hide())})),function(){var c=_("#lpac_enable_shipping_cost_by_distance_feature");if(c){var i=_("#lpac_cost_by_distance_configuration"),s=_("#lpac_distance_matrix_api_key"),a=_("#lpac_distance_matrix_store_origin_cords"),h=_("#lpac_distance_matrix_distance_unit"),l=_("#lpac_show_distance_unit_cost_in_checkout"),p=_("#lpac_distance_matrix_travel_mode"),r=_("#lpac_cost_by_distance_taxable"),d=_("#lpac_distance_matrix_shipping_methods"),g=_("#lpac_cost_by_distance_standard_hr"),u=_("#lpac_cost_by_distance_range_hr"),b=_("#lpac_cost_by_store_distance_pricing_hr"),k=_("#lpac_enable_cost_by_distance_standard"),f=_("#lpac_enable_cost_by_distance_range"),m=_("#lpac_enable_cost_by_store_distance"),w=_("#lpac_enable_use_store_selector_as_origin"),y=[i,s,a,h,l,p,r,d,g,b,u,k,f,m,w];c.is(":checked")||e(y,"hide"),c.on("click",(function(){c.is(":checked")?(e(y,"show"),k.is(":checked")&&e(t(),"show"),f.is(":checked")&&e(o(),"show"),m.is(":checked")&&e(n(),"show")):(e(y,"hide"),e(t(),"hide"),e(o(),"hide"),e(n(),"hide"))}))}}(),m=_("#lpac_enable_cost_by_distance_standard"),w=_("#lpac_limit_shipping_distance"),y=[_("#lpac_max_shipping_distance"),_("#lpac_distance_cost_no_shipping_method_available_text"),_("#lpac_distance_cost_no_shipping_method_selected_error")],_("#lpac_enable_shipping_cost_by_distance_feature").is(":checked")||(e(t(),"hide"),e(y,"hide")),m.is(":checked")||e(t(),"hide"),m.on("click",(function(){m.is(":checked")?(e(t(),"show"),w.is(":checked")?e(y,"show"):e(y,"hide")):e(t(),"hide")})),function(){_("#lpac_enable_shipping_cost_by_distance_feature").is(":checked")||e(o(),"hide");var c=_("#lpac_enable_cost_by_distance_range");c.is(":checked")||e(o(),"hide"),c.on("click",(function(){c.is(":checked")?e(o(),"show"):e(o(),"hide")}))}(),function(){_("#lpac_enable_shipping_cost_by_distance_feature").is(":checked")||e(n(),"hide");var c=_("#lpac_enable_cost_by_store_distance");c.is(":checked")||e(n(),"hide"),c.on("click",(function(){c.is(":checked")?e(n(),"show"):e(n(),"hide")}))}(),function(){var e=_("#lpac_limit_shipping_distance"),c=e.is(":checked"),i=_("#lpac_max_shipping_distance"),s=_("#lpac_distance_cost_no_shipping_method_available_text"),t=_("#lpac_distance_cost_no_shipping_method_selected_error");c||(i.closest("tr").hide(),s.closest("tr").hide(),t.closest("tr").hide()),e.on("click",(function(){e.is(":checked")?(i.closest("tr").show(),s.closest("tr").show(),t.closest("tr").show()):(i.closest("tr").hide(),s.closest("tr").hide(),t.closest("tr").hide())}))}(),function(){var e=_("#lpac_enable_cost_by_store_location");if(e){var c=e.is(":checked"),i=_("#lpac_cost_by_store_location_taxable"),s=_("#lpac_cost_by_store_location_shipping_methods"),t=_("#lpac_cost_by_store_location_delivery_prices_row_id");c||(i.closest("tr").hide(),s.closest("tr").hide(),t.closest("tr").hide()),e.on("click",(function(){e.is(":checked")?(i.closest("tr").show(),s.closest("tr").show(),t.closest("tr").show()):(i.closest("tr").hide(),s.closest("tr").hide(),t.closest("tr").hide())}))}}(),function(){var c=_("#lpac_enable_shipping_restrictions");c.is(":checked")||e(i(),"hide"),c.on("click",(function(){c.is(":checked")?e(i(),"show"):e(i(),"hide")}))}(),function(){var c=_("#lpac_enable_free_shipping_for_regions");c.is(":checked")||e(s(),"hide"),c.on("click",(function(){c.is(":checked")?e(s(),"show"):e(s(),"hide")}))}()}(jQuery);