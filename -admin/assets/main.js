function changeMenuOrder(path)
{
    cancelChangeMenuOrder();

    items = '';

    $('div.js-menu-item-header').each(function()
    {
        items = items + '<li>' + $(this).text() + '</li>';
    })

    let wiz_html = `
    <div id="change-menu-order-wiz" class="ui dimmer modals page transition visible active" style="display: flex !important;">
        <div class="ui mini test modal transition visible active" style="display: block !important;">
            <div class="header">
                Menu Items Order
            </div>
            <div class="content ui fluid ">
                
            <p>Reorder Items the way you want and click Save</p>
               
            <ol id="menu-sort">
                `+items+`
            </ol>
        
            </div>
            <div class="actions">
            <div onclick="cancelChangeMenuOrder()" class="ui negative button">
                Cancel
            </div>
            <div onclick="saveMenuSort('`+path+`')"class="ui positive right button">
                Save Order
            </div>
            </div>
        </div>
    </div>

    <script>
        $(function  () {
            $("#menu-sort").sortable();
        });
    </script>
    `;

    $('body').prepend(wiz_html);
}

function saveMenuSort(path)
{
    data = []

    $("#menu-sort li").each(function(){
        data.push($(this).text().trim())
    })

    window.location.href = PANEL_URL + '/actions.php?type=new_menu_order&path=' + path + '&new_order=' + data.join(',');
}

function cancelChangeMenuOrder()
{
    $('#change-menu-order-wiz').remove();
}

function addNewMenuItem(path)
{
    cancelAddNewMenuItem();

    let wiz_html = `
    <div id="new-menu-item-wiz" class="ui dimmer modals page transition visible active" style="display: flex !important;">
        <div class="ui mini test modal transition visible active" style="display: block !important;">
            <div class="header">
                Sets new Page name
            </div>
            <div class="content">
                <p>Name of the page to be displayed to the user</p>
                <div class="ui input">
                    <input id="new-menu-item-name" type="text" value="" placeholder="Add something">
                </div>
            </div>
            `+
            (
                path == 'menu' ?
                    `<div class="header">
                    Select page type
                </div>
                <div class="content">
                    <div class="ui input">
                        <select id="new-menu-item-type" class="ui dropdown">
                            <option selected value="system_page_home">Welcome/Landing Page</option>
                            <option value="system_page_bio">Bio/Artist Page</option>
                            <option value="system_page_portfolio">Portfolio/Gallery Page</option>
                            <option value="system_page_shop">Shop/Services Page</option>
                            <option value="system_page_contact">Contact/Reach Page</option>
                        </select>
                    </div>
                </div>` : ``
            )
            +`
            <div class="actions">
            <div onclick="cancelAddNewMenuItem()" class="ui negative button">
                Cancel
            </div>
            <div onclick="saveAddNewMenuItem('`+path+`')"class="ui positive right button">
                Add page
            </div>
            </div>
        </div>
    </div>
    <script>
        $('.ui.dropdown').dropdown();
    </script>
    `;

    $('body').prepend(wiz_html);
}

function saveAddNewMenuItem(path)
{
    window.location.href = PANEL_URL + '/actions.php?type=create_new_menu_item&path=' + path + '&item=' + $('#new-menu-item-name').val() + (path == 'menu' ? '&new_type=' + $('#new-menu-item-type').val() : '');
}

function cancelAddNewMenuItem()
{
    $('#new-menu-item-wiz').remove();
}

