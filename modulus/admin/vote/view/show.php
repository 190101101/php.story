<?php breadcump_form_panel();  ?>
<div class="row">
    <?php $main = new core\controller; ?>
    <?php $main->view('admin', 'requires', 'admin/sidebar', []); ?>  
    <div class="col-lg-9">
        <?php $vote = $data->vote; ?>
        <div class="row">
            <div class="col-lg-11">
                <h2>vote show</h2>
            </div>
            <div class="col-lg-1">
                <a class="btn btn-sm btn-success" href="/panel/vote/page/1">back</a>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4">
                <div class="form-group">
                    <label>id</label>
                    <input class="form-control" value="<?php echo $vote->vote_id; ?>" readonly>
                </div>  
            </div>  
            <div class="col-lg-4">
                <div class="form-group">
                    <label>created</label>
                    <input class="form-control" value="<?php echo $vote->vote_created; ?>" readonly>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="form-group">
                    <label>updated</label>
                    <input class="form-control" value="<?php echo $vote->vote_updated; ?>" readonly>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <label>article</label>
                    <input class="form-control" value="<?php echo $vote->article_id; ?>" readonly>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <label>user</label>
                    <input class="form-control" value="<?php echo $vote->user_id; ?>" readonly>
                </div>
            </div>
            <div class="col-lg-12 mt-3">
                <a class="btn btn-sm btn-outline-danger"
                    href="/panel/vote/destroy/<?php echo $vote->vote_id; ?>">delete</a>
            </div>
        </div>
    </div>
</div>