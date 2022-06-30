<div class="course-progress">
    <div class="course-progress__wrap">
        <div class="course-progress__line" style="width:<?php echo dff_progress_bar($future_user_id, get_the_ID()); ?>%"></div>
    </div>
    <div class="course-progress__value"><?php echo dff_progress_bar($future_user_id, get_the_ID()); ?>%</div>
</div>