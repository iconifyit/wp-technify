<?php

$staging    = "http://iconify.staging.wpengine.com";
$production = "http://iconify.it";
$the_theme  = "iconify-too";
$js_app     = "js-compressor.php";
$css_app    = "css-compressor.php";

?>
<html>
	<head>
	  <style>
	        * { font-family: Helvetica, sans-serif; }
	        h1, h2, h3, h4, h5, h6 { font-weight: bold; }
	        h1 { font-size: 1em; text-transform: uppercase; }
			.wrapper { width: 960px; margin: 50px auto; }
			.ops li, .ops li a { 
				display: inline-block; 
				width: 256px; 
				height: 80px;
			}
			.ops li { margin-right: 10px; }
			.ops li a {
			
			    border: 1px solid #000;
			    line-height: 80px;
			    text-align: center;
			    text-decoration: none;
			    font-family: Helvetica, sans-serif;
			    font-weight: bold;
			    color: #666;
			}
			:focus, :active, :visited { color: #666; }
	  </style>
	</head>
	<body>
        <div class="wrapper">
            <h1>Staging</h1>
            <ul class="ops" data-environment="staging">
                <li><a href="<?php echo $staging; ?>/wp-content/themes/<?php echo $the_theme; ?>/js/<?php echo $js_app; ?>">JavaScript Compressor</a></li>
                <li><a href="<?php echo $staging; ?>/wp-content/themes/<?php echo $the_theme; ?>/css/<?php echo $css_app; ?>">CSS Compressor</a></li>
            </ul>
            <h1>Production</h1>
            <ul class="ops" data-environment="production">
                <li><a href="<?php echo $production; ?>/wp-content/themes/<?php echo $the_theme; ?>/js/<?php echo $js_app; ?>">JavaScript Compressor</a></li>
                <li><a href="<?php echo $production; ?>/wp-content/themes/<?php echo $the_theme; ?>/css/<?php echo $css_app; ?>">CSS Compressor</a></li>
            </ul>
        </div>
        <script src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
        <script>
            ;(function($) {
                $(function() {
                    $(".ops").each(function() {
                        var $ops = $(this);
	                    $("a", $ops).on("click", function(e) {
	                    
	                        e.preventDefault();
	                        
	                        var $op = $(this);
	                        
	                        $("#covert-op, #op-alert").remove();
	                        $("body").append(
	                            '<iframe id="covert-op" src="' + $op.attr("href") + '" style="display: none;"></iframe>'
	                        );
	                        var $covert = $("#covert-op");
	                        $covert.load(function() {
	                            $covert.remove();
	                            $(".wrapper").prepend(
	                                '<h1 id="op-alert">' + $op.text() + ' in ' + $ops.data('environment') + ' successfully run</h1>'
	                            );
	                            var $alert = $("#op-alert");
	                            setTimeout(function() {
	                                $alert.fadeOut(200, function() {
	                                    $alert.remove();
	                                });
	                            }, 5000);
	                        });
	                    });
                    });
                });
            })(jQuery);
        </script>
	</body>
</html>