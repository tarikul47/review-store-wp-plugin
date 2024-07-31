<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://onlytarikul.com
 * @since      1.0.0
 *
 * @author    Your Name or Your Company
 */

?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<form id="posts-filter" method="get">
    <p class="search-box">
        <label class="screen-reader-text" for="post-search-input">Search Pages:</label>
        <input type="search" id="post-search-input" name="s" value="">
        <input type="submit" id="search-submit" class="button" value="Search Pages">
    </p>
    <div class="tablenav top">
        <div class="alignleft actions bulkactions">
            <label for="bulk-action-selector-top" class="screen-reader-text">Select bulk action</label><select
                name="action" id="bulk-action-selector-top">
                <option value="-1">Bulk actions</option>
                <option value="edit" class="hide-if-no-js">Edit</option>
                <option value="trash">Move to Trash</option>
            </select>
            <input type="submit" id="doaction" class="button action" value="Apply">
        </div>
        <div class="tablenav-pages one-page"><span class="displaying-num">2 items</span>
        </div>
        <br class="clear">
    </div>
    <table class="wp-list-table widefat fixed striped table-view-list pages">
        <thead>
            <tr>
                <td id="cb" class="manage-column column-cb check-column">
                    <input id="cb-select-all-1" type="checkbox">
                </td>
                <th scope="col" id="" class="manage-column">Name</th>

                <th scope="col" id="" class="manage-column ">Fair & Impartial</th>

                <th scope="col" id="" class="manage-column ">Professional</th>

                <th scope="col" id="" class="manage-column ">Response</th>

                <th scope="col" id="" class="manage-column ">Communication</th>

                <th scope="col" id="" class="manage-column ">Decisions</th>

                <th scope="col" id="" class="manage-column ">Recommend</th>

                <th scope="col" id="" class="manage-column column-actions">Review Text</th>

                <th scope="col" id="" class="manage-column column-actions">Review by</th>

                <th scope="col" id="" class="manage-column column-actions">Action</th>

            </tr>
        </thead>
        <tbody id="the-list">
            <tr id="post-id-1001" class="">
                <th scope="row" class="check-column"> <input id="cb-select-35" type="checkbox" value="1001"> </th>
                <td class="column-first-name" data-colname="">Rokeybur Rahman</td>
                <td class="column-last-name" data-colname="">4</td>
                <td class="column-title" data-colname="">3.5</td>
                <td class="column-City" data-colname="">4.5</td>
                <td class="column-City" data-colname="">3</td>
                <td class="column-City" data-colname="">5</td>
                <td class="column-email" data-colname="">2</td>
                <td class="column-reviews" data-colname="">He is a good in his profession.</td>
                <td class="column-reviews-by" data-colname="">Jhone edgar</td>
                <td class="column-actions" data-colname="">
                    <a class="table-btn" href="">Approve</a>
                    <a class="table-btn" href="">Reject</a>
                </td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <td id="cb" class="manage-column column-cb check-column">
                    <input id="cb-select-all-1" type="checkbox">
                </td>
                <th scope="col" id="" class="manage-column">Name</th>
                <th scope="col" id="" class="manage-column ">Fair & Impartial</th>
                <th scope="col" id="" class="manage-column ">Professional</th>
                <th scope="col" id="" class="manage-column ">Response</th>
                <th scope="col" id="" class="manage-column ">Communication</th>
                <th scope="col" id="" class="manage-column ">Decisions</th>
                <th scope="col" id="" class="manage-column ">Recommend</th>
                <th scope="col" id="" class="manage-column column-actions">Review Text</th>
                <th scope="col" id="" class="manage-column column-actions">Review by</th>
                <th scope="col" id="" class="manage-column column-actions">Action</th>
            </tr>
        </tfoot>
    </table>
    <div class="tablenav bottom">
        <div class="alignleft actions bulkactions">
            <label for="bulk-action-selector-bottom" class="screen-reader-text">Select bulk action</label><select
                name="action2" id="bulk-action-selector-bottom">
                <option value="-1">Bulk actions</option>
                <option value="edit" class="hide-if-no-js">Edit</option>
                <option value="trash">Move to Trash</option>
            </select>
            <input type="submit" id="doaction2" class="button action" value="Apply">
        </div>
        <div class="alignleft actions">
        </div>
        <div class="tablenav-pages one-page"><span class="displaying-num">2 items</span>
        </div>
        <br class="clear">
    </div>
</form>