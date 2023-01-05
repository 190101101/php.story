<h3 class="mt-4 mb-3">all
    <small>categories</small>
</h3>
<?php breadcump();  ?>

<div class="row">
    <?php if($data->page->count): ?>
    <?php foreach($data->category as $category): ?>
    <div class="col-lg-3 col-md-4 col-sm-6 portfolio-item">
        <a href="/category/article/<?php echo $category->category_id; ?>/page/1" class="card h-100 bordered border-danger">
            <div class="card-body">
                <h6 class="text-danger">
                    #<?php echo $category->category_title; ?>
                </h6>
                <p class="card-text"><?php echo substr($category->category_text, 0, 200); ?>...</p>
            </div>
        </a>
    </div>
    <?php endforeach; ?>
    <?php else: ?>
    <div class="col-md-12">
        <div class="alert alert-warning">
            bu günə qədər heç bir nəşr yoxdur. birinci ol
        </div>
    </div>
    <?php endif; ?>
</div>
<?php if($data->page->count): ?>
<span>
    <span>found <?php echo $data->page->count ?> categorys / </span>
    <span>there are <?php echo $data->page->length; ?> pages</span>
</span>
<?php endif; ?>

<ul class="pagination justify-content-center">
    <?php pagination::selector($data->page, "category/"); ?>
</ul>

