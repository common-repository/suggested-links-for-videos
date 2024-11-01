jQuery(document).ready(function ($) {

    //get the suggestions object
    const suggested_links = typeof window.suggested_links_obj != 'undefined' ? Object.entries(window.suggested_links_obj) : {}

    const suggested_links_keys = [] //returned keys of suggestion_link
    for (let i = 0; i < suggested_links.length; i++) {
        const suggestion = suggested_links[i];
        suggested_links_keys.push(suggestion[0])
    }

    var suggestions = []
    for (let i = 0; i < suggested_links.length; i++) {
        const element = suggested_links[i];
        suggestions.push(element[1]);
    }

    var videos_in_page = $('video')
    videos_in_page.each(function (index) {

        var this_video = document.getElementsByTagName('video')[index]
        var this_video_parent = $(this).parent()
        var this_video_suggested_links_box

        rednerHTMLelement()

        function rednerHTMLelement() {
            $(`
            <div class="suggested-links-box suggested-links-box-${suggestions_obj.box_direction} suggested-links-icon-${suggestions_obj.icon_direction}">
            <span>${suggestions_obj.info_image}</span>
            </div>
            `).appendTo(this_video_parent)
            this_video_suggested_links_box = $(this_video_parent).find('.suggested-links-box')
            suggestions.forEach(element => {
                element = Object.entries(element)
                element.forEach(suggestion => {
                    if (suggestion[1].video_link == $(this_video).attr('src')) {

                        $(`
                <div class="suggestion-item suggestion-item-align-${suggestions_obj.text_align}" data-time="${suggestion[1].time}">
                    <a href="${suggestion[1].url}">${suggestion[1].title}</a>
                </div>
            `).appendTo($(this_video_suggested_links_box))
                    }
                })
            })
        }

        this_video.addEventListener('timeupdate', function () {
            suggestions.forEach(element => {
                element = Object.entries(element)
                element.forEach(suggestion => {
                    if (suggestion[1].video_link == this_video.getAttribute('src')) {
                        var suggestion_time = suggestion[1].time
                        var this_video_c_time = this_video.currentTime.toString().split('.')[0]
                        var video_time = new Date(this_video_c_time * 1000).toISOString().substr(11,8)
                        if(suggestion_time == video_time){
                            ShowSuggestion(video_time,suggestion_time)
                        }
                    }
                })

                function ShowSuggestion(video_c_time) {
                    var get_suggestions_item = $(this_video_suggested_links_box).find('.suggestion-item')
                    $.each(get_suggestions_item, function (indexInArray, valueOfElement) {
                        if ($(valueOfElement).data('time') == video_c_time && !$(valueOfElement).parent().hasClass('suggestion-click-active') && $(valueOfElement).data('is-showed') != true) {
                            $(valueOfElement).addClass('suggestion-item-active')
                            $(valueOfElement).data('is-showed',true)
                            setTimeout(() => {
                                $(valueOfElement).removeClass('suggestion-item-active')
                            }, 5000);
                        }
                    });
                }
            });
        })


        //handle show suggestions list when user click on suggestion icon
        var info_click = $(this_video_suggested_links_box).find('span')

        $(info_click).on('click', function () {
            if ($(this_video_suggested_links_box).hasClass('suggestion-click-active')) {
                $(this_video_suggested_links_box).removeClass('suggestion-click-active')
            } else {
                $(this_video_suggested_links_box).addClass('suggestion-click-active')
            }
        })
        //hide info icon box when on click video if info icon has active class
        $('video').each(function(){
            $(this).on('click',function(){
                if($(this).parent().find('.suggested-links-box').hasClass('suggestion-click-active')){
                    $(this).parent().find('.suggested-links-box').removeClass('suggestion-click-active')
                }
            })
        })
    })
})