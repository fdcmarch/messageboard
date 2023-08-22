</div>
<script>
	const BASE_URL = '<?php echo Router::url('/', true); ?>';
</script>
<script>
// ==============================================================
// This is the Default SERVER request AJAX
// ==============================================================
const SERVER = (url, data) => {
    var res = null;
    $.ajax({
        type: 'POST',
        url: url,
        data: data,
        async: false,
        processData: false,
        contentType: false,
        beforeSend: function() {},
        success: function(response) {
            res = JSON.parse(response);
        },
        error: function(xhr) {
            console.log('error');
        },
    });
    return res;
}
</script>