function changeMenuItemType(path, value, type)
{
    cancelChangeMenuItemType();

    let wiz_html = `
    <div id="change-menu-item-type-wiz" class="ui dimmer modals page transition visible active" style="display: flex !important;">
        <div class="ui mini test modal transition visible active" style="display: block !important;">
            <div class="header">
                Change Menu Item Type
            </div>
            <div class="content">
                <p>Select type for the item</p>
                <div class="ui input">
                    <select id="change-menu-item-type" class="ui dropdown">
                        <option `+ (type == 'system_page_home' ? 'selected' : '') + ` value="system_page_home">Welcome/Landing Page</option>
                        <option `+ (type == 'system_page_bio' ? 'selected' : '') + ` value="system_page_bio">Bio/Artist Page</option>
                        <option `+ (type == 'system_page_portfolio' ? 'selected' : '') + ` value="system_page_portfolio">Portfolio/Gallery Page</option>
                        <option `+ (type == 'system_page_shop' ? 'selected' : '') + ` value="system_page_shop">Shop/Services Page</option>
                        <option `+ (type == 'system_page_contact' ? 'selected' : '') + ` value="system_page_contact">Contact/Reach Page</option>
                    </select>
                </div>
            </div>
            <div class="actions">
            <div onclick="cancelChangeMenuItemType()" class="ui negative button">
                Cancel
            </div>
            <div onclick="saveChangeMenuItemType('`+path+`', '`+value+`')"class="ui positive right button">
                Save
            </div>
            </div>
        </div>
    </div>
    <script>
        $('.ui.dropdown').dropdown();
    </script>
    `;

    $('body').prepend(wiz_html);
}

function saveChangeMenuItemType(path, item)
{
    window.location.href = PANEL_URL + '/actions.php?type=change_menu_item_type&path=' + path + '&item=' + item + '&new_type=' + $('#change-menu-item-type').val();
}

function renameMenuItem(path, value)
{
    cancelMenuItemRename();

    let wiz_html = `
    <div id="rename-menu-item-wiz" class="ui dimmer modals page transition visible active" style="display: flex !important;">
        <div class="ui mini test modal transition visible active" style="display: block !important;">
            <div class="header">
                Rename Menu Item
            </div>
            <div class="content">
                <p>Edit the name of the menu item</p>
                <div class="ui input">
                    <input id="renamed-menu-item" type="text" value="`+value+`" placeholder="Add something">
                </div>
            </div>
            <div class="actions">
            <div onclick="cancelMenuItemRename()" class="ui negative button">
                Cancel
            </div>
            <div onclick="saveNewMenuItemName('`+path+`', '`+value+`')"class="ui positive right button">
                Save
            </div>
            </div>
        </div>
    </div>`;

    $('body').prepend(wiz_html);
}

function saveNewMenuItemName(path, old_value)
{
    window.location.href = PANEL_URL + '/actions.php?type=rename_menu_item&path=' + path + '&old_val=' + old_value + '&new_val=' + $('#renamed-menu-item').val();
}

function renameProfile(value)
{
    cancelProfileRename();

    let wiz_html = `
    <div id="rename-profile-wiz" class="ui dimmer modals page transition visible active" style="display: flex !important;">
        <div class="ui mini test modal transition visible active" style="display: block !important;">
            <div class="header">
                Rename Profile
            </div>
            <div class="content">
                <p>Edit the name of the profile</p>
                <div class="ui input">
                    <input id="renamed-profile" type="text" value="`+value+`" placeholder="Add something">
                </div>
            </div>
            <div class="actions">
            <div onclick="cancelProfileRename()" class="ui negative button">
                Cancel
            </div>
            <div onclick="saveNewProfileName('`+value+`')"class="ui positive right button">
                Save
            </div>
            </div>
        </div>
    </div>`;

    $('body').prepend(wiz_html);
}

function saveNewProfileName(value)
{
    window.location.href = PANEL_URL + '/actions.php?type=rename_profile&val=' + value + '&new_val=' + $('#renamed-profile').val();
}

function cancelProfileRename()
{
    $('#rename-profile-wiz').remove();
}

function cancelMenuItemRename()
{
    $('#rename-menu-item-wiz').remove();
}

function cancelChangeMenuItemType()
{
    $('#change-menu-item-type-wiz').remove();
}

$('.ui.dropdown').dropdown();