<?php
require_once ('header.php');
require_once ('navbar.php');

$stmt_categorias = $conexion->prepare("SELECT * FROM categorias");
$stmt_categorias->execute();
$result_categorias = $stmt_categorias->get_result();
$categorias = $result_categorias->fetch_all(MYSQLI_ASSOC);
$stmt_categorias->close();

$stmt_state = $conexion->prepare("SELECT * FROM estado");
$stmt_state->execute();
$result_state = $stmt_state->get_result();
$states = $result_state->fetch_all(MYSQLI_ASSOC);
$stmt_state->close();

?>


<style>
  .delete-icon {
    width: 32px;
    height: 32px;
    stroke: red;
    fill: none;
  }

  .modal-dialog {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
  }

  .modal {
    background-color: rgba(0, 0, 0, 0.5);
    backdrop-filter: blur(8px);
  }
</style>
<div class="page">


  <div class="page-wrapper">
    <!-- Page header -->
    <div class="page-header d-print-none">
      <div class="container-xl">
        <div class="row g-2 align-items-center">

          <!-- Page title actions -->
          <div class="col-auto ms-auto d-print-none">
            <div class="d-flex">
              <!-- <div class="me-3">
                 <div class="input-icon">
                  <input type="text" value="" class="form-control" placeholder="Search…">
                  <span class="input-icon-addon">
                     Download SVG icon from http://tabler-icons.io/i/search 
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24"
                      stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                      <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                      <path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" />
                      <path d="M21 21l-6 -6" />
                    </svg>
                  </span>
                </div>
            </div>  -->

              <!-- Page title actions -->
              <div class="col-auto ms-auto d-print-none">
                <div class="d-flex">
                  <button id="btn_create" type="button" class="btn btn-primary me-2" data-bs-toggle="modal"
                    data-bs-target="#filtroModalPost">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                      class="bi bi-filter" viewBox="0 0 16 16">
                      <path
                        d="M6 10.5a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 0 1h-3a.5.5 0 0 1-.5-.5m-2-3a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5m-2-3a.5.5 0 0 1 .5-.5h11a.5.5 0 0 1 0 1h-11a.5.5 0 0 1-.5-.5" />
                    </svg>Apply filter
                  </button>
                </div>
              </div>

              <button id="btn_create" type="button" class="btn btn-primary" data-bs-toggle="modal"
                data-bs-target="#imageModal">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24"
                  stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                  <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                  <path d="M12 5l0 14"></path>
                  <path d="M5 12l14 0"></path>
                </svg>New post
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Page body -->
    <div class="page-body">
      <div class="container-xl">
        <div class="row row-cards">
        </div>
        <div class="d-flex">
        </div>
      </div>
    </div>
    <!-- Modal de confirmación de borrado -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel"
      aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            Are you sure you want to delete this post?
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content" style="position: absolute; top: 50px; left: 0; right: 0; bottom: 0; overflow: auto;">
          <div class="modal-header">
            <h5 class="modal-title" id="superiorTitle">Create New Post</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body text-center">
            <!-- Sección para mostrar la imagen -->
            <div class="mb-3" style="max-height: 500px; overflow: hidden;">
              <img src="../img/genericUploadImage.jpg" class="img-fluid rounded"
                style="width: 100%; height: 100%; object-fit: cover;" alt="Imagen" id="postImage">
            </div>
            <div class="mb-3">
              <form id="postFormImage" enctype="multipart/form-data">
                <input type="file" id="postInput" name="postImage" accept="image/*" style="display: none;">
                <label for="postInput" class="btn btn-secondary">Select another image</label>
              </form>
            </div>
            <!-- Sección para el título y contenido -->
            <form id="postForm">
              <div class="mb-3">
                <label for="categoria" class="form-label">Select Category</label>
                <select class="form-select" id="categoria" name="categoria">
                  <option value="" selected disabled>Select a category</option>
                  <?php foreach ($categorias as $categoria) { ?>
                    <option value="<?php echo $categoria['id_categoria']; ?>">
                      <?php echo $categoria['nombre_categoria']; ?>
                    </option>
                  <?php } ?>
                </select>
              </div>
              <div class="mb-3">
                <label for="inputTitle" class="form-label">Tags</label>
                <div class="form-selectgroup">
                </div>
              </div>
              <!-- Sección para el título -->
              <div class="mb-3">
                <label for="inputTitle" class="form-label">Title</label>
                <input type="text" class="form-control input-short" id="title_post" name="title_post"
                  placeholder="Ingrese el título">
              </div>
              <!-- Sección para el contenido -->
              <div class="mb-3">
                <label for="inputContent" class="form-label">Content</label>
                <textarea class="form-control" id="content_post" name="content_post" rows="10"
                  placeholder="Ingrese el contenido"></textarea>
              </div>
              <div class="mb-3">
                <label for="state" class="form-label">Select Visibility</label>
                <select class="form-select" id="state" name="state">
                  <option value="" selected disabled>Select status</option>
                  <?php foreach ($states as $state) { ?>
                    <option value="<?php echo $state['id_estado']; ?>">
                      <?php echo $state['nombre_estado']; ?>
                    </option>
                  <?php } ?>
                </select>
              </div>
              <!-- Botones para guardar y cerrar el modal -->
              <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <button type="button" class="btn btn-secondary me-md-2" data-bs-dismiss="modal">Close</button>
                <button id="btn_post" type="button" class="btn btn-primary">Save</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    <?php require_once ('../filters/filters_posts.php'); ?>
  </div>
</div>
<?php require_once ('footer.php'); ?>

<script src="js/posts.js"></script>
<script src="js/filters.js"></script>