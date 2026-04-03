window._ = require('lodash');

try {
    window.$ = window.jQuery = require('jquery');
    require('select2')
    window.Dropzone = require('dropzone').default
    require('flatpickr')
} catch (e) { }



window.axios = require('axios');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

// import Echo from 'laravel-echo';

// window.Pusher = require('pusher-js');

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: process.env.MIX_PUSHER_APP_KEY,
//     cluster: process.env.MIX_PUSHER_APP_CLUSTER,
//     forceTLS: true
// });
import Echo from 'laravel-echo';

window.Pusher = require('pusher-js');

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: 'key',
    cluster: 'mt1',
    forceTLS: true,
    disableStats: true,
    wsHost: window.location.hostname,
    wssHost: window.location.hostname,
    enabledTransports: ['ws', 'wss'],
    wsPort: 6001,
    wssPort: 6001

});


window.Echo.channel('trip-1').listen('.chat', (e) => {
    console.log(e);
});
window.Echo.channel('drivers').listen('.drivers1', (e) => {
    console.log(e);
});
window.Echo.channel('trip-1').listenToAll((event, data) => {
    // do what you need to do based on the event name and data
    console.log(event, data)
});
window.Echo.channel('my-channel').listenToAll((event, data) => {
    // do what you need to do based on the event name and data
    console.log(event, data)
});


window.Echo.channel('drivers')
    .listen('pusher_internal:subscription_succeeded', (e) => {
        console.log('Successfully subscribed to drivers channel.');
    })
    .listen('.*', (e) => {
        console.log('Received an event:', e);
    })
    ;
let element = document.getElementById('ChatAppPage');

if (element != undefined && element != null) {
    console.log('trip-' + element.getAttribute('chat-id'));
    window.Echo.channel('trip-' + element.getAttribute('chat-id')).listen('.chat', (e) => {
        console.log(e);
    });
}
