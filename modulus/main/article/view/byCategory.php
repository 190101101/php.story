<h3 class="mt-4 mb-3"><?php echo $data->category->category_title; ?> 
    <small>by üzrə məqalələr</small>
</h3>
<?php breadcump();  ?>

<div class="row">
    <div class="col-md-9">
        

<div class="row">

    <?php if($data->page->count): ?>
    <?php foreach($data->article as $article): ?>
    <div class="col-lg-4 col-md-4 col-sm-6 portfolio-item">
        <a href="/article/read/<?php echo $article->article_id; ?>/page/1" class="card h-100 bordered border-danger">
            <div class="card-body">
                <h6 class="text-danger">
                    @<?php echo $article->user_login; ?>
                </h6>
                <p class="card-text"><?php echo substr($article->article_text, 0, 200); ?>...</p>
                <h6 class="text-center text-info">
                    #<?php echo $article->article_title; ?>
                </h6>
                <small><?php echo date_ymd($article->article_created); ?></small>
                <small>/ # <?php echo $article->article_id; ?></small>
                <small>/ baxış: <?php echo $article->article_view; ?></small>
            </div>
        </a>
    </div>
    <?php endforeach; ?>
    <div class="col-lg-12">
        
<ul class="pagination justify-content-center">
    <?php pagination::selector($data->page, "category/article/{$data->id}/"); ?>
</ul>

    </div>
    <?php else: ?>
    <div class="col-lg-12">
        <div class="alert alert-warning">
            bu category ye aid heç bir nəşr yoxdur. birinci ol
        </div>
    </div>
    <?php endif; ?>
</div>
<?php if($data->page->count): ?>
<span>
    <span>found <?php echo $data->page->count ?> articles / </span>
    <span>there are <?php echo $data->page->length; ?> pages</span>
</span>
<?php endif; ?>

</div>

    <div class="col-md-3">
        <div class="list-group">
            <a class="list-group-item">category</a>
            <?php foreach($data->categories as $category): ?>
                <a 
                <?php if(segment(3) != $category->category_id): ?> 
                    href="/category/article/<?php echo $category->category_id; ?>/page/1"
                <?php endif; ?>
                class="list-group-item <?php echo segment(3) != $category->category_id ?: 'active'; ?>">
                    <?php echo $category->category_title; ?>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</div>