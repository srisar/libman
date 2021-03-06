<div class="card">
    <div class="card-header">
        <?php HtmlHelper::renderCardHeader("Transactions for {$member->fullname}"); ?>
    </div>
    <div class="card-body">

        <?php if (!empty($member_transactions)): ?>

            <table class="table table-bordered table-striped data-table">

                <thead>
                <tr>
                    <th>ID</th>
                    <th>Book</th>
                    <th>Borrowed Date</th>
                    <th>Returning Date</th>
                    <th>Returned Date</th>
                    <th>State</th>

                </tr>
                </thead>

                <tbody>


                <?php foreach ($member_transactions as $transaction): ?>

                    <tr>
                        <td><a href="<?= App::createURL('/transactions/single', ['id' => $transaction->id]) ?>" class="btn btn-sm btn-success"><?= $transaction->id ?></a></td>
                        <td><?= $transaction->getBookInstance() ?></td>
                        <td><?= App::toDateString($transaction->borrowing_date) ?></td>
                        <td><?= App::toDateString($transaction->returning_date) ?></td>
                        <td><?= App::toDateString($transaction->returned_date) ?></td>
                        <td>
                            <?php if ($transaction->state == BookTransaction::STATE_BORROWED): ?>
                                <span class="badge badge-pill badge-warning"><?= $transaction->state ?></span>
                            <?php elseif ($transaction->state == BookTransaction::STATE_RETURNED): ?>
                                <span class="badge badge-pill badge-success"><?= $transaction->state ?></span>
                            <?php elseif ($transaction->state == BookTransaction::STATE_DAMAGED): ?>
                                <span class="badge badge-pill badge-danger"><?= $transaction->state ?></span>
                            <?php endif; ?>
                        </td>
                    </tr>

                <?php endforeach; ?>


                </tbody>

            </table>
        <?php else: ?>
            <div class="alert alert-warning">
                No transactions.
            </div>
        <?php endif; ?>

    </div>

</div>


