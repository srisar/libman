<?php
/** @var Book $book */
$book = View::getData('book');

/** @var Category[] $categories */
$categories = View::getData('categories');

/** @var Subcategory[] $subcategories */
$subcategories = $book->getCategory()->getAllSubcategories();


/** @var BookInstance[] $book_instances */
$book_instances = $book->getAllBookInstances();

?>


<?php include_once "views/_header.php" ?>

<div class="container-fluid">

    <div class="row">
        <div class="col">
            <h1 class="text-center">Edit &mdash; <?= $book->title ?></h1>
        </div>
    </div>


    <div class="row">

        <!--SIDEBAR-->
        <div class="col-3">

            <div class="card">
                <div class="card-header">
                    <h3 class="m-0">Book details</h3>
                </div>
                <div class="card-body">

                    <?php View::renderErrorMessages() ?>

                    <form action="<?= App::createURL('/books/editing') ?>" method="post" enctype="multipart/form-data">

                        <input type="hidden" value="<?= $book->id ?>" name="id">

                        <div class="row">
                            <div class="col text-center">
                                <img id="cover-image" class="img-thumbnail" src="<?= $book->getImage() ?>"
                                     alt="Cover Image">
                            </div>
                        </div>


                        <div class="row">

                            <div class="col-12">

                                <div class="form-group">
                                    <label for="book-title">Title</label>
                                    <input class="form-control" type="text" value="<?= $book->title ?>" id="book-title"
                                           name="title" required>
                                </div>

                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <label for="book-category">Category</label>

                                    <select class="form-control" name="category_id" id="book-category">

                                        <?php foreach ($categories as $category): ?>

                                            <?php $selected = $category->id == $book->category_id ? "selected" : ""; ?>

                                            <option <?= $selected ?>
                                                    value="<?= $category->id ?>"><?= $category->category_name ?></option>
                                        <?php endforeach; ?>
                                    </select>

                                </div>

                            </div>

                            <div class="col-12">

                                <div id="output">
                                    <div class="form-group">
                                        <label for="book-category">Subcategory</label>

                                        <select class="form-control" name="subcategory_id" id="book-subcategory">

                                            <?php foreach ($subcategories as $subcategory): ?>

                                                <?php $selected = $subcategory->id == $book->subcategory_id ? "selected" : ""; ?>

                                                <option <?= $selected ?>
                                                        value="<?= $subcategory->id ?>"><?= $subcategory->subcategory_name ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>

                            </div><!--.col-->


                        </div><!--.row-->

                        <div class="row mb-3">
                            <div class="col">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="toggle_image_upload">
                                    <label class="custom-control-label" for="toggle_image_upload">Enable cover image upload</label>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col">
                                <div class="">

                                    <input class="" type="file" name="image" id="image" disabled>
                                </div>
                            </div>
                        </div><!--.row-->


                        <div class="row text-right">
                            <div class="col">

                                <?php if (empty($book_instances)): ?>
                                    <a href="<?= App::createURL('/books/delete', ['id'=>$book->id]) ?>" class="btn btn-danger">Delete</a>
                                <?php else: ?>
                                    <a href="#" class="btn btn-danger disabled">Delete</a>
                                <?php endif; ?>
                                <button class="btn btn-warning" type="submit">Save</button>
                            </div>
                        </div>


                    </form>
                </div>

            </div>

        </div><!--.col-->
        <!--END: SIDEBAR -->

        <div class="col">

            <!--BOOK INSTANCES-->
            <div class="card">
                <div class="card-header"><?php HtmlHelper::renderCardHeader("Book Instances (" . count($book_instances) . ")"); ?></div>
                <div class="card-body">

                    <?php if (isset($error)): ?>
                        <div class="alert-danger alert">
                            <div><?= $error ?></div>
                        </div>
                        <br>
                    <?php endif; ?>

                    <div class="alert alert-secondary">
                        <form class="form-inline" action="<?= App::createURL('/book-instance/adding') ?>" method="get">

                            <input type="hidden" name="book_id" value="<?= $book->id ?>">

                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <input type="checkbox" id="toggle_add_instance">
                                    </div>
                                    <span class="input-group-text">New book instances</span>
                                </div>
                                <input type="number" class="form-control" value="1" name="instance_count"
                                       id="instance_count" disabled>
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-primary" id="btn_add_instance" disabled>Add
                                    </button>
                                </div>
                            </div>


                        </form>
                    </div>

                    <table class="table table-bordered table-striped data-table">
                        <thead>
                        <tr>
                            <th>Book Instance</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($book_instances as $book_instance): ?>
                            <tr>
                                <td><?= $book_instance ?></td>
                                <td>
                                    <?php $status = $book_instance->getStatus() ?>
                                    <?php if ($status == BookTransaction::STATE_BORROWED): ?>
                                        <span class="badge badge-pill badge-warning"><?= $status ?></span>
                                    <?php elseif ($status == BookTransaction::STATE_AVAILABLE): ?>
                                        <span class="badge badge-pill badge-success"><?= $status ?></span>
                                    <?php elseif ($status == BookTransaction::STATE_DAMAGED): ?>
                                        <span class="badge badge-pill badge-danger"><?= $status ?></span>
                                    <?php endif; ?>

                                </td>
                                <td>
                                    <a class="btn btn-sm btn-warning"
                                       href="<?= App::createURL('/book-instance/view-history', ['instance_id' => $book_instance->id]) ?>">History</a>
                                    <?php if ($book_instance->getStatus() == BookInstance::STATE_AVAILABLE): ?>
                                        <a class="btn btn-sm btn-primary"
                                           href="<?= App::createURL('/transactions/show-member-search', ['instance_id' => $book_instance->id]) ?>">Lend</a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div><!--.card-->
        </div>
    </div><!--.row-->


</div><!--.container-->


<?php include_once "views/_footer.php" ?>


<script>

    $(function () {

        let subcategorySelect = $("#book-subcategory");
        let categorySelect = $("#book-category");
        let chkToggleImageUpload = $("#toggle_image_upload");
        let chkToggleAddBookInstance = $("#toggle_add_instance");

        // run the functions when page loads.
        generateSubcategories();


        // add listeners
        categorySelect.on('click', function () {
            generateSubcategories();
        });

        chkToggleImageUpload.on('click', function () {
            if (this.checked) {
                disableImageUploadField(false);
            } else {
                disableImageUploadField(true);
            }
        });

        chkToggleAddBookInstance.on('click', function () {
            if (this.checked) {
                disableAddBookInstanceForm(false);
            } else {
                disableAddBookInstanceForm(true);
            }
        });


    });

    function generateSubcategories() {

        let categorySelect = $("#book-category");
        let selectedCategoryId = categorySelect.val();

        $.get("<?= App::createURL('/api/get_subcategories') ?>", {
            id: selectedCategoryId,
            selected_subcat_id: <?= $book->subcategory_id ?>
        }).done(function (data) {
            $("#output").html(data);
        });
    }

    function disableImageUploadField(state) {
        let imageUploadField = document.getElementById("image");
        imageUploadField.disabled = state;
    }


    function disableAddBookInstanceForm(state) {
        let btnAddInstance = document.getElementById("btn_add_instance");
        let textInstanceCount = document.getElementById("instance_count");

        if (state === true) {
            btnAddInstance.disabled = true;
            textInstanceCount.disabled = true;
        } else {
            btnAddInstance.disabled = false;
            textInstanceCount.disabled = false;
        }
    }

</script>