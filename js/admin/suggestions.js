jQuery(document).ready(function ($) {

    // var local_html,post_id_to_insert
    var local_html = {}

    //handle suggestion add button click
    $(document).on('click', '.add-suggestion-link', function (e) {
        e.preventDefault()
        var input_count = $('.suggestion-box').length
        HandleSuggestionClick(input_count)
        input_count += 1
    })

    //remove suggestion form form
    $(document).on('click', '.suggestion-box span', function () {
        $(this).parent().remove()
    })
    //handle localhtml for edit content
    $(document).on('click', function (e) {
        if (Object.keys(local_html).length != 0) {
            if($(e.target).parent().attr('class') != 'suggestion-box'){
                var suggested_links_app = $('.suggested-links-app')
                for (const [key, value] of Object.entries(local_html)) {
                    if($(suggested_links_app).data('id') == key){
                        $(suggested_links_app).replaceWith(`<div class="suggested-links-app">${local_html[key]}</div>`)
                    }
                }
            }
        }
    })
    
    function HandleSuggestionClick(input_count) {

        input_count = input_count + (1)
        var suggested_links_app = $('.suggested-links-app')

        $(`
            <div class="suggestion-box" data-count="${input_count}">
                <div contenteditable data-placeholder="${suggested_links_obj.title_here}" data-name="title"></div>
                <div contenteditable data-placeholder="https://yoursite.com" data-name="url"></div>
                <div contenteditable data-placeholder="00:00:00" data-name="time">00:00:00</div>
                <span><svg enable-background="new 0 0 24 24" height="24px" id="Layer_1" version="1.1" viewBox="0 0 24 24" width="24px" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><path d="M22.245,4.015c0.313,0.313,0.313,0.826,0,1.139l-6.276,6.27c-0.313,0.312-0.313,0.826,0,1.14l6.273,6.272  c0.313,0.313,0.313,0.826,0,1.14l-2.285,2.277c-0.314,0.312-0.828,0.312-1.142,0l-6.271-6.271c-0.313-0.313-0.828-0.313-1.141,0  l-6.276,6.267c-0.313,0.313-0.828,0.313-1.141,0l-2.282-2.28c-0.313-0.313-0.313-0.826,0-1.14l6.278-6.269  c0.313-0.312,0.313-0.826,0-1.14L1.709,5.147c-0.314-0.313-0.314-0.827,0-1.14l2.284-2.278C4.308,1.417,4.821,1.417,5.135,1.73  L11.405,8c0.314,0.314,0.828,0.314,1.141,0.001l6.276-6.267c0.312-0.312,0.826-0.312,1.141,0L22.245,4.015z"/></svg></span>
            </div>
        `).appendTo(suggested_links_app)
    }

    $(document).on('click','#send_suggestion_data', function (e) {
        e.preventDefault()
        var button_html = $(this)
        $(this).html(suggested_links_obj.saving)
        var get_post_id = $(this).data('post_id')
        const data = {}
        var get_suggested_links_box = $('.suggestion-box')

        get_suggested_links_box.each(function () {
            var get_data = $(this).find('div')
            var get_count = $(this).data('count')

            var title_html = $(this).find('div:nth-child(1)').html()
            var url_html = $(this).find('div:nth-child(2)').html()
            var time_html = $(this).find('div:nth-child(3)').html()
            get_data.each(function () {
                data[get_count] = {
                    title: title_html,
                    url: url_html,
                    time: time_html
                }
            })
        })
        $.ajax({
            type: "POST",
            url: suggested_links_obj.ajax_address,
            data:
            {
                action: 'suggested_links_save',
                nonce: suggested_links_obj.nonce,
                suggestions: data,
                post_id: get_post_id
            },
            success: function () {
                var get_boxes = $(document).find('.suggested-links-app')
                get_boxes.each(function () {
                    local_html[$(this).data('id')] = $(this).html()
                })
                $(button_html).html(suggested_links_obj.save)
                $(`<span class="suggestion-saved">${suggested_links_obj.saved}</span>`).insertAfter(button_html);
                setTimeout(() => {
                    $(button_html).parent().find('span.suggestion-saved').remove()
                }, 2000);
            },
            error: function(){
                $(button_html).html(suggested_links_obj.error)
                setTimeout(function(){
                    $(button_html).html(suggested_links_obj.save)
                },2000)
            }
        });
    })
})
