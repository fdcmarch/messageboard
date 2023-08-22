<?php
$cakeDescription = __d('cake_dev', 'Message Board');
$cakeVersion = __d('cake_dev', 'CakePHP %s', Configure::version())
?>
<!DOCTYPE html>
<html>
<head>
	<?php echo $this->Html->charset(); ?>
	<title>
		<?php echo $cakeDescription ?> | 
		<?php echo $this->fetch('title'); ?>
	</title>
	<?php
		echo $this->Html->meta('icon');
        echo $this->Html->css('message');
        echo $this->Html->css('https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css');
		echo $this->Html->script('https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js');
        echo $this->Html->script('https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js');
		echo $this->Html->script('https://cdn.jsdelivr.net/npm/sweetalert2@11">');

		echo $this->fetch('meta');
		echo $this->fetch('css');
		echo $this->fetch('script');
	?>

<body>

        
        <?php echo $this->fetch('content'); ?>
		<footer>
			<?php echo $this->element('footer'); ?>
		</footer>
</body>

</html>
