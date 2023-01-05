<?php panel_breadcrumb($data->column, '/panel/comment/search/key/value'); ?>
<div class="row">
<?php $main = new core\controller; ?>
<?php $main->view('admin', 'requires', 'admin/sidebar', (object)[
    'data' => 'user',
    'user' => $data->user,
    ]); ?>
<div class="col-lg-9">
    <div class="row">
        <div class="col-md-6">
            <h2>comment by user</h2>
        </div>
    </div>
    <table class="table table-hover">
        <thead>
            <tr>
                <th>id</th>
                <th>text</th>
                <th>article</th>
                <th>user</th>
                <th>created</th>
                <th>status</th>
                <th>show</th>
                <th>update</th>
                <th>delete</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($data->comment as $comment): ?>
            <tr>
                <td><?php echo $comment->comment_id; ?></td>
                <td><?php echo substr($comment->comment_text, 0, 20); ?></td>
                <td>
                    <a href="/panel/comment/article/<?php echo $comment->article_id; ?>/page/1">
                    #<?php echo $comment->article_title; ?>
                    </a>
                </td>
                <td>@<?php echo $comment->user_login; ?></td>
                <td><?php echo date_ymd($comment->comment_created); ?></td>
                <td>
                    <label class="switch">
                    <input type="checkbox" class="data-get" 
                        data-get="/panel/comment/status/<?php echo $comment->comment_id; ?>" 
                        <?php echo $comment->comment_status == 1 ? 'checked' : NULL; ?> > 
                    <span class="slider round"></span>
                    </label>
                </td>
                <td><a class="btn btn-sm btn-success"
                    href="/panel/comment/show/<?php echo $comment->comment_id; ?>">show</a></td>
                <td><a class="btn btn-sm btn-warning"
                    href="/panel/comment/update/<?php echo $comment->comment_id; ?>">update</a></td>
                <td><a class="btn btn-sm btn-danger data-del"
                    data-get="/panel/comment/delete/<?php echo $comment->comment_id; ?>">delete</a></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <td>id</td>
                <td>text</td>
                <td>article</td>
                <td>user</td>
                <td>created</td>
                <td>status</td>
                <td>show</td>
                <td>update</td>
                <td>delete</td>
            </tr>
        </tfoot>
    </table>
    <ul class="pagination justify-content-center">
        <?php pagination::selector($data->page, "panel/article/user/{$data->user->user_id}/"); ?>
    </ul>
</div>