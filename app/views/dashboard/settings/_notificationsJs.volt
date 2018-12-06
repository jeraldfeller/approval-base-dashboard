<script type="text/javascript">
    $(function(){
        $('.notifications-checkbox').click(function(){
          $action = $(this).attr('data-action');
          $isChecked = $(this).is(':checked');
          console.log($action, $isChecked);


          $.ajax({
            url: '{{ url('notifications/notificationsUpdate?ajax=1') }}',
            type: 'POST',
            data: {
              action: $action,
              value: ($isChecked == true ? 1 : 0)
            },
            dataType: 'json'
          }).done(function (response) {
            if(response == false){
              showNotification('Ops! Something went wrong, please try again.', 'error');
            }
          });
        });
    });
</script>