<?php breadcump(); ?>

<div class="row">
    <?php $main = new core\controller; ?>
    <?php $main->view('main', 'requires', 'main/sidebar', (object) [
        'data' => 'article',
        'article' => $data->article,
        ]); ?>
    <div class="col-lg-9">
        <div class="row">
            <div class="col-lg-6">
                <h2>comment by own article</h2>
            </div>
        </div>
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>id</th>
                    <th>text</th>
                    <th>created</th>
                    <th>user</th>
                    <th>show</th>
                    <th>delete</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($data->comment as $comment): ?>
                <tr>
                    <td><?php echo $comment->comment_id; ?></td>
                    <td><?php echo substr($comment->comment_text, 0, 20); ?></td>
                    <td><?php echo date_ymd($comment->comment_created); ?></td>
                    <td class="text-danger">@<?php echo db()->t1where('user', 'user_id=?', [$comment->user_id])->user_login; ?></td>
                    <td><a class="btn btn-sm btn-success"
                        href="/comment/own/show/<?php echo $comment->comment_id; ?>">show</a></td>
                    <td><a class="btn btn-sm btn-danger data-del"
                        data-get="/comment/delete/<?php echo $comment->comment_id; ?>">delete</a></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td>id</td>
                    <td>text</td>
                    <td>created</td>
                    <td>user</td>
                    <td>show</td>
                    <td>delete</td>
                </tr>
            </tfoot>
        </table>
        <ul class="pagination justify-content-center">
            <?php pagination::selector($data->page, "comment/user/article/{$data->id}/"); ?>
        </ul>
    </div>
</div>
