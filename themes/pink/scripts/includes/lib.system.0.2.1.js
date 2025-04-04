/*! System v0.2.1 | (c) hamsbrar */

// A fun abstraction which can make your clients feel at home always :D

$(document).ready(function()
{
    $(window).on('popstate', function(ev)
    {
        // Fix pop state bug for old browsers and mobile browsers
        if (navigator.userAgent.match(/AppleWebKit/) && !navigator.userAgent.match(/Chrome/) && !window.history.ready && !ev.originalEvent.state)
        {
            return; 
        }
        // For apple users
        if (navigator.userAgent.match(/(iPad|iPhone|iPod|Android)/g) && !window.history.ready && !ev.originalEvent.state)
        {
            return; 
        }
        // Load from history
        location.reload();
    });
});

$.fn.isOnScreen = function()
{
	var win = $(window);
	
	var viewport = {
		top : win.scrollTop(),
		left : win.scrollLeft()
	};
	
	if(!$(this).length) {return false;}
	
	viewport.right = viewport.left + win.width();
	viewport.bottom = viewport.top + win.height();
	
	var bounds = this.offset();
	
    bounds.right = bounds.left + this.outerWidth();
    bounds.bottom = bounds.top + this.outerHeight();
	
    return (!(viewport.right < bounds.left || viewport.left > bounds.right || viewport.bottom < bounds.top || viewport.top > bounds.bottom));
};

var sys_events = [];

function sys_startEvent(event)
{
    sys_events[event] = true;
}

function sys_closeEvent(event)
{
    sys_events[event] = false;
}

function sys_isEvent(event)
{
    if(typeof sys_events[event] === undefined) return false;

    return sys_events[event];
}

function sys_handle(response, holding_element)
{
    let data  = typeof response.data !== undefined ? response.data : '';
    let error = typeof response.error !== undefined ? response.error : '';
    let warning = typeof response.warning !== undefined ? response.warning : '';
    let message = typeof response.message !== undefined ? response.message : '';

    holding_element.fadeOut(function()
    {
        if(data.length + error.length + warning.length + message.length < 5)
        {
            console.log('Unmatched object');
            holding_element.html(response);
        }
        else
        {
            console.log('Response - Received');
            holding_element.html( (error.length > 5) ? error : (warning.length > 5 ? warning : (message.length > 5 ? message : data)))
        }

        holding_element.fadeIn();
    });
}

function sys_handlexxhr(xxhr, status)
{
    console.log(xxhr);

    if(status == 'parsererror')
    {
        $('body').prepend(xxhr.responseText);
    }

    console.log('Response status: ' + status);
}

function sys_scrollToTop()
{
    $('html,body').animate({ scrollTop: 0 }, 200);
}

function sys_isIE()
{
    return $('html').hasClass('ie');
}

function sys_redirect(to)
{
	window.location.href = to;
}

function sys_inNewTab(url)
{
    var win = window.open(url, '_blank');
    win.focus();
}

function sys_title(title)
{
    document.title = title;
}

function sys_history(path)
{
	var complete_url = url + path;

	// Return if user has reloaded page
	if(complete_url == window.location.href)

        return true;
    
    if (sys_isIE())

        return sys_redirect(complete_url);	

    window.history.pushState({path:complete_url}, '', complete_url);
}

function sys_beforeSend()
{
    $(".alert").remove();

    console.log('Sending request. Cleared Alerts.');
}

function sys_random(min, max)
{
    return Math.floor(Math.random() * (max - min + 1) + min);
}

function sys_uniqid()
{
    var idstr=String.fromCharCode(Math.floor((Math.random()*25)+65));
    do {                
        var ascicode=Math.floor((Math.random()*42)+48);
        if (ascicode<58 || ascicode>64){
            idstr+=String.fromCharCode(ascicode);    
        }                
    } while (idstr.length<32);

    return (idstr);
}