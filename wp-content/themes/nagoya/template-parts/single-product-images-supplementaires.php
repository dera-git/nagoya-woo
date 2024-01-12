<?php
// Check rows exists.
if( have_rows('images_supplementaires') ): ?>
<ul class="kl-product-image-supp list-unstyled d-flex align-items-stretch mb-0">
<?php

    // Loop through rows.
    while( have_rows('images_supplementaires') ) : the_row(); 

        // Load sub field value.
        $image = get_sub_field('images_supple');

?>
        <?php if(!empty($image)): ?>
            <li class="kl-another-image-product">                            
                <img src="<?php echo $image ?>" class="img-fluid kl-img-cover" alt="" />
            </li>
        <?php endif; ?>

    <?php
    // End loop.
    endwhile; ?>
</ul>
<?php endif; ?>