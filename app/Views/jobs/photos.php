<?php
$header = array('title' => 'Add Job Photos');
echo view('includes/header', $header);
?>

<body class="vertical-layout vertical-menu-modern navbar-floating footer-static">

<?php echo view('includes/inner_header'); ?>
<?php echo view('includes/menu'); ?>

<div class="app-content content">
<div class="content-overlay"></div>
<div class="header-navbar-shadow"></div>
<div class="content-wrapper container-xxl p-0">
<div class="content-body">

<div class="row">
<div class="col-12">
<div class="card">

<div class="card-header border-bottom">
    <h4 class="card-title">
        Job Photos (Job Id: <?php echo sprintf('%05d', $jobid); ?>)
    </h4>
</div>

<div class="card-body py-2 my-25">

<?php echo $message_output->run(); ?>

<form method="post"
      action="<?php echo base_url('jobs/uploadPhotos'); ?>"
      enctype="multipart/form-data">

<div class="row">




<!-- ================= IMAGE GRID ================= -->

<div class="col-12 mb-2">
    <h5 class="mb-1">Uploaded Photos</h5>

    <div class="row">

        <?php if (!empty($photos)) : ?>
            <?php foreach ($photos as $photo) : ?>

                <div class="col-md-3 mb-2" id="photo_<?php echo $photo['id']; ?>">
                    <div class="card position-relative shadow-sm">

                       <a href="javascript:void(0);"
   onclick="openLightbox('<?php echo base_url('writable/uploads/jobs/'.$jobid.'/full/'.$photo['file_name']); ?>')">

                            <img src="<?php echo base_url('writable/uploads/jobs/'.$jobid.'/thumb/'.$photo['file_name']); ?>"
                                 class="img-fluid rounded"
                                 style="height:200px; object-fit:cover;">
                        </a>

                        <button type="button"
                                class="btn btn-danger btn-sm position-absolute"
                                style="top:5px; right:5px;"
                                onclick="deletePhoto(<?php echo $photo['id']; ?>)">
                            ×
                        </button>

                    </div>
                </div>

            <?php endforeach; ?>
        <?php else : ?>
            <div class="col-12">
                <p class="text-muted">No photos uploaded yet.</p>
            </div>
        <?php endif; ?>

    </div>
</div>


<!-- ================= MULTI UPLOAD ================= -->

<div class="col-12 mb-2">
    <label class="form-label">Upload Multiple Photos</label>

    <input type="hidden" id="job_id" name="job_id" value="<?php echo $jobid; ?>">

    <input type="file"
           id="photos"
           name="photos[]"
           class="form-control"
           multiple
           accept="image/*">

    <br>

    <div class="progress" style="height:20px; display:none;" id="uploadProgressWrapper">
        <div class="progress-bar progress-bar-striped progress-bar-animated"
             id="uploadProgressBar"
             role="progressbar"
             style="width: 0%;">
            0%
        </div>
    </div>

    <br>

    <button type="button"
            id="uploadBtn"
            class="btn btn-primary">
        Upload Photos
    </button>
</div>



</div>
</form>

</div>
</div>
</div>
</div>

</div>
</div>
</div>


<?php echo view('includes/footer_scripts'); ?>
<!-- Lightbox Modal -->
<div class="modal fade" id="imageLightbox" tabindex="-1">
  <div class="modal-dialog modal-xl modal-dialog-centered">
    <div class="modal-content bg-dark">

      <div class="modal-header border-0">
        <button type="button"
                class="btn-close btn-close-white"
                data-bs-dismiss="modal">
        </button>
      </div>

      <div class="modal-body text-center">
        <img id="lightboxImage"
             src=""
             class="img-fluid rounded">
      </div>

    </div>
  </div>
</div>

<script>
function openLightbox(imageUrl)
{
    $("#lightboxImage").attr("src", imageUrl);
    var modal = new bootstrap.Modal(document.getElementById('imageLightbox'));
    modal.show();
}
$("#uploadBtn").click(function(){

    var files = $("#photos")[0].files;
    var jobId = $("#job_id").val();

    if(files.length === 0){
        alert("Please select images.");
        return;
    }

    var formData = new FormData();
    formData.append("job_id", jobId);

    for (var i = 0; i < files.length; i++) {
        formData.append("photos[]", files[i]);
    }

    $("#uploadBtn").prop("disabled", true);
    $("#uploadProgressWrapper").show();

    $.ajax({
        xhr: function () {
            var xhr = new window.XMLHttpRequest();

            xhr.upload.addEventListener("progress", function (evt) {
                if (evt.lengthComputable) {

                    var percent = Math.round((evt.loaded / evt.total) * 100);

                    $("#uploadProgressBar")
                        .css("width", percent + "%")
                        .html(percent + "%");
                }
            }, false);

            return xhr;
        },

        url: "<?php echo base_url('jobs/uploadPhotos'); ?>",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        success: function (response) {

            $("#uploadProgressBar")
                .removeClass("progress-bar-animated")
                .addClass("bg-success")
                .html("Upload Complete");

            setTimeout(function(){
                location.reload();
            }, 1000);
        },

        error: function () {
            alert("Upload failed.");
            $("#uploadBtn").prop("disabled", false);
        }
    });

});

function deletePhoto(id)
{
    if(confirm("Delete this photo?")) {

        $.ajax({
            url: "<?php echo base_url('jobs/deletePhoto'); ?>/" + id,
            type: "POST",
            success: function(response){
                $("#photo_" + id).remove();
            }
        });

    }
}

</script>

<?php echo view('includes/footer'); ?>
