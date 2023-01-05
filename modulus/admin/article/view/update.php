<?php panel_breadcrumb($data->column, '/panel/article/search/title/value'); ?>
<div class="row">
    <?php $main = new core\controller; ?>
    <?php $main->view('admin', 'requires', 'admin/sidebar', []); ?>  
    <div class="col-lg-9">
        <?php $article = $data->article; ?>
        <form action="/panel/article/update" method="POST">
            <div class="row">
                <div class="col-md-10">
                    <h2>article show</h2>
                </div>
                <div class="col-md-1">
                    <button class="btn btn-sm btn-success" type="submit">update</button>
                </div>
                <div class="col-md-1">
                    <a class="btn btn-sm btn-success" href="/panel/article/page/1">back</a>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>id</label>
                        <input name="article_id" class="form-control" type="text" value="<?php echo $article->article_id; ?>"  required readonly>
                    </div>
                    <div class="form-group">
                        <label>title</label>
                        <input name="article_title" class="form-control" type="text" value="<?php echo $article->article_title; ?>" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>created</label>
                        <input name="article_created" type="text" class="form-control" value="<?php echo $article->article_created; ?>" required readonly>
                    </div>
                    <div class="form-group">
                        <label>updated</label>
                        <input name="article_updated" type="text" class="form-control" value="<?php echo date('Y-m-d H:i:s'); ?>" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>user id</label>
                        <input name="user_id" type="text" class="form-control" value="<?php echo $article->user_id; ?>" required>
                    </div>
                    <div class="form-group">
                        <label>view</label>
                        <input name="article_view" class="form-control" type="text" value="<?php echo $article->article_view; ?>" required>
                    </div>
                </div>
                <div class="col-md-12">
                    <label>article text</label>
                    <textarea name="article_text" rows="5" minlength="100" maxlength="30000" type="text" class="form-control" placeholder="article text" required id="editor1"><?php echo $article->article_text; ?></textarea>
                    <script>CKEDITOR.replace('editor1')</script>
                </div>

                <div class="col-md-12">
                   <div class="form-group">
                        <label>type</label>
                        <select class="form-control" name="article_type" required>
                            <option value="<?php echo $article->article_type ?>" selected>
                                <?php echo $article->article_type ? 'public' : 'draft'; ?>
                            </option>
                            <option value="1">public</option>
                            <option value="0">draft</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>category</label>
                        <select class="form-control" name="category_id" required>
                            <option value="<?php echo $article->category_id; ?>" selected>
                                <?php echo $article->category_title; ?>
                            </option>
                            <?php foreach($data->category as $category): ?>
                            <option value="<?php echo $category->category_id; ?>">
                                <?php echo $category->category_title; ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>