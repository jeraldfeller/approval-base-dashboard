<script type="text/javascript">
  $(function () {
    var table = $('#dt-opt').DataTable({
      "serverSide": true,
      "ajax": {
        "url": "{{ url("admin/datatables/users") }}",
        "data": function (d) {
          d.tableFilter = $('#table-filter').val();
        }
      },
      "stripeClasses": [],
      "pageLength": 25,
      "pagingType": "full_numbers",
      "lengthMenu": [[10, 25, 50, 100, 250, 500], [10, 25, 50, 100, 250, 500]],
      "stateSave": true,
      "columnDefs": [
        {"targets": [0], "orderable": false},
        {"targets": [0], "width": "5%"},
        {"targets": [0, 1, 2, 3, 4], "className": "text-center vertical-middle"},
      ],
      "language": {
        "emptyTable": "There are no users available"
      },
      "order": [[1, "asc"]]

    });

    $("#dt-opt").on("change", "#checkbox-toggle-all", function (e) {

      var toggleAllButton = $(e.target);
      var checkboxes = $(".dt-checkbox");

      var toggleAllButtonChecked = toggleAllButton.is(":checked");
      if (typeof toggleAllButtonChecked !== typeof undefined && toggleAllButtonChecked !== false) {

        checkboxes.prop("checked", true);
        checkboxes.parents(":eq(2)").removeClass().addClass("bg-active");
      } else {

        checkboxes.prop("checked", false);
        checkboxes.parents(":eq(2)").removeClass();
        if (checkboxes.parents(":eq(2)").data("seen") === false) {
          checkboxes.addClass("bg-unread");
        }
      }

      checkboxes.trigger("change");
    });

    // Checkbox changes
    $("#dt-opt").on("change", ".dt-checkbox", function (e) {

      var tableRow = $(e.target).parents(":eq(2)");
      var checkbox = $(e.target);

      var checked = checkbox.is(":checked");
      if (typeof checked !== typeof undefined && checked !== false) {

        tableRow.removeClass().addClass("bg-active");
        checkbox.prop("checked", true);
      } else {

        tableRow.removeClass();
        if (tableRow.data("seen") === false) {
          tableRow.addClass("bg-unread");
        }

        checkbox.prop("checked", false);
      }

      // Check if any checkboxes are checked
      var toggleAllCheckbox = $("#checkbox-toggle-all");
      var checkboxes = $(".dt-checkbox");
      var checkedCheckboxes = $(".dt-checkbox:checked");

      if (checkboxes.length === checkedCheckboxes.length) {
        toggleAllCheckbox.prop("checked", true);
      } else if (checkedCheckboxes.length < checkboxes.length || checkedCheckboxes.length === 0) {
        toggleAllCheckbox.prop("checked", false);
      }

      var event = jQuery.Event("change");
      event.target = $(this).find("#checkbox-toggle-all");
      $(this).trigger(event);
    });

    // Clicking on first <td>, checks the checkbox
    $("#dt-opt").on("click", "td:nth-child(1)", function () {

      var checkbox = $(this).find('>.dt-checkbox');
      checkbox.trigger("click");
    });

    // Clicking on any <td> in .lead-row, except first one
    $("#dt-opt").on("click", "td:not(:first-child)", function (e) {
      var userId = $(e.target).parent().attr("id").split("_")[1];
      location.href = "{{ url('admin/users/') }}" + userId + "/view";
    });

  });
</script>
