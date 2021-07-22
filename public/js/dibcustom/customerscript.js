$(function ()
{
    $(document).on('click', '.customersetting', function ()
    {
        var localStorageName = $(this).attr('data-localstorageName');
        var redirectLink =  $(this).attr('redirect-url');
        $.ajax({
            url: $(this).attr('ajax-url'),
            type: "GET"

        }).done(function (data) {
            DIB.alert(data.html, 'Column settings',false, false, false,'customColumnSetting');            

            $("button.primary").off('click');
            var checkedInput = [];
            $("button.primary").on('click', function (event) {
                event.preventDefault();
                checkboxes = $('ul.checklist input[type=checkbox]:checked')
                checkboxes.each(function (e) {
                    checkedInput.push($.parseJSON($(this).attr('setting-value')));
                });
                localStorage.setItem(localStorageName, JSON.stringify(checkedInput));
                $("#customColumnSetting").dialog('close');
                $("#customColumnSetting").remove();
                window.location.href =redirectLink;
                
            });


        });

    });
});