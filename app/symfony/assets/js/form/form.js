const $ = require("jquery");
const instanceModal = $("#instanceModal");

jQuery(function () {
  const addInstanceAttributeFormDeleteLink = (item) => {
    const removeFormButton = document.createElement("button");
    removeFormButton.setAttribute('type', 'button');
    removeFormButton.classList.add('btn', 'delete-attribute', 'btn-icon-alone', 'btn-outline-danger');
    removeFormButton.innerHTML = "<i class='icon-trash'></i><span class='visually-hidden'>Supprimer</span>";

    $(item).find('.row .col-attribut-btn .mb-3').append(removeFormButton);

    removeFormButton.addEventListener("click", (e) => {
      e.preventDefault();
      item.remove();
    });
  };

  const addAttributeToInstanceForm = (e) => {
    const collectionHolder = document.querySelector(
      "." + e.currentTarget.dataset.collectionHolderClass
    );
    const item = document.createElement("div");

    item.innerHTML = collectionHolder.dataset.prototype.replace(
      /__name__/g,
      collectionHolder.dataset.index
    );

    collectionHolder.appendChild(item);
    collectionHolder.dataset.index++;

    addInstanceAttributeFormDeleteLink(item);
  };

  $("body").on(
    "click",
    "#instanceModal .add_attribute_list",
    addAttributeToInstanceForm
  );

  document.querySelectorAll("div.instanceAttributes div.attribute-row").forEach((element) => {
    addInstanceAttributeFormDeleteLink(element);
  });

  instanceModal.on(
    "input",
    "[id ^=instance_instanceAttributes][id $=version]",
    function () {
      var parent = $(this).parents(".col");
      var button = parent.next(".col-attribut-btns").find(".reset-attribute");

      button.removeClass("disabled");
    }
  );

  instanceModal.on("click", ".reset-attribute", function () {
    var parent = $(this).parents(".col-attribut-btns");
    var input = parent.prev(".col").find("input");

    input.val(input.attr("value"));
    $(this).addClass("disabled");
  });

  $(".modal").on("submit", " .modal-form", function (event) {
    event.preventDefault();
    var formData = $(this).serialize();
    var url = $(this).attr("action");
    var method = $(this).find('input[name="_method"]').val()
      ? $(this).find('input[name="_method"]').val()
      : "POST";

    common.modalFormSubmit(url, method, formData);
  });
});
