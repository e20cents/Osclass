<?php if ( ! defined('OC_ADMIN')) exit('Direct access is not allowed.');
    /**
     * Osclass – software for creating and publishing online classified advertising platforms
     *
     * Copyright (C) 2012 OSCLASS
     *
     * This program is free software: you can redistribute it and/or modify it under the terms
     * of the GNU Affero General Public License as published by the Free Software Foundation,
     * either version 3 of the License, or (at your option) any later version.
     *
     * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
     * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
     * See the GNU Affero General Public License for more details.
     *
     * You should have received a copy of the GNU Affero General Public
     * License along with this program. If not, see <http://www.gnu.org/licenses/>.
     */

    function addHelp() {
        echo '<p>' . __('Add, edit or delete information associated to registered users. Keep in mind that deleting a user also deletes all the listings the user published.') . '</p>';
    }
    osc_add_hook('help_box','addHelp');

    function customPageHeader(){ ?>
        <h1><?php _e('Users'); ?>
            <a href="<?php echo osc_admin_base_url(true) . '?page=users&action=settings'; ?>" class="btn ico ico-32 ico-engine float-right"></a>
            <a href="#" class="btn ico ico-32 ico-help float-right"></a>
            <a href="<?php echo osc_admin_base_url(true) . '?page=users&action=create'; ?>" class="btn btn-green ico ico-32 ico-add-white float-right"><?php _e('Add'); ?></a>
        </h1>
<?php
    }
    osc_add_hook('admin_page_header','customPageHeader');

    function customPageTitle($string) {
        return sprintf(__('Manage users &raquo; %s'), $string);
    }
    osc_add_filter('admin_title', 'customPageTitle');

    //customize Head
    function customHead() { ?>
        <script type="text/javascript">
            $(document).ready(function(){
                // users autocomplete
                $('input[name="user"]').attr( "autocomplete", "off" );
                $('#user,#fUser').autocomplete({
                    source: "<?php echo osc_admin_base_url(true); ?>?page=ajax&action=userajax", //+$('input[name="user"]').val(), // &term=
                    minLength: 0,
                    select: function( event, ui ) {
                        if(ui.item.id=='')
                            return false;
                        $('#userId').val(ui.item.id);
                        $('#fUserId').val(ui.item.id);
                    },
                    search: function() {
                        $('#userId').val('');
                        $('#fUserId').val('');
                    }
                });

                $('.ui-autocomplete').css('zIndex', 10000);

                // check_all bulkactions
                $("#check_all").change(function(){
                    var isChecked = $(this).prop("checked");
                    $('.col-bulkactions input').each( function() {
                        if( isChecked == 1 ) {
                            this.checked = true;
                        } else {
                            this.checked = false;
                        }
                    });
                });

                // dialog delete
                $("#dialog-user-delete").dialog({
                    autoOpen: false,
                    modal: true
                });

                // dialog filters
                $('#display-filters').dialog({
                    autoOpen: false,
                    modal: true,
                    width: 700,
                    title: '<?php echo osc_esc_js( __('Filters') ); ?>'
                });
                $('#btn-display-filters').click(function(){
                    $('#display-filters').dialog('open');
                    return false;
                });

                // dialog bulk actions
                $("#dialog-bulk-actions").dialog({
                    autoOpen: false,
                    modal: true
                });
                $("#bulk-actions-submit").click(function() {
                    $("#datatablesForm").submit();
                });
                $("#bulk-actions-cancel").click(function() {
                    $("#datatablesForm").attr('data-dialog-open', 'false');
                    $('#dialog-bulk-actions').dialog('close');
                });
                // dialog bulk actions function
                $("#datatablesForm").submit(function() {
                    if( $("#bulk_actions option:selected").val() == "" ) {
                        return false;
                    }

                    if( $("#datatablesForm").attr('data-dialog-open') == "true" ) {
                        return true;
                    }

                    $("#dialog-bulk-actions .form-row").html($("#bulk_actions option:selected").attr('data-dialog-content'));
                    $("#bulk-actions-submit").html($("#bulk_actions option:selected").text());
                    $("#datatablesForm").attr('data-dialog-open', 'true');
                    $("#dialog-bulk-actions").dialog('open');
                    return false;
                });
                // /dialog bulk actions
            });

            // dialog delete function
            function delete_dialog(item_id) {
                $("#dialog-user-delete input[name='id[]']").attr('value', item_id);
                $("#dialog-user-delete").dialog('open');
                return false;
            }
        </script>
        <?php
    }
    osc_add_hook('admin_header','customHead', 10);

    $aData      = __get('aData');
    $aRawRows   = __get('aRawRows');
    $sort       = Params::getParam('sort');
    $direction  = Params::getParam('direction');

    $columns    = $aData['aColumns'];
    $rows       = $aData['aRows'];
    $withFilters = __get('withFilters');
