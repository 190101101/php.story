<?php $main = new core\controller; ?>
<?php $comment = $data->comment; ?>
<?php breadcump(); ?>

<div class="row">
    <?php $main->view('main', 'requires', 'main/sidebar', []); ?>  
    <div class="col-lg-9">
        <div class="row">
            <div class="col-lg-11">
                <h2>comment show</h2>
            </div>
            <div class="col-lg-1">
                <a class="btn btn-sm btn-success" href="/comment/page/1">back</a>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <label>comment text</label>
                <textarea type="text" rows="5" readonly class="form-control" placeholder="comment text"><?php echo $comment->comment_text; ?></textarea>
            </div>

            <?php if($comment->user_id == User::user_id()): ?>
            <div class="col-lg-12 mt-3">
                <a class="btn btn-sm btn-outline-danger"
                    href="/comment/destroy/<?php echo $comment->comment_id; ?>">delete</a>
            </div>
            <?php endif; ?>

        </div>
    </div>
</div>