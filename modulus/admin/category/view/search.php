<?php panel_breadcrumb($data->column, '/panel/category/search/key/value'); ?>
<div class="row">
    <?php $main = new core\controller; ?>
    <?php $main->view('admin', 'requires', 'admin/sidebar', (object) [
        'data' => 'page_data',
        'page' => $data->page,
        'search' => $data->search
        ]); ?>  
    <div class="col-lg-9">
        <div class="row">
            <div class="col-md-6">
                <h2>category search for: <?php echo segment(6); ?></h2>
            </div>
        </div>
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>id</th>
                    <th>title</th>
                    <th>text</th>
                    <th>created</th>
                    <th>show</th>
                    <th>update</th>
                    <th>delete</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($data->category as $category): ?>
                <tr>
                    <td><?php echo $category->category_id; ?></td>
                    <td>
                        <a href="/panel/article/category/<?php echo $category->category_id; ?>/page/1" class="<?php echo !$category->category_deleted ?: 'text-warning'; ?>">
                        <?php echo $category->category_title; ?>
                        </a>
                    </td>
                    <td><?php echo substr($category->category_text, 0, 20); ?></td>
                    <td><?php echo date_ymd($category->category_created); ?></td>
                    <td>
                        <a class="btn btn-sm btn-success"
                        href="/panel/category/show/<?php echo $category->category_id; ?>">show</a>
                        </td>
                    <td><a class="btn btn-sm btn-warning"
                        href="/panel/category/update/<?php echo $category->category_id; ?>">update</a></td>
                    <td><a class="btn btn-sm btn-danger data-del"
                        data-get="/panel/category/delete/<?php echo $category->category_id; ?>">delete</a></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td>id</td>
                    <td>key</td>
                    <td>created</td>
                    <td>show</td>
                    <td>update</td>
                    <td>delete</td>
                </tr>
            </tfoot>
        </table>
        <ul class="pagination justify-content-center">
            <?php pagination::selector($data->page, breadcump_search()); ?>
        </ul>
    </div>
</div>