?>
<?php osc_current_admin_theme_path( 'parts/header.php' ); ?>
<form method="get" action="<?php echo osc_admin_base_url(true); ?>" id="display-filters" class="has-form-actions hide nocsrf">
    <input type="hidden" name="page" value="users" />
    <input type="hidden" name="sort" value="<?php echo $sort; ?>" />
    <input type="hidden" name="direction" value="<?php echo $direction; ?>" />
    <div class="form-horizontal">
        <div class="grid-system">
            <div class="grid-row grid-50">
                <div class="row-wrapper">
                    <div class="form-row">
                        <div class="form-label">
                            <?php _e('Email'); ?>
                        </div>
                        <div class="form-controls">
                            <input id="s_email" name="s_email" type="text" value="<?php echo osc_esc_html(Params::getParam('s_email')); ?>" />
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-label">
                            <?php _e('Name'); ?>
                        </div>
                        <div class="form-controls">
                            <input id="s_name" name="s_name" type="text" value="<?php echo osc_esc_html(Params::getParam('s_name')); ?>" />
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-label">
                            <?php _e('Username'); ?>
                        </div>
                        <div class="form-controls">
                            <input id="s_username" name="s_username" type="text" value="<?php echo osc_esc_html(Params::getParam('s_username')); ?>" />
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-label">
                            <?php _e('Active'); ?>
                        </div>
                        <div class="form-controls">
                            <select id="b_active" name="b_active">
                                <option value="" <?php echo ( (Params::getParam('b_active') == '') ? 'selected="selected"' : '' )?>><?php _e('Choose an option'); ?></option>
                                <option value="1" <?php echo ( (Params::getParam('b_active') == '1') ? 'selected="selected"' : '' )?>><?php _e('ON'); ?></option>
                                <option value="0" <?php echo ( (Params::getParam('b_active') == '0') ? 'selected="selected"' : '' )?>><?php _e('OFF'); ?></option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="grid-row grid-50">
                <div class="row-wrapper">
                    <div class="form-row">
                        <div class="form-label">
                            <?php _e('Country'); ?>
                        </div>
                        <div class="form-controls">
                            <input id="countryName" name="countryName" type="text" value="<?php echo osc_esc_html(Params::getParam('countryName')); ?>" />
                            <input id="countryId" name="countryId" type="hidden" value="<?php echo osc_esc_html(Params::getParam('countryId')); ?>" />
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-label">
                            <?php _e('Region'); ?>
                        </div>
                        <div class="form-controls">
                            <input id="region" name="region" type="text" value="<?php echo osc_esc_html(Params::getParam('region')); ?>" />
                            <input id="regionId" name="regionId" type="hidden" value="<?php echo osc_esc_html(Params::getParam('regionId')); ?>" />
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-label">
                            <?php _e('City'); ?>
                        </div>
                        <div class="form-controls">
                            <input id="city" name="city" type="text" value="<?php echo osc_esc_html(Params::getParam('city')); ?>" />
                            <input id="cityId" name="cityId" type="hidden" value="<?php echo osc_esc_html(Params::getParam('cityId')); ?>" />
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-label">
                            <?php _e('Block'); ?>
                        </div>
                        <div class="form-controls">
                            <select id="b_enabled" name="b_enabled">
                                <option value="" <?php echo ( (Params::getParam('b_enabled') == '') ? 'selected="selected"' : '' )?>><?php _e('Choose an option'); ?></option>
                                <option value="0" <?php echo ( (Params::getParam('b_enabled') == '0') ? 'selected="selected"' : '' )?>><?php _e('ON'); ?></option>
                                <option value="1" <?php echo ( (Params::getParam('b_enabled') == '1') ? 'selected="selected"' : '' )?>><?php _e('OFF'); ?></option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="clear"></div>
        </div>
    </div>
    <div class="form-actions">
        <div class="wrapper">
            <input id="show-filters" type="submit" value="<?php echo osc_esc_html( __('Apply filters') ); ?>" class="btn btn-submit" />
            <a class="btn" href="<?php echo osc_admin_base_url(true).'?page=users'; ?>"><?php _e('Reset filters'); ?></a>
        </div>
    </div>
