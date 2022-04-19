<div class="lesson-gallery">
    <div class="swiper-container gallery-slider">
        <!-- <div class="fullsize">
            <a href="#">Test</a>
        </div> -->
        <div class="swiper-wrapper">

            <?php
            //  wp_reset_postdata();
            $lesson_gallery = get_sub_field('lesson_gallery');
            // print_r($lesson_gallery);
            $size = 'full'; // (thumbnail, medium, large, full or custom size)
            if ($lesson_gallery) :

                foreach ($lesson_gallery as $image) :
                    // print_r($image)
                    //   print_r($image['sizes']['large']);
                      
            ?>
                    <div class="swiper-slide">
                        <a class="gallery-fancybox" href="<?php echo $image['url']; ?>">
                       
                            <img src="<?php echo $image['sizes']['large']; ?>" width="<?php echo $image['sizes'][ $size . '-width' ]; ?>" height="<?php echo $image['sizes'][ $size . '-height' ]; ?>" alt="<?php echo $image['alt']; ?>" >
                        </a>                   
                    </div>
                <?php endforeach; ?>

            <?php endif; ?>

        </div>
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
        <!-- <div class="swiper-pagination"></div> -->
        <div class="swiper-counter"></div>
    </div>

</div>