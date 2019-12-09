import $ from 'jquery';

function getContent(timestamp) {
    const queryString = {'timestamp' : timestamp};

    $.ajax(
        {
            type: 'GET',
            url: '/api/getInstantNotification',
            data: queryString,
            success: function(data){
                // put result data into "obj"
                const obj = jQuery.parseJSON(data);
                // put the data_from_file into #response
                console.log(obj);
                // call the function again, this time with the timestamp we just got from server.php
                getContent(obj.timestamp);
            }
        }
    );
}

// initialize jQuery
$(function() {
    getContent();
});

window.onload = () => {
    getContent();
};