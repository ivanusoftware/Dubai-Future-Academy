<?php

/**
 * Save quiz answers
 *
 * @return void
 */
function quiz_answers_callback() {
    $json_data = array();
    $form = json_decode(stripslashes($_POST['form']), true);
    $id = json_decode(stripslashes($_POST['id']), true);
    $user = json_decode(stripslashes($_POST['user']), true);
    $answers = get_field('quiz_steps', $id);
    $answers_arr = array();
    if ($answers) {
        foreach ($answers as $key_step => $step) {
            $key_step++;
            foreach ($step['quiz_step_inputs'] as $key_step_inputs => $step_inputs) {
                $key_step_inputs++;
                switch ($step_inputs['acf_fc_layout']) {
                    case 'radio':
                        $answers_key = 'radio_' . $key_step . '_' . $key_step_inputs;
                        foreach ($step_inputs['answers_radio'] as $answers_radio) {
                            if ($answers_radio['correct'] == 'true') {
                                $answers_arr[$answers_key] = $answers_radio['answer'];
                            }
                        }
                    break;
                    case 'checkbox':
                        $answers_key = 'checkbox_' . $key_step . '_' . $key_step_inputs;
                        foreach ($step_inputs['answers_checkbox'] as $answers_checkbox) {
                            if ($answers_checkbox['correct'] == 'true') {
                                $answers_arr[$answers_key][] = $answers_checkbox['answer'];
                            }
                        }
                    break;
                    case 'text_with_select':
                        foreach ($step_inputs['answer_fragments'] as $key_answer_fragments => $answer_fragments) {
                            $key_answer_fragments++;
                            $answers_key = 'text_with_select_' . $key_step . '_' . $key_step_inputs . '_' . $key_answer_fragments;
                            switch ($answer_fragments['variant']) {
                                case 'input':
                                    $answers_arr[$answers_key] = $answer_fragments['input'];
                                break;
                                case 'select':
                                    foreach ($answer_fragments['select'] as $select) {
                                        if ($select['correct'] == 'true') {
                                            $answers_arr[$answers_key] = $select['answer'];
                                        }
                                    }
                                break;
                            }
                        }
                    break;
                }
            }
        }
        $answers_acf = array();
        $percentage = 0;
        foreach ($form as $key => $value) {
            if (strpos($key, 'checkbox') !== false && is_array($value)) {
                $value = implode(', ', $value);
                $answers_arr[$key] = implode(', ', $answers_arr[$key]);
            } elseif (strpos($key, 'fragment_text') !== false ) {
                $key = str_replace('fragment_text', 'text_with_select', $key);
            } elseif (strpos($key, 'select') !== false ) {
                $key = str_replace('select', 'text_with_select', $key);
            }
            $answers_acf[] = array(
                'field_name' => $key,
                'answer' => $value,
                'correct_answer' => $answers_arr[$key]
            );
            if ($value == $answers_arr[$key]) {
                $percentage++;
            }
        }
        $percentage = $percentage / count($form) * 100;
        $percentage = round($percentage, 2);

        $post_id = wp_insert_post([
            'post_title' => 'User: ' . $user . ', Date: ' . date_i18n('j F Y H:i'),
            'post_type' => 'quizzes_answers',
            'post_status' => 'publish',
        ]);

        update_field('percentage', $percentage, $post_id);
        update_field('answers', $answers_acf, $post_id);

        $json_data['result'] = 1;
        $json_data['percentage'] = $percentage;
        wp_send_json($json_data);
    }
    wp_die();
}
add_action('wp_ajax_quiz_answers', 'quiz_answers_callback');
add_action('wp_ajax_nopriv_quiz_answers', 'quiz_answers_callback');
