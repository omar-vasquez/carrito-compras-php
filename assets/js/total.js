<script type="text/javascript" src="assets/dataTables/jquery-1.12.3.js"></script>
<script>
       var total_cart = jQuery.noConflict();
       window.jQuery = total_cart;
</script>
<script>
total_cart(document).ready(function() {
 $( "#table" ).fadeIn(1000);
   importe_total = 0
  total_cart(".importe_linea").each(
    function(index, value) {
      importe_total = importe_total + eval(total_cart(this).val());
    }
  );
  total_cart("#total").val(importe_total);
});
</script>