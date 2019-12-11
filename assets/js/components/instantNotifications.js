import $ from 'jquery';
import axios from 'axios';

function getContent() {
    $.ajax(
        {
            type: 'GET',
            url: '/api/instant_notification',
            success: function(){
                if(data.description === undefined) {

                    getContent();
                    return;
                }

                let snackbar = $('.snackbar-template');

                snackbar.clone();
                snackbar.removeClass('snackbar-template');
                snackbar.find('span').html(data.description);
                snackbar.find('i').click(function () {
                    $(this).parents('.Snackbar-value').remove();
                });
                snackbar.show();
                snackbar.parent().append(snackbar);

                getContent();
            }
        }
    );

    // axios.get('/api/instant_notification').then(response => {
    //     if(response.data.description === undefined) {
    //
    //         getContent();
    //         return;
    //     }
    //
    //     let snackbar = $('.snackbar-template');
    //
    //     snackbar.clone();
    //     snackbar.removeClass('snackbar-template');
    //     snackbar.find('span').html(response.data.description);
    //     snackbar.find('i').click(function () {
    //         $(this).parents('.Snackbar-value').remove();
    //     });
    //     snackbar.show();
    //     snackbar.parent().append(snackbar);
    //
    //     getContent();
    // })
}

// initialize jQuery
$(function() {
    getContent();
});