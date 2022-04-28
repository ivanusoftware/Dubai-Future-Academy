
/**
 * Clicking the button "try again" adds an active 
 * class to the main tab (Modules).
 * @param {*} tabId 
 */
export default function dffTryAgainActiveMainTab(tabId) {
    const tabItem = $('.tabs-nav-my-courses ul li button');
    tabItem.each(function () {
        const tabMainId = $(this).attr('tab-id');
        if (tabMainId === tabId) {
            $(this).parent().addClass('active');
        } else {
            $(this).parent().removeClass('active');
        }
    });
}