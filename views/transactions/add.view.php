<?php include_once "views/_header.php" ?>

<?php

/** @var BookInstance $book_instance */
$book_instance = View::getData('book_instance');
/** @var Book $book */
$book = View::getData('book');
/** @var Member $member */
$member = View::getData('member');
/** @var BookTransaction[] $member_transactions */
$member_transactions = View::getData('member_transactions');

$borrowing_date = View::getData('borrowing_date');
$returning_date = View::getData('returning_date');

?>


<div class="container-fluid">

  <div class="row mb-3">
    <div class="col text-center">
      <h1 class="text-center">Lend a book</h1>
    </div>
  </div><!--.row-->

  <div class="row justify-content-center">
    <div class="col-12">

      <?php View::renderErrorMessages() ?>

    </div>
  </div>

  <div class="row">

    <div class="col-4">

      <div class="card">
        <div class="card-header"></div>
        <div class="card-body">

          <form action="<?= App::createURL('/transactions/adding') ?>" method="post">

            <input type="hidden" name="instance_id" value="<?= $book_instance->id ?>">
            <input type="hidden" name="member_id" value="<?= $member->id ?>">

            <div class="form-group">
              <input type="text" class="form-control" value="<?= $book_instance ?>" readonly>
            </div>

            <div class="form-group">
              <input type="text" class="form-control" value="<?= $member ?>" readonly>
            </div>

            <div class="form-group">
              <label for="">Borrowing Date</label>
              <input type="text" class="form-control date-picker" name="borrowing_date"
                     value="<?= App::toDateString($borrowing_date) ?>">
            </div>

            <div class="form-group">
              <label for="">Returning Date</label>
              <input type="text" class="form-control date-picker" name="returning_date"
                     value="<?= App::toDateString($returning_date) ?>">
            </div>

            <div class="form-group">
              <label for="">Remarks</label>
              <textarea class="form-control" name="remarks" id="" cols="" rows="5"></textarea>
            </div>

            <div>
              <button class="btn btn-success" type="submit">Lend</button>
            </div>

          </form>

        </div>
      </div>

    </div>

    <div class="col-8">
      <?php include_once BASE_PATH . "/views/book_instance/view_history.php" ?>
    </div>

  </div>


</div>

<?php include_once "views/_footer.php" ?>




