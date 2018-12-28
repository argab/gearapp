var _csrf = yii.getCsrfToken();

$(function ()
{
    $('.sidebar-collapse-icon').click(function ()
    {
        Cookies.set('admin_sidebar_collapsed',
            $(this).parents('.page-container').hasClass('sidebar-collapsed')
        );
    });
});

$(document).ready(function ()
{
    appendTinyMCE($('textarea.js-tiny-mce'));
});

$(document).on('click', '[data-modal^="js-modal"]', function (event)
{
    load_modal($(this), null, event)
});

$(document).on('click', '.js-a-modal', function (event)
{
    link_modal($(this), event)
});

$(document).on('click', '.js-modal-submit,#modal-submit', function (event)
{
    submit_modal($(this), event)
});

$(document).on('click', '.js-delete-row a', function (event)
{
    delete_row($(this), event);

    return false;
});

$(document).on('click', '.js-toggle-inp', function (event) {

    toggleTableCellInputs(event, $(this))

});


/******************************************************************************
 * Panel Group
 */

$(document).on('click', '.panel > .panel-heading', function ()
{
    var $this       = $(this),
        $panel      = $this.closest('.panel'),
        $body       = $panel.children('.panel-body'),
        do_collapse = !$panel.hasClass('panel-collapse');

    if ($panel.is('[data-collapsed="1"]'))
    {
        $panel.attr('data-collapsed', 0);
        $body.hide();
        do_collapse = false;
    }

    if (do_collapse)
    {
        $body.slideUp('normal');
        $panel.addClass('panel-collapse');
    }
    else
    {
        $body.slideDown('normal');
        $panel.removeClass('panel-collapse');
    }
});

/******************************************************************************
 * Get drop messages
 */

function getDropMessages()
{
    $.get('/site/get-drop-messages', function (data)
    {
        if (data.messages)
        {
            $.each(data.messages, function (i, val)
            {
                switch (val.type)
                {
                    case '1':
                        $.growl.notice({
                            title:    "Уведомление",
                            duration: 100000,
                            fixed:    true,
                            message:  val.message,
                            timeout:  100000
                        });
                        play_sound('my/echoed-ding');
                        break;
                    case '2':
                        $.growl.warning({
                            title:    "Уведомление",
                            duration: 100000,
                            fixed:    true,
                            message:  val.message,
                            timeout:  100000
                        });
                        play_sound('my/echoed-ding');
                        break;
                    case '3':
                        $.growl.error({
                            title:    "Внимание!",
                            duration: 100000,
                            fixed:    true,
                            message:  val.message,
                            timeout:  100000
                        });
                        play_sound('my/echoed-ding');
                        break;
                }
            });
        }
    });
}

$(document).ready(function ()
{
    // setInterval(function ()
    // {
    //     getDropMessages()
    // }, 10000);
});

/******************************************************************************
 * Multiple checkbox check
 */

function checkAll(el, checkbox_prefix_name, parent)
{
    var parent = parent ? parent : 'table';
    var checkboxes = el.closest(parent)
        .find(':checkbox' + (checkbox_prefix_name ? '[name^="' + checkbox_prefix_name + '"]' : '')).not(el);

    var checked = el.prop('checked');

    checkboxes.each(function ()
    {
        $(this).prop('checked', checked);

        if ($(this).parents('.checkbox').first().length)
        {
            $(this).parents('.checkbox').first().toggleClass('checked', checked);
        }
    });
}

