(function ($) {
  "use strict";

  /**
   * Show the address input fields when the addresses drop down is changed.
   */
  function lpacDisplayAddressInputs() {
    const presentAddressFields = document.querySelector("#lpac-address-fields");

    // Remove all fields when user changes dropdown value.
    if (presentAddressFields) {
      presentAddressFields.remove();
    }

    const addressesDropdown = document.querySelector(
      "#lpac-saved-addresses-dropdown"
    );
    const selectedIndex = addressesDropdown.selectedIndex;
    const addressDetailsJson =
      addressesDropdown[selectedIndex].dataset.addressDetails;

    // Show/Hide action buttons based on selected item from dropdown
    if (addressesDropdown[selectedIndex].value.length > 0) {
      document.querySelector(".lpac-address-action-buttons").style.visibility =
        "visible";
    } else {
      document.querySelector(".lpac-address-action-buttons").style.visibility =
        "hidden";
    }

    // Return if we have no values e.g for default "Select" text.
    if (!addressDetailsJson) {
      return;
    }

    const addressDetails = JSON.parse(addressDetailsJson);

    const ulEl = document.createElement("ul");
    ulEl.setAttribute("id", "lpac-address-fields");

    const savedAddresses = document.querySelector(
      "#lpac-saved-addresses-dropdown-wrap"
    );
    savedAddresses.insertAdjacentElement("afterend", ulEl);

    for (const key in addressDetails) {
      const liEl = document.createElement("li");
      ulEl.appendChild(liEl);

      const labelEl = document.createElement("label");
      labelEl.setAttribute("for", key);
      labelEl.innerText = lpacTranslatedWCAddressFields[key];
      liEl.appendChild(labelEl);

      const inputEl = document.createElement("input");
      inputEl.setAttribute("name", key);
      inputEl.setAttribute("id", key);
      inputEl.setAttribute("type", "text");
      inputEl.setAttribute("value", addressDetails[key]);

      // Allow only editing of address name. This is further validated in PHP.
      if (key !== "address_name") {
        inputEl.setAttribute("disabled", true);
      }

      if (key === "address_name") {
        inputEl.setAttribute("required", true);
      }

      liEl.appendChild(inputEl);
    }
  }

  /**
   * Set our action to update the address.
   */
  function updateAddress() {
    const actionInput = document.querySelector("#lpac-update-delete");
    actionInput.value = "update";
  }

  /**
   * Set our action to delete the address.
   */
  function deleteAddress() {
    const actionInput = document.querySelector("#lpac-update-delete");
    actionInput.value = "delete";
  }

  /**
   * Create our event listeners.
   */
  function lpacAddAddressesEventListeners() {
    const addressesDropdown = document.querySelector(
      "#lpac-saved-addresses-dropdown"
    );

    if (
      typeof addressesDropdown === "undefined" ||
      addressesDropdown === null
    ) {
      return;
    }

    const updateBtn = document.querySelector("#lpac-update-saved-address");
    const deleteBtn = document.querySelector("#lpac-delete-saved-address");

    addressesDropdown.addEventListener("change", lpacDisplayAddressInputs);
    // Listen to which one of the action buttons were clicked (Update or Delete)
    updateBtn.addEventListener("click", updateAddress);
    deleteBtn.addEventListener("click", deleteAddress);
  }

  /**
   * Initialize our code.
   */
  $(function () {
    lpacAddAddressesEventListeners();
  });
})(jQuery);
