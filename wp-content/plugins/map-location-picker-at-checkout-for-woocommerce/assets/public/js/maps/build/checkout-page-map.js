!function(){function e(e,t,n,r,o,a,i){try{var c=e[a](i),s=c.value}catch(e){return void n(e)}c.done?t(s):Promise.resolve(s).then(r,o)}function t(t){return function(){var n=this,r=arguments;return new Promise((function(o,a){var i=t.apply(n,r);function c(t){e(i,o,a,c,s,"next",t)}function s(t){e(i,o,a,c,s,"throw",t)}c(void 0)}))}}function n(e){if(!mapOptions.lpac_remove_address_plus_code)return e;var t=e.split(" ",1);return t[0].includes("+")&&(e=e.replace(t[0],"").trim()),e}function r(e){if(e[0]){if(!1===mapOptions.dissect_customer_address)return function(e){if(e[0]){var t=e[0].formatted_address;return n(t)}}(e);if(e[0].hasOwnProperty("name"))return e[0].name;var t=e[0].formatted_address,r="";if(mapOptions.lpac_remove_address_plus_code){r=(a=n(t).split(","))[0].trim()}else{var o=t.split(" ",1),a=t.split(",");if(o[0].includes("+"))r=t.replace(o[0],"").split(",",1)[0].trim();else r=a[0].trim()}return r}}function o(e){if(e[0]){var t="",n=e[0].address_components.find((function(e){return e.types[0]===mapOptions.places_address_components_mappings.stateCounty}));return n&&(t=n),t}}function a(){var e=document.querySelector("#ship-to-different-address-checkbox");return!(!e||!0!==e.checked)}function i(e){if(!1!==mapOptions.fill_in_fields)if(checkoutProvider&&"fluidcheckout"===checkoutProvider){c(e);var t=document.querySelector("#billing_same_as_shipping");t&&!1===t.checked&&s(e)}else!0!==a()?s(e):c(e)}function c(e){!1!==mapOptions.fill_in_fields&&(u(e,"shipping"),l(e,"shipping"),p(e,"shipping"),d(e,"shipping"),f(e,"shipping"),jQuery?jQuery(document.body).trigger("update_checkout"):console.log("Kikote: Can't find jQuery instance. fillShippingFields()"))}function s(e){!1!==mapOptions.fill_in_fields&&(u(e,"billing"),l(e,"billing"),p(e,"billing"),d(e,"billing"),f(e,"billing"),jQuery?jQuery(document.body).trigger("update_checkout"):console.log("Kikote: Can't find jQuery instance. fillBillingFields()"))}function l(e,t){var n=document.querySelector("#".concat(t,"_country"));null!=n&&(n.value=function(e){if(e[0]){var t="",n=e[0].address_components.find((function(e){return e.types[0]===mapOptions.places_address_components_mappings.country}));return n&&(t=n.short_name),t}}(e),n.dispatchEvent(new Event("change",{bubbles:!0})))}function u(e,t){var n=document.querySelector("#".concat(t,"_address_1"));null!=n&&(n.value=r(e))}function p(e,t){var n=document.querySelector("#".concat(t,"_city"));null!=n&&(n.value=function(e){if(e[0]){var t="",n=e[0].address_components.find((function(e){return e.types[0]===mapOptions.places_address_components_mappings.townCity1})),r=e[0].address_components.find((function(e){return e.types[0]===mapOptions.places_address_components_mappings.townCity2})),o=e[0].address_components.find((function(e){return e.types[0]===mapOptions.places_address_components_mappings.townCity3}));return o&&(t=o.long_name),n&&(t=n.long_name),r&&(t=r.long_name),t}}(e))}function d(e,t){if(o(e)){var n=document.querySelector("#".concat(t,"_state"));if(null==n)return;n.classList.contains("select2-hidden-accessible")?(n.value=o(e).short_name,n.dispatchEvent(new Event("change",{bubbles:!0}))):n.value=o(e).long_name}}function f(e,t){var n=document.querySelector("#".concat(t,"_postcode"));null!=n&&(n.value=function(e){if(e[0]){var t="",n=e[0].address_components.find((function(e){return e.types[0]===mapOptions.places_address_components_mappings.zipcode}));return n&&(t=n.short_name),t}}(e))}var _={},m=function(e){var t,n=Object.prototype,r=n.hasOwnProperty,o="function"==typeof Symbol?Symbol:{},a=o.iterator||"@@iterator",i=o.asyncIterator||"@@asyncIterator",c=o.toStringTag||"@@toStringTag";function s(e,t,n){return Object.defineProperty(e,t,{value:n,enumerable:!0,configurable:!0,writable:!0}),e[t]}try{s({},"")}catch(e){s=function(e,t,n){return e[t]=n}}function l(e,t,n,r){var o=t&&t.prototype instanceof h?t:h,a=Object.create(o.prototype),i=new P(r||[]);return a._invoke=function(e,t,n){var r=p;return function(o,a){if(r===f)throw new Error("Generator is already running");if(r===_){if("throw"===o)throw a;return C()}for(n.method=o,n.arg=a;;){var i=n.delegate;if(i){var c=x(i,n);if(c){if(c===m)continue;return c}}if("next"===n.method)n.sent=n._sent=n.arg;else if("throw"===n.method){if(r===p)throw r=_,n.arg;n.dispatchException(n.arg)}else"return"===n.method&&n.abrupt("return",n.arg);r=f;var s=u(e,t,n);if("normal"===s.type){if(r=n.done?_:d,s.arg===m)continue;return{value:s.arg,done:n.done}}"throw"===s.type&&(r=_,n.method="throw",n.arg=s.arg)}}}(e,n,i),a}function u(e,t,n){try{return{type:"normal",arg:e.call(t,n)}}catch(e){return{type:"throw",arg:e}}}e.wrap=l;var p="suspendedStart",d="suspendedYield",f="executing",_="completed",m={};function h(){}function g(){}function v(){}var y={};s(y,a,(function(){return this}));var w=Object.getPrototypeOf,b=w&&w(w(j([])));b&&b!==n&&r.call(b,a)&&(y=b);var k=v.prototype=h.prototype=Object.create(y);function L(e){["next","throw","return"].forEach((function(t){s(e,t,(function(e){return this._invoke(t,e)}))}))}function O(e,t){function n(o,a,i,c){var s=u(e[o],e,a);if("throw"!==s.type){var l=s.arg,p=l.value;return p&&"object"==typeof p&&r.call(p,"__await")?t.resolve(p.__await).then((function(e){n("next",e,i,c)}),(function(e){n("throw",e,i,c)})):t.resolve(p).then((function(e){l.value=e,i(l)}),(function(e){return n("throw",e,i,c)}))}c(s.arg)}var o;this._invoke=function(e,r){function a(){return new t((function(t,o){n(e,r,t,o)}))}return o=o?o.then(a,a):a()}}function x(e,n){var r=e.iterator[n.method];if(r===t){if(n.delegate=null,"throw"===n.method){if(e.iterator.return&&(n.method="return",n.arg=t,x(e,n),"throw"===n.method))return m;n.method="throw",n.arg=new TypeError("The iterator does not provide a 'throw' method")}return m}var o=u(r,e.iterator,n.arg);if("throw"===o.type)return n.method="throw",n.arg=o.arg,n.delegate=null,m;var a=o.arg;return a?a.done?(n[e.resultName]=a.value,n.next=e.nextLoc,"return"!==n.method&&(n.method="next",n.arg=t),n.delegate=null,m):a:(n.method="throw",n.arg=new TypeError("iterator result is not an object"),n.delegate=null,m)}function E(e){var t={tryLoc:e[0]};1 in e&&(t.catchLoc=e[1]),2 in e&&(t.finallyLoc=e[2],t.afterLoc=e[3]),this.tryEntries.push(t)}function S(e){var t=e.completion||{};t.type="normal",delete t.arg,e.completion=t}function P(e){this.tryEntries=[{tryLoc:"root"}],e.forEach(E,this),this.reset(!0)}function j(e){if(e){var n=e[a];if(n)return n.call(e);if("function"==typeof e.next)return e;if(!isNaN(e.length)){var o=-1,i=function n(){for(;++o<e.length;)if(r.call(e,o))return n.value=e[o],n.done=!1,n;return n.value=t,n.done=!0,n};return i.next=i}}return{next:C}}function C(){return{value:t,done:!0}}return g.prototype=v,s(k,"constructor",v),s(v,"constructor",g),g.displayName=s(v,c,"GeneratorFunction"),e.isGeneratorFunction=function(e){var t="function"==typeof e&&e.constructor;return!!t&&(t===g||"GeneratorFunction"===(t.displayName||t.name))},e.mark=function(e){return Object.setPrototypeOf?Object.setPrototypeOf(e,v):(e.__proto__=v,s(e,c,"GeneratorFunction")),e.prototype=Object.create(k),e},e.awrap=function(e){return{__await:e}},L(O.prototype),s(O.prototype,i,(function(){return this})),e.AsyncIterator=O,e.async=function(t,n,r,o,a){void 0===a&&(a=Promise);var i=new O(l(t,n,r,o),a);return e.isGeneratorFunction(n)?i:i.next().then((function(e){return e.done?e.value:i.next()}))},L(k),s(k,c,"Generator"),s(k,a,(function(){return this})),s(k,"toString",(function(){return"[object Generator]"})),e.keys=function(e){var t=[];for(var n in e)t.push(n);return t.reverse(),function n(){for(;t.length;){var r=t.pop();if(r in e)return n.value=r,n.done=!1,n}return n.done=!0,n}},e.values=j,P.prototype={constructor:P,reset:function(e){if(this.prev=0,this.next=0,this.sent=this._sent=t,this.done=!1,this.delegate=null,this.method="next",this.arg=t,this.tryEntries.forEach(S),!e)for(var n in this)"t"===n.charAt(0)&&r.call(this,n)&&!isNaN(+n.slice(1))&&(this[n]=t)},stop:function(){this.done=!0;var e=this.tryEntries[0].completion;if("throw"===e.type)throw e.arg;return this.rval},dispatchException:function(e){if(this.done)throw e;var n=this;function o(r,o){return c.type="throw",c.arg=e,n.next=r,o&&(n.method="next",n.arg=t),!!o}for(var a=this.tryEntries.length-1;a>=0;--a){var i=this.tryEntries[a],c=i.completion;if("root"===i.tryLoc)return o("end");if(i.tryLoc<=this.prev){var s=r.call(i,"catchLoc"),l=r.call(i,"finallyLoc");if(s&&l){if(this.prev<i.catchLoc)return o(i.catchLoc,!0);if(this.prev<i.finallyLoc)return o(i.finallyLoc)}else if(s){if(this.prev<i.catchLoc)return o(i.catchLoc,!0)}else{if(!l)throw new Error("try statement without catch or finally");if(this.prev<i.finallyLoc)return o(i.finallyLoc)}}}},abrupt:function(e,t){for(var n=this.tryEntries.length-1;n>=0;--n){var o=this.tryEntries[n];if(o.tryLoc<=this.prev&&r.call(o,"finallyLoc")&&this.prev<o.finallyLoc){var a=o;break}}a&&("break"===e||"continue"===e)&&a.tryLoc<=t&&t<=a.finallyLoc&&(a=null);var i=a?a.completion:{};return i.type=e,i.arg=t,a?(this.method="next",this.next=a.finallyLoc,m):this.complete(i)},complete:function(e,t){if("throw"===e.type)throw e.arg;return"break"===e.type||"continue"===e.type?this.next=e.arg:"return"===e.type?(this.rval=this.arg=e.arg,this.method="return",this.next="end"):"normal"===e.type&&t&&(this.next=t),m},finish:function(e){for(var t=this.tryEntries.length-1;t>=0;--t){var n=this.tryEntries[t];if(n.finallyLoc===e)return this.complete(n.completion,n.afterLoc),S(n),m}},catch:function(e){for(var t=this.tryEntries.length-1;t>=0;--t){var n=this.tryEntries[t];if(n.tryLoc===e){var r=n.completion;if("throw"===r.type){var o=r.arg;S(n)}return o}}throw new Error("illegal catch attempt")},delegateYield:function(e,n,r){return this.delegate={iterator:j(e),resultName:n,nextLoc:r},"next"===this.method&&(this.arg=t),m}},e}(_);try{regeneratorRuntime=m}catch(e){"object"==typeof globalThis?globalThis.regeneratorRuntime=m:Function("r","regeneratorRuntime = r")(m)}_.mark(E),_.mark(x),_.mark(L),_.mark(w),_.mark(v);function h(e){var t=e.map,n=e.marker,r=e.latlng,o=e.results,a=e.infowindow;t.setCenter(r),n.setPosition(r),t.setZoom(16),a.setContent(o[0].formatted_address),a.open(t,n)}function g(){return new Promise((function(e,t){navigator.geolocation?navigator.geolocation.getCurrentPosition(e,t):alert(lpacTranslatedJsStrings.geolocation_not_supported)})).catch((function(e){console.log("Location Picker At Checkout Plugin: "+e.message),1!==e.code?alert(e.message):alert(lpacTranslatedJsStrings.manually_select_location)}))}function v(){return(v=t(_.mark((function e(t){var n,r,o,a;return _.wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return e.next=2,g();case 2:if(n=e.sent,r="",!n){e.next=8;break}o=n.coords.latitude,a=n.coords.longitude,e.next=14;break;case 8:return console.log("Location Picker At Checkout Plugin: Position object is empty. Navigator might be disabled or this site might be detected as insecure."),t.map.getZoom()<13&&t.map.setZoom(13),x(t),E(t),e.abrupt("return",r={lat:"",lng:""});case 14:return r={lat:parseFloat(o),lng:parseFloat(a)},e.abrupt("return",r);case 16:case"end":return e.stop()}}),e)})))).apply(this,arguments)}function y(e){return v.apply(this,arguments)}function w(){return(w=t(_.mark((function e(t){var n,r,o;return _.wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return e.next=2,y(t);case 2:if(""===(n=e.sent).lat||""===n.lng){e.next=12;break}return r=t.map,e.next=7,O(n,r);case 7:o=e.sent,t.geocodeResults=o,t.latLng=n,b(t),i(o);case 12:k(n,mapOptions);case 13:case"end":return e.stop()}}),e)})))).apply(this,arguments)}function b(e){var t=e.map,r=e.marker,o=e.latLng,a=e.infowindow,i=e.geocodeResults;if(i[0]){t.setZoom(16),t.setCenter(o),r.setPosition(o);var c=i[0].formatted_address;c=n(c),a.setContent(c),a.open(t,r),x(e),E(e)}}function k(e,t){""!==e.lat&&""!==e.lng||console.log("Location Picker At Checkout Plugin: Empty latLng. See fillLatLong()");var n=document.querySelector("#lpac_latitude"),r=document.querySelector("#lpac_longitude");null!=n&&null!=r?(n.value=e.lat,r.value=e.lng,n.dispatchEvent(new Event("input",{bubbles:!1})),r.dispatchEvent(new Event("input",{bubbles:!1})),!1===t.fill_in_fields&&(jQuery?jQuery(document.body).trigger("update_checkout"):console.log("Kikote: Can't find jQuery instance. fillLatLong()"))):console.log("LPAC: Can't find latitude and longitude input areas. Can't insert location coordinates.")}function L(){return(L=t(_.mark((function e(t,n){var r,o;return _.wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return r="",o=new google.maps.Geocoder,e.next=4,o.geocode({location:t},(function(e,t){console.log(e),"OK"===t?e[0]?r=e:window.alert(lpacTranslatedJsStrings.no_results_found):console.log("Geocoder failed due to: "+t)})).then((function(e){n.panTo(t)})).catch((function(e){console.log(e),"OVER_QUERY_LIMIT"===e.code&&(alert(lpacTranslatedJsStrings.moving_too_quickly),location.reload()),"UNKNOWN_ERROR"===e.code&&(alert(lpacTranslatedJsStrings.generic_error),location.reload())}));case 4:return e.abrupt("return",r);case 5:case"end":return e.stop()}}),e)})))).apply(this,arguments)}function O(e,t){return L.apply(this,arguments)}function x(e){var r=e.map,o=e.mapOptions,a=e.marker,c=e.infowindow;google.maps.event.clearListeners(r,"click");var s,l=document.querySelector("#lpac_places_autocomplete");r.addListener("click",(s=t(_.mark((function e(t){var s,u,p,d,f;return _.wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return e.next=2,O(t.latLng,r);case 2:if((s=e.sent)[0]){e.next=6;break}return console.log("LPAC: Results not as expected. See listenToMapClicks()"),e.abrupt("return");case 6:u=t.latLng.lat(),p=t.latLng.lng(),d={lat:parseFloat(u),lng:parseFloat(p)},i(s),k(d,o),a.setPosition(t.latLng),f=n(f=s[0].formatted_address),c.setContent(f),c.open(r,a),l.value=0;case 17:case"end":return e.stop()}}),e)}))),function(){return s.apply(this,arguments)}))}function E(e){var r=e.map,o=e.mapOptions,a=e.marker,c=e.infowindow;google.maps.event.clearListeners(a,"dragend");var s,l=document.querySelector("#lpac_places_autocomplete");google.maps.event.addListener(a,"dragend",(s=t(_.mark((function e(t){var a,s,u,p,d;return _.wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return a=t.latLng.lat(),s=t.latLng.lng(),u={lat:parseFloat(a),lng:parseFloat(s)},e.next=5,O(u,r);case 5:if((p=e.sent)[0]){e.next=9;break}return console.log("Results not as expected. See lpac_marker_listen_to_drag()"),e.abrupt("return");case 9:d=n(d=p[0].formatted_address),c.setContent(d),i(p),k(u,o),l.value=0;case 15:case"end":return e.stop()}}),e)}))),function(){return s.apply(this,arguments)}))}function S(e,t){Object.keys(t).forEach((function(n){var r,o,a=t[n],i=a.store_cords_text.split(","),c=i[0],s=i[1],l={lat:parseFloat(c),lng:parseFloat(s)},u={clickable:!1,icon:"undefined"!=typeof lpac_pro_js&&lpac_pro_js.is_pro?a.store_icon_text:"",position:l,map:e},p={content:a.store_name_text,disableAutoPan:!0},d=function(e){var t=void 0===e?{}:e;return new google.maps.Marker(t)}(u);(o=void 0===(r=p)?{disableAutoPan:!0}:r,new google.maps.InfoWindow(o)).open(e,d)}))}var P=window.lpac_map;P.setMapTypeId(mapOptions.lpac_checkout_page_map_default_type);var j,C=window.lpac_marker,q=window.lpac_infowindow,F=document.querySelector("#lpac-find-location-btn"),T=document.querySelector("#lpac_places_autocomplete");function A(e){var t=new Event("custom:lpacMapVisibilityCheckedBefore");document.dispatchEvent(t);var n=document.querySelector("#lpac_order__origin_store_field"),r=document.querySelector("#lpac_save_address_checkbox_field");if(e){document.querySelector("#lpac-map-container").style.display="block",document.querySelector("#lpac_is_map_shown").value=1,n&&n.classList.remove("hidden"),r&&r.classList.remove("hidden");var o=new Event("custom:lpacMapVisibilityShow");document.dispatchEvent(o)}else{document.querySelector("#lpac-map-container").style.display="none",document.querySelector("#lpac_is_map_shown").value=0,n&&n.classList.add("hidden"),r&&r.classList.add("hidden");var a=new Event("custom:lpacMapVisibilityHide");document.dispatchEvent(a)}var i=new Event("custom:lpacMapVisibilityCheckedAfter");document.dispatchEvent(i)}function R(){wp.ajax.post("lpac_checkout_map_visibility",{}).done((function(e){A(Boolean(e))})).fail((function(e){console.log(e)}))}function Q(){if("undefined"!=typeof lpacLastOrder&&null!==lpacLastOrder)if(lpacLastOrder.latitude&&lpacLastOrder.longitude){var e={lat:parseFloat(lpacLastOrder.latitude),lng:parseFloat(lpacLastOrder.longitude)},t=document.querySelector("#lpac_latitude"),n=document.querySelector("#lpac_longitude"),r=document.querySelector("#lpac_places_autocomplete");null!=t&&null!=n?(k(e,mapOptions),r.value=lpacLastOrder.used_places_autocomplete):console.log("LPAC: Can't find latitude and longitude input areas. Can't insert location coordinates.")}else console.log("Kikote - no last order location found.")}function N(){"undefined"!=typeof mapOptions&&null!==mapOptions?mapOptions.lpac_enable_places_autocomplete&&(mapOptions.lpac_places_autocomplete_hide_map&&A(!1),function(e){var t=e.map,n=e.mapOptions,o=n.lpac_places_autocomplete_fields;"undefined"!=typeof lpac_pro_js&&!1!==lpac_pro_js.places_autocomplete_searchbox_on_map&&o.push("lpac-map-searchbox"),o.forEach((function(o){var l=document.querySelector("#"+o),u=document.querySelector("#lpac_places_autocomplete");if(l){var p={fields:["address_components","formatted_address","geometry","name"],types:["address"]};"undefined"!=typeof lpac_pro_js&&null!==lpac_pro_js&&(lpac_pro_js.places_autocomplete_restrictions.length>0&&(p.componentRestrictions={country:lpac_pro_js.places_autocomplete_restrictions}),p.types=lpac_pro_js.places_autocomplete_type);var d=new google.maps.places.Autocomplete(l,p);d.bindTo("bounds",t),d.addListener("place_changed",(function(){var t=[d.getPlace()];"lpac-map-searchbox"!==o&&(l.value=d.getPlace().name,l.addEventListener("blur",(function(){d.getPlace().name&&setTimeout((function(){l.value.length>0||(l.value=r(d.getPlace()))}),1)})));var p={lat:parseFloat(t[0].geometry.location.lat()),lng:parseFloat(t[0].geometry.location.lng())};if(e.latlng=p,e.results=t,o.includes("shipping")&&(n.lpac_places_fill_shipping_fields&&c(t),k(p,n),h(e),u.value=1,x(e),E(e)),o.includes("billing")){if(n.lpac_places_fill_billing_fields&&s(t),!0===a())return;if("billing_only"===n.lpac_wc_shipping_destination_setting&&s(t),checkoutProvider&&"fluidcheckout"===checkoutProvider){var f=document.querySelector("#billing_same_as_shipping");if(f&&!1===f.checked)return void s(t)}k(p,n),h(e),u.value=1,x(e),E(e)}o.includes("lpac-map-searchbox")&&!0===window.lpacCanUsePremiumCode&&(i(t),k(p,n),h(e),u.value=1,x(e),E(e))}))}}))}({map:P,marker:C,infowindow:q,mapOptions:mapOptions})):console.log("LPAC: mapOptions object not present. This shouldn't be happening here. Contact Support.")}null!=F?F.addEventListener("click",t(_.mark((function e(){var t,n,r;return _.wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return t={map:P,mapOptions:mapOptions,marker:C,infowindow:q},e.next=3,y(t);case 3:if(""===(n=e.sent).lat||""===n.lng){e.next=12;break}return e.next=7,O(n,P);case 7:r=e.sent,t.latLng=n,t.geocodeResults=r,b(t),i(r);case 12:k(n,mapOptions),T.value=0;case 14:case"end":return e.stop()}}),e)})))):console.log("LPAC: Detect location button not present, skipping..."),(j=jQuery)(document).ready((function(){var e;jQuery?jQuery(document.body).trigger("update_checkout"):console.log("Kikote: Can't find jQuery instance. ($)"),N(),mapOptions.lpac_places_autocomplete_hide_map||j(document.body).on("updated_checkout",R),checkoutProvider&&"fluidcheckout"===checkoutProvider&&j(document.body).on("updated_checkout",(function(){N()})),!mapOptions.lpac_auto_detect_location||"undefined"!=typeof lpacLastOrder&&null!==lpacLastOrder?(null!==lpacLastOrder&&google.maps.event.addListenerOnce(P,"tilesloaded",(function(){if(Q(),lpacLastOrder.latitude&&lpacLastOrder.longitude){var e={lat:parseFloat(lpacLastOrder.latitude),lng:parseFloat(lpacLastOrder.longitude)};if(C.setPosition(e),lpacLastOrder.address)if(q.setContent(lpacLastOrder.address),"undefined"!=typeof lpac_pro_js&&null!==lpac_pro_js&&lpac_pro_js.shippingRegions.enabled&&lpac_pro_js.shippingRegions.showShippingRegions)var t=setInterval((function(){void 0!==window.lpacRegionsPlottingComplete&&(q.open(P,C),P.setCenter(e),clearInterval(t))}),100);else q.open(P,C),P.setCenter(e);else q.setContent(lpacTranslatedJsStrings.generic_last_order_address),q.open(P,C),P.setCenter(e);P.setZoom(16);var n={map:P,mapOptions:mapOptions,marker:C,infowindow:q};E(n),x(n)}})),function(){var e=mapOptions.lpac_places_autocomplete_hide_map;if(!1!==mapOptions.lpac_enable_places_autocomplete&&!1!==e){var t=document.querySelector("#lpac_order__origin_store_field");t&&t.classList.remove("hidden"),Q()}}()):(function(e){w.apply(this,arguments)}({map:P,mapOptions:mapOptions,marker:C,infowindow:q}),T.value=0),function(){var e=mapOptions.lpac_places_autocomplete_hide_map;if(mapOptions.lpac_enable_places_autocomplete,!1!==e){var t=j("#lpac_order__origin_store_field"),n=j("#billing_address_2_field").length>0?j("#billing_address_2_field"):j("#billing_address_1_field"),r=j("#shipping_address_2_field").length>0?j("#shipping_address_2_field"):j("#shipping_address_1_field"),o="";if((o="funnelkit"===checkoutProvider?j("#shipping_same_as_billing"):j("#ship-to-different-address-checkbox")).length){var a=o.is(":checked");"fluidcheckout"!==checkoutProvider&&(a?t.insertAfter(r):t.insertAfter(n)),o.on("click",(function(){("funnelkit"===checkoutProvider?j("#shipping_same_as_billing"):j("#ship-to-different-address-checkbox")).is(":checked")?t.insertAfter(r):t.insertAfter(n)})),"fluidcheckout"===checkoutProvider&&j(document.body).on("updated_checkout",(function(){j("#lpac_order__origin_store_field").insertAfter("#shipping_address_1_field")}))}}}(),function(){var e=mapOptions.lpac_places_autocomplete_hide_map;if(!0!==mapOptions.lpac_enable_places_autocomplete||!0!==e){var t=j("#lpac_order__origin_store_field");t.hide().removeClass("hidden"),j("#lpac_latitude").on("input",(function(){t.slideDown()}))}}(),function(){var e=j("#lpac_order__origin_store");if(!(e.length<1)){var t=j.map(j("#lpac_order__origin_store option"),(function(e){return e.value}));if(!0===j("body").hasClass("logged-in")){if(null===lpacLastOrder)return;lpacLastOrder.store_origin_id.length>0&&t.indexOf(lpacLastOrder.store_origin_id)>-1&&e.val(lpacLastOrder.store_origin_id).change()}else{var n=localStorage.getItem("lpac_user_preferred_store_location_id");if(!n)return;e.val(n).change()}}}(),(e=j("#lpac_order__origin_store")).length<1||e.on("change",(function(){j(document.body).trigger("update_checkout")})),"undefined"==typeof storeLocations||null===storeLocations||!storeLocations.length>0||google.maps.event.addListenerOnce(P,"tilesloaded",(function(){S(P,storeLocations)}))}))}();