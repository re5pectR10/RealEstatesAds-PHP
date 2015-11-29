<?php
use \FW\HTML\Form;
?>
<div class="container">

    <hr>
    <footer>
        <div class="row">
            <div class="col-lg-12">
                <p>Copyright &copy; Your Website 2015</p>
            </div>
        </div>
    </footer>

</div>
<?= Form::script('js/ekko-lightbox.min.js') ?>
<script>
    $(document).delegate('*[data-toggle="lightbox"]', 'click', function(event) {
        event.preventDefault();
        $(this).ekkoLightbox();
    });
</script>
</body>
</html>