import { initializeMap } from "../../../../js-modules/utils/initialize-map.js";
import { attachSelectWooInstance } from "../../../../js-modules/utils/selectWoo.js";
import { plotStoreLocations } from "../../../../js-modules/utils/store-locations.js";
import { showSelectedRegionsOnShortcodeMap } from "../../../js-modules/utils/regionDrawing.js";

(function ($) {
  "use strict";

  /**
   * Initialize our selectWoo instances on our passed fields.
   */
  function initializeSelectWoo() {
    const fields = ["shipping-regions", "store-locations"];
    return attachSelectWooInstance($, fields);
  }

  function initializeMapPreview() {
    const cords = previewSettings.display_settings.default_coordinates;

    const latlng = {
      lat: parseFloat(cords.latitude),
      lng: parseFloat(cords.longitude),
    };

    // If we're creating a new shortcode then we wouldn't have preview settings yet. So lets set a few needed defaults for map to load and then bail.
    if (previewSettings.is_new === true) {
      const mapConfig = {
        center: latlng,
        zoom: 12,
      };
      initializeMap(mapConfig, "kikote-map-builder-preview");
      return;
    }

    const mapConfig = {
      streetViewControl: previewSettings.display_settings.streetview_control
        ? true
        : false,
      clickableIcons: previewSettings.display_settings.clickable_icons
        ? true
        : false,
      backgroundColor: previewSettings.display_settings.background_color ?? "",
      mapId: previewSettings.display_settings.google_map_id ?? "",
      mapTypeId: previewSettings.display_settings.map_type ?? "",
      center: latlng,
      zoom: parseInt(previewSettings.display_settings.zoom),
    };
    const map = initializeMap(mapConfig, "kikote-map-builder-preview");
    showSelectedRegionsOnShortcodeMap(map, postID, previewSettings); // postID and previewSettings are global see Admin_Enqueues::enqueue_scripts()
    const storeLocations =
      previewSettings.shipping_settings.store_locations ?? {};
    plotStoreLocations(map, storeLocations);
  }

  /**
   * On document ready.
   */
  $(function () {
    initializeSelectWoo();
    initializeMapPreview();
  });
})(jQuery);
