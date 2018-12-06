<script src="https://checkout.stripe.com/checkout.js"></script>
<script>
    $(function(){
      var handler = StripeCheckout.configure({
        key: 'pk_test_1Vnj0DXwfyRR50rFbF7m5k1N',
        image: '{{ (url) }}dashboard_assets/images/logo-sm.png',
        locale: 'auto',
        token: function(token) {
          $('body').block({ message: '<h3><i class="fa fa-spinner fa-spin"></i> Processing....</h3>' });
          // You can access the token ID with `token.id`.
          // Get the token ID to your server-side code for use.
          console.log(token);
          $.ajax({
            url: '{{ url('billing/stripeApi?ajax=3') }}',
            type: 'POST',
            data: {
              token: token.id
            },
            dataType: 'json',
            success: function (data) {
              if(data == true){
                showNotification('Payment successfully completed.', 'success');
              }else{
                showNotification('Ops! Something went wrong, please try again.', 'error');
              }
              $('body').unblock();
              location.href = '/billing';
            },
            error: function (data) {
              showNotification('Ops! Something went wrong, please try again.', 'error');
              $('body').unblock();
            }
          })
        }
      });

      document.getElementById('customButton').addEventListener('click', function(e) {
        // Open Checkout with further options:
        handler.open({
          name: 'Approval Base',
          description: '$999/mo',
          amount: 99900,
          email: '{{ user['email'] }}'
        });
        e.preventDefault();
      });

      // Close Checkout on page navigation:
      window.addEventListener('popstate', function() {
        handler.close();
      });
    })
</script>