</form>
<h2 class="render-title"><?php _e('Manage users'); ?> <a href="<?php echo osc_admin_base_url(true) . '?page=users&action=create'; ?>" class="btn btn-mini"><?php _e('Add new'); ?></a></h2>
<div class="relative">
    <div id="users-toolbar" class="table-toolbar">
        <div class="float-right">
            <form method="get" action="<?php echo osc_admin_base_url(true); ?>" id="shortcut-filters" class="inline nocsrf">
                <input type="hidden" name="page" value="users" />
                <a id="btn-display-filters" href="#" class="btn <?php if($withFilters) { echo 'btn-red'; } ?>"><?php _e('Show filters'); ?></a>
                <input id="fUser" name="user" type="text" class="fUser input-text input-actions" value="<?php echo osc_esc_html(Params::getParam('user')); ?>" />
                <input id="fUserId" name="userId" type="hidden" value="<?php echo osc_esc_html(Params::getParam('userId')); ?>" />
                <input type="submit" class="btn submit-right" value="<?php echo osc_esc_html( __('Find') ); ?>">
            </form>
        </div>
    </div>
    <form class="" id="datatablesForm" action="<?php echo osc_admin_base_url(true); ?>" method="post">
        <input type="hidden" name="page" value="users" />

        <div id="bulk-actions">
            <label>
                <?php osc_print_bulk_actions('bulk_actions', 'action', __get('bulk_options'), 'select-box-extra'); ?>
                <input type="submit" id="bulk_apply" class="btn" value="<?php echo osc_esc_html( __('Apply') ); ?>" />
            </label>
        </div>
        <div class="table-contains-actions">
            <table class="table" cellpadding="0" cellspacing="0">
                <thead>
                    <tr>
                        <?php foreach($columns as $k => $v) {
                            echo '<th class="col-'.$k.' '.($sort==$k?($direction=='desc'?'sorting_desc':'sorting_asc'):'').'">'.$v.'</th>';
                        }; ?>
                    </tr>
                </thead>
                <tbody>
                <?php if( count($rows) > 0 ) { ?>
                    <?php foreach($rows as $key => $row) {
                    $class = ''; $aU = $aRawRows[$key];
                        if( !$aU['b_enabled'] ) {
                            $class = 'status-blocked';
                        } else if( !$aU['b_active'] ) {
                            $class = 'status-inactive';
                        } else {
                            $class = 'status-active';
                        } ?>
                        <tr class="<?php echo $class;?>">
                            <?php foreach($row as $k => $v) { ?>
                                <td class="col-<?php echo $k; ?>"><?php echo $v; ?></td>
                            <?php }; ?>
                        </tr>
                    <?php }; ?>
                <?php } else { ?>
                    <tr>
                        <td colspan="6" class="text-center">
                        <p><?php _e('No data available in table'); ?></p>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
            <div id="table-row-actions"></div> <!-- used for table actions -->
        </div>
    </form>
</div>
<?php
    function showingResults(){
        $aData = __get("aData");
        echo '<ul class="showing-results"><li><span>'.osc_pagination_showing((Params::getParam('iPage')-1)*$aData['iDisplayLength']+1, ((Params::getParam('iPage')-1)*$aData['iDisplayLength'])+count($aData['aRows']), $aData['iTotalDisplayRecords'], $aData['iTotalRecords']).'</span></li></ul>';
    }
    osc_add_hook('before_show_pagination_admin','showingResults');
    osc_show_pagination_admin($aData);
?>
<form id="dialog-user-delete" method="get" action="<?php echo osc_admin_base_url(true); ?>" class="has-form-actions hide" title="<?php echo osc_esc_html(__('Delete user')); ?>">
    <input type="hidden" name="page" value="users" />
    <input type="hidden" name="action" value="delete" />
    <input type="hidden" name="id[]" value="" />
    <div class="form-horizontal">
        <div class="form-row">
            <?php _e('Are you sure you want to delete this user?'); ?>
        </div>
        <div class="form-actions">
            <div class="wrapper">
            <a class="btn" href="javascript:void(0);" onclick="$('#dialog-user-delete').dialog('close');"><?php _e('Cancel'); ?></a>
            <input id="user-delete-submit" type="submit" value="<?php echo osc_esc_html( __('Delete') ); ?>" class="btn btn-red" />
            </div>
        </div>
    </div>
</form>
<div id="dialog-bulk-actions" title="<?php _e('Bulk actions'); ?>" class="has-form-actions hide">
    <div class="form-horizontal">
        <div class="form-row"></div>
        <div class="form-actions">
            <div class="wrapper">
                <a id="bulk-actions-cancel" class="btn" href="javascript:void(0);"><?php _e('Cancel'); ?></a>
                <a id="bulk-actions-submit" href="javascript:void(0);" class="btn btn-red" ><?php echo osc_esc_html( __('Delete') ); ?></a>
                <div class="clear"></div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
$(document).ready(function(){

    $('#countryName').attr( "autocomplete", "off" );
    $('#region').attr( "autocomplete", "off" );
    $('#city').attr( "autocomplete", "off" );

    $('#countryId').change(function(){
        $('#regionId').val('');
        $('#region').val('');
        $('#cityId').val('');
        $('#city').val('');
    });

    $('#countryName').on('keyup.autocomplete', function(){
        $('#countryId').val('');
        $( this ).autocomplete({
            source: "<?php echo osc_base_url(true); ?>?page=ajax&action=location_countries",
            minLength: 0,
            select: function( event, ui ) {
                $('#countryId').val(ui.item.id);
                $('#regionId').val('');
                $('#region').val('');
                $('#cityId').val('');
                $('#city').val('');
            }
        });
    });

    $('#region').on('keyup.autocomplete', function(){
        $('#regionId').val('');
        $( this ).autocomplete({
            source: "<?php echo osc_base_url(true); ?>?page=ajax&action=location_regions&country="+$('#countryId').val(),
            minLength: 2,
            select: function( event, ui ) {
                $('#cityId').val('');
                $('#city').val('');
                $('#regionId').val(ui.item.id);
            }
        });
    });

    $('#city').on('keyup.autocomplete', function(){
        $('#cityId').val('');
        $( this ).autocomplete({
            source: "<?php echo osc_base_url(true); ?>?page=ajax&action=location_cities&region="+$('#regionId').val(),
            minLength: 2,
            select: function( event, ui ) {
                $('#cityId').val(ui.item.id);
            }
        });
    });
});
</script>
<?php osc_current_admin_theme_path( 'parts/footer.php' ); ?>