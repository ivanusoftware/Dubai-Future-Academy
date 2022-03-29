<?php # -*- coding: utf-8 -*-
/*
 * This file is part of the MultilingualPress package.
 *
 * (c) Inpsyde GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Inpsyde\MultilingualPress\Framework\Admin\Metabox;

interface TermMetabox extends Metabox
{
    /**
     * @param \WP_Term $term
     * @param string $saveOrShow
     * @return bool
     */
    public function acceptTerm(\WP_Term $term, string $saveOrShow): bool;

    /**
     * @param \WP_Term $term
     * @return View
     */
    public function viewForTerm(\WP_Term $term): View;

    /**
     * @param \WP_Term $term
     * @return Action
     */
    public function actionForTerm(\WP_Term $term): Action;
}
