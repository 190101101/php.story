<?php panel_breadcrumb($data->column, '/panel/vote/search/key/value'); ?>
<div class="row">
    <?php $main = new core\controller; ?>
    <?php $main->view('admin', 'requires', 'admin/sidebar', (object) [
        'data' => 'page_data',
        'page' => $data->page
        ]); ?>  
    <div class="col-lg-9">
        <div class="row">
            <div class="col-md-6">
                <h2>vote</h2>
            </div>
        </div>
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>id</th>
                    <th>user</th>
                    <th>article</th>
                    <th>created</th>
                    <th>show</th>
                    <th>delete</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($data->vote as $vote): ?>
                <tr>
                    <td><?php echo $vote->vote_id; ?></td>
                    <td><?php echo $vote->user_id; ?></td>
                    <td><?php echo $vote->article_id; ?></td>
                    <td><?php echo date_dy($vote->vote_created); ?></td>
                    <td><a class="btn btn-sm btn-success"
                        href="/panel/vote/show/<?php echo $vote->vote_id; ?>">show</a></td>
                    <td><a class="btn btn-sm btn-danger data-del"
                        data-get="/panel/vote/delete/<?php echo $vote->vote_id; ?>">delete</a></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td>id</td>
                    <td>user</td>
                    <td>article</td>
                    <td>created</td>
                    <td>show</td>
                    <td>delete</td>
                </tr>
            </tfoot>
        </table>
        <ul class="pagination justify-content-center">
            <?php pagination::selector($data->page, breadcump_search()); ?>
        </ul>
    </div>
</div>