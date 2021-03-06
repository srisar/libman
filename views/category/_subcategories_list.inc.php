<div class="card">

    <div class="card-header">

        <button class="btn btn-warning btn-sm" id="btn_open_edit_category_modal"><i class="far fa-edit"></i></button>
        <?php HtmlHelper::renderCardHeader("Subcategories in {$selected_category}"); ?>|
        <button class="btn btn-sm btn-warning" id="btn_open_add_subcategory_modal"><i class="far fa-plus-circle"></i> Add new subcategory</button>
    </div>


    <div class="card-body">

        <?php if (!empty($subcategories)): ?>

            <table class="table table-striped table-bordered data-table-basic">
                <thead>
                <tr>
                    <th>Subcategories</th>
                    <th>Number of Books</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>

                <?php foreach ($subcategories as $subcategory): ?>
                    <tr>
                        <td>
                            <a href="#" class="subcat_item" data-id="<?= $subcategory->id ?>"><?= $subcategory ?></a>
                        </td>
                        <td class="text-right"><?= $subcategory->getBooksCount() ?></td>
                        <td>
                            <a href="<?= App::createURL('/books/subcategory', ['subcat_id' => $subcategory->id]) ?>" class="btn btn-sm btn-success">
                                View Books
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>

                </tbody>
            </table>

        <?php endif; ?>
    </div>

</div>
