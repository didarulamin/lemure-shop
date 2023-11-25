import { initializeMap } from "../../../../js-modules/utils/initialize-map.js";
import { plotStoreLocations } from "../../../../js-modules/utils/store-locations.js";
import { showSelectedRegionsOnShortcodeMap } from "../../../js-modules/utils/regionDrawing.js";

/**
 * Output the map on the page.
 */
export function renderMap() {
  const shortcodeMaps = document.querySelectorAll(".kikote-shortcode-map");
  shortcodeMaps.forEach(setupMap);
}

/**
 * Prepare the map for display.
 *
 * @param {object} shortcodeMap
 */
function setupMap(shortcodeMap) {
  const settings = JSON.parse(shortcodeMap.dataset.mapSettings);
  const cords = settings.display_settings.default_coordinates;

  const latlng = {
    lat: parseFloat(cords.latitude),
    lng: parseFloat(cords.longitude),
  };

  const mapConfig = {
    streetViewControl: settings.display_settings.streetview_control
      ? true
      : false,
    clickableIcons: settings.display_settings.clickable_icons ? true : false,
    backgroundColor: settings.display_settings.background_color ?? "", //loading background color
    mapId: settings.display_settings.google_map_id ?? "",
    mapTypeId: settings.display_settings.map_type ?? "",
    center: latlng,
    zoom: parseInt(settings.display_settings.zoom),
  };

  const mapId = shortcodeMap.dataset.mapId;
  const mapDiv = `kikote-shortcode-map[data-map-id='${mapId}']`; // All our shortcode maps have this class.
  const map = initializeMap(mapConfig, mapDiv);

  showSelectedRegionsOnShortcodeMap(map, mapId, settings);
  const storeLocations = settings.shipping_settings.store_locations ?? {};
  plotStoreLocations(map, storeLocations);
}
