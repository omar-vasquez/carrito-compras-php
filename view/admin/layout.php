<!DOCTYPE html>
<html lang="en">
  
<head>
    <meta charset="utf-8">
    <title>FAQ - Bootstrap Admin Template</title>
    
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">    
    
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/bootstrap-responsive.min.css" rel="stylesheet">
    
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:400italic,600italic,400,600" rel="stylesheet">
    <link href="assets/css/font-awesome.css" rel="stylesheet">
    
    <link href="assets/css/style.css" rel="stylesheet">
    
    
    <link href="assets/css/pages/faq.css" rel="stylesheet"> 

    <link rel="stylesheet" type="text/css" href="assets/dataTables/css/jquery.dataTables.min.css"/>
     
    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
  </head>

<body>
<!-- Nav bar users -->
<?php echo $_content->nav?>

<?php echo $_content->subnav?>

<div class="main">
	
	<div class="main-inner">

	    <div class="container">
	
  			<?php echo $_content->content?>

		</div> <!-- /container -->

	</div> <!-- /extra-inner -->

</div> <!-- /extra -->


    
    
<!-- <div class="footer">
    
    <div class="footer-inner">
        
        <div class="container">
            
            <div class="row">
                
                <div class="span12">
                    &copy; 2013 <a href="http://www.egrappler.com/">Bootstrap Responsive Admin Template</a>.
                </div> /span12
                
            </div> /row
            
        </div> /container
        
    </div> /footer-inner
    
</div> /footer
     -->

<!-- Le javascript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->


<script src="assets/js/jquery-1.7.2.min.js"></script>

<script src="assets/js/bootstrap.js"></script>
<script src="assets/js/base.js"></script>
<script src="assets/js/faq.js"></script>

<script>

$(function () {
	
	$('.faq-list').goFaq();

});

</script>
<script type="text/javascript">
$(document).ready(function() {
    $('#table').fadeIn(1000); 
    setTimeout(function() {
        $(".messages").fadeOut(1500);
    },2000);
});
</script>

  </body>

</html>
