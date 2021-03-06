<?php include_once "views/_header.php" ?>

<?php
/** @var Department $department */
$department = View::getData('department');
$type = View::getData('type');
/** @var Member $member */
$member = View::getData('member');

$errors = View::getError('errors');

/** @var BookTransaction[] $member_transactions */
$member_transactions = View::getData('member_transactions');

?>


<div class="container-fluid">

    <div class="row justify-content-center">
        <div class="col-12 col-lg-4 mb-3">

            <?php if (!empty($errors)): ?>
                <div class="alert alert-warning mb-3">
                    <p class="font-weight-bold">Correct the following errors</p>
                    <?php foreach ($errors as $error): ?>
                        <li><?= $error ?></li>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <div class="card">
                <div class="card-header">
                    <?php HtmlHelper::renderCardHeader("Edit {$type}: {$member->fullname}") ?>
                </div>
                <div class="card-body">

                    <form action="<?= App::createURL('/members/editing') ?>" method="post" enctype="multipart/form-data">

                        <input type="hidden" name="id" value="<?= $member->id ?>">

                        <div class="row">

                            <div class="col-12 col-lg-6">
                                <div class="form-group">
                                    <label for="full_name">Full name</label>
                                    <input type="text" name="full_name" id="full_name" class="form-control" required value="<?= $member->fullname ?>">
                                </div>
                            </div>


                        </div>

                        <div class="row">
                            <div class="col-12 col-lg-6">
                                <div class="form-group">
                                    <label for="member_since">Member since</label>
                                    <input type="text" name="member_since" id="member_since" class="form-control date-picker"
                                           value="<?= App::toDateString($member->member_since) ?>">
                                    <!--                                    <div class="" id="date-picker-error"></div>-->
                                    <div class="invalid-feedback">Invalid date</div>
                                    <div class="valid-feedback">Looks good</div>
                                </div>
                            </div>
                            <div class="col-12 col-lg-6">
                                <div class="form-group">
                                    <label for="full_name">Department</label>
                                    <input type="text" value="<?= $department ?>" class="form-control" readonly>
                                    <input type="hidden" name="dept_id" value="<?= $department->id ?>">
                                </div>
                            </div>
                        </div>


                        <input type="hidden" name="type" value="<?= $type ?>">

                        <div class="row">
                            <div class="col text-right">
                                <button type="submit" id="submit-button" class="btn btn-primary"><i class="far fa-check"></i> Save</button>
                                <a href="<?= App::createURL('/members/department', ['dept_id' => $department->id]) ?>" class="btn btn-secondary"><i class="far fa-times"></i> Cancel</a>
                            </div>
                        </div>

                    </form>

                </div>
            </div><!--.card-->


        </div><!--.col-->

        <div class="col-lg-12 mb-3">

            <?php include_once BASE_PATH . "/views/members/_transaction_history_table.inc.php" ?>

        </div>

    </div><!--.row-->


</div>

<?php include_once "views/_footer.php" ?>
