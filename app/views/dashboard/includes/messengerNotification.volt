<script>

      // Notifications
      Messenger.options = {
        extraClasses: 'messenger-fixed messenger-on-top messenger-on-right',
        theme: 'flat'
      }

      function showNotification(message, type){
        Messenger().post({
          message: message,
          type: type,
          showCloseButton: true
        });
      }

</script>