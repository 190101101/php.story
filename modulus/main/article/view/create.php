<?php breadcump(); ?>
<div class="row">
    <?php $main = new core\controller; ?>
    <?php $main->view('main', 'requires', 'main/sidebar', []); ?>  
    <div class="col-md-9">
        <form action="/article/create" method="POST">
            <div class="row">
                <div class="col-md-10">
                    <h2>article create</h2>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-sm btn-success" type="submit">create</button>
                    <a class="btn btn-sm btn-success" href="/article/page/1">back</a>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label>title</label>
                        <input name="article_title" class="form-control" type="text" placeholder="title" required min="2" maxlength="20" 
                            
                        <?php if(old::article_title()): ?>
                            value="<?php echo old::article_title(); ?>"
                        <?php endif; ?>
                        >
                    </div>

                    <label>article text</label>
                    <textarea name="article_text" type="text" minlength="100" maxlength="30000" class="form-control" placeholder="article text" 
                    required id="editor1"><?php if(old::article_text()): ?>
                            <?php echo old::article_text(); ?>
                        <?php endif; ?></textarea>
                    <script>CKEDITOR.replace('editor1')</script>

                    <div class="form-group">
                        <label>type</label>
                        <select class="form-control" name="article_type" required>
                            <option value="1">public</option>
                            <option value="0">draft</option>
                        </select>
                    </div>

                    <div class="form-group mt-2">
                        <label>category</label>
                        <select class="form-control" name="category_id" required>
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